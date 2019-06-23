<?php
namespace app\api\controller;
use app\common\exception\InvalidParamException;
use app\common\service\ProductService;
use function PHPSTORM_META\elementType;
use think\facade\Validate;

class Product extends BaseController
{
    /**
     * 获取产品列表
     */
    public function list()
    {
        $query = $this->request->param('query');
        $page = $this->request->param('page',1);
        $ps = $this->request->param('pageSize',20);
        $offset = ($page - 1) * $ps;
        $cid = $this->request->param('category_id',0);
        $domain = $this->request->domain();
        $where[] = ['category_id','=',$cid];
        if($query){
            $where[] = ['title','like','%'.$query.'%'];
        }
        $result = (new ProductService())->appList($offset,$ps,$where);
        foreach ($result as &$v){
            $v['img_path'] = $v['img'] ? $domain.$v['img'] : '';
            unset($v['img']);
        }
        $this->success($result);

    }

    /**
     * 单个商品详情
     */
    public function baseInfo()
    {
        $pid = $this->request->param('pid');
        if(!$pid || !is_numeric($pid)){
            throw new InvalidParamException();
        }
        $result = (new ProductService())->getBaseInfo($pid);
        $domain = $this->request->domain();
        $result['img_path'] =  !empty($result['img']) ? $domain.$result['img'] : '';
        unset($result['img']);
        $this->success($result);
    }

    /**
     * 产品详情
     */
    public function detail()
    {
        $pid = $this->request->param('pid');
        if(!$pid || !is_numeric($pid)){
            throw new InvalidParamException();
        }
        $domain = $this->request->domain();
        $service = new ProductService();
        $ims = $service->apiImgList($pid);
        $imgs = [];
        foreach ($ims as $v){
            $imgs[] = $v ? $domain.$v : '';
        }
        $content = $service->content($pid);
        $this->success(['pid'=>(int)$pid,'imgs' => $imgs,'content'=>$content ?: '']);
    }

    /**
     * 选择产品属性
     */
    public function option()
    {
        $pid = $this->request->param('pid');
        if(!$pid || !is_numeric($pid)){
            throw new InvalidParamException();
        }
        $sku_ids = $this->request->param('sku_ids','');
        if($sku_ids){
            $sku_ids = explode(',',$sku_ids);
        }else{
            $sku_ids = [];
        }
        $domain = $this->request->domain();
        $ao = (new ProductService())->attribute($pid);
        $ato = [];
        foreach ($ao as $v){
            $ato[$v['attr_id']]['attr_id'] = $v['attr_id'];
            $ato[$v['attr_id']]['attr_name'] = $v['attr_name'];
            $ato[$v['attr_id']]['has_src'] = $v['has_src'];
            $ato[$v['attr_id']]['data'][] = [
                'sku_id'      => $v['sku_id'],
                'option_id'   => $v['option_id'],
                'option_name' => $v['option_name'],
                'file_path'   => $v['path'] ? $domain.$v['path'] : '',
                'has_checked' => in_array($v['sku_id'],$sku_ids) ? true : false
            ];
        };
        if($ato){
            $ato = array_values($ato);
        }
        $this->success($ato);
    }
}
