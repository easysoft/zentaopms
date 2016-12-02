<?php
/**
 * The control file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        $this->view->tree        = $this->dept->getDataStructure(0);
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
            die(js::reload('parent'));
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
            $this->dept->manageChild($_POST['parentDeptID'], $_POST['depts']);
            die(js::reload('parent'));
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
            die(js::alert($this->lang->dept->successSave) . js::reload('parent'));
        }

        $dept  = $this->dept->getById($deptID);
        $users = $this->loadModel('user')->getPairs('nodeleted|noletter|noclosed');

        $this->view->optionMenu = $this->dept->getOptionMenu();

        $this->view->dept  = $dept;
        $this->view->users = $users;

        /* Remove self and childs from the $optionMenu. Because it's parent can't be self or childs. */
        $childs = $this->dept->getAllChildId($deptID);
        foreach($childs as $childModuleID) unset($this->view->optionMenu[$childModuleID]);

        die($this->display());
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
        $users = $this->dept->getUsers($deptID);
        if($sons)  die(js::alert($this->lang->dept->error->hasSons));
        if($users) die(js::alert($this->lang->dept->error->hasUsers));

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->dept->confirmDelete, $this->createLink('dept', 'delete', "deptID=$deptID&confirm=yes")));
        }
        else
        {
            $this->dept->delete($deptID);
            die(js::reload('parent'));
        }
    }

    /**
     * Ajax get users 
     * 
     * @param  int    $dept 
     * @param  string $user 
     * @access public
     * @return void
     */
    public function ajaxGetUsers($dept, $user = '')
    {
        $users = array('' => '') + $this->dept->getDeptUserPairs($dept);
        die(html::select('user', $users, $user, "class='form-control chosen'"));
    }
}
