<?php
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
    const NEW_CHILD_COUNT = 10;

    /**
     * Construct function, set menu.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('company')->setMenu();
    }

    /**
     * Browse a department.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function browse($deptID = 0)
    {
        $parentDepts = $this->dept->getParents($deptID);
        $this->view->title       = $this->lang->dept->manage . $this->lang->colon . $this->app->company->name;
        $this->view->position[]  = $this->lang->dept->manage;
        $this->view->deptID      = $deptID;
        $this->view->depts       = $this->dept->getTreeMenu($rootDeptID = 0, array('deptmodel', 'createManageLink'));
        $this->view->parentDepts = $parentDepts;
        $this->view->sons        = $this->dept->getSons($deptID);
        $this->view->tree        = $this->dept->getDataStructure();
        $this->display();
    }

    /**
     * Update the departments order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if(!empty($_POST))
        {
            $this->dept->updateOrder($_POST['orders']);
            return print(js::reload('parent'));
        }
    }

    /**
     * Manage childs.
     *
     * @access public
     * @return void
     */
    public function manageChild()
    {
        if(!empty($_POST))
        {
            $deptIDList = $this->dept->manageChild($_POST['parentDeptID'], $_POST['depts']);
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $deptIDList));
            return print(js::reload('parent'));
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
            return print(js::alert($this->lang->dept->successSave) . js::reload('parent'));
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
     * Delete a department.
     *
     * @param  int    $deptID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($deptID, $confirm = 'no')
    {
        /* Check this dept when delete. */
        $sons  = $this->dept->getSons($deptID);
        $users = $this->dept->getUsers('all', $deptID);
        if($sons)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->dept->error->hasSons));
            return print(js::alert($this->lang->dept->error->hasSons));
        }
        if($users)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => $this->lang->dept->error->hasUsers));
            return print(js::alert($this->lang->dept->error->hasUsers));
        }

        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->dept->confirmDelete, $this->createLink('dept', 'delete', "deptID=$deptID&confirm=yes")));
        }
        else
        {
            $this->dept->delete($deptID);
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::reload('parent'));
        }
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
        $users = array('' => '') + $this->dept->getDeptUserPairs($dept, $key);
        return print(html::select('user', $users, $user, "class='form-control chosen'"));
    }
}
