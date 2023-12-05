<?php
/**
 * The product entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class feedbackEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function get($feedbackID)
    {
        $control = $this->loadController('feedback', 'adminView');
        $control->adminView($feedbackID);

        $data = $this->getData();

        $feedback = $data->data->feedback;

        $feedback->publicStatus = $feedback->public;
        $feedback->productName  = $data->data->product;
        $feedback->moduleName   = isset($data->data->modulePath[0]->name) ? $data->data->modulePath[0]->name : '/';
        $feedback->resultType   = $data->data->type;
        if(isset($feedback->resultInfo) and $feedback->resultInfo->deleted == 0) $feedback->resultStatus = $this->loadModel('feedback')->processStatus($feedback->resultType, $feedback->resultInfo);

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $feedback->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->feedback);
        return $this->send(200, $this->format($feedback, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function put($feedbackID)
    {
        $oldFeedback = $this->loadModel('feedback')->getById($feedbackID);

        $fields = 'module,product,type,title,public,desc,status,feedbackBy,notifyEmail,notify,uid';
        $this->batchSetPost($fields, $oldFeedback);

        $control = $this->loadController('feedback', 'edit');
        $control->edit($feedbackID, '');

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $feedback = $this->feedback->getByID($feedbackID);

        return $this->send(200, $this->format($feedback, 'openedBy:user,openedDate:time,reviewedBy:user,reviewedDate:time,processedBy:user,processedDate:time,closedBy:user,closedDate:time,editedBy:user,editedDate:time,mailto:userList,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function delete($feedbackID)
    {
        $control = $this->loadController('feedback', 'delete');
        $control->delete($feedbackID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}

