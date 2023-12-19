<?php
declare(strict_types=1);
/**
 * The control file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: control.php 4648 2013-04-15 02:45:49Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class group extends control
{
    /**
     * 构造方法，将所有resource追加到package里。
     * Construct function.
     *
     * @param  $moduleName
     * @param  $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->appendResourcePackages();
    }

    /**
     * 权限分组列表页面。
     * Browse groups.
     *
     * @access public
     * @return void
     */
    public function browse()
    {
        $groups     = $this->group->getList();
        $groupUsers = $this->group->getAllGroupMembers();
        foreach($groups as $group)
        {
            $group->actions = array();
            $group->users   = implode(',', zget($groupUsers, $group->id, array()));
        }

        $this->view->title  = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $this->view->groups = $groups;

        $this->display();
    }

    /**
     * 创建权限分组。
     * Create a group.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $group = form::data($this->config->group->form->create)->get();
            if($this->post->limited) $group->role = 'limited';
            $this->group->create($group);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->display();
    }

    /**
     * 编辑权限分组。
     * Edit a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function edit(int $groupID)
    {
        if(!empty($_POST))
        {
            $group = form::data($this->config->group->form->edit)->get();
            $this->group->update($groupID, $group);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->edit;
        $this->view->group = $this->group->getByID($groupID);
        $this->display();
    }

    /**
     * 复制权限分组。
     * Copy a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function copy(int $groupID)
    {
        if(!empty($_POST))
        {
            $group = form::data($this->config->group->form->copy)->get();
            $this->group->copy($groupID, $group, (array)$this->post->options);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->copy;
        $this->view->group = $this->group->getById($groupID);
        $this->display();
    }

    /**
     * 视野维护。
     * Manage view.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function manageView(int $groupID)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $formData = $this->buildUpdateViewForm();
            $this->group->updateView($groupID, $formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = isInModal() ? 'parent' : $this->createLink('group', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        /* Get the group data by id. */
        $group = $this->group->getByID($groupID);
        $this->view->title = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageView;

        /* Get the list of data sets under administrator permission. */
        if(!$this->app->user->admin)
        {
            $this->app->user->admin = true;
            $changeAdmin            = true;
        }

        $executionProject = $this->dao->select('t1.id, t2.name')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.id')->in($this->app->user->view->sprints)
            ->fetchPairs();

        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'all');
        foreach($executions as $id => $name)
        {
            if(isset($executionProject[$id])) $executions[$id] = $executionProject[$id] . ' / ' . trim($name, '/');
        }

        $this->app->loadLang('action');

        $this->view->group      = $group;
        $this->view->programs   = $this->loadModel('program')->getParentPairs('', '', false);
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram(0, 'all', true, 'order_desc');
        $this->view->executions = $executions;
        $this->view->products   = $this->loadModel('product')->getPairs();
        if(!empty($changeAdmin)) $this->app->user->admin = false;

        $this->view->navGroup = $this->getNavGroup();

        $this->display();
    }

    /**
     * 分配分组权限。
     * Manage privleges of a group.
     *
     * @param  string $type     byPackage|byGroup|byModule
     * @param  int    $param
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return void
     */
    public function managePriv(string $type = 'byPackage', int $param = 0, string $nav = '', string $version = '')
    {
        if($type == 'byGroup' or $type == 'byPackage') $groupID = $param;

        $this->view->type = $type;

        if(!empty($_POST))
        {
            if($type == 'byGroup' || $type == 'byPackage') $result = $this->group->updatePrivByGroup($groupID, $nav, $version);
            if($type == 'byModule') $result = $this->group->updatePrivByModule();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($type == 'byGroup' or $type == 'byPackage') return $this->send(array('result' => 'success', 'message' => ($result ? $this->lang->group->dependPrivsSaveTip : $this->lang->saveSuccess)));
            if($type == 'byModule') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        if($type == 'byGroup' || $type == 'byPackage') $this->groupZen->managePrivByGroup($groupID, $nav, $version);
        if($type == 'byModule') $this->groupZen->managePrivByModule();

        $this->display();
    }

    /**
     * 维护分组成员。
     * Manage members of a group.
     *
     * @param  int    $groupID
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function manageMember(int $groupID, int $deptID = 0)
    {
        if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }
        $group        = $this->group->getById($groupID);
        $groupUsers   = $this->group->getUserPairs($groupID);
        $allUsers     = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers   = array_diff_assoc($allUsers, $groupUsers);
        $outsideUsers = $this->loadModel('user')->getPairs('outside|noclosed|noletter|noempty');

        $this->view->title        = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $this->view->group        = $group;
        $this->view->deptTree     = $this->loadModel('dept')->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createGroupManageMemberLink'), $groupID);
        $this->view->groupUsers   = $groupUsers;
        $this->view->otherUsers   = $otherUsers;
        $this->view->outsideUsers = array_diff_assoc($outsideUsers, $groupUsers);
        $this->view->deptID       = $deptID;

        $this->display();
    }

    /**
     * 维护项目管理员。
     * Manage members of a group.
     *
     * @param  int    $groupID
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function manageProjectAdmin(int $groupID, int $deptID = 0)
    {
        if(!empty($_POST))
        {
            $this->group->updateProjectAdmin($groupID, $this->buildProjectAdminForm());
            return $this->sendSuccess(array('load' => true));
        }

        list($programs, $projects, $products, $executions) = $this->group->getObjectForAdminGroup();

        $group      = $this->group->getById($groupID);
        $groupUsers = $this->dao->select('t1.account, t2.realname')->from(TABLE_PROJECTADMIN)->alias('t1')->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')->fetchPairs();

        $this->view->title         = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $this->view->allUsers      = $groupUsers + $this->loadModel('dept')->getDeptUserPairs($deptID);
        $this->view->groupID       = $groupID;
        $this->view->deptID        = $deptID;
        $this->view->deptName      = $deptID ? $this->dao->findById($deptID)->from(TABLE_DEPT)->fetch('name') : '';
        $this->view->programs      = $programs;
        $this->view->projects      = $projects;
        $this->view->products      = $products;
        $this->view->executions    = $executions;
        $this->view->deptTree      = $this->loadModel('dept')->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createManageProjectAdminLink'), $groupID);
        $this->view->projectAdmins = $this->group->getProjectAdmins();

        $this->display();
    }

    /**
     * 删除一个分组。
     * Delete a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function delete(int $groupID)
    {
        $group = $this->group->getByID($groupID);
        $this->group->remove($groupID);

        /* if ajax request, send result. */
        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError();
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $response['load']    = $this->app->tab == 'project' ? $this->createLink('project', 'group', "projectID={$group->project}"): inLink('browse');
        }
        return $this->send($response);
    }

    /**
     * AJAX: Get privs by parents.
     *
     * @param  string  $selectedSubset
     * @param  string  $selectedPackages
     * @access public
     * @return bool
     */
    public function ajaxGetPrivByParents(string $selectedSubset, string $selectedPackages)
    {
        $privs = $this->group->getPrivsByParents($selectedSubset, $selectedPackages);

        return print(html::select('actions[]', $privs, '', "multiple='multiple' class='form-control'"));
    }

    /**
     * AJAX: Get priv's related priv list.
     *
     * @access public
     * @return int
     */
    public function ajaxGetRelatedPrivs()
    {
        $allPrivList      = zget($_POST, 'allPrivList');
        $selectedPrivList = zget($_POST, 'selectPrivList');
        $recommendSelect  = zget($_POST, 'recommendSelect');

        $relatedPrivData = $this->group->getRelatedPrivs(explode(',', $allPrivList), explode(',', $selectedPrivList), explode(',', $recommendSelect));
        if($recommendSelect)
        {
            $recommendList = array();
            foreach($relatedPrivData['recommend'] as $privs)
            {
                $children             = array();
                $checkedChildrenCount = 0;
                foreach($privs['children'] as $child)
                {
                    if(strpos(",{$recommendSelect},", ",{$child['id']},") !== false)
                    {
                        $child['checked'] = true;
                        $checkedChildrenCount ++;
                    }
                    $children[] = $child;
                }

                $privs['checked']    = false;
                $privs['labelClass'] = '';
                if($checkedChildrenCount == count($children)) $privs['checked'] = true;
                if($checkedChildrenCount > 0 && $checkedChildrenCount < count($children)) $privs['labelClass'] = 'checkbox-indeterminate-block';

                $privs['children'] = $children;
                $recommendList[] = $privs;
            }
            $relatedPrivData['recommend'] = $recommendList;
        }

        $this->view->relatedPrivData = $relatedPrivData;

        $this->display();
    }
}
