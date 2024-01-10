<?php
declare(strict_types=1);
/**
 * The control file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @link        https://www.zentao.net
 */
class program extends control
{
    /**
     * Construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('project');
    }

    /**
     * Program list.
     *
     * @param  string  $status
     * @param  string  $orderBy
     * @access public
     * @return void
     */
    public function browse(string $status = 'unclosed', string $orderBy = 'order_asc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1, int $param = 0)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('programList', $uri, 'program');
        $this->session->set('projectList', $uri, 'program');
        $this->session->set('createProjectLocate', $uri, 'program');

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->program->refreshStats(); // Refresh stats fields of projects.

        $programs = $this->programZen->getProgramsByType($status, $orderBy, $param, $pager);
        $PMList   = $this->programZen->getPMListByPrograms($programs);

        /* Build the search form. */
        $actionURL = $this->createLink('program', 'browse', "status=bySearch&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}&param=myQueryID");
        $this->config->program->search['actionURL'] = $actionURL;
        $this->loadModel('search')->setSearchParams($this->config->program->search);

        $this->view->title       = $this->lang->program->browse;
        $this->view->programs    = $programs;
        $this->view->status      = $status;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->usersAvatar = $this->user->getAvatarPairs('');
        $this->view->PMList      = $PMList;
        $this->view->param       = $param;

        $this->display();
    }

    /**
     * 项目集看板。
     * Program kanban list.
     *
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function kanban($browseType = 'my')
    {
        $this->view->title      = $this->lang->program->kanban->common;
        $this->view->kanbanList = $this->programZen->getKanbanList($browseType);
        $this->view->browseType = $browseType;
        $this->display();
    }

    /**
     * 项目集下产品列表。
     * Program products list.
     *
     * @param  int     $programID
     * @param  string  $browseType
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function product(int $programID = 0, string $browseType = 'noclosed', string $orderBy = 'order_asc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $programPairs = $this->program->getPairs();
        if($programID && !isset($programPairs[$programID]))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('program', 'browse'))));
        }

        $programID = $this->program->checkAccess($programID, $programPairs);

        helper::setcookie("lastProgram", (string)$programID);
        common::setMenuVars('program', $programID);

        /* Get the top programID. */
        if($programID)
        {
            $program   = $this->program->getByID($programID);
            $path      = explode(',', $program->path);
            $path      = array_filter($path);
            $programID = (int)current($path);

            $this->view->program = $program;
        }

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $products = $this->loadModel('product')->getList($programID, $browseType);
        $this->view->products = $this->product->getStats(array_keys($products), $orderBy, $pager, 'story',  $programID);

