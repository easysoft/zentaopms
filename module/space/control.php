<?php
declare(strict_types=1);
/**
 * The control file of space module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Yang Li
 * @package   space
 * @link      https://www.zentao.net
 */
class space extends control
{
    /**
     * 空间列表。
     * Browse space.
     *
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function browse(int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $serverHeath = $this->loadModel('gitfox')->checkHealth();
        if(!$serverHeath) return $this->locate($this->createLink('gitfox', "devopsIntroduction"));

        $this->space->setMenu();
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $spaces = $this->space->getListByAccount($this->app->user->account, $pager);
        if(!empty($spaces->data))
        {
            $pager->recTotal   = $spaces->pager->total;
            $pager->recPerPage = $spaces->pager->pageSize;
            $pager->pageID     = $spaces->pager->page;
        }

        $spaces = zget($spaces, 'data', array());
        foreach($spaces as &$space)
        {
            $space->desc    = str_replace('&nbsp;', ' ', strip_tags(htmlspecialchars_decode($space->desc)));
            $space->manager = '';
            foreach($space->members as $member)
            {
                if($member->role != 'manager') continue;
                $space->manager .= $member->account . ',';
            }
            $space->manager = trim($space->manager, ',');
        }

        $this->view->title  = $this->lang->space->browse;
        $this->view->spaces = $spaces;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->pager  = $pager;
        $this->display();
    }

    /**
     * 创建空间。
     * Create space.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $this->space->setMenu();
        if($_POST)
        {
            $formData = form::data($this->config->space->form->create)
                ->setDefault('createdBy', $this->app->user->account)
                ->get();

            $spaceID = $this->space->create($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($spaceID) $this->loadModel('action')->create('space', $spaceID, 'created');
            return $this->sendSuccess(array('load' => $this->inLink('ajaxTips', "spaceID={$spaceID}")));
        }
        $this->view->title = $this->lang->space->create;
        $this->view->users = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->display();
    }

    /**
     * 编辑空间。
     * Edit space.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function edit(int $id)
    {
        $this->space->setMenu();
        $space = $this->space->getByID($id);
        if(!empty($space->members))
        {
            foreach($space->members as $account)
            {
                if($account->role != 'manager') continue;
                $space->manager[] = $account->account;
            }
        }

        if($_POST)
        {
            $formData = form::data($this->config->space->form->edit)
                ->setDefault('editedBy', $this->app->user->account)
                ->get();

            $changes = $this->space->update($space, $formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('space', $id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $this->sendSuccess(array('load' => $this->inLink('view', "id=$id")));
        }

        $this->view->title = $this->lang->space->edit;
        $this->view->space = $space;
        $this->view->users = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->display();
    }

    /**
     * 查看空间。
     * View space.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function view(int $id)
    {
        $this->space->setMenu($id);
        $space = $this->space->getByID($id);

        $managers = $members = array();
        if(!empty($space->members))
        {
            foreach($space->members as $member)
            {
                if($member->role != 'manager') continue;
                $managers[] = $member->account;
            }
            foreach($space->members as $member)
            {
                if($member->role == 'manager') continue;
                $members[] = $member->account;
            }
        }

        $this->view->title           = $this->lang->space->view;
        $this->view->space           = $space;
        $this->view->spaceID         = $id;
        $this->view->repoList        = $this->space->getReposBySpace($id);
        $this->view->repoPairs       = $this->loadModel('repo')->getRepoPairs();
        $this->view->artifactLibList = $this->space->getArtifactLibsBySpace($id);
        $this->view->managers        = $this->loadModel('user')->getListByAccounts($managers, 'account');
        $this->view->members         = $this->user->getListByAccounts($members, 'account');
        $this->view->systemList      = $this->space->getSystemBySpace($id);
        $this->view->pipelineList    = $this->space->getPipelineBySpace($id);
        $this->view->actions         = $this->loadModel('action')->getList('space', $id);

        $this->display();
    }

    /**
     * 删除空间。
     * Delete space.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $repos         = $this->space->getReposBySpace($id);
        $artifactRepos = $this->space->getArtifactLibsBySpace($id);

        if(!empty($repos) || !empty($artifactRepos)) $this->sendError($this->lang->space->notice->deleteFail);

        $this->space->deleteSpace($id);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->sendSuccess(array('load' => $this->inLink('browse')));
    }

    /**
     * 获取下拉菜单。
     * Get drop menu.
     *
     * @param  int    $spaceID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $spaceID, string $module = 'repo', string $method = 'maintain')
    {
        $spacePairs = $this->space->getPairs($this->app->user->account);
        $spaceGroup = array();
        foreach($spacePairs as $spaceID => $spaceName)
        {
            $items = array();
            $items['id']       = $spaceID;
            $items['keys']     = $spaceName;
            $items['text']     = $spaceName;
            $items['data-app'] = 'devops';

            $spaceGroup[] = $items;
        }

        if($method == 'managepriv')    $method = 'group';
        if($method == 'edit')          $method = 'browse';
        if($module == 'artifact' && $method == 'view') $method = 'browse';
        if($module == 'deploy' && in_array($method, array('steps', 'managestep', 'cases', 'linkcases', 'artifact', 'view'))) $method = 'browse';
        $link = in_array($method, array('view', 'group', 'members', 'managemembers', 'browsesystem')) ? $this->createLink($module, $method, "spaceID=%s") : $this->createLink($module, $method, "inSpace=1&spaceID=%s");
        $link = $method == 'createrepo' ? $this->createLink('repo', 'createRepo', "objectID=0&spaceID=%s") : $link;
        $link = $module == 'pipeline' ? $this->createLink('pipeline', 'browse', "spaceID=%s") : $link;
        $link = $module == 'deploy' ? $this->createLink('deploy', $method, "spaceID=%s") : $link;
        $link = $module == 'system' ? $this->createLink('repo', 'browsesystem', "spaceID=%s") : $link;
        $link = $module == 'artifact' ? $this->createLink('artifact', 'browse', "spaceID=%s&repoID=0&type=space") : $link;

        $this->view->spaceID    = $spaceID;
        $this->view->spaceGroup = $spaceGroup;
        $this->view->link       = $link;
        $this->display();
    }

    /**
     * 查看空间列表。
     * View space list.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function group(int $spaceID)
    {
        $this->space->setMenu($spaceID);
        $this->loadModel('group');

        $groups     = $this->group->getList(0, $spaceID);
        $groupUsers = array();
        foreach($groups as $group)
        {
            $group->users = '';

            $groupUsers = $this->group->getUserPairs($group->id);
            foreach($groupUsers as $realname) $group->users .= $realname . ' ';
        }
        $this->lang->group->confirmDelete = $this->lang->space->notice->confirmDelete;

        $this->view->title      = $this->lang->space->group;
        $this->view->groups     = $groups;
        $this->view->spaceID    = $spaceID;
        $this->view->groupUsers = $groupUsers;
        $this->display();
    }

    /**
     * 创建空间组。
     * Create space group.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function createGroup(int $spaceID)
    {
        $this->loadModel('group');
        if(!empty($_POST))
        {
            $this->lang->space->name = $this->lang->group->name;

            $group = form::data($this->config->group->form->create)->get();
            $group->devopsSpace = $spaceID;
            $this->group->create($group);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->space->createGroup;
        $this->display('group', 'create');
    }

    /**
     * 编辑空间组。
     * Edit space group.
     *
     * @param  int $groupID
     * @access public
     * @return void
     */
    public function editGroup(int $groupID)
    {
        $this->loadModel('group');
        if(!empty($_POST))
        {
            $this->lang->space->name = $this->lang->group->name;

            $group = form::data($this->config->group->form->edit)->get();
            $this->group->update($groupID, $group);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->space->editGroup;
        $this->view->group = $this->group->getByID($groupID);
        $this->display('group', 'edit');
    }

    /**
     * 删除空间组。
     * Delete space group.
     *
     * @param  int $groupID
     * @access public
     * @return void
     */
    public function deleteGroup(int $groupID)
    {
        $this->loadModel('group');
        $group = $this->group->getByID($groupID);
        if(empty($group)) return $this->sendError($this->lang->group->notFound);

        $this->group->remove($groupID);

        if(dao::isError()) return $this->sendError(dao::getError());

        $loadURL = $this->createLink('space', 'group', "spaceID={$group->devopsSpace}");
        return $this->send(array('result' => 'success', 'message' => '', 'load' => $loadURL));
    }

    /**
     * 权限分配页面。
     * Manage priv.
     *
     * @param  int    $spaceID
     * @param  int    $groupID
     * @param  string $type
     * @access public
     * @return void
     */
    public function managePriv(int $spaceID, int $groupID = 0, string $type = 'byPackage')
    {
        $this->loadModel('group');
        foreach($this->lang->resource as $moduleName => $action) $this->app->loadLang($moduleName);

        if(!empty($_POST))
        {
            $this->group->updatePrivByGroup($groupID, '', '');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('space', 'group', "spaceID={$spaceID}")));
        }

