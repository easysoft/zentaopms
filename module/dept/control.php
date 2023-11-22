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
            $deptIDList = $this->dept->manageChild($_POST['parentDeptID'], $_POST['depts']);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'idList' => $deptIDList));
        }
    }

    /**
     * Edit dept.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function edit($deptID)
    {
        if(!empty($_POST))
        {
            $this->dept->update($deptID);
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->dept->successSave, 'closeModal' => true, 'load' => true));
        }

        $dept  = $this->dept->getById($deptID);
        $users = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted|all', $dept->manager, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["manager"] = $this->config->user->moreLink;

        $this->view->optionMenu = $this->dept->getOptionMenu();

        $this->view->dept  = $dept;
        $this->view->users = $users;

        /* Remove self and childs from the $optionMenu. Because it's parent can't be self or childs. */
        $childs = $this->dept->getAllChildId($deptID);
        foreach($childs as $childModuleID) unset($this->view->optionMenu[$childModuleID]);

        $this->display();
    }

    /**
     * 删除部门。
     * Delete a department.
     *
     * @param  int    $deptID
     * @access public
     * @return string
     */
    public function delete(int $deptID): string
    {
        /* Check this dept when delete. */
        $sons  = $this->dept->getSons($deptID);
        $users = $this->dept->getUsers('all', $deptID);
        if($sons)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->dept->error->hasSons));
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->dept->error->hasSons}');"));
        }
        if($users)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->dept->error->hasUsers));
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->dept->error->hasUsers}');"));
        }

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        $this->dept->deleteDept($deptID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