        $this->view->title         = $this->lang->program->product;
        $this->view->programID     = $programID;
        $this->view->browseType    = $browseType;
        $this->view->orderBy       = $orderBy;
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->userIdPairs   = $this->user->getPairs('noletter|showid');
        $this->view->usersAvatar   = $this->user->getAvatarPairs('');
        $this->display();
    }

    /**
     * Create a program.
     *
     * @param  int    $parentProgramID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create(int $parentProgramID = 0, string $extra = '')
    {
        $parentProgram = $this->program->getByID($parentProgramID);

        if($_POST)
        {
            $program   = $this->programZen->buildProgramForCreate();
            $programID = $this->program->create($program);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('program', $programID, 'opened');
            $locateLink = $this->session->programList ? $this->session->programList : $this->createLink('program', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $programID, 'locate' => $locateLink));
        }

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $this->view->title      = $this->lang->program->create;

        $this->view->gobackLink     = (isset($output['from']) && $output['from'] == 'global') ? $this->createLink('program', 'browse') : '';
        $this->view->pmUsers        = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->poUsers        = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted');
        $this->view->parentProgram  = $parentProgram;
        $this->view->parents        = $this->program->getParentPairs();
        $this->view->programList    = $this->program->getList();
        $this->view->budgetUnitList = $this->project->getBudgetUnitList();
        $this->view->budgetLeft     = empty($parentProgram) ? 0 : $this->program->getBudgetLeft($parentProgram);

        $this->display();
    }

    /**
     * 编辑项目集。
     * Edit the program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function edit(int $programID = 0)
    {
        if($_POST)
        {
            $program = $this->programZen->buildProgramForEdit($programID);
            $changes = $this->program->update($programID, $program);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('program', $programID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->programList ? $this->session->programList : inLink('browse')));
        }

        $program = $this->program->getByID($programID);
        $program->budget = round((float)$program->budget / $this->config->project->budget->tenThousand, $this->config->project->budget->precision);

        $parentProgram = $program->parent ? $this->program->getByID($program->parent) : new stdclass();
        $parents       = $this->program->getParentPairs();
        $parents       = $this->programZen->removeSubjectToCurrent($parents, $programID);

        $this->view->title = $this->lang->program->edit;

        $this->view->pmUsers         = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->program         = $program;
        $this->view->parents         = $parents;
        $this->view->budgetUnitList  = $this->loadModel('project')->getBudgetUnitList();
        $this->view->parentProgram   = $parentProgram;

        $this->display();
    }

    /**
     * 关闭一个项目集。
     * Close a program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function close(int $programID)
    {
        $this->loadModel('action');
        $program = $this->program->getByID($programID);

        if(!empty($_POST))
        {
            /* Only when all subprograms and subprojects are closed can the program be closed. */
            $hasUnfinished = $this->program->hasUnfinishedChildren($program);
            if($hasUnfinished) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->program->closeErrorMessage}');"));

            $programData = form::data()->get();
            $changes     = $this->program->close($programData, $program);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' || !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($programID);
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $this->view->title   = $this->lang->program->close;
        $this->view->program = $program;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions = $this->action->getList('program', $programID);

        $this->display();
    }

    /**
     * 启动项目集。
     * Start a program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function start(int $programID)
    {
        $this->loadModel('action');
        $program = $this->project->getByID($programID);

        if(!empty($_POST))
        {
            $postData = form::data($this->config->program->form->start);
            $postData = $this->programZen->prepareStartExtras($postData);
            $changes  = $this->project->start($programID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->loadModel('common')->syncPPEStatus($programID);
            $this->executeHooks($programID);
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $this->view->title      = $this->lang->program->start;
        $this->view->project    = $program;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->display('project', 'start');
    }

    /**
     * 激活一个项目集。
     * Activate a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function activate(int $programID = 0)
    {
        $this->loadModel('action');
        $program = $this->program->getByID($programID);

        if(!empty($_POST))
        {
            $programData = form::data()->get();
            $changes     = $this->program->activate($programData, $program);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' || !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $program->begin);
        $newEnd   = date('Y-m-d', strtotime($program->end) + $dateDiff * 24 * 3600);

        $this->view->title    = $this->lang->program->activate;
        $this->view->program  = $program;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions  = $this->action->getList('program', $programID);
        $this->view->newBegin = $newBegin;
        $this->view->newEnd   = $newEnd;
        $this->display();
    }

    /**
     * 挂起项目集。
     * Suspend a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function suspend(int $programID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $postData = fixer::input('post')
                ->add('id', $programID)
                ->setDefault('status', 'suspended')
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->setDefault('suspendedDate', helper::today())
                ->stripTags($this->config->program->editor->suspend['id'], $this->config->allowedTags)
                ->get();

            $isSucceed = $this->program->suspend($programID, $postData);
            if(!$isSucceed) return $this->sendError(dao::getError(), true);

            $this->executeHooks($programID);
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $this->view->title   = $this->lang->program->suspend;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions = $this->action->getList('program', $programID);
        $this->view->program = $this->program->getByID($programID);

        $this->display();
    }

    /**
     * 删除一个项目集。
     * Delete a program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function delete(int $programID)
    {
        /* The program can NOT be deleted if it has a child program. */
        $childrenCount = $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->andWhere('deleted')->eq('0')->fetch('count');
        if($childrenCount)
        {
            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => 'Can not delete the program has children.'));
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->program->hasChildren}');"));
        }

        /* The program can NOT be deleted if it has a product. */
        $productCount = $this->dao->select('count(*) as count')->from(TABLE_PRODUCT)->where('program')->eq($programID)->andWhere('deleted')->eq('0')->fetch('count');
        if($productCount) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->program->hasProduct}');"));

        /* Mark the program is deleted and record the action log. */
        $program = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->andWhere('deleted')->eq('0')->fetch();
        if($program)
        {
            $this->dao->update(TABLE_PROGRAM)->set('deleted')->eq('1')->where('id')->eq($programID)->exec();
            $this->loadModel('action')->create('program', $programID, 'deleted', '', actionModel::CAN_UNDELETED);
        }

        return $this->send(array('result' => 'success'));
    }

    /**
     * 获取项目集下的项目。
     * Program project list.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function project(int $programID = 0, string $browseType = 'doing', string $orderBy = 'order_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $programID = $this->program->checkAccess($programID, $this->program->getPairs());
        helper::setcookie("lastProgram", (string)$programID);
        common::setMenuVars('program', $programID);

        /* Set back link. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('programProject', $uri, 'program');
        $this->app->session->set('projectList', $uri, 'program');
        $this->app->session->set('createProjectLocate', $uri, 'program');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $programTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=program&key=programTitle');
        $order        = explode('_', $orderBy);
        $sortField    = zget($this->config->program->sortFields, $order[0], 'id') . '_' . $order[1];
        $projectStats = $this->program->getProjectStats($programID, $browseType, 0, $sortField, $programTitle, false, $pager);

        $this->view->title        = $this->lang->program->project;
        $this->view->projectStats = $projectStats;
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->PMList       = $this->user->getListByAccounts(helper::arrayColumn($projectStats, 'PM'), 'account');
        $this->view->browseType   = $browseType;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * 项目集下的干系人列表。
     * Program stakeholder list.
     *
     * @param  int    $programID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function stakeholder(int $programID = 0, string $orderBy = 't1.id_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $this->app->loadLang('stakeholder');
        common::setMenuVars('program', $programID);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $sort = $orderBy;
        if(strpos($sort, 'name_') !== false) $sort = str_replace('name_', 'realname_', $sort);

        $this->view->title        = $this->lang->program->stakeholder;
        $this->view->pager        = $pager;
        $this->view->stakeholders = $this->program->getStakeholders($programID, $sort, $pager);
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getByID($programID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->orderBy      = $orderBy;

        $this->display();
    }

    /**
     * 创建项目集干系人。
     * Create program stakeholder.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function createStakeholder(int $programID = 0)
    {
        return print($this->fetch('stakeholder', 'create', "objectID=$programID"));
    }

    /**
     * 解除干系人跟项目集的关联关系。
     * Unlink stakeholder from the program.
     *
     * @param  int    $programID
     * @param  int    $stakeholderID
     * @access public
     * @return void
     */
    public function unlinkStakeholder(int $programID, int $stakeholderID)
    {
        $this->program->batchUnlinkStakeholders($programID, array($stakeholderID));

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 批量移除项目集下的关系人。
     * Batch unlink program stakeholders.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function batchUnlinkStakeholders(int $programID = 0)
    {
        if(!$this->post->stakeholderIdList) return $this->send(array('load' => true));

        $this->program->batchUnlinkStakeholders($programID, $this->post->stakeholderIdList);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 导出项目集。
     * Export program.
     *
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export(string $status, string $orderBy)
    {
        if($_POST)
        {
            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $this->config->program->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($this->lang->program, $fieldName);
                unset($fields[$key]);
            }

            /* Get and process program list. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $programs = $this->program->getList($status, $orderBy);
            $products = $this->program->getProductByProgram(array_keys($programs));
            foreach($programs as $programID => $program)
            {
                $program->PM      = zget($users, $program->PM);
                $program->status  = $this->processStatus('project', $program);
                $program->model   = zget($this->lang->project->modelList, $program->model);
                $program->product = empty($products[$programID]) ? '' : implode(",", helper::arrayColumn($products[$programID], 'name'));
                $program->budget  = $program->budget . zget($this->lang->project->unitList, $program->budgetUnit);

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->post->checkedItem;
                    if(strpos(",$checkedItem,", ",{$program->id},") === false) unset($programs[$programID]);
                }
            }

            if($this->config->edition != 'open') list($fields, $projectStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $projectStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $programs);
            $this->post->set('kind', 'program');
            $this->fetch('file', 'export2' . $this->post->fileType);
        }

        $this->display();
    }

    /**
     * 获取项目集下1.5级导航数据。
     * Get sub navigation data of program.
     *
     * @param  int    $programID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $programID, string $module, string $method)
    {
        $programs = $this->program->getList('all');
        foreach($programs as $programID => $program)
        {
            if($program->type != 'program') unset($programs[$programID]);
            if($module == 'program' && $method == 'product' && $program->parent != 0) unset($programs[$programID]);
        }

        $this->view->programTree = $this->programZen->buildTree($programs);
        $this->view->link        = $this->programZen->getLink($module, $method, '{id}', '', 'program');
        $this->display();
    }

    /**
     * 更新项目集排序。
     * Update program order.
     *
     * @access public
     * @return string
     */
    public function updateOrder()
    {
        $programs = $this->post->programs;
        if(!$programs) return $this->send(array('result' => 'success'));

        foreach($programs as $programID => $order) $this->program->updateOrder((int) $programID, (int) $order);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->send(array('result' => 'success'));
    }

    /*
     * 移除项目集白名单成员。
     * Removing users from the white list.
     *
     * @param  int    $aclID
     * @access public
     * @return void
     */
    public function unbindWhitelist(int $aclID)
    {
        echo $this->fetch('personnel', 'unbindWhitelist', "id={$aclID}&confirm=yes");
    }

    /**
     * 查看项目集详情。
     * View program detail.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function view(int $programID)
    {
        $program = $this->program->getByID($programID);
        if(!$program) return $this->sendError($this->lang->notFound, true);

        if(common::hasPriv('program', 'product')) $this->locate(inlink('product', "programID=$programID"));
        if(common::hasPriv('program', 'project')) $this->locate(inlink('project', "programID=$programID"));
    }

    /**
     * 设置显示非当前项目集的项目信息。
     * Set to display project information other than the current project set.
     *
     * @access public
     * @return void
     */
    public function ajaxSetShowSetting()
    {
        if($this->post->showAllProjects !== false) $this->loadModel('setting')->updateItem("{$this->app->user->account}.program.showAllProjects", $this->post->showAllProjects);
    }

    /**
     * 以产品视角查看项目集。
     * Show program list in product view.
     * copied from all() function of product module.
     *
     * @param  string  $browseType
     * @param  string  $orderBy
     * @param  int     $param
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function productView(string $browseType = 'unclosed', string $orderBy = 'program_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        /* Load module and set session. */
        $this->loadModel('product');
        $this->loadModel('user');
        $this->session->set('productList', $this->app->getURI(true), 'program');

        if($this->app->viewType == 'mhtml')
        {
            $productID = $this->product->checkAccess(0, $this->products);
            $this->product->setMenu($productID);
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Process product structure. */
        if($this->config->systemMode == 'light' and $orderBy == 'program_asc') $orderBy = 'order_asc';
        $queryID  = strtolower($browseType) == 'bysearch' ? $param : 0;
        $products = strtolower($browseType) == 'bysearch' ? $this->product->getListBySearch($queryID) : $this->product->getList();
        $products = $this->programZen->getProductsByBrowseType($browseType, $products); /* Filter the program by browse type. */

        $productStats     = $this->product->getStats(array_keys($products), $orderBy, $pager); /* The product stats list with program data and product data. */
        $productStructure = $this->product->statisticProgram($productStats);
        $productLines     = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('`order` asc')->fetchAll();
        $programLines     = array();

        foreach($productLines as $productLine)
        {
            if(!isset($programLines[$productLine->root])) $programLines[$productLine->root] = array();
            $programLines[$productLine->root][$productLine->id] = $productLine->name;
        }

        $actionURL = $this->createLink('program', 'productview', "browseType=bySearch&orderBy=order_asc&queryID=myQueryID");
        $this->config->program->search['actionURL'] = $actionURL;
        $this->loadModel('search')->setSearchParams($this->config->program->search);

        $this->view->title              = $this->lang->product->common;
        $this->view->productStats       = $productStats;
        $this->view->productStructure   = $productStructure;
        $this->view->productLines       = $productLines;
        $this->view->programLines       = $programLines;
        $this->view->users              = $this->user->getPairs('noletter');
        $this->view->orderBy            = $orderBy;
        $this->view->browseType         = $browseType;
        $this->view->pager              = $pager;
        $this->view->checkedEditProduct = !empty((int)$this->cookie->checkedEditProduct);
        $this->view->param              = $param;

        $this->render();
    }
}