        $group = $this->group->getById($groupID);
        $this->space->setMenu($spaceID);

        $this->group->sortResource();

        $this->lang->resource = $this->space->getPrivs();

        $getPrivs = $this->group->getPrivs($groupID);
        foreach($getPrivs as $moduleName => $actions)
        {
            if(!isset($this->lang->resource->$moduleName))
            {
                unset($getPrivs[$moduleName]);
            }
            else
            {
                foreach($actions as $method => $value)
                {
                    if(!isset($this->lang->resource->{$moduleName}->{$method})) unset($getPrivs[$moduleName][$method]);
                }
            }
        }

        /* Subsets — only those tagged with nav='devops'. */
        $subsets = array();
        foreach($this->config->group->subset as $subsetName => $subset)
        {
            if(!isset($subset->nav) || $subset->nav != 'devops') continue;

            $subset->code        = $subsetName;
            $subset->allCount    = 0;
            $subset->selectCount = 0;

            $subsets[$subset->code] = $subset;
        }

        $selectPrivs = $this->group->getPrivsByGroup($groupID);
        $allPrivList = $this->group->getPrivsByNav('devops');

        $selectedPrivList = array();
        $packages         = array();
        foreach($allPrivList as $privCode => $priv)
        {
            $subsetCode  = $priv->subset;
            $packageCode = $priv->package;

            /* Drop privs whose subset is not exposed on devops nav. */
            if(!isset($subsets[$subsetCode])) continue;
            if(!isset($this->lang->resource->{$priv->module})) continue;
            if(!isset($this->lang->resource->{$priv->module}->{$priv->method})) continue;

            if(!isset($packages[$subsetCode])) $packages[$subsetCode] = array();

            if(!isset($packages[$subsetCode][$packageCode]))
            {
                $package = new stdclass();
                $package->allCount    = 0;
                $package->selectCount = 0;
                $package->subset      = $subsetCode;
                $package->privs       = array();

                $packages[$subsetCode][$packageCode] = $package;
            }

            $packages[$subsetCode][$packageCode]->privs[$privCode] = $priv;

            $packages[$subsetCode][$packageCode]->allCount ++;
            $subsets[$subsetCode]->allCount ++;

            if(isset($selectPrivs[$privCode]))
            {
                $packages[$subsetCode][$packageCode]->selectCount ++;
                $subsets[$subsetCode]->selectCount ++;
                $selectedPrivList[] = $privCode;
            }
        }

