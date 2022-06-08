<?php
/**
 * The department entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class departmentEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $departmentID
     * @access public
     * @return void
     */
    public function get($departmentID)
    {
        $dept = $this->loadModel('dept')->getByID($departmentID);

        if(!$dept) return $this->send404();
        return $this->send(200, $dept);
    }

    /**
     * PUT method.
     *
     * @param  int    $departmentID
     * @access public
     * @return void
     */
    public function put($departmentID)
    {
        $oldDept = $this->loadModel('dept')->getByID($departmentID);

        /* Set $_POST variables. */
        $fields = 'parent,name,manager';
        $this->batchSetPost($fields, $oldDept);

        $this->requireFields('name');
        $control = $this->loadController('dept', 'edit');
        $control->edit($departmentID);

        $this->getData();
        $department = $this->dept->getByID($departmentID);
        $this->send(200, $department);
    }

    /**
     * DELETE method.
     *
     * @param  int    $departmentID
     * @access public
     * @return void
     */
    public function delete($departmentID)
    {
        $control = $this->loadController('dept', 'delete');
        $control->delete($departmentID, 'true');

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendSuccess(200, 'success');
    }
}
