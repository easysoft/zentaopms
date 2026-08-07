<?php
declare(strict_types=1);
/**
 * The model file of space module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @property    repoTao $repoTao
 * @link        https://www.zentao.net
 */
class spaceModel extends model
{
    /**
     * 通过用户账号获取空间列表。
     * Get space list by user account.
     *
     * @param  string $account
     * @param  object $pager
     * @access public
     * @return array|object
     */
    public function getListByAccount(string $account, ?object $pager = null): array|object
    {
        $members = $this->getMemberList();

        $userSpaces = array();
        if($this->app->user->admin)
        {
            $userSpaces = $members;
        }
        else
        {
            foreach($members as $spaceID => $users)
            {
                if(!isset($users[$account])) continue;
                $userSpaces[$spaceID] = $users;
            }
        }

        $query = array();
        $query['ids']    = implode(',', array_keys($userSpaces));
        $query['isOpen'] = true;

        $result = $this->loadModel('gitfox')->apiGetSpaces($query, $pager);
        if(empty($result) || empty($result->data)) return array();
        foreach($result->data as &$space) $space->members = zget($members, $space->id, array());

        return is_null($pager) ? $result->data : $result;
    }

    /**
     * 获取所有空间成员列表。
     * Get all space members list.
     *
     * @access public
     * @return array
     */
    public function getMemberList(): array
    {
        $managers   = $this->dao->select('*')->from(TABLE_DEVOPSSPACEUSER)->fetchAll();
        $memberList = array();
        if(!empty($managers))
        {
            foreach($managers as $manager)
            {
                $user = new stdClass();
                $user->account = $manager->account;
                $user->space   = $manager->space;
                $user->role    = $manager->role;
                $memberList[$manager->space][$manager->account] = $user;
            }
        }
        $groupMembers = $this->getGroupMembersBySpace();
        if(!empty($groupMembers))
        {
            foreach($groupMembers as $groupMember)
            {
                $user = new stdClass();
                $user->account = $groupMember->account;
                $user->space   = $groupMember->space;
                $user->role    = 'member';
                if(isset($memberList[$groupMember->space][$user->account])) $user->role = $memberList[$groupMember->space][$user->account]->role;
                $memberList[$groupMember->space][$user->account] = $user;
            }
        }
        $repoMembers  = $this->getRepoUsersBySpace();
        if(!empty($repoMembers))
        {
            foreach($repoMembers as $repoMember)
            {
                $user = new stdClass();
                $user->account = $repoMember->account;
                $user->space   = $repoMember->space;
                $user->role    = 'member';
                if(isset($memberList[$repoMember->space][$user->account])) $user->role = $memberList[$repoMember->space][$user->account]->role;
                $memberList[$repoMember->space][$user->account] = $user;
            }
        }
        return $memberList;
    }