        $allPrivList     = array_keys($allPrivList);
        $relatedPrivData = $this->group->getRelatedPrivs($allPrivList, $selectedPrivList);

        $this->view->allPrivList      = $allPrivList;
        $this->view->selectedPrivList = $selectedPrivList;
        $this->view->relatedPrivData  = $relatedPrivData;

        $this->view->title      = $group->name . $this->lang->hyphen . $this->lang->group->managePriv;
        $this->view->subsets    = $subsets;
        $this->view->packages   = $packages;
        $this->view->group      = $group;
        $this->view->groupPrivs = $getPrivs;
        $this->view->groupID    = $groupID;
        $this->view->spaceID    = $spaceID;
        $this->view->type       = $type;
        $this->display();
    }

    /**
     * 查看空间成员。
     * View space members.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function members(int $spaceID)
    {
        $this->space->setMenu($spaceID);

        $this->loadModel('user');
        $this->view->users   = $this->user->getPairs('noletter|nodeleted');
        $this->view->members = $this->space->getSpaceMembers($spaceID);
        $this->view->title   = $this->lang->space->members;
        $this->view->spaceID = $spaceID;
        $this->view->space   = $this->space->getByID($spaceID);
        $this->display();
    }

    /**
     * 给权限组分配成员。
     * Manage group members.
     *
     * @param  int $groupID
     * @param  int $deptID
     * @access public
     * @return void
     */
    public function manageGroupMember(int $groupID, int $deptID = 0)
    {
        $this->loadModel('group');
        if($this->server->request_method == 'POST')
        {
            $this->group->updateUser($groupID);
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $group      = $this->group->getById($groupID);
        $space      = $this->space->getByID((int)$group->devopsSpace);
        $groupUsers = $this->group->getUserPairs($groupID);
        $allUsers   = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers = array_diff_assoc($allUsers, $groupUsers);

        $outsideUsers = $this->loadModel('user')->getPairs('outside|noclosed|noletter|noempty');
        if($space->acl != 'open')
        {
            foreach($outsideUsers as $account => $outsideUser)
            {
                if(!isset($canViewMembers[$account])) unset($outsideUsers[$account]);
            }
        }

        $this->view->title        = $group->name . $this->lang->hyphen . $this->lang->group->manageMember;
        $this->view->group        = $group;
        $this->view->deptTree     = $this->loadModel('dept')->getTreeMenu(0, array('deptModel', 'createGroupManageMemberLink'), (int)$groupID);
        $this->view->groupUsers   = $groupUsers;
        $this->view->otherUsers   = $otherUsers;
        $this->view->deptID       = $deptID;
        $this->view->noUsers      = empty($groupUsers) && empty($otherUsers);
        $this->view->outsideUsers = array_diff_assoc($outsideUsers, $groupUsers);

        $this->display('group', 'manageMember');
    }

    /**
     * 给空间组分配成员。
     * Manage space members.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function manageMembers(int $spaceID)
    {
        $this->space->setMenu($spaceID);
        $members = $this->space->getSpaceMembers($spaceID);
        if($_POST)
        {
            $formData   = form::batchData($this->config->space->form->manageMembers)->get();
            $newMembers = $this->spaceZen->buildManageMembersData($formData, $members);
            $this->space->manageMembers($spaceID, $newMembers);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->sendSuccess(array('load' => $this->inLink('members', 'spaceID=' . $spaceID)));
        }

        $this->view->title   = $this->lang->space->manageMembers;
        $this->view->fields  = $this->spaceZen->buildManageMembersFields($spaceID);
        $this->view->members = $members;
        $this->view->spaceID = $spaceID;
        $this->view->space   = $this->space->getByID($spaceID);
        $this->display();
    }

    /**
     * 移除成员。
     * Remove member.
     *
     * @param  int    $spaceID
     * @param  string $account
     * @access public
     * @return void
     */
    public function removeMember(int $spaceID, string $account)
    {
        $this->space->removeMember($spaceID, $account);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->sendSuccess(array('load' => $this->inLink('members', 'spaceID=' . $spaceID)));
    }

    /**
     * 展示创建空间后的提示。
     * Display tips after created.
     *
     * @param  int       $spaceID
     * @access public
     * @return void
     */
    public function ajaxTips(int $spaceID): void
    {
        $this->space->setMenu($spaceID);
        $this->view->title   = $this->lang->space->create;
        $this->view->spaceID = $spaceID;
        $this->display();
    }

    /**
     * 导入分组。
     * Import group.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function importGroup(int $spaceID)
    {
        $this->loadModel('group');
        if(!empty($_POST))
        {
            $newGroup = form::data($this->config->group->form->copy)
                ->add('devopsSpace', $spaceID)
                ->get();

            $this->group->copy((int)$this->post->sourceGroup, $newGroup, array('copyPriv', 'copyUser'));

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->company->orgView . $this->lang->hyphen . $this->lang->space->importGroup;
        $this->view->spaces = $this->space->getPairs($this->app->user->account);
        $this->display();
    }

    /**
     * 通过空间ID获取分组。
     * Get groups by spaceID.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function ajaxGetGroupsBySpace(int $spaceID)
    {
        $groups = $this->loadModel('group')->getList(0, $spaceID);

        $groupList = array();
        foreach($groups as $group) $groupList[] = array('value' => $group->id, 'text' => $group->name, 'keys' => $group->name);

        echo json_encode($groupList);
    }

    /**
     * 通过分组ID获取分组。
     * Get groups by groupID.
     *
     * @param  int $groupID
     * @access public
     * @return void
     */
    public function ajaxGetGroupByID(int $groupID)
    {
        $group = $this->loadModel('group')->getById($groupID);
        echo json_encode($group);
    }
}
