<?php
/**
 * The issue entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class issueEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int|string $issueID. Issues id for Gitlab has '-', such as task-1, bug-1.
     * @access public
     * @return string
     */
    public function get($issueID)
    {
        /* If $issueID has '-', go to productIssue entry point for Gitlab. */
        if(strpos($issueID, '-') !== FALSE) return $this->fetch('productIssue', 'get', array('issueID' => $issueID));

        /* Otherwise, get issue of project. */
        $control = $this->loadController('issue', 'view');
        $control->view($issueID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $this->format($data->data->issue, 'createdDate:time,editedDate:time,assignedDate:time'));
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $this->sendError(400, 'error');
    }

    /**
     * PUT method.
     *
     * @param  int    $issueID
     * @access public
     * @return string
     */
    public function put($issueID)
    {
        $oldIssue = $this->loadModel('issue')->getByID($issueID);

        /* Set $_POST variables. */
        $fields = 'type,title,severity,pri,assignedTo,deadline,desc';
        $this->batchSetPost($fields, $oldIssue);

        $control = $this->loadController('issue', 'edit');
        $control->edit($issueID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $issue = $this->issue->getByID($issueID);
        return $this->send(200, $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $issueID
     * @access public
     * @return string
     */
    public function delete($issueID)
    {
        $control = $this->loadController('issue', 'delete');
        $control->delete($issueID, 'true');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
