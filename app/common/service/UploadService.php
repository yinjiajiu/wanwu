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

class UploadService
{
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
}/uploads/admin/20190517\298361adbfbf1ef017d98658882fb7e8.jpg