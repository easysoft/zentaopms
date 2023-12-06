<?php
/**
 * The bug close entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 **/
class bugCloseEntry extends entry
{
    /** 
     * POST method.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function post($bugID)
    {   
        $fields = 'comment';
        $this->batchSetPost($fields);

        $control = $this->loadController('bug', 'close');
        $control->close($bugID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        $bug = $this->loadModel('bug')->getByID($bugID);

        return $this->send(200, $this->format($bug, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }   
}