    /**
     * 获取空间列表键值对。
     * Get space list pairs.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getPairs(string $account = '', bool $filterRepoCreate = false): array
    {
        $userSpaces = $this->getSpacesByAccount($account);

        $spaces = $this->getByIdList(array_keys($userSpaces), false);

        $spacesPairs = array();
        foreach($spaces as $space)
        {
            if($filterRepoCreate && $space->auth == 'reset')
            {
                $privs = $this->loadModel('group')->getDevOpsSpacePrivs((int)$space->id);
                if($privs === null) continue;
                if(!isset($privs['repo']['create'])) continue;
            }
            $spacesPairs[$space->id] = $space->name;
        }

        return $spacesPairs;
    }

    /**
     * 通过用户获取空间列表。
     * Get space list by account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getSpacesByAccount(string $account = ''): array
    {
        $members = $this->getMemberList();
        if($this->app->user->admin || !$account) return $members;

        $userSpaces = array();
        foreach($members as $spaceID => $users)
        {
            if(!isset($users[$account])) continue;
            $userSpaces[$spaceID] = $users;
        }

        return $userSpaces;
    }

    /**
     * 通过空间ID列表获取空间列表。
     * Get space list by space ID list.
     *
     * @param  array  $spaceIdList
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getByIdList(array $spaceIdList = array(), bool $showDeleted = true): array
    {
        $query = array();
        if(!empty($spaceIdList)) $query['ids']     = implode(',', $spaceIdList);
        if($showDeleted)         $query['deleted'] = true;
        $query['isOpen'] = true;

        $pager = new stdClass();
        $pager->recPerPage = 100;

        $spaceList = array();
        for($i = 1; true; $i++)
        {
            $pager->pageID = $i;

            $result = $this->loadModel('gitfox')->apiGetSpaces($query, $pager);
            if(empty($result) || empty($result->data)) break;

            $spaceList = array_merge($spaceList, $result->data);
            if(!empty($result->pager) && $result->pager->pageSize < 100) break;
        }
        return $spaceList;
    }

    /**
     * 创建空间。
     * Create space.
     *
     * @param  object $formData
     * @access public
     * @return int|bool
     */
    public function create(object $formData): int|bool
    {
        $manager = empty($formData->manager) ? array() : explode(',', $formData->manager);
        if(!in_array($this->app->user->account, $manager)) $manager[] = $this->app->user->account;

        unset($formData->team);
        $space = $this->loadModel('gitfox')->apiCreateSpace($formData);
        if(dao::isError()) return false;
        if(!$space) return false;

        $spaceID = $space->id;
        if(!empty($manager))
        {
            if(!in_array($this->app->user->account, $manager)) $manager[] = $this->app->user->account;
            foreach($manager as $account) $this->dao->insert(TABLE_DEVOPSSPACEUSER)->data(array('space' => $spaceID, 'account' => $account, 'role' => 'manager'))->exec();
            if(dao::isError()) return false;
        }

        return $spaceID;
    }

    /**
     * 通过空间ID获取空间信息。
     * Get space info by space ID.
     *
     * @param  int $spaceID
     * @access public
     * @return array|object
     */
    public function getByID(int $spaceID): array|object
    {
        $space = $this->loadModel('gitfox')->apiGetSpace($spaceID);
        if(empty($space)) return array();

        $createdDate = new DateTime($space->createdDate);
        $space->createdDate = $createdDate->format('Y-m-d H:i:s');

        $space->members = $this->getSpaceMembers($spaceID);
        return $space;
    }

    /**
     * 更新空间。
     * Update space.
     *
     * @param  object $space
     * @param  object $formData
     * @access public
     * @return bool|array
     */
    public function update(object $space, object $formData): false|array
    {
        $newManager = empty($formData->manager) ? array() : explode(',', $formData->manager);
        unset($formData->manager);

        $this->loadModel('gitfox')->apiUpdateSpace($space->id, $formData);
        if(dao::isError()) return false;

        $oldManager = zget($space, 'manager', array());
        $member     = zget($space, 'member', array());

        $conflictUsers = array_intersect($newManager, $member);

        if(!empty($conflictUsers))
        {
            $userList  = $this->loadModel('user')->getListByAccounts($conflictUsers, 'account');
            $userNames = array();
            foreach($conflictUsers as $account)
            {
                $userNames[] = isset($userList[$account]) && !empty($userList[$account]->realname) ? $userList[$account]->realname : $account;
            }

            $message       = sprintf($this->lang->space->notice->managerMemberConflict, implode(', ', $userNames));
            dao::$errors[] = $message;
            return false;
        }

        if(empty($oldManager) || !empty($newManager))
        {
            $this->dao->delete()->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($space->id)->andWhere('role')->eq('manager')->exec();
            foreach($newManager as $account) $this->dao->insert(TABLE_DEVOPSSPACEUSER)->data(array('space' => $space->id, 'account' => $account, 'role' => 'manager'))->exec();
            if(dao::isError()) return false;

            $formData->manager = implode(',', $newManager);
            $space->manager    = implode(',', $oldManager);
        }

        return common::createChanges($space, $formData);
    }

    /**
     * 根据空间获取仓库列表。
     * Get repo list by space.
     *
     * @param  int $spaceID
     * @param  string $acl
     * @access public
     * @return array
     */
    public function getReposBySpace(int $spaceID, $acl = ''): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('spaceID')->eq($spaceID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('importing')
            ->beginIF($acl)->andWhere('acl')->eq($acl)->fi()
            ->fetchAll('id');
    }

