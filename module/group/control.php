<?php
/**
 * The control file of group module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class group extends control
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('company')->setMenu();
        $this->loadModel('user');
    }

    /* 分组列表。*/
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

    /* 创建一个用户组。*/
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

    /* 编辑一个用户组。*/
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

    /* 复制一个用户组。*/
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


    /* 维护权限。*/
    public function managePriv($groupID)
    {
        if(!empty($_POST))
        {
            $this->group->updatePriv($groupID);
            die(js::alert($this->lang->group->successSaved));
        }

        $group      = $this->group->getById($groupID);
        $groupPrivs = $this->group->getPrivs($groupID);

        $this->view->header->title = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->managePriv;
        $this->view->position[]    = $group->name . $this->lang->colon . $this->lang->group->managePriv;

        $this->view->group      = $group;
        $this->view->groupPrivs = $groupPrivs;

        /* 加载每一个模块的语言文件。*/
        foreach($this->lang->resource as $moduleName => $action) $this->app->loadLang($moduleName);
        $this->display();
    }

    /* 维护用户。*/
    public function manageMember($groupID)
    {
        if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
        $group      = $this->group->getById($groupID);
        $groupUsers = $this->group->getUserPairs($groupID);
        $groupUsers = join(',', array_keys($groupUsers));
        $allUsers   = $this->user->getPairs('noclosed|noempty|noletter');

        $header['title'] = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[]      = $group->name . $this->lang->colon . $this->lang->group->manageMember;

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->group      = $group;
        $this->view->groupUsers = $groupUsers;
        $this->view->allUsers   = $allUsers;

        $this->display();
    }

    /* 删除一个分组。*/
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
