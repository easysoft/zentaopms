<?php
/**
 * The host entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class hostSubmitEntry extends baseEntry
{
    /**
     * Listen host task finish submit.
     *
     * @param  int|string $userID
     * @access public
     * @return void
     */
    public function post()
    {
        /* Check authorize. */
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? substr($header['Authorization'], 7) : '';
        if(!$token) return $this->sendError(401, 'Unauthorized');

        $now    = helper::now();

        /* Check param. */
        $image = new stdclass();
        $task  = $this->requestBody->task;
        $image->status = $this->requestBody->status;

        $this->dao = $this->loadModel('common')->dao;
        $id = $this->dao->select('id')->from(TABLE_ZAHOST)
            ->where('tokenSN')->eq($token)
            ->andWhere('tokenTime')->gt($now)->fi()
            ->fetch('id');
            
        if(!$id) return $this->sendError(400, 'Secret error.');

        $imageInfo = $this->dao->select('`status`,`from`', 'name')->from(TABLE_IMAGE)
            ->where('id')->eq($task)
            ->fetch();
        if(empty($imageInfo)) return $this->sendSuccess(200, 'success');
        if($imageInfo->from == 'snapshot' && $imageInfo->status == 'restoring') 
        {
            if(in_array($image->status, array('failed', 'completed'))) $image->status = $image->status == 'completed' ? 'restore_completed' : 'restore_failed';
        }
        else if($imageInfo->from == 'snapshot' && $imageInfo->status == 'creating')
        {
            if($image->status == 'failed')
            {
                $this->dao->delete()->from(TABLE_IMAGE)->where("id")->eq($task)->exec();
                return $this->sendSuccess(200, 'success');
            }
        }

        $this->dao->update(TABLE_IMAGE)->data($image)->where("id")->eq($task)->exec();

        return $this->sendSuccess(200, 'success');
    }
}
