<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/25 0025
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;

use app\common\model\{BusinessCode, OrderCart, OrderItem, SubOrder, Product, ProductCategory,Business};
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\facade\Db;

class OrderService
{
    /**
     * 直接下单
     * @param array $param
     * @param string $custom
     * @return array
     */
    public function buy(array $param,string $custom = '')
    {
        //获取商品信息
        $product = Product::findOrEmpty($param['pid']);
        if($product->isEmpty()){
            return ['error'=>true,'msg'=>'该商品不存在'];
        }
        if((int)$product->status === Product::DOWN_SHELF){
            return ['error'=>true,'msg'=>'该商品已下架'];
        }elseif((int)$product->status === Product::DELETE_SHELF){
            return ['error'=>true,'msg'=>'该商品已删除'];
        }
        //理论总价
        $total_price = bcmul($product->price , $param['number'],2);
        //实际支付 = 理论总价 - 折扣价
        $actual_price = bcsub($total_price , bcmul($product->discount , $param['number'],2),2);
        //生成唯地址
        $address = $param['area'] . $param['address'];
        //查询订单分类，若为印章笔定制则需要商户确认才行。
        $cate = ProductCategory::find($param['category_id']);
        if($cate->status == ProductCategory::BUSINESS_OBJECT){
            if(empty($param['code'])){
                return ['error'=>true,'msg'=>'请输入供应商编码'];
            }
            $status = SubOrder::WAIT_SHIP;
        }else{
            $status = SubOrder::WAIT_CONFIRM;
        }
        if(empty($param['bid'])){
            if(empty($param['code'])){
                return ['error'=>true,'msg'=>'请输入供应商编码'];
            }else{
                $bid = BusinessCode::where('code',trim($param['code']))->value('bid');
                if(!$bid){
                    return ['error'=>true,'msg'=>'该供应商编码不存在'];
                }
            }
        }else{
            $bid = $param['bid'];
        }
        //收货一主订单号
        $sub_no = orderNum();
        $date = date('Y-m-d H:i:s');
        $cart = new CartService();
        $options = $cart->getAttributeRelate($param['sku_ids']);
        $skus = $cart->getAttributeOption($options);
        $sku = '';
        foreach ($skus as $v) {
            $sku .= $v->name .';';
        }

        //处理所有子订单的商品价格
        Db::startTrans();
        try {
            SubOrder::create([
                'sub_no'      => $sub_no,
                'bid'         => $bid,
                'category_id' => $param['category_id'],
                'total_price' => $total_price,
                'actual_price'=> $actual_price,
                'trade_name'  => $param['trade_name'],
                'trade_phone' => $param['trade_phone'],
                'address'     => $address,
                'code'        => isset($param['code']) ? trim($param['code']) : '',
                'shop_address'=> $param['shop_address'] ?? '',
                'status'      => $status,
                'create_time' => $date,
                'update_time' => $date
            ]);
            OrderItem::create([
                'sub_no'       => $sub_no,
                'trade_no'     => $sub_no.'01',
                'product_id'   => $param['pid'],
                'sku'          => $sku,
                'no'           => $product->no,
                'product_name' => $product->title,
                'product_marque' => $product->marque,
                'number'       => $param['number'],
                'real_price'   => $actual_price,
                'free_price'   => bcmul($product->discount , $param['number'],2),
                'custom'       => $custom,
                'unit_price'   => bcsub($product->price,$product->discount,2),
                'create_time'  => $date,
                'update_time'  => $date
            ]);
            // 提交事务
            Db::commit();
            return ['error'=>false];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['error'=>true,'msg'=>$e->getMessage()];
        }
    }

