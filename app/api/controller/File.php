<?php
namespace app\api\controller;
use app\common\service\FileService;
class File extends BaseController
{
    /**
     * 单图上传
     * @return string
     */
    public function upload()
    {
        $domain =  $this->request->domain();
        $file = $this->request->file('file');
        if(!$file){
            $this->error('请选择文件上传');
        }
        $path = '/uploads/api/custom/';
        $info = $file->move( '../public'.$path );
        if($info){
            $name = str_replace('\\','/',$info->getSaveName());
            $data['ext']  = $info->getExtension();
            $data['size'] = formatSize($info->getSize());
            $data['path'] = $path.$name;
            $data['hash'] =  $info->hash('sha1');
            $data['old_name'] =  $info->getName();
            (new FileService())->saveFileLog($data);
            $this->success(['path'=>$path.$name,'url'=> $domain.$path.$name]);
        }else{
            $this->error($file->getError(),130);
        }
    }

    public function delete()
    {
        (new FileService())->localDelete();
    }
}
