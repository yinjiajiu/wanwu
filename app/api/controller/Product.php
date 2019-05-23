<?php
namespace app\api\controller;
use app\common\exception\InvalidParamException;
use app\common\service\ProductService;

class Product extends BaseController
{
    /**
     * 获取产品列表
     */
    public function list()
    {
        $query = $this->request->param('query');
        $offset = $this->request->param('offset',0);
        $ps = $this->request->param('pageSize',20);
        $cid = $this->request->param('category_id',0);
        $host = $this->request->domain();
        $where[] = ['category_id','=',$cid];
        if($query){
            $where[] = ['title','like','%'.$query.'%'];
        }
        $result = (new ProductService())->appList($offset,$ps,$where);
        foreach ($result as &$v){
            $v['img'] = $v['img'] ? $host.$v['img'] : '';
        }
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
        $host = $this->request->domain();
        $service = new ProductService();
        $ims = $service->apiImgList($pid);
        $imgs = [];
        foreach ($ims as $v){
            $imgs[] = $v ? $host.$v : '';
        }
        $content = $service->content($pid);
        $this->success(['imgs' => $imgs,'content'=>$content ?: '']);
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

        $ao = (new ProductService())->attribute($pid);
        $ato = [];
        foreach ($ao as $v){
            $ato[$v['attr_id']]['attr_id'] = $v['attr_id'];
            $ato[$v['attr_id']]['attr_name'] = $v['attr_name'];
            $ato[$v['attr_id']]['data'][] = [
                'sku_id'      => $v['sku_id'],
                'option_id'   => $v['option_id'],
                'option_name' => $v['option_name'],
            ];
        };
        if($ato){
            $ato = array_values($ato);
        }
        $this->success($ato);
    }
}
