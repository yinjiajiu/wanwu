<?php
namespace app\admin\controller;
use app\common\service\UploadService;
class Index extends BaseController
{
    /**
     * 单图上传
     * @return string
     */
    public function upload()
    {
        $domain =  $this->request->domain();
        $file = request()->file('image');
        if(!$file){
            $this->error('请选择文件上传');
        }
        $path = '/uploads/admin/';
        $info = $file->validate(['size'=>1024*1024*2])->move( '../public/'.$path );
        if($info){
            $name = $info->getSaveName();
            $data['ext']  = $info->getExtension();
            $data['size'] = formatSize($info->getSize());
            $data['path'] = $path.$name;
            $data['hash'] =  $info->hash('sha1');
            $data['old_name'] =  $info->getName();
            (new UploadService())->saveFileLog($data);
            $data['url'] = $domain.$path.$name;
            $this->success($data);
        }else{
            $this->error($file->getError(),130);
        }
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hellggggggggo,' . $name;
    }
}