    /**
     * 根据空间获取制品库列表。
     * Get artifact lib list by space.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getArtifactLibsBySpace(int $spaceID): array
    {
        return $this->dao->select('*')->from(TABLE_ARTIFACT)
            ->where('spaceID')->eq($spaceID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 根据空间获取流水线列表。
     * Get pipeline list by space.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getPipelineBySpace(int $spaceID): array
    {
        return $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where('spaceID')->eq($spaceID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 根据空间获取应用列表。
     * Get app list by space.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getSystemBySpace(int $spaceID): array
    {
        $repos = $this->getReposBySpace($spaceID);
        if(empty($repos)) return array();

        $products = array();
        foreach($repos as $repo)
        {
            if(empty($repo->product)) continue;
            foreach(explode(',', $repo->product) as $productID) $products[] = $productID;
        }
        if(empty($products)) return array();

        return $this->dao->select('*')->from(TABLE_SYSTEM)
            ->where('product')->in($products)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 删除空间。
     * Delete space.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function deleteSpace(int $spaceID): bool
    {
        $this->loadModel('gitfox')->apiDeleteSpace($spaceID);
        if(dao::isError()) return false;

        $this->loadModel('action')->create('space', $spaceID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
        return !dao::isError();
    }

    /**
     * 设置空间菜单。
     * Set space menu.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function setMenu(int $spaceID = 0)
    {
        $this->session->set('devopsSpace', $spaceID);
        $this->loadModel('common')->resetDevOpsPriv($spaceID);
        if($spaceID)
        {
            $userSpaces = $this->getPairs($this->app->user->account);
            if(!isset($userSpaces[$spaceID])) return $this->app->control->locate(helper::createLink('user', 'deny', "module={$this->app->rawModule}&method={$this->app->rawMethod}"));
            $this->session->set('devopsSpace', $spaceID);
            unset($this->lang->devops->homeMenu->space);
            unset($this->lang->devops->homeMenu->configure);
            unset($this->lang->devops->homeMenu->codescan);

            foreach($this->config->setSpaceMenu as $menu)
            {
                if(isset($this->lang->devops->homeMenu->$menu)) $this->lang->devops->homeMenu->$menu = common::setMenuVarsEx($this->lang->devops->homeMenu->$menu, $spaceID);
            }

            foreach($this->lang->devops->homeMenu as $label => &$menu)
            {
                if(empty($menu['link'])) continue;

                if($label == 'spaceSetting' && !empty($menu['subMenu']))
                {
                    $menu = common::setMenuVarsEx($menu, $spaceID);
                    foreach($menu['subMenu'] as &$subMenu)
                    {
                        $subMenu = common::setMenuVarsEx($subMenu, $spaceID);
                    }
                }
                elseif($label == 'deploy')
                {
                    unset($menu['subMenu']->host, $menu['subMenu']->env, $menu['subMenu']->publishTemplate);

                    $menu['link'] .= '|space=' . $spaceID;
                    $menu['subMenu']->deploy['link'] .= '|space=' . $spaceID;
                }
                else
                {
                    $link = $menu['link'];
                    if(strpos($link, '|inSpace=1') !== false) continue;

                    $menu['link'] = in_array($label, array('pipeline', 'system')) ? $link . '|space=' . $spaceID : $link . '|inSpace=1&space=' . $spaceID;
                }
            }
        }
        else
        {
            $this->session->set('repoID', 0);
            $this->session->set('devopsSpace', 0);
            unset($this->lang->devops->homeMenu->pipeline);
            unset($this->lang->devops->homeMenu->artifact);
            unset($this->lang->devops->homeMenu->spaceSetting);
        }
    }

    /**
     * 获取空间所有权限。
     * Get space privs.
     *
     * @access public
     * @return object
     */
    public function getPrivs(): object
    {
        $privs = new stdclass();
        foreach($this->lang->resource as $module => $methods)
        {
            if(empty($methods)) continue;
            if($module == 'space') continue;

            /* Only include modules that belong to the devops nav group. */
            $navGroup = zget($this->lang->navGroup, $module, '');
            if($navGroup != 'devops') continue;

            foreach($methods as $method => $label)
            {
                if(!isset($privs->$module)) $privs->$module = new stdclass();
                $privs->$module->$method = $label;
            }
        }

        return $privs;
    }

