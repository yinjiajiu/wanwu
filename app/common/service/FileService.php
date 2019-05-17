<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */

namespace app\common\service;

use app\common\model\UploadLog;
use think\Exception;

class FileService
{
    /**
     * 保存图片
     * @param array $data
     * @return bool
     */
    public function saveFileLog(array $data) :bool
    {
        $data['date'] = date('Ymd');
        try{
            UploadLog::create($data, ['path','size','ext','old_name','hash','date']);
        }catch(Exception $e){
            return true;
        }
        return true;
    }

    /**
     * 清理本地过期的垃圾图片
     * @return void
     */
    public function localDelete()
    {
        $date = date('Ymd',strtotime('-1 days'));
        $paths = UploadLog::where('date','<=',$date)
            ->field('path')
            ->column('path','id');
        $ids = [];
        foreach($paths as $id=>$path){
            $ids[] = $id;
            @unlink('../public'.$path);
        }
        $ids && UploadLog::destroy($ids);
    }
}