<?php
/**
 * The feedback assignto entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class feedbackAssignToEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function post($feedbackID)
    {
        $feedback = $this->loadModel('feedback')->getById($feedbackID);

        $fields = 'assignedTo,comment,mailto';
        $this->batchSetPost($fields);

        $control = $this->loadController('feedback', 'assignTo');
        $control->assignTo($feedbackID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $feedback = $this->loadModel('feedback')->getById($feedbackID);

        return $this->send(200, $this->format($feedback, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }
}