    /**
     * 从购物车下单
     */
    public function cartBuy(array $param)
    {
        $cart = new CartService();
        $carts = $cart->carts($param['cart_ids']);
        if(!$carts){
            return ['error'=>true,'msg'=>'非法参数'];
        }
        $total_price = $free_price = 0.00;
        //收货一主订单号
        $sub_no = orderNum();
        $date = date('Y-m-d H:i:s');
        foreach ($carts as $k=>$cart){
            if((int)$cart['product_status'] === Product::DOWN_SHELF){
                return ['error'=>true,'msg'=>'购物车中存在商品已下架'];
            }elseif((int)$cart['product_status'] === Product::DELETE_SHELF){
                return ['error'=>true,'msg'=>'购物车中存在商品已删除'];
            }
            if((int)$cart['cart_status'] === OrderCart::INVALID){
                return ['error'=>true,'msg'=>'存在购物车已删除'];
            }
            $part = bcmul($cart['price'] , $cart['number'],2);
            $free = bcmul($cart['discount'] , $cart['number'],2);
            //理论总价
            $total_price += $part;
            //减免价
            $free_price += $free;
            $bid = $cart['bid'];
            $category_id = $cart['category_id'];
            $list[] = [
                'sub_no'       => $sub_no,
                'trade_no'     => $sub_no.str_pad(++$k,2,0,STR_PAD_LEFT ),
                'product_id'   => $cart['pid'],
                'sku'          => $cart['sku'],
                'no'           => $cart['no'],
                'product_name' => $cart['title'],
                'product_marque' => $cart['marque'],
                'number'       => $cart['number'],
                'real_price'   => $part-$free,
                'free_price'   => $free,
                'unit_price'   => bcsub($cart['price'],$cart['discount'],2),
                'create_time'  => $date,
                'update_time'  => $date
            ];
        }
        if($bid != $param['bid']){
            return ['error'=>true,'msg'=>'非法提交'];
        }
        //实际支付 = 理论总价 - 折扣价
        $actual_price = bcsub($total_price , $free_price,2);
        //生成唯一地址
        $address = $param['area'] . $param['address'];
        //查询订单分类，若为印章笔定制则需要商户确认才行。
        $cate = ProductCategory::find($category_id);
        $date = date('Y-m-d H:i:s');
        $cart_ids = explode(',',$param['cart_ids']);
        //处理所有子订单的商品价格
        Db::startTrans();
        try {
            SubOrder::create([
                'sub_no'      => $sub_no,
                'bid'         => $bid,
                'category_id' => $category_id,
                'total_price' => $total_price,
                'actual_price'=> $actual_price,
                'trade_name'  => $param['trade_name'],
                'trade_phone' => $param['trade_phone'],
                'address'     => $address,
                'code'        => $param['code'] ?? '',
                'shop_address'=> $param['shop_address'] ?? '',
                'status'      => $cate->status == ProductCategory::BUSINESS_OBJECT ? SubOrder::WAIT_SHIP : SubOrder::WAIT_CONFIRM,
                'create_time' => $date,
                'update_time' => $date
            ]);
            $item = new OrderItem();
            $item->saveAll($list);
            //删除购物车
            OrderCart::destroy($cart_ids);
            // 提交事务
            Db::commit();
            return ['error'=>false];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['error'=>true,'msg'=>$e->getMessage()];
        }
    }

