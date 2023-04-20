<?php
/**
 * The control file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: control.php 4648 2013-04-15 02:45:49Z chencongzhi520@gmail.com $
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
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('company')->setMenu();
        $this->loadModel('user');
    }

    /**
     * Browse groups.
     *
     * @access public
     * @return void
     */
    public function browse()
    {
        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[] = $this->lang->group->browse;

        $groups = $this->group->getList();
        $groupUsers = array();
        foreach($groups as $group)
        {
            if($group->role == 'projectAdmin')
            {
                $groupUsers[$group->id] = $this->dao->select('t1.account, t2.realname')->from(TABLE_PROJECTADMIN)->alias('t1')->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')->fetchPairs();
            }
            else
            {
                $groupUsers[$group->id] = $this->group->getUserPairs($group->id);
            }
        }

        $this->view->title      = $title;
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
            $groupID = $this->group->create();
            if(dao::isError()) return print(js::error(dao::getError()));
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $groupID));
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->view->position[] = $this->lang->group->create;
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
            if(dao::isError()) return print(js::error(dao::getError()));
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->edit;
        $position[] = $this->lang->group->edit;

        $this->view->title    = $title;
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
            if(dao::isError()) return print(js::error(dao::getError()));
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->copy;
        $this->view->position[] = $this->lang->group->copy;
        $this->view->group      = $this->group->getById($groupID);
        $this->display();
    }

    /**
     * Manage view.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function manageView($groupID)
    {
        if($_POST)
        {
            $this->group->updateView($groupID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = isonlybody() ? 'parent' : $this->createLink('group', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
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
            if(isset($executionProject[$id])) $executions[$id] = $executionProject[$id] . ' / ' . $name;
        }

        $this->view->group      = $group;
        $this->view->programs   = $this->loadModel('program')->getParentPairs('', '', false);
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram('', 'all', true, 'order_desc');
        $this->view->executions = $executions;
        $this->view->products   = $this->loadModel('product')->getPairs();
        if(!empty($changeAdmin)) $this->app->user->admin = false;

        $navGroup = array();
        foreach($this->lang->navGroup as $moduleName => $groupName)
        {
            if($groupName == $moduleName) continue;
            if($moduleName == 'testcase') $moduleName = 'case';

            $navGroup[$groupName][$moduleName] = $moduleName;
        }
        $this->view->navGroup = $navGroup;

        $this->display();
    }

    /**
     * Manage privleges of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function managePriv($type = 'byGroup', $param = 0, $menu = '', $version = '')
    {
        if($type == 'byGroup' or $type == 'byPackage') $groupID = $param;

        $this->view->type = $type;
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->group->checkMenuModule($menu, $moduleName) or ($type != 'byGroup' and $type != 'byPackage')) $this->app->loadLang($moduleName);
        }

        if(!empty($_POST))
        {
            if($type == 'byGroup' or $type == 'byPackage')  $result = $this->group->updatePrivByGroup($groupID, $menu, $version);
            if($type == 'byModule') $result = $this->group->updatePrivByModule();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($type == 'byGroup' or $type == 'byPackage') return $this->send(array('result' => 'success', 'message' => ($result ? $this->lang->group->dependPrivsSaveTip : $this->lang->saveSuccess), 'locate' => 'reload'));
            if($type == 'byModule') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        if($type == 'byGroup' or $type == 'byPackage')
        {
            $this->group->sortResource();
            $group      = $this->group->getById($groupID);
            $groupPrivs = $this->group->getPrivs($groupID);

            $this->view->title      = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[] = $group->name;
            $this->view->position[] = $this->lang->group->managePriv;

            /* Join changelog when be equal or greater than this version.*/
            $realVersion = str_replace('_', '.', $version);
            $changelog   = array();
            foreach($this->lang->changelog as $currentVersion => $currentChangeLog)
            {
                if(version_compare($currentVersion, $realVersion, '>=')) $changelog[] = join(',', $currentChangeLog);
            }
            $changelogs = ',' . join(',', $changelog) . ',';

            $this->lang->custom->common = $this->lang->group->config;
            if($this->config->edition == 'max' and $this->config->vision == 'rnd' and isset($this->lang->baseline)) $this->lang->baseline->common = $this->lang->group->docTemplate;

            $modules = $this->group->getPrivManagerPairs('module', $menu);

            $this->view->group      = $group;
            $this->view->groupPrivs = $groupPrivs;
            $this->view->groupID    = $groupID;
            $this->view->menu       = $menu;
            $this->view->version    = $version;

            $privs = $this->group->getPrivsListByView($menu);
            $privs = $this->group->transformPrivLang($privs);
            $privs = $this->group->getCustomPrivs($menu, $privs);

            $privList           = $modules;
            $privMethods        = array();
            $selectPrivs        = array();
            $selectedPrivIdList = array();
            foreach($privs as $priv)
            {
                if(!empty($version) and strpos($changelogs, ",{$priv->module}-{$priv->method},") === false) continue;

                if(!isset($privList[$priv->parentCode])) $privList[$priv->parentCode] = array();
                if(!is_array($privList[$priv->parentCode])) $privList[$priv->parentCode] = array();
                if(!isset($privList[$priv->parentCode][$priv->parent])) $privList[$priv->parentCode][$priv->parent] = array();
                $privList[$priv->parentCode][$priv->parent][$priv->key] = $priv;

                if(!isset($privMethod[$priv->module])) $privMethod[$priv->module] = array();
                $privMethods[$priv->module][$priv->method] = $priv->method;

                if(!isset($selectPrivs[$priv->parentCode])) $selectPrivs[$priv->parentCode] = array();
                if(!isset($selectPrivs[$priv->parentCode][$priv->parent])) $selectPrivs[$priv->parentCode][$priv->parent] = 0;
                if(!empty($groupPrivs[$priv->module][$priv->method]))
                {
                    $selectPrivs[$priv->parentCode][$priv->parent] ++;
                    if(isset($priv->id)) $selectedPrivIdList[$priv->id] = $priv->id;
                }
            }
            foreach($privList as $module => $privs)
            {
                if(!is_array($privList[$module])) unset($privList[$module]);
            }

            if(empty($menu) or $menu == 'general')
            {
                $unassignedModule = array_diff(array_keys(get_object_vars($this->lang->resource)), array_keys($modules));
                foreach($unassignedModule as $index => $module)
                {
                    if(!$this->group->checkMenuModule($menu, $module) or isset($privList[$module])) continue;

                    $selectPrivs[$module] = array();
                    $selectPrivs[$module][0] = 0;

                    foreach($this->lang->resource->{$module} as $method => $methodLabel)
                    {
                        if(isset($privMethods[$module][$method])) continue;
                        $privMethods[$module][$method] = $method;

                        $privList[$module][0]["{$module}-$method"] = new stdclass();
                        $privList[$module][0]["{$module}-$method"]->module = $module;
                        $privList[$module][0]["{$module}-$method"]->method = $method;
                        $privList[$module][0]["{$module}-$method"]->name   = $this->lang->{$module}->{$methodLabel};
                        $privList[$module][0]["{$module}-$method"]->action = "{$module}-{$method}";
                        if(!empty($groupPrivs[$module][$method])) $selectPrivs[$module][0] ++;
                    }
                }
            }

            $groupPrivsIdList   = $this->group->getPrivsIdListByGroup($groupID);
            $excludePrivsIdList = array_diff(array_keys($groupPrivsIdList), $selectedPrivIdList);
            $relatedPrivData    = $this->group->getRelatedPrivs($selectedPrivIdList, '', $excludePrivsIdList);

            unset($privList['index']);

            $this->view->privList           = $privList;
            $this->view->privMethods        = $privMethods;
            $this->view->selectPrivs        = $selectPrivs;
            $this->view->privPackages       = $this->group->getPrivManagerPairs('package');
            $this->view->selectedPrivIdList = $selectedPrivIdList;
            $this->view->relatedPrivData    = $relatedPrivData;
            $this->view->excludePrivsIdList = $excludePrivsIdList;
        }
        elseif($type == 'byModule')
        {
            $this->group->sortResource();
            $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[] = $this->lang->group->managePriv;

            $privs             = $this->group->getPrivsListByView('');
            $privs             = $this->group->getCustomPrivs('', $privs);
            $modules           = $this->dao->select('*')->from(TABLE_PRIVMANAGER)->where('type')->eq('module')->fetchAll('code');
            $modulePairs       = $this->group->getPrivManagerPairs('module');
            $unassignedModules = array_diff(array_keys(array_filter(get_object_vars($this->lang->resource), function($modulePrivs){return !empty((array)$modulePrivs);})), array_keys($modulePairs));
            foreach($unassignedModules as $unassignedModule)
            {
                $this->app->loadLang($unassignedModule);
                $modulePairs[$unassignedModule] = isset($this->lang->{$unassignedModule}->common) ? $this->lang->{$unassignedModule}->common : $unassignedModule;
            }

            $packageGroup = array();
            foreach($modulePairs as $moduleCode => $moduleLang)
            {
                $modulePackages  = $this->group->getPrivManagerPairs('package', $moduleCode);
                $unassignedPrivs = $this->group->getUnassignedPrivsByModule($moduleCode);
                $packageGroup[$moduleCode] = isset($modules[$moduleCode]) ? $modulePackages : array();
                $unassignedPrivPackages    = isset($modules[$moduleCode]) ? $modules[$moduleCode]->id : 0;
                $packageGroup[$moduleCode] = $packageGroup[$moduleCode] + array($unassignedPrivPackages => $this->lang->group->other);
            }

            $hasPrivModule = array();
            foreach($privs as $privKey => $priv)
            {
                if(!isset($modulePairs[$priv->module])) $modulePairs[$priv->module] = isset($this->lang->{$priv->module}->common) ? $this->lang->{$priv->module}->common : $priv->module;

                $hasPrivModule[] = $priv->parentCode;
            }
            $emptyPrivModules = array_diff(array_keys($modulePairs), $hasPrivModule);
            foreach($emptyPrivModules as $emptyPrivModule) unset($modulePairs[$emptyPrivModule]);

            $indexPrivs = $this->group->getPrivByParent(isset($packageGroup['index']) ? array_keys($packageGroup['index']) : $modules['index']->id);
            $indexPrivs = $this->group->transformPrivLang($indexPrivs);
            foreach($indexPrivs as $privID => $priv)
            {
                $indexPrivs["{$priv->module}-{$priv->method}"] = $priv->name;
                unset($indexPrivs[$privID]);
            }

            $this->view->groups       = $this->group->getPairs();
            $this->view->modulePairs  = $modulePairs;
            $this->view->packageGroup = $packageGroup;
            $this->view->indexPrivs   = $indexPrivs;
        }
        $this->display();
    }

    /**
     * Manage members of a group.
     *
     * @param  int    $groupID
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function manageMember($groupID, $deptID = 0)
    {
        if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
        $group        = $this->group->getById($groupID);
        $groupUsers   = $this->group->getUserPairs($groupID);
        $allUsers     = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers   = array_diff_assoc($allUsers, $groupUsers);
        $outsideUsers = $this->user->getPairs('outside|noclosed|noletter|noempty');

        $this->view->outsideUsers = array_diff_assoc($outsideUsers, $groupUsers);

        $title      = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[] = $group->name;
        $position[] = $this->lang->group->manageMember;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->group        = $group;
        $this->view->deptTree     = $this->loadModel('dept')->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createGroupManageMemberLink'), $groupID);
        $this->view->groupUsers   = $groupUsers;
        $this->view->otherUsers   = $otherUsers;
        $this->display();
    }

    /**
     * Manage members of a group.
     *
     * @param  int    $groupID
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function manageProjectAdmin($groupID, $deptID = 0)
    {
        if(!empty($_POST))
        {
            $this->group->updateProjectAdmin($groupID);
            return print(js::locate(inlink('manageProjectAdmin', "group=$groupID"), 'parent'));
        }

        list($programs, $projects, $products, $executions) = $this->group->getObject4AdminGroup();

        $group      = $this->group->getById($groupID);
        $groupUsers = $this->dao->select('t1.account, t2.realname')->from(TABLE_PROJECTADMIN)->alias('t1')->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')->fetchPairs();

        $title      = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[] = $group->name;
        $position[] = $this->lang->group->manageMember;

        $this->view->title         = $title;
        $this->view->position      = $position;
        $this->view->allUsers      = array('' => '') + $groupUsers + $this->loadModel('dept')->getDeptUserPairs($deptID);
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
            return print(js::confirm($this->lang->group->confirmDelete, $this->createLink('group', 'delete', "groupID=$groupID&confirm=yes")));
        }
        else
        {
            $this->group->delete($groupID);

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                return $this->send($response);
            }
            return print(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
    }

   /**
     * Edit manage priv.
     *
     * @param  string $browseType
     * @param  string $view
     * @param  int    $paramID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function editManagePriv($browseType = '', $view = '', $paramID = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(empty($browseType) and $browseType != 'bysearch') $browseType = $this->cookie->managePrivEditType ? $this->cookie->managePrivEditType : 'bycard';
        if($browseType == 'bysearch' and $this->cookie->managePrivEditType == 'bycard') $browseType = 'bycard';

        $moduleLang = $this->group->getMenuModules($view, true);

        if($browseType == 'bycard')
        {
            $privs        = $this->group->getPrivsListByView($view);
            $privPackages = $this->group->getPrivPackagePairs();
            $privLang     = $this->group->getPrivLangPairs();

            $privList = array();
            foreach($privs as $privID => $priv)
            {
                if(!isset($privList[$priv->module])) $privList[$priv->module] = array();
                if(!isset($privList[$priv->module][$priv->package])) $privList[$priv->module][$priv->package] = array();
                $privList[$priv->module][$priv->package][$priv->id] = $priv;
            }

            $this->view->privLang     = $privLang;
            $this->view->privPackages = $privPackages;
        }
        else
        {
            $privList = $browseType != 'bysearch' ? $this->group->getPrivsListByView($view) : $this->group->getPrivsListBySearch($paramID);

            foreach($privList as $privID => $priv) if(!$this->group->checkMenuModule($view, $priv->module)) unset($privList[$privID]);

            /* Pager. */
            $this->app->loadClass('pager', $static = true);
            $recTotal = count($privList);
            $pager    = new pager($recTotal, $recPerPage, $pageID);
            $privList = array_chunk($privList, $pager->recPerPage, true);
            $privList = empty($privList) ? $privList : $privList[$pageID - 1];

            /* Build the search form. */
            $queryID   = ($browseType == 'bysearch') ? (int)$paramID : 0;
            $actionURL = $this->createLink('group', 'editManagePriv', "browseType=bysearch&view=&paramID=myQueryID&recTotal=$recTotal&recPerPage=$recPerPage");
            $this->group->buildPrivSearchForm($queryID, $actionURL);

            $privRelations = $this->group->getPrivRelationsByIdList(array_keys($privList));
            if(!isset($privRelations['recommend'])) $privRelations['recommend'] = array();
            if(!isset($privRelations['depend']))    $privRelations['depend']    = array();

            $this->view->pager         = $pager;
            $this->view->privRelations = $privRelations;
        }

        $this->view->title          = $this->lang->group->editManagePriv;
        $this->view->browseType     = $browseType;
        $this->view->privList       = $privList;
        $this->view->packages       = $this->group->getPrivPackagePairs($view);
        $this->view->moduleLang     = $moduleLang;
        $this->view->modulePackages = $this->group->getModuleAndPackageTree();
        $this->view->view           = $view;

        $this->display();
    }

    /**
     * Batch change package.
     *
     * @param  string $module
     * @param  int    $packageID
     * @access public
     * @return void
     */
    public function batchChangePackage($module, $packageID)
    {
        if(empty($_POST['privIdList'])) return print(js::reload('parent'));
        $privIdList = array_unique($_POST['privIdList']);
        $allChanges = $this->group->batchChangePackage($privIdList, $module, $packageID);
        if(dao::isError()) return print(js::error(dao::getError()));

        $this->loadModel('action');
        foreach($allChanges as $privID => $changes)
        {
            $actionID = $this->action->create('privlang', $privID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        return print(js::reload('parent'));
    }

    /**
     * Manage privilege packages.
     *
     * @access public
     * @return void
     */
    public function managePrivPackage()
    {
        $this->view->title            = $this->lang->group->managePrivPackage;
        $this->view->packagesTreeList = $this->group->getPrivPackageTreeList();
        $this->display();
    }

    /**
     * Create a privilege package.
     *
     * @access public
     * @return void
     */
    public function createPrivPackage()
    {
        if(!empty($_POST))
        {
            $packageID = $this->group->createPrivPackage();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title   = $this->lang->group->createPrivPackage;
        $this->view->modules = $this->group->getPrivModules();
        $this->display();
    }

    /**
     * Edit a privilege package.
     *
     * @access public
     * @return void
     */
    public function editPrivPackage($privPackageID)
    {
        if(!empty($_POST))
        {
            $changes = $this->group->updatePrivPackage($privPackageID);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('privpackage', $privPackageID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title       = $this->lang->group->editPrivPackage;
        $this->view->privPackage = $this->group->getPrivPackageByID($privPackageID);
        $this->view->modules     = $this->group->getPrivModules();
        $this->view->actions     = $this->loadModel('action')->getList('privpackage', $privPackageID);
        $this->view->users       = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Sort priv packages.
     *
     * @param  int    $parentID
     * @param  string $type
     * @access public
     * @return void
     */
    public function sortPrivPackages($parentID = 0, $type = '')
    {
        $orders = $_POST['orders'];
        if(empty($orders)) return false;
        if($type == 'view')
        {
            $orders = str_replace('View,', ',', $orders);
            $orders = trim($orders, ',');
            $this->loadModel('setting')->setItem("system.priv.views", $orders);
        }
        if($type == 'module')
        {
            $orders   = trim($orders, ',');
            $parentID = rtrim($parentID, 'View');
            $this->loadModel('setting')->setItem("system.priv.{$parentID}Modules", $orders);
        }
        if($type == 'package')
        {
            $orders = explode(',', $orders);
            foreach($orders as $index => $id)
            {
                if(!empty($id)) $this->dao->update(TABLE_PRIVPACKAGE)->set('order')->eq(($index + 1) * 5)->where('id')->eq($id)->exec();
            }
        }
    }

    /**
     * Delete a priv package.
     *
     * @param  int    $privPackageID
     * @access public
     * @return void
     */
    public function deletePrivPackage($privPackageID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->group->confirmDeleteAB, $this->createLink('group', 'deletePrivPackage', "privPackageID=$privPackageID&confirm=yes")));
        }
        else
        {
            $privPackage = $this->group->getPrivPackageByID($privPackageID);
            $this->group->deletePrivPackage($privPackageID);
            if(!dao::isError()) $this->loadModel('action')->create('privpackage', $privPackageID, 'deleted', '', zget($privPackage, 'name'));

            return print(js::reload('parent'));
        }
    }

    /**
     * Add recommendation.
     *
     * @param  string    $privIdList
     * @param  string    $type     depend|recommend
     * @access public
     * @return void
     */
    public function addRelation($privIdList, $type)
    {
        if(strpos("depend|recommend", $type) === false) return print('Error type');

        if($this->server->request_method == 'POST')
        {
            if(empty($_POST['relation'])) return print(js::alert($this->lang->group->noticeNoChecked));

            $this->group->saveRelation($privIdList, $type);
            if(strpos($privIdList, ',') === false) print(js::execute("if(typeof(parent.parent.getSideRelation) == 'function') parent.parent.getSideRelation({$privIdList})"));
            return print(js::reload('parent'));
        }

        $privs   = $this->group->getPrivByIdList($privIdList);
        $modules = array();
        foreach($privs as $priv) $modules[$priv->module] = $priv->module;

        $modulePrivs   = $this->group->getPrivByModule($modules);
        $packageIdList = array();
        foreach($modulePrivs as $packagePrivs) $packageIdList = array_unique(array_merge($packageIdList, array_keys($packagePrivs)));

        $this->view->privs       = $privs;
        $this->view->modules     = array('' => $this->lang->group->selectModule) + $this->group->getMenuModules(null, true);
        $this->view->packages    = $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->where('id')->in($packageIdList)->orderBy('`order`')->fetchPairs('id', 'name');
        $this->view->modulePrivs = $modulePrivs;
        $this->view->type        = $type;
        $this->display();
    }

    /**
     * Delete relation.
     *
     * @param  string $type   recommend|depend
     * @param  int    $privID
     * @param  int    $relationPriv
     * @access public
     * @return void
     */
    public function deleteRelation($type, $privID, $relationPriv)
    {
        $this->dao->delete()->from(TABLE_PRIVRELATION)->where('type')->eq($type)->andWhere('priv')->eq($privID)->andWhere('relationPriv')->eq($relationPriv)->exec();
    }

    /**
     * Batch delete relation.
     *
     * @param  string  $privIdList
     * @param  string  $type     recommend|depend
     * @access public
     * @return void
     */
    public function batchDeleteRelation($privIdList, $type)
    {
        if(strpos("depend|recommend", $type) === false) return print('Error type');

        if($this->server->request_method == 'POST')
        {
            if(empty($_POST['relation'])) return print(js::alert($this->lang->group->noticeNoChecked));

            $data        = fixer::input('post')->get();
            $deletedPriv = array();
            foreach($data->relation as $module => $relations) $deletedPriv = array_merge($deletedPriv, $relations);
            $this->dao->delete()->from(TABLE_PRIVRELATION)->where('type')->eq($type)->andWhere('priv')->in($privIdList)->andWhere('relationPriv')->in($deletedPriv)->exec();

            return print(js::reload('parent'));
        }

        $privs   = $this->group->getPrivByIdList($privIdList);
        $modules = array();
        foreach($privs as $priv) $modules[$priv->module] = $priv->module;

        $modulePrivs   = $this->group->getPrivByModule($modules);
        $packageIdList = array();
        foreach($modulePrivs as $packagePrivs) $packageIdList = array_unique(array_merge($packageIdList, array_keys($packagePrivs)));

        $this->view->privs       = $privs;
        $this->view->modules     = array('' => $this->lang->group->selectModule) + $this->group->getMenuModules(null, true);
        $this->view->packages    = $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->where('id')->in($packageIdList)->orderBy('`order`')->fetchPairs('id', 'name');
        $this->view->modulePrivs = $modulePrivs;
        $this->view->type        = $type;
        $this->display('group', 'addrelation');
    }

    /**
     * Ajax get priv tree.
     *
     * @param  string $privIdList
     * @param  string $module
     * @param  string $type     recommend|depend
     * @access public
     * @return void
     */
    public function ajaxGetPrivTree($privIdList, $module, $type = 'recommend')
    {
        if(is_string($privIdList)) $privIdList = explode(',', $privIdList);

        $modulePrivs   = $this->group->getPrivByModule($module);
        $packageIdList = array();
        foreach($modulePrivs as $packagePrivs) $packageIdList = array_unique(array_merge($packageIdList, array_keys($packagePrivs)));

        $modules  = $this->group->getMenuModules(null, true);
        $packages = $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->where('id')->in($packageIdList)->orderBy('`order`')->fetchPairs('id', 'name');

        $tree  = "<ul class='tree' data-ride='tree'><li>";
        $tree .= html::a('#', $modules[$module]);
        $tree .= "<ul class='relationBox'>";
        foreach($packages as $packageID => $packageName)
        {
            if(empty($modulePrivs[$module][$packageID])) continue;
            $tree .= "<li class='clearleft'>";
            $tree .= html::a('#', $packageName);
            $tree .= '<ul>';

            foreach($modulePrivs[$module][$packageID] as $id => $modulePriv)
            {
                $tree .= '<li>';
                $tree .= html::checkbox("relation[$module]", array($id => $modulePriv->name), '');
                $tree .= '</li>';
            }
            $tree .= '</ul></li>';
            unset($modulePrivs[$module][$packageID]);
        }
        if(!empty($modulePrivs[$module]))
        {
            $tree .= "<li class='clearleft'>";
            $tree .= html::a('#', $this->lang->group->unassigned);
            $tree .= '<ul>';
            foreach($modulePrivs[$module] as $packageID => $packagePrivs)
            {
                foreach($packagePrivs as $id => $modulePriv)
                {
                    $tree .= '<li>';
                    $tree .= html::checkbox("relation[$module]", array($id => $modulePriv->name), '');
                    $tree .= '</li>';
                }
            }
            $tree .= '</ul></li>';
        }
        $tree .= '</ul></li></ul>';
        return print($tree);
    }

    /**
     * Create a priv.
     *
     * @access public
     * @return void
     */
    public function createPriv()
    {
        if(!empty($_POST))
        {
            $packageID = $this->group->createPriv();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }
        $views      = $this->loadModel('setting')->getItem("owner=system&module=priv&key=views");
        $views      = explode(',', $views);
        $moduleLang = $this->group->getMenuModules('', true);
        foreach($views as $index => $view)
        {
            $views[$view] = isset($this->lang->{$view}->common) ? $this->lang->{$view}->common : zget($moduleLang, $view);
            unset($views[$index]);
        }

        $this->view->title              = $this->lang->group->createPriv;
        $this->view->views              = array('' => '') + $views;
        $this->view->modules            = array('' => '') + $this->group->getPrivModules('', 'noViewName');
        $this->view->packages           = array('' => '') + $this->group->getPrivPackagePairs();
        $this->view->moduleViewPairs    = $this->group->getPrivModuleViewPairs();
        $this->view->packageModulePairs = $this->group->getPrivPackagePairs('', '', 'module');
        $this->display();
    }

    /**
     * Edit a priv.
     *
     * @param   int     $privID
     * @access  public
     * @return  void
     **/
    public function editPriv($privID)
    {
        $privID = intval($privID);
        $priv   = $this->group->getPrivByID($privID);

        if(!$priv)
        {
            return print(js::alert($this->lang->group->noneProject));
        }

        if(!empty($_POST))
        {
            $currentLang = $this->app->clientLang ? : 'zh-cn';
            $changes     = $this->group->updatePriv($privID, $currentLang);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('privlang', $privID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));

            $this->send(array('result' => $responseResult, 'message' => $responseMessage, 'locate' => $locate));
        }

        $views      = $this->loadModel('setting')->getItem("owner=system&module=priv&key=views");
        $views      = explode(',', $views);
        $moduleLang = $this->group->getMenuModules('', true);
        foreach($views as $index => $view)
        {
            $views[$view] = isset($this->lang->{$view}->common) ? $this->lang->{$view}->common : zget($moduleLang, $view);
            unset($views[$index]);
        }
        $moduleViewPairs = $this->group->getPrivModuleViewPairs();
        $priv->view      = zget($moduleViewPairs, $priv->module, '');

        $this->view->title              = $this->lang->group->editPriv;
        $this->view->views              = array('' => '') + $views;
        $this->view->modules            = array('' => '') + $this->group->getPrivModules($priv->view, 'noViewName');
        $this->view->packages           = array('' => '') + $this->group->getPrivPackagePairs($priv->view, $priv->module);
        $this->view->moduleViewPairs    = $moduleViewPairs;
        $this->view->packageModulePairs = $this->group->getPrivPackagePairs('', '', 'module');
        $this->view->priv               = $priv;
        $this->view->actions            = $this->loadModel('action')->getList('privlang', $privID);
        $this->view->users              = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Delete a priv.
     *
     * @param  int    $privID
     * @access public
     * @return void
     */
    public function deletePriv($privID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->group->confirmDeleteAB, $this->createLink('group', 'deletePriv', "privID=$privID&confirm=yes")));
        }
        else
        {
            $priv = $this->group->getPrivByID($privID);
            $this->group->deletePriv($privID);
            if(!dao::isError()) $this->loadModel('action')->create('privlang', $privID, 'deleted', '', "$priv->moduleName-$priv->methodName");

            return print(js::reload('parent'));
        }
    }

    /**
     * Ajax get priv modules by view.
     *
     * @param  string $viewName
     * @access public
     * @return string
     */
    public function ajaxGetPrivModules($viewName = '')
    {
        $modules = $this->group->getPrivModules($viewName, 'noViewName');
        echo html::select('module', array('' => '') + $modules, '', "class='form-control picker-select' onchange='loadPackages(this.value, \"module\")'");
    }

    /**
     * Ajax get priv packages by view or module.
     *
     * @param  string $object
     * @param  string $type
     * @access public
     * @return string
     */
    public function ajaxGetPrivPackages($object = '', $type = 'view')
    {
        $packages = array();
        if($type == 'view') $packages = $this->group->getPrivPackagePairs($object);
        if($type == 'module') $packages = $this->group->getPrivPackagePairs('', $object);
        echo html::select('package', array('' => '') + $packages, '', "class='form-control picker-select' onchange='changeViewAndModule(this.value)'");
    }

    /**
     * AJAX: Get priv's related priv list.
     *
     * @param  int    $privID
     * @access public
     * @return bool
     */
    public function ajaxGetPrivRelations($privID)
    {
        $relatedPrivs = $this->group->getPrivRelation($privID);
        if(empty($relatedPrivs)) return print('');

        $moduleLang = $this->group->getMenuModules('', true);
        $privList   = array('depend' => array(), 'recommend' => array());
        foreach($relatedPrivs as $type => $relations)
        {
            foreach($relations as $relatedPriv)
            {
                $module = $relatedPriv->module;
                if(!isset($privList[$type][$module])) $privList[$type][$module] = array();
                $privList[$type][$module]['title']      = zget($moduleLang, $module, $module);
                $privList[$type][$module]['module']     = $relatedPriv->module;
                $privList[$type][$module]['children'][] = array('title' => $relatedPriv->name, 'relationPriv' => $relatedPriv->id, 'privID' => $privID);
            }
            $privList[$type] = array_values($privList[$type]);
        }
        return print(json_encode($privList));
    }

    /**
     * AJAX: update priv order.
     *
     * @access public
     * @return void
     */
    public function ajaxUpdatePrivOrder()
    {
        if(!empty($_POST)) $this->group->updatePrivOrder();
    }

    /**
     * AJAX: Get privs by parents.
     *
     * @param  string  $module
     * @param  string  $parentType
     * @param  string  $parentList
     * @access public
     * @return bool
     */
    public function ajaxGetPrivByParents($module, $parentType, $parentList)
    {
        $menu     = isset($this->lang->navGroup->$module) ? $this->lang->navGroup->$module : $module;
        $privList = array();
        if($parentType == 'module')
        {
            $privs = $this->group->getPrivByModule($parentList);
            foreach($privs as $moduleID => $packages)
            {
                foreach($packages as $packageID => $packagePrivs)
                {
                    foreach($packagePrivs as $privID => $packagePriv)
                    {
                        $privList["{$packagePriv->module}-{$packagePriv->method}"] = $packagePriv;
                    }
                }
            }
        }
        elseif($parentType == 'package')
        {
            $privList = $this->group->getPrivByParent(trim($parentList, ','));
            foreach($privList as $privID => $priv)
            {
                $privList["{$priv->module}-{$priv->method}"] = $priv;
                unset($privList[$privID]);
            }
        }

        $privList = $this->group->getCustomPrivs($menu, $privList);
        foreach($privList as $privKey => $priv)
        {
            if((isset($priv->parentCode) and $priv->parentCode != $module) or (strpos($parentList, ",{$priv->parent},") === false and $parentType == 'package')) unset($privList[$privKey]);
        }
        $privList = $this->group->transformPrivLang($privList, true);

        return print(html::select('actions[]', $privList, '', "multiple='multiple' class='form-control'"));
    }

    /**
     * AJAX: Get priv's dependent priv list.
     *
     * @param  string  $privIdList
     * @access public
     * @return bool
     */
    public function ajaxGetRelatedPrivs()
    {
        $privIdList     = zget($_POST, 'privList');
        $recommedSelect = zget($_POST, 'recommedSelect');
        $excludeIdList  = zget($_POST, 'excludeIdList');
        $privList       = $this->group->getRelatedPrivs($privIdList, '', $excludeIdList, $recommedSelect);
        return print(json_encode($privList));
    }
}
