<?php
/**
 * The control file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        $this->loadModel('user');
        $this->appendResourcePackages();
    }

    /**
     * Append packages in resource.
     *
     * @access public
     * @return void
     */
    public function appendResourcePackages()
    {
        $allPrivs = array();
        foreach($this->config->group->package as $packageCode => $package)
        {
            if(empty($package->privs)) continue;

            foreach($package->privs as $privCode => $priv)
            {
                $allPrivs[$privCode] = $privCode;
            }
        }

        /* Privs in resource but not in package. */
        foreach($this->lang->resource as $module => $methodList)
        {
            foreach($methodList as $method => $methodLang)
            {
                if(isset($allPrivs[$module . '-' . $method])) continue;

                /* Create subset. */
                if(!isset($this->config->group->subset->$module))
                {
                    $this->config->group->subset->$module = new stdclass();
                    $this->config->group->subset->$module->order = 10000;
                    $this->config->group->subset->$module->nav   = $module;
                }

                /* Create subset. */
                $methodPackage = array('create' => 'manage', 'batchcreate' => 'manage', 'browse' => 'browse', 'view' => 'browse', 'delete' => 'delete', 'batchdelete' => 'delete', 'edit' => 'manage', 'batchedit' => 'manage');
                $packageName = isset($methodPackage[$method]) ? $methodPackage[$method] : 'other';
                $packageCode = $module . $packageName;
                if(!isset($this->config->group->package->$packageCode))
                {
                    $this->config->group->package->$packageCode = new stdclass();
                    $this->config->group->package->$packageCode->order  = 5;
                    $this->config->group->package->$packageCode->subset = $module;
                    $this->config->group->package->$packageCode->privs  = array();
                    $this->lang->group->package->$packageCode = $this->lang->group->package->$packageName;
                }

                /* Browse action in workflow. */
                if(isset($this->lang->$module->menus) && $method == 'browse')
                {
                    $this->config->group->package->$packageCode->privs["$module-$method"] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,or', 'order' => 5, 'depend' => array(), 'recommend' => array());

                    foreach($this->lang->$module->menus as $flowMethod => $flowName)
                    {
                        $this->config->group->package->$packageCode->privs["$module-$flowMethod"] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,or', 'order' => 5, 'depend' => array("$module-$method"), 'recommend' => array());
                    }
                }
                else
                {
                    $this->config->group->package->$packageCode->privs["$module-$method"] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,or', 'order' => 5, 'depend' => array(), 'recommend' => array());
                }
            }
        }
    }

    /**
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
            $this->group->updateView($groupID);
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
            if(isset($executionProject[$id])) $executions[$id] = $executionProject[$id] . ' / ' . $name;
        }

        $this->view->group      = $group;
        $this->view->programs   = $this->loadModel('program')->getParentPairs('', '', false);
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram(0, 'all', true, 'order_desc');
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
     * @param  string $type     byPackage|byGroup|byModule
     * @param  int    $param
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return void
     */
    public function managePriv($type = 'byPackage', $param = 0, $nav = '', $version = '')
    {
        if($type == 'byGroup' or $type == 'byPackage') $groupID = $param;

        $this->view->type = $type;

        if(!empty($_POST))
        {
            if($type == 'byGroup' || $type == 'byPackage') $result = $this->group->updatePrivByGroup($groupID, $nav, $version);
            if($type == 'byModule') $result = $this->group->updatePrivByModule();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($type == 'byGroup' or $type == 'byPackage') return $this->send(array('result' => 'success', 'message' => ($result ? $this->lang->group->dependPrivsSaveTip : $this->lang->saveSuccess), 'load' => true));
            if($type == 'byModule') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        if($type == 'byGroup' || $type == 'byPackage') $this->groupZen->managePrivByGroup($groupID, $nav, $version);
        if($type == 'byModule') $this->groupZen->managePrivByModule();

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
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }
        $group        = $this->group->getById($groupID);
        $groupUsers   = $this->group->getUserPairs($groupID);
        $allUsers     = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers   = array_diff_assoc($allUsers, $groupUsers);
        $outsideUsers = $this->user->getPairs('outside|noclosed|noletter|noempty');

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
            return $this->sendSuccess(array('load' => true));
        }

        list($programs, $projects, $products, $executions) = $this->group->getObject4AdminGroup();

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
        $this->group->delete($groupID);

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
     * Edit manage priv.
     *
     * @param  string $browseType
     * @param  string $view
     * @param  int    $paramID
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function editManagePriv($browseType = '', $view = '', $paramID = 0, $recPerPage = 100, $pageID = 1)
    {
        if(empty($browseType) and $browseType != 'bysearch') $browseType = $this->cookie->managePrivEditType ? $this->cookie->managePrivEditType : 'bycard';
        if($browseType == 'bysearch' and $this->cookie->managePrivEditType == 'bycard') $browseType = 'bycard';

        $moduleLang = $this->group->getMenuModules($view, true);

        if($browseType == 'bycard')
        {
            $privs        = $this->group->getPrivsListByView($view);
            $privs        = $this->group->transformPrivLang($privs);
            $privPackages = $this->group->getPrivManagerPairs('package');

            $privList = array();
            foreach($privs as $privID => $priv)
            {
                if(!isset($privList[$priv->parentCode])) $privList[$priv->parentCode] = array();
                if(!is_array($privList[$priv->parentCode])) $privList[$priv->parentCode] = array();
                if(!isset($privList[$priv->parentCode][$priv->parent])) $privList[$priv->parentCode][$priv->parent] = array();
                $privList[$priv->parentCode][$priv->parent][$priv->key] = $priv;
            }

            $this->view->privPackages = $privPackages;
        }
        else
        {
            $privList = $browseType != 'bysearch' ? $this->group->getPrivsListByView($view) : $this->group->getPrivsListBySearch($paramID);
            $privs    = $this->group->transformPrivLang($privList);

            /* Pager. */
            $this->app->loadClass('pager', $static = true);
            $total    = count($privList);
            $pager    = new pager($total, $recPerPage, $pageID);
            $privList = array_chunk($privList, $pager->recPerPage, true);
            $privList = empty($privList) ? $privList : $privList[$pageID - 1];

            /* Build the search form. */
            $queryID   = ($browseType == 'bysearch') ? (int)$paramID : 0;
            $actionURL = $this->createLink('group', 'editManagePriv', "browseType=bysearch&view=&paramID=myQueryID&recPerPage=$recPerPage");
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
                if(!empty($id)) $this->group->updatePackageOrder($id, ($index + 1) * 5);
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
        $this->view->packages    = $this->group->getPackagePairs($packageIdList);
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
        $this->view->packages    = $this->group->getPackagePairs($packageIdList);
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
        $packages = $this->group->getPackagePairs($packageIdList);

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
        $this->view->views              = $views;
        $this->view->modules            = $this->group->getPrivModules('', 'noViewName');
        $this->view->packages           = $this->group->getPrivPackagePairs();
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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));

            $this->send(array('result' => $responseResult, 'message' => $responseMessage, 'load' => $locate));
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
        $this->view->views              = $views;
        $this->view->modules            = $this->group->getPrivModules($priv->view, 'noViewName');
        $this->view->packages           = $this->group->getPrivPackagePairs($priv->view, $priv->module);
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
                $privList[$type][$module]['text']       = zget($moduleLang, $module, $module);
                $privList[$type][$module]['module']     = $relatedPriv->module;
                $privList[$type][$module]['children'][] = array('text' => $relatedPriv->name, 'relationPriv' => $relatedPriv->id, 'privID' => $privID);
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
     * @param  string  $selectedSubset
     * @param  string  $selectedPackages
     * @access public
     * @return bool
     */
    public function ajaxGetPrivByParents($selectedSubset, $selectedPackages)
    {
        $privs = $this->group->getPrivByParents($selectedSubset, $selectedPackages);

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
