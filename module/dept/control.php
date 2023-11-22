<?php
declare(strict_types=1);
/**
 * The control file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: control.php 4157 2013-01-20 07:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
class dept extends control
{
    /**
     * 部门结构维护页面。
     * Department management page.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function browse(int $deptID = 0)
    {
        $this->view->title       = $this->lang->dept->manage . $this->lang->colon . $this->app->company->name;
        $this->view->deptID      = $deptID;
        $this->view->depts       = $this->dept->getTreeMenu(0, array('deptmodel', 'createManageLink'));
        $this->view->sons        = $this->dept->getSons($deptID);
        $this->view->tree        = $this->dept->getDataStructure();
        $this->view->parentDepts = $this->dept->getParents($deptID);
        $this->display();
    }

    /**
     * 部门结构排序。
     * Sort of departments.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if(!empty($_POST))
        {
            $this->dept->updateOrder($_POST['orders']);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }
    }

    /**
     * 修改子部门结构。
     * Update child departments.
     *
     * @access public
     * @return void
     */
    public function manageChild()
    {
        if(!empty($_POST))
        {
            $formData   = form::data($this->config->dept->form->manage)->get();
            $deptIDList = $this->dept->manageChild($formData->parentDeptID, $formData->depts);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'idList' => $deptIDList));
        }
    }

    /**
     * 编辑部门。
     * Edit a dept.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function edit(int $deptID)
    {
        if(!empty($_POST))
        {
            /* 获取表单提交内容。 */
            $formData = form::data($this->config->dept->form->edit)->setDefault('id', $deptID)->get();
            $this->dept->update($formData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'status' => 'success', 'message' => $this->lang->dept->successSave, 'closeModal' => true, 'load' => true));
        }

        /* Remove self and childs from the $optionMenu. Because it's parent can't be self or childs. */
        $optionMenu = $this->dept->getOptionMenu();
        $childs     = $this->dept->getAllChildId($deptID);
        foreach($childs as $childModuleID) unset($optionMenu[$childModuleID]);

        $dept = $this->dept->fetchByID($deptID);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["manager"] = $this->config->user->moreLink;

        $this->view->dept       = $dept;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted|all', $dept->manager, $this->config->maxCount);;
        $this->view->optionMenu = $optionMenu;
        $this->display();
    }

    /**
     * 删除部门。
     * Delete a department.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function delete(int $deptID)
    {
        /* 部门下有子部门的无法被删除。 */
        $sons = $this->dept->getSons($deptID);
        if($sons)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->dept->error->hasSons));
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->dept->error->hasSons}');"));
        }

        /* 部门下有人员的无法被删除。 */
        $users = $this->dept->getUsers('all', $deptID);
        if($users)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->dept->error->hasUsers));
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->dept->error->hasUsers}');"));
        }

        $this->dept->deleteDept($deptID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'status' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Ajax get users
     *
     * @param  int    $dept
     * @param  string $user
     * @param  string $key  id|account
     * @access public
     * @return void
     */
    public function ajaxGetUsers($dept, $user = '', $key = 'account')
    {
        $users = $this->dept->getDeptUserPairs($dept, $key);
        return print(html::select('user', $users, $user, "class='form-control chosen'"));
    }
}
