<?php
/**
 * The control file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class group extends control
{
    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('company')->setMenu();
        $this->loadModel('user');
    }

    /**
     * Browse groups.
     * 
     * @param  int    $companyID 
     * @access public
     * @return void
     */
    public function browse($companyID = 0)
    {
        if($companyID == 0) $companyID = $this->app->company->id;

        $header['title'] = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[]      = $this->lang->group->browse;

        $groups     = $this->group->getList($companyID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->groups     = $groups;
        $this->view->groupUsers = $groupUsers;

        $this->display();
    }

    /**
     * Create a group.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->group->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->header->title = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->view->position[]    = $this->lang->group->create;
        $this->display();
    }

    /**
     * Edit a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function edit($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->update($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $header['title'] = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->edit;
        $position[]      = $this->lang->group->edit;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->group    = $this->group->getById($groupID);

        $this->display();
    }

    /**
     * Copy a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function copy($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->copy($groupID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->header->title = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->copy;
        $this->view->position[]    = $this->lang->group->copy;
        $this->view->group         = $this->group->getById($groupID);
        $this->display();
    }

    /**
     * Manage privleges of a group. 
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function managePriv($type = 'byGroup', $param = 0)
    {
        if($type == 'byGroup') $groupID = $param;
        $this->view->type = $type;
        foreach($this->lang->resource as $moduleName => $action) $this->app->loadLang($moduleName);

        if(!empty($_POST))
        {
            if($type == 'byGroup')  $result = $this->group->updatePrivByGroup($groupID);
            if($type == 'byModule') $result = $this->group->updatePrivByModule();
            print(js::alert($result ? $this->lang->group->successSaved : $this->lang->group->errorNotSaved));
        }

        if($type == 'byGroup')
        {
            $group      = $this->group->getById($groupID);
            $groupPrivs = $this->group->getPrivs($groupID);

            $this->view->header->title = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[]    = $group->name . $this->lang->colon . $this->lang->group->managePriv;

            $this->view->group      = $group;
            $this->view->changelogs = $this->lang->changelog;
            $this->view->groupPrivs = $groupPrivs;

        }
        elseif($type == 'byModule')
        {
            $this->view->header->title = $this->lang->company->common . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[]    = $this->lang->group->managePriv;

            foreach($this->lang->resource as $module => $moduleActions)
            {
                $modules[$module] = $this->lang->$module->common;
                foreach($moduleActions as $action)
                {
                    $actions[$module][$action] = $this->lang->$module->$action;
                }
            }
            $this->view->groups  = $this->group->getPairs();
            $this->view->modules = $modules;
            $this->view->actions = $actions;
        }
        $this->display();
    }

    /**
     * Manage members of a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function manageMember($groupID)
    {
        if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
        $group      = $this->group->getById($groupID);
        $groupUsers = $this->group->getUserPairs($groupID);
        $allUsers   = $this->user->getPairs('noclosed|noempty|noletter');
        $otherUsers = array_diff_assoc($allUsers, $groupUsers);

        $header['title'] = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[]      = $group->name . $this->lang->colon . $this->lang->group->manageMember;

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->group      = $group;
        $this->view->groupUsers = $groupUsers;
        $this->view->otherUsers = $otherUsers;

        $this->display();
    }

    /**
     * Delete a group.
     * 
     * @param  int    $groupID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($groupID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->group->confirmDelete, $this->createLink('group', 'delete', "groupID=$groupID&confirm=yes")));
        }
        else
        {
            $this->group->delete($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
    }
}