    /**
     * 获取订单信息
     */
    public function record(int $bid,int $cid,int $offset, int $limit,string $domain)
    {   
        $subOrder = SubOrder::where('bid',$bid)
            ->where('category_id',$cid)
            ->field('id as sub_id,sub_no,bid,category_id,total_price,actual_price,trade_name,trade_phone,address,mark,
            code,shop_address,status,create_time')
            ->order('id','desc')
            ->limit($offset,$limit)
            ->select();
            
        $data = [];
        if($subOrder){
            foreach($subOrder as $v){
                $son = OrderItem::where('sub_no',$v->sub_no)
                ->field('trade_no,sku,no,product_name,number,real_price,free_price,unit_price,custom')
                ->select();
                foreach($son as &$vv){
                    if($vv['custom']){
                        $custom = explode(',',$vv['custom']);
                        !empty($custom['logo']) && $custom['logo'] = $domain. $custom['logo'];
                    }else{
                        $custom = new \ArrayObject();
                    }
                    $vv['custom'] = $custom;
                }
                $data[] = [
                    'sub_id'       => $v->sub_id,
                    'sub_no'       => $v->sub_no,
                    'bid'          => $v->bid,
                    'category_id'  => $v->category_id,
                    'total_price'  => $v->total_price,
                    'actual_price' => $v->actual_price,
                    'trade_name'   => $v->trade_name,
                    'trade_phone'  => $v->trade_phone,
                    'address'      => $v->address,
                    'code'         => $v->code,
                    'shop_address' => $v->shop_address,
                    'status'       => $v->status,
                    'create_time'  => $v->create_time,
                    'son'          => $son
                ];
            }
        }
        return $data;
    }

    /**
     * 确认
     */
    public function confirm(int $bid,int $sub_id,string $mark = '')
    {
        $subOrder = SubOrder::findOrEmpty($sub_id);
        if($subOrder->isEmpty()){
            return ['error' => true,'msg' => '找不到该订单'];
        }
        if($subOrder->bid != $bid){
            return ['error' => true,'msg' => '非法操作'];
        }
        if((int)$subOrder->status !== SubOrder::WAIT_CONFIRM){
            return ['error' => true,'msg' => '该订单无需确认'];
        }
        $subOrder->status = SubOrder::WAIT_SHIP;
        $subOrder->mark   = $mark;
        $subOrder->save();
        return ['error' => false];
    }

    /**
     * 账目核对
     */
    public function check(string $code,string $start, string $end)
    {
        $cids = ProductCategory::where('object',ProductCategory::COMMON_OBJECT)->column('id');
        $result = ['total'=> 0,'actual'=>0];
        $sum =  SubOrder::where('code',$code)
            ->whereTime('create_time', 'between', [$start, $end])
            ->where('category_id','in',$cids)
            ->field('sum(total_price) as total,sum(actual_price) as actual')
            ->select(); 
            foreach($sum as $v){
                $result['total'] = $v->total ?: 0;
                $result['actual'] = $v->actual ?:0;
            }
        return $result;
    }

    /**
     * 后台管理列表
     */
    public function list(string $merchant,int $offset,int $limit,array $where,string $domain)
    {
        if($merchant){
            $bids = Business::where('merchant','like','%'.$merchant.'%')->column('id');
            $where[] = ['bid','in',$bids];
        }
        $order = SubOrder::where($where)
            ->field('id as sub_id,sub_no,bid,category_id,total_price,actual_price,trade_name,trade_phone,address,mark,
            code,shop_address,status,create_time')
            ->order('id','desc')
            ->limit($offset,$limit)
            ->select();
        $data = [];
        if($order){
            foreach($order as $v){
                $son = OrderItem::where('sub_no',$v->sub_no)
                ->field('trade_no,sku,no,product_name,number,real_price,free_price,unit_price,custom')
                ->select();
                foreach($son as &$vv){
                    if($vv['custom']){
                        $custom = explode(',',$vv['custom']);
                        !empty($custom['logo']) && $custom['logo'] = $domain. $custom['logo'];
                    }else{
                        $custom = new \ArrayObject();
                    }
                    $vv['custom'] = $custom;
                }
                $data[] = [
                    'sub_id'       => $v->sub_id,
                    'sub_no'       => $v->sub_no,
                    'bid'          => $v->bid,
                    'category_id'  => $v->category_id,
                    'total_price'  => $v->total_price,
                    'actual_price' => $v->actual_price,
                    'trade_name'   => $v->trade_name,
                    'trade_phone'  => $v->trade_phone,
                    'address'      => $v->address,
                    'code'         => $v->code,
                    'shop_address' => $v->shop_address,
                    'status'       => $v->status,
                    'create_time'  => $v->create_time,
                    'son'          => $son
                ];
            }
        }
        $total = SubOrder::where($where)->count();
        return ['list'=>$data,'total'=>$total];
    }

    /**
     * excel导出
     */
    public function export(string $merchant ,array $where,string $sub_no)
    {
        if($sub_no){
            $path = '/uploads/excel/'.$sub_no.'.Xlsx';
            if(file_exists('../public'.$path)){
                return $path;
            }
        }
        if($merchant){
            $bids = Business::where('merchant','like','%'.$merchant.'%')->column('id');
            $where[] = ['bid','in',$bids];
        }
        $order = SubOrder::where($where)
            ->field('sub_no,bid,category_id,total_price,actual_price,trade_name,trade_phone,address,mark,
            code,shop_address,status,create_time')
            ->order('id','desc')
            ->select();
        $data = [];
        if($order){
            foreach($order as $v){
                $sons = OrderItem::where('sub_no',$v->sub_no)
                ->field('trade_no,sku,no,product_name,number,real_price,free_price,unit_price')
                ->select(); 
                $pro = '';
                foreach($sons as $son){
                    $pro .= $son->product_name.'~'.$son->sku.' '.$son->number.'*'.$son->unit_price."\n";
                }
                $data[] = [
                    'sub_no'       => $v->sub_no,
                    'category_id'  => $v->category_id,
                    'total_price'  => $v->total_price,
                    'actual_price' => $v->actual_price,
                    'trade_name'   => $v->trade_name,
                    'trade_phone'  => $v->trade_phone,
                    'address'      => $v->address,
                    'code'         => $v->code,
                    'shop_address' => $v->shop_address,
                    'status'       => $v->status,
                    'create_time'  => $v->create_time,
                    'pro'          => $pro
                ];
            }
        }
        if(!$data){
            return ;
        }
        $cate = ProductCategory::column('name','id');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('订单明细表');
        $styleArrayBody = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '666666'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '原价');
        $sheet->setCellValue('C1', '实付');
        $sheet->setCellValue('D1', '分类');
        $sheet->setCellValue('E1', '商品信息');
        $sheet->setCellValue('F1', '收货人');
        $sheet->setCellValue('G1', '收货号码');
        $sheet->setCellValue('H1', '收货地址');
        $sheet->setCellValue('I1', '创建时间');

        $k = 2;
        foreach ($data as $value) {
            $sheet->setCellValue('A'.$k, $value['sub_no']."\t");
            $sheet->setCellValue('B'.$k, $value['total_price']);
            $sheet->setCellValue('C'.$k, $value['actual_price']);
            $sheet->setCellValue('D'.$k, $cate[$value['category_id']] ?? '');
            $sheet->setCellValue('E'.$k, $value['pro']);
            $sheet->setCellValue('F'.$k, $value['trade_name']);
            $sheet->setCellValue('G'.$k, $value['trade_phone']."\t");
            $sheet->setCellValue('H'.$k, $value['address']);
            $sheet->setCellValue('I'.$k, $value['create_time']);
            $k++;
        }
        
        $sheet->getStyle('A1:I1')->getFont()->setBold(true)->setName('Arial')->setSize(10);
        $setBorder = 'A2:I'.($k-1);
        $sheet->getStyle($setBorder)->applyFromArray($styleArrayBody);
        if($sub_no){
            $path = '/uploads/excel/'.$sub_no.'.Xlsx';
        }else{
            $path = '/uploads/excel/'.date('YmdHis').'.Xlsx';
        }
        $fileName = '../public'.$path;
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fileName);
        $spreadsheet->disconnectWorksheets();
        return $path;
    }
    

    /**
     * 订单状态修改
     */
    public function change(int $sub_id,int $status)
    {
        $order = SubOrder::findOrEmpty($sub_id);
        if($order->isEmpty()){
            return ['error'=>true,'msg'=>'该订单不存在'];
        }
        if(!in_array((int)$status,SubOrder::STATUS_ARR,true)){
            return ['error'=>true,'msg'=>'非法订单状态'];
        }
        $order->status = $status;
        $order->save();
        return ['error'=>false];
    }

    /**
     * 获取订单信息
     */
    public function getOrderint( $bid,int $cid,int $offset, int $limit)
    {
        return  Db::table('wu_sub_order')->alias('s')
        ->join('wu_order_item i','s.sub_no = i.sub_no')
        ->field('s.sub_no,s.bid,s.category_id,s.total_price,s.actual_price,s.trade_name,s.trade_phone,s.address,s.mark,
        s.code,s.shop_address,s.status,s.create_time,i.trade_no,i.sku,i.no,i.product_name,i.number,i.real_price,i.free_price,i,unit_price,i.custom')
        ->order('s.id','desc')
        ->where('s.bid',$bid)
        ->limit($offset,$limit)
        ->where('s.category_id',$cid)
        ->select();
    }
}