<?php
namespace app\admin\controller;
use app\common\service\FileService;
use app\common\helper\ImgHelper;

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
        $path = '/uploads/admin/';
        $info = $file->move( '../public'.$path );
        if($info){
            $name = str_replace('\\','/',$info->getSaveName());
            //如果上传的实图片，压缩图片
            if($info->checkImg()){
                $imgHelper = new ImgHelper('../public'.$path.$name,0.5);
                $path = '/uploads/admin/thumb/'.$name;
                $imgHelper->compressImg('../public'.$path);
            }else{
                $path .= $name;
            }
            $data['ext']  = $info->getExtension();
            $data['size'] = formatSize($info->getSize());
            $data['path'] = $path;
            $data['hash'] =  $info->hash('sha1');
            $data['old_name'] =  $info->getName();
            (new FileService())->saveFileLog($data);
            $this->success(['path'=>$path,'url'=> $domain.$path]);
        }else{
            $this->error($file->getError(),130);
        }
    }

    public function delete()
    {
        (new FileService())->localDelete();
    }
}
