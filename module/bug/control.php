<?php
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class bug extends control
{
    private $products = array();

    /* 构造函数，加载story, release, tree等模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('task');
        $this->products = $this->product->getPairs();
        if(empty($this->products))
        {
            echo js::alert($this->lang->product->errorNoProduct);
            die(js::locate($this->createLink('product', 'create')));
        }
        $this->view->products = $this->products;
    }

    /* bug首页。*/
    public function index()
    {
        $this->locate($this->createLink('bug', 'browse'));
    }

    /* 浏览一个产品下面的bug。*/
    public function browse($productID = 0, $browseType = 'byModule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 设置产品id和模块id。*/
        $browseType = strtolower($browseType);
        $productID  = $this->product->saveState($productID, key($this->products));
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* 设置菜单，登记session。*/
        $this->bug->setMenu($this->products, $productID);
        $this->session->set('bugList', $this->app->getURI(true));

        /* 加载分页类。*/
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $bugs = array();
        if($browseType == 'all')
        {
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == "bymodule")
        {
            $childModuleIds = $this->tree->getAllChildId($moduleID);
            $bugs = $this->bug->getModuleBugs($productID, $childModuleIds, $orderBy, $pager);
        }
        elseif($browseType == 'assigntome')
        {
            $bugs = $this->dao->findByAssignedTo($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'openedbyme')
        {
            $bugs = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'resolvedbyme')
        {
            $bugs = $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'assigntonull')
        {
            $bugs = $this->dao->findByAssignedTo('')->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'longlifebugs')
        {
            $bugs = $this->dao->findByLastEditedDate("<", date(DT_DATE1, strtotime('-7 days')))->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('openedDate')->lt(date(DT_DATE1,strtotime('-7 days')))
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->ne('closed')->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'postponedbugs')
        {
            $bugs = $this->dao->findByResolution('postponed')->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'needconfirm')
        {
            $bugs = $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_BUG)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->orderBy($orderBy)
                ->fetchAll();
        }
        elseif($browseType == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('bugQuery', $query->sql);
                    $this->session->set('bugForm', $query->form);
                }
                else
                {
                    $this->session->set('bugQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->bugQuery == false) $this->session->set('bugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", '1', $this->session->bugQuery); // 如果指定了搜索所有的产品，去掉这个查询条件。
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where($bugQuery)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        /* 处理查询语句，获取条件部分，并记录session。需求待确认的，不参与报表统计。*/
        if($browseType != 'needconfirm')
        {
            $sql = explode('WHERE', $this->dao->get());
            $sql = explode('ORDER', $sql[1]);
            $this->session->set('bugReportCondition', $sql[0]);
        }

        /* 设置搜索表单。*/
        $this->config->bug->search['actionURL'] = $this->createLink('bug', 'browse', "productID=$productID&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['params']['product']['values']       = array($productID => $this->products[$productID], 'all' => $this->lang->bug->allProduct);
        $this->config->bug->search['params']['module']['values']        = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairs($productID);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->build->getProductBuildPairs($productID);
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->bug->search);

        $users = $this->user->getPairs('noletter');

        /* 设置自定义字段。*/
        $customFields = $this->cookie->bugFields != false ? $this->cookie->bugFields : $this->config->bug->list->defaultFields;
        $customed     = !($customFields == $this->config->bug->list->defaultFields);
        
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->common;

        $this->view->header      = $header;
        $this->view->position    = $position;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->moduleTree  = $this->tree->getTreeMenu($productID, $viewType = 'bug', $startModuleID = 0, array('treeModel', 'createBugLink'));
        $this->view->browseType  = $browseType;
        $this->view->bugs        = $bugs;
        $this->view->users       = $users;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->moduleID    = $moduleID;
        $this->view->customed    = $customed;
        $this->view->customFields= explode(',', str_replace(' ', '', trim($customFields)));

        $this->display();
    }

    /* 统计报表。*/
    public function report($productID, $browseType, $moduleID)
    {
        $this->loadModel('report');
        $this->view->charts   = array();
        $this->view->renderJS = '';

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->bug->$chartFunc();
                $chartOption = $this->lang->bug->report->$chart;
                $this->bug->mergeChartOption($chart);

                $chartXML  = $this->report->createSingleXML($chartData, $chartOption->graph);
                $this->view->charts[$chart] = $this->report->createJSChart($chartOption->swf, $chartXML, $chartOption->width, $chartOption->height);
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
            $this->view->renderJS = $this->report->renderJsCharts(count($this->view->charts));
        }

        $this->bug->setMenu($this->products, $productID);
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->moduleID      = $moduleID;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
        $this->display();
    }

    /* 创建Bug。extras是其他的参数，key和value之间使用=连接，多个键值对之间使用,隔开。*/
    public function create($productID, $extras = '')
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        if(!empty($_POST))
        {
            $bugID = $this->bug->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Opened');
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'browse', "productID={$this->post->product}&type=byModule&param={$this->post->module}"), 'parent'));
        }

        /* 设置当前的产品，设置菜单。*/
        $productID = $this->product->saveState($productID, key($this->products));
        $this->bug->setMenu($this->products, $productID);

        /* 去掉几个类型的设置。*/
        unset($this->lang->bug->typeList['designchange']);
        unset($this->lang->bug->typeList['newfeature']);
        unset($this->lang->bug->typeList['trackthings']);

        /* 初始化变量。*/
        $moduleID   = 0;
        $projectID  = 0;
        $taskID     = 0;
        $storyID    = 0;
        $buildID    = 0;
        $caseID     = 0;
        $runID      = 0;
        $title      = '';
        $steps      = $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect;
        $os         = '';
        $browser    = '';
        $assignedTo = '';
        $mailto     = '';
        $keywords   = '';

        /* 解析extra参数。*/
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras);

        /* 如果设置了runID，获得最后一次的resultID。*/
        if($runID > 0) $resultID = $this->dao->select('id')->from(TABLE_TESTRESULT)->where('run')->eq($runID)->orderBy('id desc')->limit(1)->fetch('id');
        if(isset($resultID) and $resultID > 0) extract($this->bug->getBugInfoFromResult($resultID));
        if(isset($bugID)) 
        {
            $bug = $this->bug->getById($bugID);
            extract((array)$bug);
            $projectID = $bug->project;
            $moduleID  = $bug->module;
            $taskID    = $bug->task;
            $storyID   = $bug->story;
            $buildID   = $bug->openedBuild;
        }

        /* 如果指定了项目，则查找项目范围内的build和story。*/
        if($projectID)
        {
            $builds  = $this->loadModel('build')->getProjectBuildPairs($projectID, $productID, 'noempty');
            $stories = $this->story->getProjectStoryPairs($projectID);
        }
        else
        {
            $builds  = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty');
            $stories = $this->story->getProductStoryPairs($productID);
        }

        /* 位置信息。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->create;

        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->stories          = $stories;
        $this->view->users            = $this->user->getPairs('noclosed,nodeleted');
        $this->view->projects         = $this->product->getProjectPairs($productID);
        $this->view->builds           = $builds;
        $this->view->tasks            = $this->loadModel('task')->getProjectTaskPairs($projectID);
        $this->view->moduleID         = $moduleID;
        $this->view->projectID        = $projectID;
        $this->view->taskID           = $taskID;
        $this->view->storyID          = $storyID;
        $this->view->buildID          = $buildID;
        $this->view->caseID           = $caseID;
        $this->view->title            = $title;
        $this->view->steps            = $steps;
        $this->view->os               = $os;
        $this->view->browser          = $browser;
        $this->view->assignedTo       = $assignedTo;
        $this->view->mailto           = $mailto;
        $this->view->keywords         = $keywords;

        $this->display();
    }

    /* 查看一个bug。*/
    public function view($bugID)
    {
        /* 查找bug信息及相关产品信息。*/
        $bug = $this->bug->getById($bugID);
        if(!$bug) die(js::error($this->lang->notFound) . js::locate('back'));

        $productID   = $bug->product;
        $productName = $this->products[$productID];
        
        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 位置信息。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->view;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName);
        $this->view->position[]    = $this->lang->bug->view;

        /* 赋值。*/
        $this->view->productID   = $productID;
        $this->view->productName = $productName;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bug         = $bug;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getProductBuildPairs($productID);

        $this->display();
    }

    /* 编辑一个Bug。*/
    public function edit($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $changes  = $this->bug->update($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('bug', $bugID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($bugID, $actionID);
            }
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 查找当前bug信息和产品模块信息。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $currentModuleID = $bug->module;

        /* 修改类型的配置。*/
        if($bug->type != 'designchange') unset($this->lang->bug->typeList['designchange']);
        if($bug->type != 'newfeature')   unset($this->lang->bug->typeList['newfeature']);
        if($bug->type != 'trackthings')  unset($this->lang->bug->typeList['trackthings']);

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 位置。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->edit;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->edit;

        /* 赋值。*/
        $this->view->bug              = $bug;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->projects         = $this->product->getProjectPairs($bug->product);
        $this->view->stories          = $bug->project ? $this->story->getProjectStoryPairs($bug->project) : $this->story->getProductStoryPairs($bug->product);
        $this->view->tasks            = $this->task->getProjectTaskPairs($bug->project);
        $this->view->users            = $this->user->appendDeleted($this->user->getPairs('nodeleted'), "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy");
        $this->view->openedBuilds     = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty');
        $this->view->resolvedBuilds   = array('' => '') + $this->view->openedBuilds;
        $this->view->actions          = $this->action->getList('bug', $bugID);
        $this->view->templates        = $this->bug->getUserBugTemplates($this->app->user->account);

        $this->display();
    }

    /* 解决bug。*/
    public function resolve($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $this->bug->resolve($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Resolved', $this->post->comment, $this->post->resolution);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 查找bug和产品信息。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 位置。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $this->view->position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->bug->resolve;

        /* 赋值。*/
        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted');
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /* 激活bug。*/
    public function activate($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $this->bug->activate($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);
            $actionID = $this->action->create('bug', $bugID, 'Activated', $this->post->comment);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 获得bug和产品信息。*/
        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 当前位置。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $this->view->position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->bug->activate;

        /* 赋值。*/
        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted');
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->actions = $this->action->getList('bug', $bugID);

        $this->display();
    }

    /* 激活bug。*/
    public function close($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $this->bug->close($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Closed', $this->post->comment);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* bug和产品信息。*/
        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->close;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->close;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs();
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /* 确认需求变动。*/
    public function confirmStoryChange($bugID)
    {
        $bug = $this->bug->getById($bugID);
        $this->dao->update(TABLE_BUG)->set('storyVersion')->eq($bug->latestStoryVersion)->where('id')->eq($bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'confirmed', '', $bug->latestStoryVersion);
        die(js::reload('parent'));
    }

    /* 删除bug。*/
    public function delete($bugID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->bug->confirmDelete, inlink('delete', "bugID=$bugID&confirm=yes")));
        }
        else
        {
            $this->bug->delete(TABLE_BUG, $bugID);
            die(js::locate($this->session->bugList, 'parent'));
        }
    }

    /* 保存模板。*/
    public function saveTemplate()
    {
        $this->bug->saveUserBugTemplate();
        if(dao::isError()) die(js::error(dao::getError()));
        die($this->fetch('bug', 'buildTemplates'));
    }

    /* 生成模板选择页面。*/
    public function buildTemplates()
    {
        $this->view->templates = $this->bug->getUserBugTemplates($this->app->user->account);
        $this->display('bug', 'buildTemplates');
    }

    /* 删除一个bug模板。*/
    public function deleteTemplate($templateID)
    {
        $this->dao->delete()->from(TABLE_USERTPL)->where('id')->eq($templateID)->andWhere('account')->eq($this->app->user->account)->exec();
        die();
    }

    /* 自定义显示字段。*/
    public function customFields()
    {
        if($_POST)
        {
            $customFields = $this->post->customFields;
            $customFields = join(',', $customFields);
            setcookie('bugFields', $customFields, $this->config->cookieLife);
            die(js::reload('parent'));
        }
        /* 设定自定义字段列表。*/
        $customFields = $this->cookie->bugFields ? $this->cookie->bugFields : $this->config->bug->list->defaultFields;

        $this->view->allFields     = $this->bug->getFieldPairs($this->config->bug->list->allFields);
        $this->view->customFields  = $this->bug->getFieldPairs($customFields);
        $this->view->defaultFields = $this->bug->getFieldPairs($this->config->bug->list->defaultFields);
        die($this->display());
    }

    /* 获得用户的bug列表。*/
    public function ajaxGetUserBugs($account = '')
    {
        if($account == '') $account = $this->app->user->account;
        $bugs = $this->bug->getUserBugPairs($account);
        die(html::select('bug', $bugs, '', 'class=select-1'));
    }

    /* 获得模块的默认指派人。*/
    public function ajaxGetModuleOwner($moduleID, $productID = 0)
    {
        if($moduleID) die($this->dao->findByID($moduleID)->from(TABLE_MODULE)->fetch('owner'));
        die($this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('bugOwner'));
    }

    /* 发送邮件。*/
    private function sendmail($bugID, $actionID)
    {
        /* 设定toList和ccList。*/
        $bug    = $this->bug->getByID($bugID);
        $toList = $bug->assignedTo;
        $ccList = trim($bug->mailto, ',');
        if($toList == '')
        {
            if($ccList == '') return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $bug->resolvedBy;
        }

        /* 获得action信息。*/
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();
        if(strtolower($action->action) == 'opened') $action->comment = $bug->steps;

        /* 赋值，获得邮件内容。*/
        $this->view->bug    = $bug;
        $this->view->action = $action;
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* 发信。*/
        $this->loadModel('mail')->send($toList, 'BUG #' . $bug->id . $this->lang->colon . $bug->title, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
}
