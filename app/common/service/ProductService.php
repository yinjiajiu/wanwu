<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */

namespace app\common\service;

use app\common\model\AttributeRelate;
use app\common\model\Product;
use app\common\model\ProductCategory;
use app\common\model\ProductAttribute;
use app\common\model\AttributeOption;
use app\common\model\ProductContent;
use app\common\model\ProductImg;
use app\common\model\UploadLog;
use think\facade\Db;

class ProductService
{
    /**
     * 获取商品大类
     * @param string $field
     * @return object
     */
    public function getCategory(string $field) :object
    {
        return ProductCategory::where('status',ProductCategory::STATUS_VALID)
            ->order('sort','asc')
            ->field($field)
            ->select();
    }

    public function getBaseInfo(int $pid)
    {
        return Product::fetchArray()
            ->field('id as pid,no,price,title,category_id,tags,
            marque,img,keywords,brand,unit,desc,discount,status,barcode,stock')
            ->find($pid);
    }

    /**
     * 获取商品属性
     * @param int $category_id
     * @param string $asc
     * @return object
     */
    public function getAttribute(int $category_id ,string $asc = 'asc') :object
    {
        return ProductAttribute::where('category_id',$category_id)
            ->field('id as attr_id,category_id,name,is_sale,has_src')
            ->order('sort',$asc)
            ->select();
    }

    /**
     * 添加商品属性
     * @param array $data
     *
     */
    public function addAttribute(array $data) :int
    {
        $attr = ProductAttribute::create($data, ['category_id','name','is_sale','sort','has_src']);
        return $attr->id;
    }

    /**
     * 获取商品属性值
     * @param int $category_id
     * @param string $asc
     * @return object
     */
    public function getAttrValue(int $attr_id ,string $asc = 'asc') :object
    {
        return AttributeOption::where('attr_id',$attr_id)
            ->field('id as option_id,attr_id,name')
            ->order('sort',$asc)
            ->select();
    }

    /**
     * 添加商品属性值
     * @param array $data
     *
     */
    public function addAttrValue(array $data) :int
    {
        $option = AttributeOption::create($data, ['attr_id','name','sort']);
        return $option->id;
    }

    /**
     * 发布商品
     * @param array $param
     * @throws \think\exception\PDOException
     */
    public function publish(array $param)
    {
        $data['category_id'] = $param['category_id'];
        $data['no'] = $param['no'];
        $data['title'] = $param['title'];
        $data['price'] = $param['price'];
        $data['discount'] = $param['discount'] ?? $param['price'];
        $data['img'] = $param['img'] ?? '' ;
        $data['stock'] = $param['stock'] ?? 99999;

        /**
         *  attrs:json  {"attr_id":{"option_id":"path"},.....}
         *
         *  imgs:string,逗号隔开 "img","img","img","img"
         */
        $attrs = $param['attrs'] ?? '';
        $imgs = $param['imgs'] ?? '';
        $date = date('Y-m-d H:i:s');
        Db::startTrans();
        try {
            $pid = $this->addProduct($data);
            if($attrs){
                $attrs = json_decode($attrs,true);
                foreach($attrs as $k=>$v){
                    foreach($v as $kk=>$vv){
                        AttributeRelate::create([
                            'pid'       => $pid,
                            'attr_id'   => $k,
                            'option_id' => $kk,
                            'path'      => $vv,
                        ]);
                        UploadLog::where('path',$vv)->delete();
                    }
                }
            }
            if($imgs){
                $imgs = explode(',',$imgs);
                foreach($imgs as $v){
                    $log = UploadLog::where('path',$v)->find();
                    if(!$log) continue;
                    ProductImg::create([
                        'pid'         => $pid,
                        'img'         => $v,
                        'size'        => $log->size,
                        'ext'         => $log->ext,
                        'name'        => $log->old_name,
                        'create_time' => $date,
                        'update_time' => $date,
                    ]);
                    $log->delete();
                }
            }
            $param['img'] && UploadLog::where('path',$param['img'])->delete();
            $this->addContent($pid,$param['content'] ?? '',$param['title']);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
        return true;
    }

    /**
     * 修改商品
     * @param array $param
     * @throws \think\exception\PDOException
     */
    public function edit(array $param)
    {
        $id = $param['pid'];
        $data['category_id'] = $param['category_id'];
        $data['no'] = $param['no'];
        $data['title'] = $param['title'];
        $data['price'] = $param['price'];
        $data['discount'] = $param['discount'] ?? $param['price'];
        $data['img'] = $param['img'] ?? '' ;

        /**
         *  attrs:json  {"attr_id":{"option_id":"path"},.....}
         *
         *  imgs:string,逗号隔开 "img","img","img","img"
         */
        $attrs = $param['attrs'] ?? '';
        $imgs = $param['imgs'] ?? '';
        $product = Product::find($id);
        if(!$product) return false;
        Db::startTrans();
        try {
            $product->save($_POST);
            if($attrs){
                AttributeRelate::where('pid',$id)->delete();
                $attrs = json_decode($attrs,true);
                foreach($attrs as $k=>$v){
                    foreach($v as $kk=>$vv){
                        AttributeRelate::create([
                            'pid'       => $id,
                            'attr_id'   => $k,
                            'option_id' => $kk,
                            'path'      => $vv,
                        ]);
                        UploadLog::where('path',$vv)->delete();
                    }
                }
            }
            $date = date('Y-m-d H:i:s');
            if($imgs){
                $imgs = explode(',',$imgs);
                $uc = [];
                foreach($imgs as $v){
                    $uc[] = $v;
                    $log = UploadLog::where('path',$v)->find();
                    if(!$log) continue;
                    ProductImg::create([
                        'pid'         => $id,
                        'img'         => $v,
                        'size'        => $log->size,
                        'ext'         => $log->ext,
                        'name'        => $log->old_name,
                        'create_time' => $date,
                        'update_time' => $date,
                    ]);
                    $log->delete();
                }
                if($uc){
                    ProductImg::where('pid',$id)->whereNotIn('img',$uc)->delete();
                } 
            }
            $param['img'] && UploadLog::where('path',$param['img'])->delete();
            ProductContent::where('pid',$id)->update([
                'content'=>$param['content'] ?? '','title'=>$param['title'] ?? '']);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
        return true;
    }


    /**
     * 添加商品
     * @param array $data
     * @return int
     */
    public function addProduct(array $data) : int
    {
        $data['status'] = Product::UP_SHELF;
        $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');
        $product = Product::create($data);
        return $product->id;
    }

    /**
     * 添加商品详情
     * @param int $pid
     * @param string $content
     * @param string $title
     * @return int
     */
    public function addContent(int $pid ,string $content ,string $title = '') : int
    {
        $date = date('Y-m-d H:i:s');
        $content = ProductContent::create([
            'pid'     => $pid,
            'title'   => $title,
            'content' => $content,
            'create_time' => $date,
            'update_time' => $date,
        ]);
        return $content->id;
    }

    /*
     * 商品列表
     */
    public function list(int $offset , int $ps ,array $where = [])
    {
        $list = Product::fetchArray()
            ->where($where)
            ->field('id as pid,no,price,title,category_id,tags,
            marque,img,keywords,brand,unit,desc,discount,status,barcode,stock')
            ->limit($offset,$ps)
            ->order('id','desc')
            ->select();
        return $list;
    }

    /**
     * 商品数量
     * @param array $where
     * @return int
     */
    public function listTotal(array $where = []) : int
    {
        return Product::where($where)->count('id');
    }

    /**
     * 商品状态变化
     */
    public function changeStatus(int $pid ,int $status) : bool
    {
        if(!in_array($status,Product::STATUS_LIST,true)){
            return false;
        }
        Product::update(['status' => $status], ['id' => $pid]);
        return true;
    }

    /**
     * 获取商品详情
     */
    public function detail(int $pid)
    {
        $info =  Product::field('id as pid,no,price,title,category_id,tags,
            marque,img,keywords,brand,unit,desc,discount,status,barcode,stock')
            ->find($pid);
        return $info;
    }

    /**
     * 商品内容
     */
    public function content(int $pid)
    {
        return ProductContent::where('pid',$pid)->value('content');
    }

    /**
     * 商品轮播图
     */
    public function imgList(int $pid)
    {
        return ProductImg::where('pid',$pid)->field('id as img_id,img,size,name')->select();
    }

    /**
     * 获取商品所有属性
     */
    public function attribute(int $pid)
    {
        $ao = Db::table('wu_attribute_relate')->alias('ar')
            ->join('wu_product_attribute pa','ar.attr_id = pa.id')
            ->join('wu_attribute_option ao','ar.option_id = ao.id')
            ->where('ar.pid',$pid)
            ->field('ar.id as sku_id,ar.attr_id,ar.option_id,ar.path,pa.name as attr_name,ao.name as option_name,pa.has_src')
            ->select();
        return $ao;
    }

    /**
     * 小程序商品列表
     */
    public function appList(int $offset , int $ps ,array $where = [])
    {
        $where[] = ['status','=',Product::UP_SHELF];
        $list = Product::fetchArray()
            ->where($where)
            ->field('id as pid,no,price,title,category_id,tags,
            marque,img,keywords,brand,unit,desc,discount,barcode')
            ->limit($offset,$ps)
            ->order('id','desc')
            ->select();
        return $list;
    }

    /**
     * 获取轮播图
     */
    public function apiImgList(int $pid)
    {
        return ProductImg::where('pid',$pid) ->column('img','id');
    }

}