    /**
     * 获取 DevOps 空间所有的权限列表（数组格式），供 groupModel::getDevOpsSpacePrivs 在管理员场景使用。
     * Get all devops space privs as array format for admin/manager use.
     *
     * @access public
     * @return array  [module][method] => 1
     */
    public function getDevOpsAllPrivs(): array
    {
        $privs = array();
        foreach($this->lang->resource as $module => $methods)
        {
            if(empty($methods)) continue;
            if($module == 'space') continue;

            $navGroup = zget($this->lang->navGroup, $module, '');
            if($navGroup != 'devops') continue;

            foreach($methods as $method => $label)
            {
                $privs[strtolower($module)][strtolower($method)] = 1;
            }
        }

        return $privs;
    }

    /**
     * 获取空间成员。
     * Get space users.
     *
     * @param  int    $spaceID
     * @param  string $role
     * @access public
     * @return array
     */
    public function getSpaceUsers(int $spaceID, string $role = ''): array
    {
        return $this->dao->select('account')->from(TABLE_DEVOPSSPACEUSER)
            ->where('space')->eq($spaceID)
            ->beginIf($role)->andWhere('role')->eq($role)->fi()
            ->fetchPairs('account', 'account');
    }

    /**
     * 根据空间ID获取所有成员。
     * Get all members by spaceID.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getRepoUsersBySpace(int $spaceID = 0): array
    {
        return $this->dao->select('t1.*, t2.name AS repoName, t2.spaceID AS space')->from(TABLE_DEVOPSREPOUSER)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo = t2.id')
            ->where('t2.deleted')->eq(0)
            ->beginIF($spaceID)->andWhere('t2.spaceID')->eq($spaceID)->fi()
            ->beginIF(!$spaceID)->andWhere('t2.spaceID')->ne(0)->fi()
            ->fetchAll();
    }

    /**
     * 根据空间ID获取所有成员。
     * Get all members by spaceID.
     *
     * @param  int $spaceID
     * @param  bool $allVision
     * @access public
     * @return array
     */
    public function getGroupMembersBySpace(int $spaceID = 0, bool $allVision = false): array
    {
        return $this->dao->select('t1.*, t2.id AS `groupID`, t2.name AS `groupName`, t2.`devopsSpace` AS space')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')
            ->on('t1.`group` = t2.id')
            ->where('t2.project')->eq(0)
            ->beginIF($spaceID)->andWhere('t2.`devopsSpace`')->eq($spaceID)->fi()
            ->beginIF(!$spaceID)->andWhere('t2.`devopsSpace`')->ne(0)->fi()
            ->beginIF(!$allVision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->fetchAll();
    }

    /**
     * 根据空间ID获取所有成员。
     * Get all members by spaceID.
     *
     * @param  int  $spaceID
     * @param  bool $allVision
     * @access public
     * @return array
     */
    public function getSpaceMembers(int $spaceID, bool $allVision = false): array
    {
        $members     = array();
        $memberGroup = $this->getGroupMembersBySpace($spaceID, $allVision);
        if(!empty($memberGroup))
        {
            foreach($memberGroup as $member)
            {
                if(empty($members[$member->account]))
                {
                    $members[$member->account] = array('spaceID' => $spaceID, 'account' => $member->account, 'role' => 'member', 'group' => array(), 'repo' => array());
                }
                if(in_array($member->groupID, array_keys($members[$member->account]['group']))) continue;

                $members[$member->account]['group'][$member->groupID] = $member->groupName;
            }
        }

        $repoUsers = $this->getRepoUsersBySpace($spaceID);
        if(!empty($repoUsers))
        {
            foreach($repoUsers as $repoUser)
            {
                if(empty($members[$repoUser->account])) $members[$repoUser->account] = array('spaceID' => $spaceID, 'account' => $repoUser->account, 'role' => 'member', 'group' => array(), 'repo' => array());
                if(in_array($repoUser->repo, array_keys($members[$repoUser->account]['repo']))) continue;
                $members[$repoUser->account]['repo'][$repoUser->repo] = $repoUser->repoName;
            }
        }

        $spaceUsers = $this->dao->select('*')->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($spaceID)->fetchAll();
        if(!empty($spaceUsers))
        {
            foreach($spaceUsers as $spaceUser)
            {
                if(empty($members[$spaceUser->account])) $members[$spaceUser->account] = array('spaceID' => $spaceID, 'account' => $spaceUser->account, 'role' => $spaceUser->role, 'group' => array(), 'repo' => array());
                $members[$spaceUser->account]['role'] = $spaceUser->role;
            }
        }

        if(!empty($members))
        {
            $count = 1;
            foreach($members as &$member)
            {
                $member = (object)$member;
                $member->id = $count++;
            }
        }
        return $members;
    }

    /**
     * 管理空间成员。
     * Manage space members.
     *
     * @param  int   $spaceID
     * @param  array $members
     * @access public
     * @return bool
     */
    public function manageMembers(int $spaceID, array $members): bool
    {
        $this->loadModel('group');
        $this->loadModel('repo');
        $spaceGroups = $this->group->getList(0, $spaceID);
        $spaceGroups = !empty($spaceGroups) ? array_column($spaceGroups, 'id') : array();

        $spaceRepos = $this->getReposBySpace($spaceID);
        $spaceRepos = !empty($spaceRepos) ? array_column($spaceRepos, 'id') : array();

        if(!empty($members['group']))
        {
            foreach($members['group'] as $groupID => $groupMembers)
            {
                if(!in_array($groupID, $spaceGroups)) continue;

                $_POST['members'] = $groupMembers;
                $this->group->updateUser($groupID);
            }
        }

        if(!empty($members['repo']))
        {
            foreach($members['repo'] as $repoID => $repoMembers)
            {
                if(!in_array($repoID, $spaceRepos)) continue;
                $this->repo->updateMembers($repoID, $repoMembers);
            }
        }

        if(!empty($members['space']))
        {
            foreach($members['space'] as $account)
            {
                $spaceMembers = new stdClass();
                $spaceMembers->account = $account;
                $spaceMembers->space   = $spaceID;
                $spaceMembers->role    = 'member';

                $this->dao->insert(TABLE_DEVOPSSPACEUSER)->data($spaceMembers)->exec();
            }
        }

        if(!empty($members['delete']))
        {
            foreach($members['delete'] as $type => $deleteUsers)
            {
                if($type == 'group')
                {
                    $group = $this->dao->select('id')->from(TABLE_GROUP)->where('devopsSpace')->eq($spaceID)->fetchPairs();
                    $this->dao->delete()->from(TABLE_USERGROUP)
                        ->where('`account`')->in($deleteUsers)
                        ->andWhere('`group`')->in(array_keys($group))
                        ->beginIF(!empty($spaceGroups))->andWhere('`group`')->in($spaceGroups)->fi()
                        ->exec();
                }
                elseif($type == 'repo')
                {
                    $this->dao->delete()->from(TABLE_DEVOPSREPOUSER)
                        ->where('`account`')->in($deleteUsers)
                        ->beginIF(!empty($spaceRepos))->andWhere('`repo`')->in($spaceRepos)->fi()
                        ->exec();
                }
                elseif($type == 'space')
                {
                    $this->dao->delete()->from(TABLE_DEVOPSSPACEUSER)
                        ->where('`account`')->in($deleteUsers)
                        ->andWhere('`space`')->eq($spaceID)
                        ->exec();
                }
            }
        }

        return !dao::isError();
    }

    /**
     * 移除成员。
     * Remove member.
     *
     * @param  int $spaceID
     * @param  string $account
     * @access public
     * @return bool
     */
    public function removeMember(int $spaceID, string $account): bool
    {
        $group = $this->loadModel('group')->getList(0, $spaceID);
        if(!empty($group))
        {
            $grouopIdList = array_column($group, 'id');
            $this->dao->delete()->from(TABLE_USERGROUP)
                ->where('`account`')->eq($account)
                ->andWhere('`group`')->in($grouopIdList)
                ->exec();
        }

        $repos = $this->getReposBySpace($spaceID);
        if(!empty($repos))
        {
            $repoIdList = array_column($repos, 'id');
            $this->dao->delete()->from(TABLE_DEVOPSREPOUSER)
                ->where('`account`')->eq($account)
                ->andWhere('`repo`')->in($repoIdList)
                ->exec();
        }

        $this->dao->delete()->from(TABLE_DEVOPSSPACEUSER)
            ->where('`account`')->eq($account)
            ->andWhere('`space`')->eq($spaceID)
            ->exec();
        return !dao::isError();
    }

    /**
     * 还原空间数据。
     * Restore space data.
     *
     * @param  int    $spaceID
     * @access public
     * @return bool
     */
    public function restore(int $spaceID, int $actionID): bool
    {
        $result = $this->loadModel('gitfox')->apiRestoreSpace($spaceID);
        if(!$result) return false;

        /* 在action表中更新action记录。 */
        /* Update action record in action table. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($actionID)->exec();
        $this->loadModel('action')->create('space', $spaceID, 'undeleted');
        return !dao::isError();
    }

    /**
     * 获取空间下的产品信息。
     * Get products by space.
     *
     * @param  int    $spaceID
     * @param  bool   $hasPairs
     * @access public
     * @return array
     */
    public function getProductsBySpace(int $spaceID, bool $hasPairs = false): array
    {
        if(empty($spaceID)) return array();

        $this->loadModel('repo');

        $repos = $this->dao->select('id,product,acl')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('importing')
            ->andWhere('spaceID')->eq($spaceID)->fetchAll();
        $productIdList = array();
        foreach($repos as $key => $repo)
        {
            if(!$this->repo->checkPriv($repo)) unset($repos[$key]);
            $products = explode(',', $repo->product);
            foreach($products as $productID) $productIdList[$productID] = $productID;
        }

        if(!$hasPairs || empty($productIdList)) return $productIdList;
        return $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchPairs('id', 'name');
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $space
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $space, string $action): bool
    {
        $action = strtolower($action);
        if($action == 'removemember') return $space->role != 'manager';

        return true;
    }

    /**
     * 创建默认空间。
     * Create default space.
     *
     * @access public
     * @return bool
     */
    public function createDefaultSpace(): bool
    {
        $hasSpace = $this->dao->select('*')->from(TABLE_SPACE)->orderBy('id')->limit(1)->fetch();
        if($hasSpace) return true;

        $company = $this->loadModel('company')->getFirst();
        $admins  = !empty($company->admins) ? explode(',', $company->admins) : array();
        $admins  = array_filter($admins);

        $space = new stdClass();
        $space->name        = $this->lang->space->defaultSpace;
        $space->code        = 'default';
        $space->acl         = 'open';
        $space->auth        = 'extend';
        $space->createdBy   = zget($admins, 0, 'system');
        $space->createdDate = helper::now();

        $this->dao->insert(TABLE_SPACE)->data($space)->exec();
        if(dao::isError()) return false;

        $space->id = $this->dao->lastInsertId();
        foreach($admins as $admin)
        {
            $this->dao->insert(TABLE_DEVOPSSPACEUSER)->data(array('space' => $space->id, 'account' => $admin, 'role' => 'manager'))->exec();
        }
        return !dao::isError();
    }

    /**
     * 迁移组权限。
     * Migrate group privs.
     *
     * @access public
     * @return bool
     */
    public function migrateGroupPrivs(): bool
    {
        $spaceMethods = array('browse', 'view', 'create', 'edit', 'delete', 'members', 'manageMembers', 'removeMember', 'group', 'createGroup', 'editGroup', 'deleteGroup', 'managePriv', 'manageGroupMember', 'importGroup');
        $groups       = $this->dao->select('DISTINCT `group`')->from(TABLE_GROUPPRIV)->where('module')->eq('repo')->andWhere('method')->in('createRepo,create,import,edit')->fetchPairs('group', 'group');

        $migratePrivs = array();
        foreach($groups as $groupID)
        {
            foreach($spaceMethods as $method) $migratePrivs[] = "('{$groupID}', 'space', '{$method}')";
        }

        if(!empty($migratePrivs))
        {
            $sql = 'REPLACE INTO ' . TABLE_GROUPPRIV . ' (`group`, `module`, `method`) VALUES ' . implode(',', $migratePrivs);
            $this->dao->exec($sql);
        }

        return !dao::isError();
    }
}
