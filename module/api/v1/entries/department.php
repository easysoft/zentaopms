<?php
/**
 * The department entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class departmentEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $departmentID
     * @access public
     * @return string
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
     * @return string
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
        return $this->send(200, $department);
    }

    /**
     * DELETE method.
     *
     * @param  int    $departmentID
     * @access public
     * @return string
     */
    public function delete($departmentID)
    {
        $control = $this->loadController('dept', 'delete');
        $control->delete($departmentID, 'true');

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendSuccess(200, 'success');
    }
}
