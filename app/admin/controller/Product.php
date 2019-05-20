<?php
namespace app\admin\controller;
use app\common\exception\InvalidParamException;
use app\common\exception\ParamNotExistException;
use app\common\service\ProductService;
use think\exception\ValidateException;
use \think\facade\Validate;
class Product extends BaseController
{
    /**
     * 商品大类
     */
    public function category()
    {
        $result = (new ProductService)->getCategory('id as category_id,name');
        $this->success($result);
    }

    /**
     * 商品属性
     */
    public function attribute()
    {
        $category_id = $this->request->param('category_id',0);
        if(!is_numeric($category_id)){
            throw new InvalidParamException('分类id必须为整数');
        }
        $result = (new ProductService)->getAttribute($category_id);
        $this->success($result);
    }

    /**
     * 添加商品属性
     */
    public function addAttr()
    {
        $name = trim($this->request->param('name'));
        if(empty($name)){
            throw new ParamNotExistException();
        }
        $category_id = $this->request->param('category_id',0);
        $is_sale = $this->request->param('is_sale',0);

        if(!is_numeric($category_id) || (!is_numeric($is_sale))) {
            throw new InvalidParamException('分类id或是否销售属性必须为整数');
        }
        $result['name'] = $name;
        $result['category_id'] = $category_id;
        $result['is_sale'] = $is_sale;
        $result['attr_id'] = (new ProductService)->addAttribute($result);
        $this->success($result);
    }

    /**
     * 商品属性值
     */
    public function attrValue()
    {
        $attr_id = $this->request->param('attr_id');
        if(!$attr_id || !is_numeric($attr_id)){
            throw new InvalidParamException('属性必传且必须为整数');
        }
        $result = (new ProductService)->getAttrValue($attr_id);
        $this->success($result);
    }

    /**
     * 添加商品属性值
     */
    public function addOption()
    {
        $attr_id = $this->request->param('attr_id');
        $name = trim($this->request->param('name'));
        $has_src = ($this->request->param('has_src',1));
        if(!$attr_id || !is_numeric($attr_id)){
            throw new InvalidParamException('属性必传且必须为整数');
        }
        if(empty($name)){
            throw new ParamNotExistException();
        }
        $result['attr_id'] = $attr_id;
        $result['name']    = $name;
        $result['has_src'] = $has_src;
        $result['option_id'] = (new ProductService)->addAttrValue($result);
        $this->success($result);
    }

    /**
     * 发布商品
     */
    public function publish()
    {
        $validate = Validate::rule([
            'category_id'  => 'require|number',
            'title'        => 'require',
            'price'        => 'require|float',
            'discount'     => 'float',
            'no'           => 'require|alphaDash',
            'img'          => 'require',
            ])->message([
                'category_id.require' => '分类id必须',
                'category_id.number'  => '分类id必须是整数',
                'title.require'       => '商品名必须',
                'price.require'       => '价格必须',
                'price.float'         => '价格必须精准到小数点后两位',
                'discount.float'      => '折扣价必须精准到小数点后两位',
                'no.require'          => '商品编号必须',
                'no.alphaDash'        => '商品编号必须是字母和数字，下划线_及破折号-',
                'img.require'         => '商品缩略图必须',]
        );
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        (new ProductService)->publish($this->request->param());
        $this->success();
    }

    /**
     * 更新商品
     */
    public function edit()
    {
        $validate = Validate::rule([
            'pid'          => 'require|number',
            'category_id'  => 'require|number',
            'title'        => 'require',
            'price'        => 'require|float',
            'discount'     => 'float',
            'no'           => 'require|alphaDash',
            'img'          => 'require',
        ])->message([
                'category_id.require' => '分类id必须',
                'category_id.number'  => '分类id必须是整数',
                'title.require'       => '商品名必须',
                'price.require'       => '价格必须',
                'price.float'         => '价格必须精准到小数点后两位',
                'discount.float'      => '折扣价必须精准到小数点后两位',
                'no.require'          => '商品编号必须',
                'no.alphaDash'        => '商品编号必须是字母和数字，下划线_及破折号-',
                'img.require'         => '商品缩略图必须',]
        );
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        (new ProductService)->edit($this->request->param());
        $this->success();
    }

    /**
     * 商品列表
     */
    public function list()
    {
        $query = trim($this->request->param('query'));
        $category_id = $this->request->param('category_id');
        $status = $this->request->param('status');
        $page = $this->request->param('page',1);
        $ps = $this->request->param('pageSize',20);
        $offset = $ps * ($page - 1);
        $where = [];
        if($query){
            $where[] = ['title','like','%'.$query.'%'];
        }
        if($category_id){
            $where[] = ['category_id','=',$category_id];
        }
        if($status){
            $where[] = ['status','=',$status];
        }
        $list = (new ProductService)->list($offset,$ps,$where);
        if($list){
            $domain = $this->request->domain();
            foreach($list as &$v){
                $v['img_path'] = $v['img'] ? $domain.$v['img'] : '';
            }
        }
        $total = (new ProductService)->listTotal($where);
        $this->success(['list'=>$list,'total'=>$total]);
    }

    /**
     * 上架，下架，删除
     */
    public function change()
    {
        $id = $this->request->param('pid');
        $status = $this->request->param('status');
        if(!$id || !is_numeric($id)){
            throw new ParamNotExistException();
        }
        if(!is_numeric($status)){
            throw new InvalidParamException();
        }
        $result = (new ProductService)->changeStatus($id,$status);
        if($result){
            $this->success();
        }
        $this->error('无效状态',104);
    }

    /**
     * 商品详情
     */
    public function detail()
    {
        $id = $this->request->param('pid');
        if(!$id || !is_numeric($id)){
            throw new ParamNotExistException();
        }
        $productService = new ProductService;
        $info = $productService->detail($id);
        if(!$info){
            $this->error();
        }
        $domain = $this->request->domain();
        $info['img_path'] = $info['img'] ? $domain.$info['img'] : '';
        $info['content'] = $productService->content($id) ?: '';
        $imgs = $productService->imgList($id);
        foreach($imgs as &$img){
            $img['img_path'] = $img['img'] ? $domain.$img['img'] : '';
        }
        $info['imgs'] = $imgs;
        $ao = $productService->attribute($id);
        $ato = [];
        foreach ($ao as $v){
           $ato[$v['attr_id']]['attr_id'] = $v['attr_id'];
           $ato[$v['attr_id']]['attr_name'] = $v['attr_name'];
           $ato[$v['attr_id']]['data'][] = [
               'option_id'   => $v['option_id'],
               'option_name' => $v['option_name'],
               'has_src'     => $v['has_src'],
               'path'        => $v['path'],
               'file_path'   => $v['path'] ? $domain.$v['path'] : ''
           ];
        }
        $info['attr'] = array_values($ato);
        $this->success($info);
    }
}
