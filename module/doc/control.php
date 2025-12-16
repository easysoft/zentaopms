<?php
declare(strict_types=1);
/**
 * The control file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: control.php 933 2010-07-06 06:53:40Z wwccss $
 * @link        https://www.zentao.net
 */
class doc extends control
{
    public docModel $doc;

    /**
     * 构造函数，加载通用模块。
     * Construct function, load user, tree, action auto.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('user');
        $this->loadModel('tree');
        $this->loadModel('action');
        $this->loadModel('product');
        $this->loadModel('project');
        $this->loadModel('execution');
    }

    /**
     * 最近访问的空间首页。
     * Last viewed doc space home.
     *
     * @access public
     * @return void
     */
    public function lastViewedSpaceHome()
    {
        $lastViewedSpaceHome = $this->doc->getLastViewed('lastViewedSpaceHome');
        if($this->config->vision == 'lite' && $lastViewedSpaceHome == 'api') $lastViewedSpaceHome = 'mine';

        if($lastViewedSpaceHome === 'api') return $this->locate($this->createLink('api', 'index'));

        $spaceMap = array(
            'mine'    => 'mySpace',
            'product' => 'productSpace',
            'project' => 'projectSpace',
            'custom'  => 'teamSpace'
        );
        $method = $spaceMap[$lastViewedSpaceHome];
        if(empty($method)) return $this->locate($this->createLink('doc', 'mySpace'));

        return $this->locate($this->createLink('doc', $method));
    }

    /**
     * 最近访问的空间。
     * Last viewed doc space.
     *
     * @access public
     * @return void
     */
    public function lastViewedSpace()
    {
        $lastViewedSpaceHome = $this->doc->getLastViewed('lastViewedSpaceHome');
        if($this->config->vision == 'lite' && $lastViewedSpaceHome == 'api') $lastViewedSpaceHome = 'mine';

        if($lastViewedSpaceHome === 'api') return $this->locate($this->createLink('api', 'index'));

        $spaceMap = array(
            'mine'    => 'mySpace',
            'product' => 'productSpace',
            'project' => 'projectSpace',
            'custom'  => 'teamSpace'
        );
        $method = $spaceMap[$lastViewedSpaceHome];
        if(empty($method) || !common::hasPriv('doc', $method)) return $this->locate($this->createLink('doc', 'mySpace'));

        $lastViewedSpace = $this->doc->getLastViewed('lastViewedSpace');
        if(!is_numeric($lastViewedSpace))  return $this->locate($this->createLink('doc', 'mySpace'));
        return $this->locate($this->createLink('doc', $method, "objectID={$lastViewedSpace}"));
    }

    /**
     * 最近访问的库。
     * Last viewed doc lib.
     *
     * @access public
     * @return void
     */
    public function lastViewedLib()
    {
        $lastViewedSpaceHome = $this->doc->getLastViewed('lastViewedSpaceHome');
        $lastViewedLib       = $this->doc->getLastViewed('lastViewedLib');

        if($lastViewedSpaceHome === 'api')
        {
            if(is_numeric($lastViewedLib)) return $this->locate($this->createLink('api', 'index', "libID={$lastViewedLib}"));
            return $this->locate($this->createLink('api', 'index'));
        }

        $lastViewedSpace = $this->doc->getLastViewed('lastViewedSpace');
        $spaceMap = array(
            'mine'    => 'mySpace',
            'product' => 'productSpace',
            'project' => 'projectSpace',
            'custom'  => 'teamSpace'
        );
        $method = $spaceMap[$lastViewedSpaceHome];
        if(empty($method)) return $this->locate($this->createLink('doc', 'mySpace'));

        if(!is_numeric($lastViewedSpace) || !is_numeric($lastViewedLib)) return $this->locate($this->createLink('doc', 'mySpace'));
        return $this->locate($this->createLink('doc', $method, "objectID={$lastViewedSpace}&libID={$lastViewedLib}"));
    }

    /**
     * 设置上次访问的文档对象。
     * Set last viewed doc object.
     *
     * @access public
     * @return void
     */
    public function ajaxSetLastViewed()
    {
        if(empty($_POST)) return $this->send(array('result' => 'fail'));
        $this->doc->setLastViewed($_POST);
        return $this->send(array('result' => 'success'));
    }

    /**
     * 文档仪表盘。
     * Document dashboard.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->session->set('docList', $this->app->getURI(true), 'doc');
        echo $this->fetch('block', 'dashboard', 'dashboard=doc');
    }

    /**
     * 禅道数据列表。
     * Zentao data list.
     *
     * @param  string     $type
     * @param  int|string $blockID
     * @access public
     * @return void
     */
    public function zentaoList(string $type, int|string $blockID)
    {
        if(is_string($blockID)) $blockID = (int)str_replace('__TML_ZENTAOLIST__', '', $blockID);

        $blockData = $this->doc->getZentaoList($blockID);
        if(!$blockData)
        {
            if(helper::isAjaxRequest('fetch'))
            {
                echo '<div class="text-gray text-sm surface p-1">' . $this->lang->notFound . '</div>';
                return;
            }
            return $this->sendError($this->lang->notFound);
        }

        $doc = $this->doc->getByID($blockData->doc);
        $this->view->isTemplate = !empty($doc->templateType) && !in_array($blockData->extra, array('fromReview', 'fromReport'));
        $this->view->fromReport = $blockData->extra == 'fromReport';

        $noSupport = false;
        if($this->view->fromReport)
        {
            $project   = $this->loadModel('project')->fetchByID((int)$doc->project);
            $noSupport = in_array($type, array('HLDS', 'DDS', 'DBDS', 'ADS')) && in_array($project->model, array('scrum', 'agileplus', 'kanban'));
        }
        $this->view->noSupport = $noSupport;

        if($this->view->isTemplate)
        {
            $this->view->title     = sprintf($this->lang->doc->insertTitle, $this->lang->docTemplate->zentaoList[$type]);
            $this->view->type      = $type;
            $this->view->blockID   = $blockID;
            $this->view->settings  = $blockData->settings;
            $this->view->searchTab = zget($blockData->content, 'searchTab', '');

            if($type == 'productCase' || $type == 'projectCase') $this->view->caseStage = $blockData->content->caseStage;

            return $this->display();
        }

        $fromTemplate = in_array($blockData->extra, array('fromTemplate', 'fromReview', 'fromReport'));
        if($fromTemplate)
        {
            $this->view->title     = sprintf($this->lang->doc->insertTitle, $this->lang->docTemplate->zentaoList[$type]);
            $this->view->searchTab = zget($blockData->content, 'searchTab', '');
            $this->view->isSetted  = isset($blockData->content->data) && isset($blockData->content->cols) || isset($blockData->content->ganttOptions);
            if($type == 'productCase' || $type == 'projectCase') $this->view->caseStage = $blockData->content->caseStage;
        }
        else
        {
            $this->view->title = sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList[$type]);
        }

        if($type == 'gantt')
        {
            $this->view->ganttData   = (array)zget($blockData->content, 'ganttOptions', array());
            $this->view->ganttFields = (array)zget($blockData->content, 'ganttFields', array());
            $this->view->showFields  = zget($blockData->content, 'showFields', '');
        }
        else
        {
            $data = (array)zget($blockData->content, 'data', array());
            if($type == 'task')
            {
                foreach($data as $task) $task->canView = $this->execution->checkPriv($task->execution);
            }

            $this->app->loadClass('pager', true);

            $this->view->idList = (array)zget($blockData->content, 'idList', array());
            $this->view->cols   = (array)zget($blockData->content, 'cols', array());
            $this->view->data   = $data;
            $this->view->pager  = new pager(count($data), 10);
        }

        $this->view->type         = $type;
        $this->view->settings     = $blockData->settings;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|pofirst');
        $this->view->blockID      = $blockID;
        $this->view->fromTemplate = $fromTemplate;

        if(strpos(',productStory,ER,UR,planStory,projectStory', $type) !== false) $this->docZen->assignStoryGradeData($type);

        if($type == 'productRelease')
        {
            $releaseList = array();
            foreach($this->view->data as $release)
            {
                if($release->id == $release->rowID) $releaseList[$release->id] = $release;
            }
            $children      = implode(',', array_column($releaseList, 'releases'));
            $childReleases = $this->loadModel('release')->getListByCondition(explode(',', $children), 0, true);
            $this->view->data = $this->release->processReleaseListData($releaseList, $childReleases, false);
        }

        $this->display();
    }

    /**
     * 导出禅道数据列表。
     * Export Zentao data list.
     *
     * @param  int|string $blockID
     * @access public
     * @return void
     */
    public function ajaxExportZentaoList(int|string $blockID)
    {
        if(is_string($blockID)) $blockID = (int)str_replace('__TML_ZENTAOLIST__', '', $blockID);

        $blockData = $this->doc->getZentaoList($blockID);
        if(!$blockData) return $this->lang->notFound;

        if(!helper::isAjaxRequest('fetch')) return $this->locate(inlink('zentaoList', "type={$blockData->type}&blockID={$blockID}"));

        if(empty($blockData->title))
        {
            $blockType    = $blockData->type;
            $blockContent = $blockData->content;
            $templateLang = zget($this->lang, 'docTemplate', array());
            $searchTab    = zget($blockContent, 'searchTab', '');

            $blockTitle = '';
            if(!empty($templateLang))
            {
                $blockTitle = empty($templateLang->searchTabList[$blockType][$searchTab]) ? '' : $templateLang->searchTabList[$blockType][$searchTab] . $templateLang->of;
                if($blockType == 'bug' && $searchTab == 'overduebugs') $blockTitle = $templateLang->overdue . $templateLang->of;
                if(!empty($blockContent->caseStage)) $blockTitle .= $this->app->loadLang('testcase')->testcase->stageList[$blockContent->caseStage];
            }

            $blockData->title = $blockTitle . zget($this->lang->doc->zentaoList, $blockType, '') . $this->lang->doc->list;
        }
        $content = $this->docZen->exportZentaoList($blockData);
        echo $content;
    }

    /**
     * 构建禅道数据列表。
     * Build Zentao data list.
     *
     * @param  int    $docID
     * @param  string $type
     * @param  int    $oldBlockID
     * @access public
     * @return void
     */
    public function buildZentaoList(int $docID, string $type, int $oldBlockID = 0)
    {
        $docblock = new stdClass();
        $docblock->doc      = $docID;
        $docblock->type     = $type;
        $docblock->settings = $this->post->url;
        if($type == 'gantt')
        {
            $docblock->content = json_encode(array('ganttOptions' => json_decode($this->post->ganttOptions), 'showFields' => $this->post->showFields, 'ganttFields' => json_decode($this->post->ganttFields)));
        }
        else
        {
            $docblock->content = json_encode(array('cols' => json_decode($this->post->cols), 'data' => json_decode($this->post->data), 'idList' => $this->post->idList));
        }

        $this->dao->insert(TABLE_DOCBLOCK)->data($docblock)->exec();
        if(dao::isError()) return print(json_encode(array('result' => 'fail', 'message' => dao::getError())));

        $newBlockID = $this->dao->lastInsertId();

        return print(json_encode(array('result' => 'success', 'oldBlockID' => $oldBlockID, 'newBlockID' => $newBlockID)));
    }

    /**
     * 我的空间。
     * My space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType all|draft|bysearch
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function mySpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', int $param = 0, string $orderBy = 'order_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = '', int $docID = 0, string $search = '')
    {
        $this->docZen->initLibForMySpace();
        echo $this->fetch('doc', 'app', "type=mine&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search");
    }

    /**
     * 创建一个空间。
     * Create a space.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function createSpace(string $type = '')
    {
        if(!empty($_POST))
        {
            $this->lang->doc->name = $this->lang->doclib->spaceName;
            $space = form::data()->setDefault('addedBy', $this->app->user->account)->get();
            if($type) $space->type = $type;

            $spaceID = $this->doc->doInsertLib($space);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('docspace', $spaceID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('doc', 'teamSpace', "objectID={$spaceID}"), 'docApp' => array('call' => 'selectSpace', 'args' => array($spaceID, true))));
        }

        $this->display();
    }

    /**
     * 创建一个文档库。
     * Create a library.
     *
     * @param  string $type     api|project|product|execution|custom|mine
     * @param  int    $objectID
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function createLib(string $type = '', int $objectID = 0, int $libID = 0)
    {
        $this->app->loadLang('api');

        /* 项目模板下setMenu会修改common语言项，所以执行文档要先执行setAclForCreateLib。 */
        if($type != 'project') $this->docZen->setAclForCreateLib($type);
        $this->doc->setMenuByType($type, (int)$objectID, (int)$libID);
        if($type == 'project') $this->docZen->setAclForCreateLib($type);

        if(!empty($_POST))
        {
            $lib = $this->docZen->buildLibForCreateLib();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $libID = $this->doc->createLib($lib, (string)$this->post->type, (string)$this->post->libType);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($type == 'custom' || $type == 'mine')
            {
                $lib      = $this->doc->getLibByID($libID);
                $objectID = $lib->parent;
            }

            return $this->docZen->responseAfterCreateLib($type, $objectID, $libID, $lib->name, $lib->orderBy);
        }

        $objects = array();
        if($type == 'project')
        {
            $this->view->project = $this->project->getById($objectID);
            if($this->app->tab == 'doc') $this->view->executionPairs = $this->execution->getPairs($objectID, 'all', 'multiple,leaf,noprefix');
        }

        if($type == 'execution')
        {
            $objects   = $this->execution->getPairs(0, 'all', 'multiple,leaf,noprefix,withobject');
            $execution = $this->execution->getByID($objectID);
            if($execution->type == 'stage') $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);
            $this->view->execution = $this->doc->fetchByID($objectID, 'execution');
        }

        if($type == 'custom' || $type == 'mine' || $type == 'doctemplate')
        {
            $lib = $this->doc->getLibByID($libID);
            $this->view->spaces  = $this->doc->getSubSpacesByType($type);
            $this->view->spaceID = !empty($lib->parent) ? $lib->parent : $objectID;
        }

        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->users    = $this->user->getPairs('nocode|noclosed');
        $this->view->objects  = $objects;
        $this->view->type     = $type;
        $this->view->objectID = $objectID;
        $this->display();
    }

    /**
     * 编辑一个文档空间。
     * Edit a doc space.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function editSpace(int $spaceID)
    {
        $this->commonEditAction($spaceID);
        $this->display();
    }

    /**
     * 编辑一个文档库。
     * Edit a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editLib(int $libID)
    {
        $this->commonEditAction($libID);
        $this->display();
    }

    /**
     * 编辑空间和编辑库的公用方法。
     * Edit a lib or space.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function commonEditAction($libID)
    {
        if(!common::hasPriv('doc', 'editSpace') && !common::hasPriv('doc', 'editLib')) return;
        $lib = $this->doc->getLibByID($libID);

        /* 项目模板下setMenu会修改common语言项，所以执行文档要先执行setAclForCreateLib。 */
        if($lib->type != 'project') $this->docZen->setAclForEditLib($lib);
        $this->doc->setMenuByType($lib->type, (int)zget($lib, $lib->type, 0), (int)$libID);
        if($lib->type == 'project') $this->docZen->setAclForEditLib($lib);

        if(!empty($_POST))
        {
            $this->lang->doc->name = $this->lang->nameAB;
            $libData = form::data($this->config->doc->form->editlib)->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->doc->updateLib($libID, $libData);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $object = ($lib->type == 'custom' && $lib->parent == 0) ? 'docspace' : 'doclib';
                $actionID = $this->action->create($object, $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $lib = $this->doc->getLibByID($libID);
            $docAppActions = array();
            $docAppActions[] = array('update', ($lib->parent == 0) ? 'space' : 'lib', $lib);
            $docAppActions[] = array('home:loadHomeLibs');
            $docAppActions[] = array('list:load');
            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'closeModal' => true, 'docApp' => $docAppActions));
        }

        if(!empty($lib->product)) $this->view->object = $this->product->getByID($lib->product);
        if(!empty($lib->project) && empty($lib->execution)) $this->view->object = $this->project->getByID($lib->project);
        if(!empty($lib->execution))
        {
            $execution = $this->execution->getByID($lib->execution);
            if($execution->type == 'stage')
            {
                if($execution->grade > 1)
                {
                    $parentExecutions = $this->execution->getPairsByList(explode(',', trim($execution->path, ',')), 'stage,kanban,sprint');
                    $execution->name  = implode('/', $parentExecutions);
                }

                $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);
            }

            $this->view->object = $execution;
        }

        $this->view->lib    = $lib;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->user->getPairs('noletter|noclosed', $lib->users);
        $this->view->libID  = $libID;
    }

    /**
     * 删除一个文档空间。
     * Delete a doc space.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function deleteSpace(int $libID)
    {
        $this->commonDeleteAction($libID);
    }

    /**
     * 删除一个文档库。
     * Delete a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function deleteLib(int $libID)
    {
        $this->commonDeleteAction($libID);
    }

    /**
     * 删除空间和删除库的公用方法。
     * Delete a lib or space.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function commonDeleteAction(int $libID)
    {
        if(in_array($libID, array('product', 'execution'))) return;

        $lib = $this->doc->getLibByID($libID);
        if(!empty($lib->main)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorMainSysLib, 'load' => array('alert' => $this->lang->doc->errorMainSysLib)));

        $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq('1')->where('id')->eq($libID)->exec();

        $object = ($lib->type == 'custom' && $lib->parent == 0) ? 'docspace' : 'doclib';
        $this->loadModel('action')->create($object, $libID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);

        $moduleName = 'doc';
        $objectType = $lib->type;
        $methodName = zget($this->config->doc->spaceMethod, $objectType);
        if($this->app->tab == 'doc') return $this->send(array('result' => 'success', 'load' => $this->createLink($moduleName, $methodName), 'app' => $this->app->tab));

        $objectID   = strpos(',product,project,execution,', ",$objectType,") !== false ? $lib->{$objectType} : 0;
        if($this->app->tab == 'execution' && $objectType == 'execution')
        {
            $moduleName = 'execution';
            $methodName = 'doc';
        }
        $browseLink = $this->createLink($moduleName, $methodName, "objectID=$objectID");

        return $this->send(array('result' => 'success', 'load' => $browseLink, 'app' => $this->app->tab));
    }

    /**
     * 文档模板列表。
     * Browse template list.
     *
     * @param  int    $libID
     * @param  string $type
     * @param  mixed  $docID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseTemplate(int $libID = 0, string $type = 'all', mixed $docID = 0, string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1, string $mode = 'home')
    {
        /* 添加内置的范围、分类、文档模板。*/
        /* Add the built-in scopes and type and doc template. */
        $builtInScopes = $this->dao->select('id')->from(TABLE_DOCLIB)->where('type')->eq('template')->andWhere('main')->eq('1')->fetchAll();
        if(empty($builtInScopes))
        {
            $this->doc->addBuiltInScopes();
            $this->doc->addBuiltInDocTemplateType();
            $this->doc->addBuiltInDocTemplateByType();
        }

        $this->lang->doc->menu->template['alias'] .= ',' . $this->app->rawMethod;
        $libModules = $this->doc->getTemplateModules($libID);
        if($mode == 'create' && empty($libModules)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->docTemplate->createTypeFirst)));

        $templateList = $this->doc->getDocTemplateList(0, $type, $orderBy);
        $templateList = $this->doc->filterDeletedDocs($templateList);
        $templateList = $this->doc->filterPrivDocs($templateList, 'template');
        $allModules   = $this->doc->getTemplateModules();
        $allModules   = array_column($allModules, 'fullName', 'id');
        foreach($templateList as $template) $template->moduleName = zget($allModules, $template->module);

        $docVersion = 0;
        if(is_string($docID) && strpos($docID, '_') !== false) list($docID, $docVersion) = explode('_', $docID);

        $this->view->title        = $this->lang->doc->template;
        $this->view->libID        = $libID;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->view->templateList = $templateList;
        $this->view->docID        = $docID;
        $this->view->docVersion   = $docVersion;
        $this->view->orderBy      = $orderBy;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->mode         = $mode;
        $this->view->hasModules   = count($libModules) ? true : false;
        $this->view->scopes       = $this->doc->getTemplateScopes();
        $this->display();
    }

    /**
     * 创建一个模板类型。
     * Add a template type.
     *
     * @param  int        $scope
     * @param  int|string $parentModule
     * @access public
     * @return void
     */
    public function addTemplateType(int $scope, int|string $parentModule = '')
    {
        $moduleList = $this->doc->getTemplateModules($scope, '1');

        if(!empty($_POST))
        {
            $this->lang->doc->name = $this->lang->docTemplate->typeName;
            $moduleData = form::data($this->config->doc->form->addTemplateType)
                ->setDefault('type', 'docTemplate')
                ->setDefault('path', '')
                ->get();
            $moduleData->grade = $moduleData->parent === 0 ? 1 : 2;
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->doc->addTemplateType($moduleData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->docZen->responseAfterAddTemplateType($scope);
        }

        $moduleItems = array();
        if($parentModule === 0)
        {
            $moduleItems[] = array('value' => 0, 'text' => '/');
        }
        else
        {
            foreach($moduleList as $module) $moduleItems[] = array('value' => $module->id, 'text' => '/' . $module->name);
            $moduleItems[] = array('value' => 0, 'text' => '/');
        }

        $scopeList = $this->doc->getTemplateScopes();

        $this->view->scope        = $scope;
        $this->view->scopes       = $this->doc->getScopeItems($scopeList);
        $this->view->moduleItems  = $moduleItems;
        $this->view->parentModule = $parentModule === '' ? current($moduleItems) : $parentModule;
        $this->display();
    }

    /**
     * 删除一个模板类型。
     * Delete a template type.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function deleteTemplateType(int $moduleID)
    {
        $templates = $this->doc->getTemplatesByType($moduleID);
        if(empty($templates))
        {
            $this->dao->update(TABLE_MODULE)->set('deleted')->eq('1')->where('id')->eq($moduleID)->andWhere('type')->eq('docTemplate')->exec();
            $this->dao->update(TABLE_MODULE)->set('deleted')->eq('1')->where('parent')->eq($moduleID)->andWhere('type')->eq('docTemplate')->exec();
            return $this->sendSuccess(array('load' => true));
        }
        else
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->docTemplate->errorDeleteType));
        }
    }

    /**
     * 编辑一个模板类型。
     * Edit a template type.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function editTemplateType(int $moduleID)
    {
        echo $this->fetch('tree', 'edit', "moduleID={$moduleID}&type=docTemplate");
    }

    /**
     * 创建一个文档模板。
     * Create a doc template.
     *
     * @param  int $moduleID
     * @access public
     * @return void
     */
    public function createTemplate(int $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $this->lang->doc->title = $_POST['type'] == 'chapter' ? $this->lang->doc->chapterName : $this->lang->docTemplate->title;

            helper::setcookie('lastDocModule', $moduleID);
            $docData = form::data()
                ->setDefault('addedBy', $this->app->user->account)
                ->setDefault('editedBy', $this->app->user->account)
                ->get();
            if(empty($docData->module)) return $this->sendError(sprintf($this->lang->error->notempty, $this->lang->docTemplate->module));

            if(!empty($docData->parent))
            {
                $parentTemplate = $this->doc->fetchByID((int)$docData->parent);
                $docData->module       = $parentTemplate->module;
                $docData->lib          = (int)$parentTemplate->lib;
                $docData->acl          = $parentTemplate->acl;
                $docData->templateType = $parentTemplate->templateType;
            }

            $docResult = $this->doc->create($docData);
            if(!$docResult || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->docZen->responseAfterCreate($docResult, 'docTemplate');
        }
    }

    /**
     * 编辑一个文档。
     * Edit a doc.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function editTemplate(int $docID)
    {
        $doc = $this->doc->getByID($docID);
        if(!empty($_POST))
        {
            $this->lang->doc->module = $this->lang->docTemplate->module;
            $changes = $files = array();
            $docData = form::data()
                ->setDefault('editedBy', $this->app->user->account)
                ->setIF(!isset($_POST['parent']), 'parent', $doc->parent)
                ->setIF(strpos(",$doc->editedList,", ",{$this->app->user->account},") === false, 'editedList', $doc->editedList . ",{$this->app->user->account}")
                ->setIF($this->post->type == 'chapter', 'content', $doc->content)
                ->setIF($this->post->type == 'chapter', 'rawContent', $doc->rawContent)
                ->remove('fromVersion')
                ->get();

            $result = $this->doc->update($docID, $docData);
            if(dao::isError())
            {
                if(!empty(dao::$errors['lib']) || !empty(dao::$errors['keywords'])) return $this->send(array('result' => 'fail', 'message' => dao::getError(), 'callback' => "zui.Modal.open({id: 'modalBasicInfo'});"));
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $changes = $result['changes'];
            $files   = $result['files'];
            return $this->docZen->responseAfterEditTemplate($doc, $changes, $files);
        }
    }

    /**
     * 上传文档。
     * Upload docs.
     *
     * @param  int        $docID
     * @param  string     $objectType product|project|execution|custom
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType    html|word|ppt|excel|attachment
     * @access public
     * @return void
     */
    public function uploadDocs(int $docID, string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = '')
    {
        if(!empty($_POST))
        {
            /* parent带‘m_’前缀为目录。*/
            if(isset($_POST['parent']) && strpos($_POST['parent'], 'm_') !== false)
            {
                $_POST['module'] = str_replace('m_', '', $_POST['parent']);
                $_POST['parent'] = 0;
            }

            $libID = (int)$this->post->lib;

            if(!$libID) return $this->send(array('result' => 'fail', 'message' => array('lib' => sprintf($this->lang->error->notempty, $this->lang->doc->lib))));

            $doclib   = $this->loadModel('doc')->getLibByID($libID);
            $canVisit = $this->docZen->checkPrivForCreate($doclib, $objectType);
            if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));

            $moduleID = $this->post->module;
            helper::setcookie('lastDocModule', $moduleID);

            if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
            if($_POST['type'] == 'attachment' && empty($_FILES['files']['name'][0]))
            {
                $this->config->doc->form->create['title']['required'] = false;
                $this->config->doc->form->create['title']['skipRequired'] = true;
            }

            $this->config->doc->create->requiredFields = trim(str_replace(array(',content,', ',keywords,'), ",", ",{$this->config->doc->create->requiredFields},"), ',');

            $docData = form::data(!empty($docID) ? $this->config->doc->form->edit : $this->config->doc->form->create)->remove('fromVersion')->get();
            if(empty($docID))  $docData->addedBy  = $this->app->user->account;
            if(!empty($docID)) $docData->editedBy = $this->app->user->account;

            if(!empty($docID))
            {
                $docResult   = $this->doc->update($docID, $docData);
                $docData->id = $docID;
            }
            elseif($this->post->uploadFormat == 'combinedDocs')
            {
                $docResult = $this->doc->create($docData);
            }
            else
            {
                $docResult = $this->doc->createSeperateDocs($docData);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return !empty($docID) ? $this->send($this->docZen->responseAfterEdit($docData, $docResult['changes'], $docResult['files'], $docResult['deletedFiles'])) : $this->docZen->responseAfterUploadDocs($docResult);
        }

        if($objectType == 'execution' && $libID) // 此时传入的objectID是projectID，用lib的信息更改回executionID
        {
            $lib = $this->doc->getLibByID($libID);
            $objectID = $this->doc->getObjectIDByLib($lib);
        }

        $this->docZen->assignVarsForUploadDocs($docID, $objectType, $objectID, $libID, $moduleID, $docType);
        $this->display();
    }

    /**
     * 创建一个文档。
     * Create a doc.
     *
     * @param  string     $objectType product|project|execution|custom
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType    html|word|ppt|excel
     * @param  int        $appendLib
     * @access public
     * @return void
     */
    public function create(string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = '', int $appendLib = 0)
    {
        if(!empty($_POST))
        {
            /* parent带‘m_’前缀为目录。*/
            if(isset($_POST['parent']) && strpos($_POST['parent'], 'm_') !== false)
            {
                $_POST['module'] = str_replace('m_', '', $_POST['parent']);
                $_POST['parent'] = 0;
            }

            $libID  = (int)$this->post->lib;
            $docLib = $this->loadModel('doc')->getLibByID($libID);
            if($docLib)
            {
                $canVisit = $this->docZen->checkPrivForCreate($docLib, $objectType);
                if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));
            }

            $moduleID = $this->post->module;
            helper::setcookie('lastDocModule', $moduleID);

            if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);

            if($_POST['type'] == 'chapter') $this->lang->doc->title = $this->lang->doc->chapterName;

            $docData = form::data()
                ->setDefault('addedBy', $this->app->user->account)
                ->setDefault('editedBy', $this->app->user->account)
                ->get();

            if($docData->parent && !$docData->module)
            {
                $parentDoc = $this->doc->getByID($docData->parent);
                $docData->module = $parentDoc->module;
            }

            $docResult = $this->doc->create($docData);
            if(!$docResult || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->docZen->responseAfterCreate($docResult);
        }

        $this->docZen->assignVarsForCreate($objectType, $objectID, $libID, $moduleID, $docType);

        $lib = $this->view->lib;
        if(empty($lib)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorEmptySpaceLib));

        $objectID = $this->view->objectID;
        $libData  = $this->doc->setMenuByType($lib->type, (int)$objectID, (int)$lib->id, (int)$appendLib);
        if(is_string($libData)) return $this->locate($libData);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $libData;
        if($this->config->edition != 'open') $this->loadModel('file');
        if($lib->type == 'custom' || $lib->type == 'mine') $this->view->spaces = $this->docZen->getAllSpaces($lib->type == 'mine' ? 'onlymine' : 'nomine');

        $this->view->title            = zget($lib, 'name', '', $lib->name . $this->lang->hyphen) . $this->lang->doc->create;
        $this->view->object           = $object;
        $this->view->objectDropdown   = $objectDropdown;
        $this->view->libTree          = $this->doc->getLibTree((int)$libID, $libs, $objectType, (int)$moduleID, (int)$objectID);
        $this->view->linkParams       = "objectID={$objectID}&%s&browseType=&orderBy=status,id_desc&param=0";

        if(!empty($objectType) && $objectType !== 'custom')
        {
            $objectIDVar = $objectType . 'ID';
            $this->view->$objectIDVar = $objectID;
        }
        $this->display();
    }

    /**
     * 编辑一个文档。
     * Edit a doc.
     *
     * @param  int    $docID
     * @param  bool   $comment
     * @param  int    $appendLib
     * @access public
     * @return void
     */
    public function edit(int $docID, bool $comment = false, int $appendLib = 0)
    {
        $doc = $this->doc->getByID($docID);
        if(!empty($_POST))
        {
            /* parent带‘m_’前缀为目录。*/
            if(isset($_POST['parent']) && strpos($_POST['parent'], 'm_') !== false)
            {
                $_POST['module'] = str_replace('m_', '', $_POST['parent']);
                $_POST['parent'] = 0;
            }

            if(!$doc) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorNotFound));

            $isOpen          = $doc->acl == 'open';
            $currentAccount  = $this->app->user->account;
            $isAuthorOrAdmin = $doc->acl == 'private' && ($doc->addedBy == $currentAccount || $this->app->user->admin);
            $isInEditUsers   = strpos(",$doc->users,", ",$currentAccount,") !== false;
            $isInEditGroups  = false;
            if(!empty($doc->groups))
            {
                foreach($this->app->user->groups as $groupID)
                {
                    if(strpos(",$doc->groups,", ",$groupID,") !== false) $isInEditGroups = true;
                }
            }

            if(!$isOpen && !$isAuthorOrAdmin && !$isInEditUsers && !$isInEditGroups) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->needEditable));

            if($doc->type == 'chapter') $this->lang->doc->title = $this->lang->doc->chapterName;

            $changes = $files = array();
            if($comment == false)
            {
                $docData = form::data()
                    ->setDefault('editedBy', $this->app->user->account)
                    ->setDefault('acl', $doc->acl)
                    ->setIF(strpos(",$doc->editedList,", ",{$this->app->user->account},") === false, 'editedList', $doc->editedList . ",{$this->app->user->account}")
                    ->setIF(!isset($_POST['module']), 'module', $doc->module)
                    ->setIF(!isset($_POST['mailto']), 'mailto', $doc->mailto)
                    ->setIF(!isset($_POST['users']), 'users', $doc->users)
                    ->setIF(!isset($_POST['groups']), 'groups', $doc->groups)
                    ->setIF(!isset($_POST['readUsers']), 'readUsers', $doc->readUsers)
                    ->setIF(!isset($_POST['readGroups']), 'readGroups', $doc->readGroups)
                    ->setIF(!isset($_POST['fromVersion']), 'fromVersion', $doc->fromVersion)
                    ->removeIF($this->post->project === false, 'project')
                    ->removeIF($this->post->product === false, 'product')
                    ->removeIF($this->post->execution === false, 'execution')
                    ->get();
                $result = $this->doc->update($docID, $docData);
                if(dao::isError())
                {
                    if(!empty(dao::$errors['lib']) || !empty(dao::$errors['keywords'])) return $this->send(array('result' => 'fail', 'message' => dao::getError(), 'callback' => "zui.Modal.open({id: 'modalBasicInfo'});"));
                    return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }

                $changes = $result['changes'];
                $files   = $result['files'];
            }

            return $this->send($this->docZen->responseAfterEdit($doc, $changes, $files));
        }

        /* Get doc and set menu. */
        $moduleID   = (int)$doc->module;
        $libID      = (int)$doc->lib;
        $lib        = $this->doc->getLibByID($libID);
        $objectType = isset($lib->type) ? $lib->type : 'custom';
        $objectID   = zget($lib, $lib->type, 0);
        if($lib->type == 'custom') $objectID = $lib->parent;

        if($doc->type == 'text' || $doc->type == 'article')
        {
            echo $this->fetch('doc', 'app', "type=$objectType&spaceID=$objectID&libID=$libID&moduleID=$doc->module&docID=$docID&mode=edit");
            return;
        }

        $libPairs = $this->doc->getLibs($lib->type, '', $doc->lib, $objectID);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($objectType, $objectID, (int)$doc->lib, (int)$appendLib);

        if($lib->type == 'custom' || $lib->type == 'mine') $this->view->spaces = $this->docZen->getAllSpaces($lib->type == 'mine' ? 'onlymine' : 'nomine');
        $this->docZen->setObjectsForEdit($lib->type, $objectID);

        $this->view->title          = $lib->name . $this->lang->hyphen . $this->lang->doc->edit;
        $this->view->doc            = $doc;
        $this->view->optionMenu     = $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
        $this->view->type           = $lib->type;
        $this->view->libs           = $libPairs;
        $this->view->lib            = $lib;
        $this->view->libID          = $libID;
        $this->view->libTree        = $this->doc->getLibTree($libID, $libs, $objectType, $moduleID, (int)$objectID, '', 0, $docID);
        $this->view->groups         = $this->loadModel('group')->getPairs();
        $this->view->users          = $this->user->getPairs('noletter|noclosed|nodeleted', $doc->users);
        $this->view->files          = $this->loadModel('file')->getByObject('doc', $docID);
        $this->view->objectType     = $objectType;
        $this->view->object         = $object;
        $this->view->objectID       = $objectID;
        $this->view->objectDropdown = $objectDropdown;
        $this->view->moduleID       = $moduleID;
        $this->view->spaceType      = $objectType;
        $this->view->productID      = $doc->product;
        $this->view->projectID      = $doc->project;
        $this->view->executionID    = $doc->execution;
        $this->view->linkParams     = "objectID={$objectID}&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->otherEditing   = $this->doc->checkOtherEditing($docID);
        $this->display();
    }

    /**
     * 删除一个文档。
     * Delete a doc.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function delete(int $docID)
    {
        $this->doc->delete(TABLE_DOC, $docID);

        /* Delete doc files. */
        $doc = $this->doc->getByID($docID);
        if($doc->files) $this->doc->deleteFiles(array_keys($doc->files));

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->sendSuccess(array('load' => $this->session->docList ? $this->session->docList : $this->createLink('doc', 'index')));
    }

    /**
     * 删除一个文档模板。
     * Delete a doctemplate.
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function deleteTemplate(int $templateID)
    {
        $this->doc->deleteTemplate($templateID);

        /* Delete template files. */
        $template = $this->doc->getByID($templateID);
        if($template->files) $this->doc->deleteFiles(array_keys($template->files));

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $link = $this->createLink('doc', 'browseTemplate');
        return $this->sendSuccess(array('load' => $link));
    }

    /**
     * 收藏一个文档。
     * Collect a doc.
     *
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function collect(int $objectID)
    {
        $action = $this->doc->getActionByObject($objectID, 'collect');
        if($action)
        {
            $this->doc->deleteAction($action->id);
            $this->action->create('doc', $objectID, 'uncollected');
        }
        else
        {
            $this->doc->createAction($objectID, 'collect');
            $this->action->create('doc', $objectID, 'collected');
        }

        if($this->viewType == 'json') $this->send(array('status' => $action ? 'no' : 'yes'));
        return $this->send(array('result' => 'success', 'message' => $action ? $this->lang->doc->cancelCollection : $this->lang->doc->collectSuccess, 'load' => true, 'status' => $action ? 'no' : 'yes'));
    }

    /**
     *
     * AJAX: 获取对象的目录。
     * Ajax get modules by object.
     *
     * @param  string $objectType project|product|custom|mine
     * @param  int    $objectID
     * @param  string $docType    doc|api
     * @param  int    $docID
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function ajaxGetModules(string $objectType, int $objectID, string $docType = 'doc', int $docID = 0, int $libID = 0)
    {
        if($objectType != 'mine' && empty($objectID)) return print(json_encode(array()));

        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($objectType, $unclosed, '', (int)$objectID);
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs(0, $objectType, $objectID);
            $libPairs = array();
            foreach($libs as $lib) $libPairs[$lib->id] = $lib->name;
        }
        $libItems = array();
        foreach($libPairs as $id => $name) $libItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $moduleItems = array();
        $libID       = $libID ? $libID : key($libPairs);
        if($libID)
        {
            $chapterAndDocs = $this->doc->getDocsOfLibs(array($libID), $objectType, $docID);
            $modulePairs    = empty($libID) ? array() : $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
            $nestedDocs     = $this->doc->buildNestedDocs($chapterAndDocs, $modulePairs);
            $moduleItems    = array_merge(array(array('text' => '/', 'value' => 'm_0')), array_values($nestedDocs));
        }

        return print(json_encode(array('libs' => $libItems, 'modules' => $moduleItems)));
    }

    /**
     *
     * AJAX: 获取范围下的类型。
     * Ajax get types of scope.
     *
     * @param  int    $libID
     * @access public
     * @return json
     */
    public function ajaxGetScopeTypes(int $libID = 0)
    {
        $optionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'docTemplate', 0, 'all', 'nodeleted', 'all');
        $types       = array();
        foreach($optionMenu as $menuID => $menu)
        {
            if(empty($menuID)) continue;
            $types[] = array('text' => $menu, 'value' => $menuID, 'keys' => $menu);
        }
        return print(json_encode($types));
    }

    /**
     * AJAX: 通过空间类型获取文档库。
     * AJAX: Get libs by type.
     *
     * @param  string $type    mine|product|project|nolink|custom
     * @param  string $docType doc|api
     * @access public
     * @return void
     */
    public function ajaxGetLibsByType(string $type, string $docType = 'doc', string $extra = '')
    {
        $libPairs = array();
        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            if($extra) $unclosed .= ",$extra";
            $libPairs = $this->doc->getLibs($type, $unclosed);
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs(0, 'nolink');
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }

        $libItems = array();
        foreach($libPairs as $id => $name) $libItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $moduleItems = array();
        $libID       = key($libPairs);
        if($libID)
        {
            $optionMenu  = $this->loadModel('tree')->getOptionMenu($libID, $docType, 0);
            foreach($optionMenu as $id => $name) $moduleItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode(array('libs' => $libItems, 'modules' => $moduleItems)));
    }

    /**
     * Ajax: 获取白名单。
     * Ajax: Get whitelist.
     *
     * @param  int    $doclibID
     * @param  string $acl     open|custom|private
     * @param  string $control user|group
     * @param  int    $docID
     * @access public
     * @return string
     */
    public function ajaxGetWhitelist(int $doclibID, string $acl = '', string $control = '', int $docID = 0)
    {
        $doclib = $this->doc->getLibByID($doclibID);
        $users  = $this->user->getPairs('noletter|noempty|noclosed');
        if($control == 'group')
        {
            if($doclib->acl == 'private') return print('private');

            $groupItems = array();
            $groups     = $this->loadModel('group')->getPairs();
            foreach($groups as $groupID => $groupName) $groupItems[] = array('text' => $groupName, 'value' => $groupID, 'keys' => $groupName);
            return print(json_encode($groupItems));
        }

        if($doclib->acl == 'private') return print('private');

        $userItems = array();
        foreach($users as $account => $realname)
        {
            if($doclib->acl == 'private' && strpos($doclib->users, (string)$account) === false ) unset($users[$account]);
            $userItems[] = array('text' => $realname, 'value' => $account, 'keys' => $realname);
        }
        return print(json_encode($userItems));
    }

    /**
     * 附件库。
     * Show files.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $viewType
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $searchTitle
     * @access public
     * @return void
     */
    public function showFiles(string $type, int $objectID, string $viewType = '', string $browseType = '', int $param = 0,  string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $searchTitle = '')
    {
        $this->loadModel('file');
        if(empty($viewType)) $viewType = !empty($_COOKIE['docFilesViewType']) ? $this->cookie->docFilesViewType : 'list';
        helper::setcookie('docFilesViewType', $viewType, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $objects = $this->doc->getOrderedObjects($type, 'nomerge', $objectID);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($type, $objectID, 0);

        $object = $this->doc->getObjectByID($type, $objectID);
        if(empty($_POST) && !empty($searchTitle)) $this->post->title = $searchTitle;

        $this->session->set('storyList', $this->app->getURI(true), 'doc');
        $this->session->set('bugList', $this->app->getURI(true), 'doc');

        /* Load pager. */
        $rawMethod = $this->app->rawMethod;
        $this->app->rawMethod = 'showFiles';
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $this->app->rawMethod = $rawMethod;

        $files       = $this->doc->getLibFiles($type, $objectID, $browseType, (int)$param, $orderBy, $pager);
        $fileIcon    = $this->doc->getFileIcon($files);
        $sourcePairs = $this->doc->getFileSourcePairs($files);

        if($this->app->tab != 'doc')
        {
            $objectVar = $this->app->tab . 'ID';
            $this->view->{$objectVar} = $objectID;
        }

        $this->docZen->buildSearchFormForShowFiles($type, $objectID, $viewType, $param);

        $this->view->title          = $object->name;
        $this->view->type           = $type;
        $this->view->object         = $object;
        $this->view->files          = $this->docZen->processFiles($files, $fileIcon, $sourcePairs);
        $this->view->fileIcon       = $fileIcon;
        $this->view->sourcePairs    = $sourcePairs;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager          = $pager;
        $this->view->viewType       = $viewType;
        $this->view->orderBy        = $orderBy;
        $this->view->objectID       = $objectID;
        $this->view->canBeChanged   = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->summary        = $this->doc->summary($files);
        $this->view->libTree        = $this->doc->getLibTree(0, $libs, $type, 0, $objectID);
        $this->view->objectDropdown = $objectDropdown;
        $this->view->searchTitle    = $searchTitle;
        $this->view->linkParams     = "objectID=$objectID&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->spaceType      = $type;
        $this->view->objectType     = $type;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->libID          = 0;
        $this->view->moduleID       = 0;

        $this->display();
    }

    /**
     * 文档详情页面。
     * Document details page.
     *
     * @param  string|int   $docID
     * @param  int          $version
     * @access public
     * @return void
     */
    public function view(string|int $docID = 0, int $version = 0)
    {
        $isApi = is_string($docID) && strpos($docID, 'api.') === 0;
        $docID = $isApi ? (int)str_replace('api.', '', $docID) : (int)$docID;
        $doc   = $isApi ? $this->loadModel('api')->getByID($docID, $version) : $this->doc->getByID($docID, $version, true);
        $docParam = $version ? ($docID . '_' . $version) : $docID;
        if($isApi) $docParam = 'api.' . $docParam;
        if(!$doc || !isset($doc->id))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->sendError($this->lang->notFound, $this->inlink('index'));
        }

        $_SESSION["doc_{$doc->id}_nopriv"] = '';
        if(!$isApi && !$this->doc->checkPrivDoc($doc))
        {
            $user         = $this->loadModel('user')->getByID($doc->addedBy);
            $realname     = $user->deleted ? $user->account : $user->realname;
            $errorMessage = empty($_SESSION["doc_{$doc->id}_nopriv"]) ? sprintf($this->lang->doc->cannotView, $realname) : $_SESSION["doc_{$doc->id}_nopriv"];
            unset($_SESSION["doc_{$doc->id}_nopriv"]);
            return $this->sendError($errorMessage, inlink('index'));
        }
        unset($_SESSION["doc_{$doc->id}_nopriv"]);

        if($doc->templateType)
        {
            echo $this->fetch('doc', 'browseTemplate', "libID=$doc->lib&type=all&docID=$docParam&orderBy=id_desc&recPerPage=20&pageID=1&mode=view");
            return;
        }

        $lib        = $this->doc->getLibByID((int)$doc->lib);
        $objectType = isset($lib->type) ? $lib->type : 'custom';
        if($objectType == 'api')
        {
            if(isset($lib->product) && $lib->product) list($objectType, $objectID) = array('product', $lib->product);
            if(isset($lib->project) && $lib->project) list($objectType, $objectID) = array('project', $lib->project);
        }
        else
        {
            $objectID = $this->doc->getObjectIDByLib($lib, $objectType);
        }

        /* Get doc. */
        if($docID) $this->doc->createAction($docID, 'view');

        $this->view->title = $doc->title;
        $libID = isset($lib->id) ? $lib->id : 0;
        echo $this->fetch('doc', 'app', "type=$objectType&spaceID=$objectID&libID=$libID&moduleID=$doc->module&docID=$docParam&mode=view");
    }

    /**
     * 产品/项目/执行/团队空间。
     * ProductSpace/ProjectSpace/ExecutionSpace/TeamSpace.
     *
     * @param  string $type        custom|product|project|execution|doctemplate
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType  all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function tableContents(string $type = 'custom', int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->docZen->setSpacePageStorage($type, $browseType, $objectID, $libID, $moduleID, $param);

        if($moduleID) $libID = $this->tree->getById($moduleID)->root;
        $isFirstLoad = $libID == 0 ? true : false;
        if(empty($browseType)) $browseType = 'all';

        $libData = $this->doc->setMenuByType($type, $objectID, $libID);
        if(is_string($libData)) return $this->locate($libData);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $libData;

        $libID   = (int)$libID;
        $lib     = $this->doc->getLibByID($libID);
        $libType = isset($lib->type) && $lib->type == 'api' ? 'api' : 'lib';

        /* Build the search form. */
        $queryID = $browseType == 'bySearch' ? $param : 0;
        $queryID = $queryID == 'myQueryID' ? 0 : $queryID;
        $params  = "objectID={$objectID}&libID={$libID}&moduleID=0&browseType=bySearch&orderBy={$orderBy}&param=myQueryID";
        if($this->app->rawMethod == 'tablecontents') $params = "type={$type}&" . $params;
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, $params);
        if($libType == 'api') $this->loadModel('api')->buildSearchForm($lib, $queryID, $actionURL, $libs, $type);
        if($libType != 'api') $this->doc->buildSearchForm($libID, $libs, $queryID, $actionURL, $type);

        $this->docZen->assignApiVarForSpace($type, $browseType, $libType, $libID, $libs, $objectID, $moduleID, $queryID, $orderBy, $param, $recTotal, $recPerPage, $pageID);

        /* For product drop menu. */
        if(in_array($type, array('product', 'project', 'execution')))
        {
            $objectKey = $type . 'ID';
            $this->view->$objectKey = $objectID;
        }

        $isOrderByOrder = $orderBy == 'order_asc';
        $isNotCustomLib = $lib && !($lib->type == 'custom' && $lib->parent == 0);
        $canUpdateOrder = $isOrderByOrder && $isNotCustomLib && common::hasPriv('doc', 'sortDoc');

        $this->view->title          = $type == 'custom' ? $this->lang->doc->tableContents : $object->name . $this->lang->hyphen . $this->lang->doc->tableContents;
        $this->view->type           = $type;
        $this->view->objectType     = $type;
        $this->view->spaceType      = $type;
        $this->view->browseType     = $browseType;
        $this->view->isFirstLoad    = $isFirstLoad;
        $this->view->param          = $queryID;
        $this->view->users          = $this->user->getPairs('noletter');
        $this->view->libTree        = $this->doc->getLibTree($libID, $libs, $type, $moduleID, $objectID, $browseType, (int)$param);
        $this->view->libID          = $libID;
        $this->view->moduleID       = $moduleID;
        $this->view->objectDropdown = $objectDropdown;
        $this->view->lib            = $lib;
        $this->view->libType        = $libType;
        $this->view->objectID       = $objectID;
        $this->view->orderBy        = $orderBy;
        $this->view->release        = $browseType == 'byrelease' ? $param : 0;
        $this->view->exportMethod   = $libType == 'api' ? 'export' : $type . '2export';
        $this->view->linkParams     = "objectID={$objectID}&%s&browseType=&orderBy={$orderBy}&param=0";
        $this->view->canUpdateOrder = $canUpdateOrder;

        $this->display();
    }

    /**
     * 产品空间。
     * Product space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType   all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function productSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = '', int $docID = 0, string $search = '')
    {
        $noSpace = $this->app->tab != 'doc';
        $mode    = empty($mode) && $noSpace ? 'list' : $mode;
        echo $this->fetch('doc', 'app', "type=product&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&noSpace=$noSpace");
    }

    /**
     * 项目空间。
     * Project space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType  all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function projectSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = '', int $docID = 0, string $search = '')
    {
        $noSpace = $this->app->tab != 'doc';
        $mode    = empty($mode) && $noSpace ? 'list' : $mode;
        echo $this->fetch('doc', 'app', "type=project&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&noSpace=$noSpace");
    }

    /**
     * 团队空间。
     * Team space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType    all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function teamSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = '', int $docID = 0, string $search = '')
    {
        $this->docZen->initLibForTeamSpace();
        echo $this->fetch('doc', 'app', "type=custom&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search");
    }

    /**
     * 设置选择文档库类型的范围。
     * Set the scope of the document library type to be selected.
     *
     * @param  string $objectType
     * @param  string $params
     * @param  string $from
     * @access public
     * @return void
     */
    public function selectLibType($objectType = 'mine', $params = '', $from = '')
    {
        if($_POST)
        {
            $response = array();

            $libID     = $_POST['lib'];
            $moduleID  = $_POST['module'];
            $rootSpace = $_POST['rootSpace'];
            $docType   = isset($_POST['type']) ? $_POST['type'] : 'doc';
            if(empty($libID)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->doc->lib)));

            $spaceID = 0;
            if(in_array($rootSpace, array('mine', 'custom', 'product', 'project')))
            {
                $methodList = array('mine' => 'mySpace', 'custom' => 'teamSpace', 'product' => 'productSpace', 'project' => 'projectSpace');
                $spaceID    = $_POST[$rootSpace];
                $method     = $methodList[$rootSpace];

                if($docType == 'doc') $url = $this->createLink('doc', $method, "objectID=$spaceID&libID=$libID&moduleID=$moduleID&browseType=all&params=0&orderBy=&recTotal=0&recPerPage=20&pageID=1&mode=create");
                else $url = helper::createLink('api', 'create', "libID=$libID&moduleID=$moduleID&space=$rootSpace");
            }
            elseif($rootSpace == 'api')
            {
                $url = helper::createLink('api', 'create', "libID=$libID&moduleID=$moduleID&space=$rootSpace");
            }

            $response['result']     = 'success';
            $response['closeModal'] = true;
            $response['callback']   = "redirectParentWindow(\"{$url}\", \"{$from}\", \"{$spaceID}\", \"{$libID}\", \"{$moduleID}\")";
            return $this->send($response);
        }

        $spaceList = $this->lang->doc->spaceList;
        $typeList  = $this->lang->doc->types;
        if(!common::hasPriv('doc', 'create'))       unset($spaceList['mine'], $spaceList['custom'], $typeList['doc']);
        if(!common::hasPriv('doc', 'mySpace'))      unset($spaceList['mine']);
        if(!common::hasPriv('doc', 'productSpace')) unset($spaceList['product']);
        if(!common::hasPriv('doc', 'projectSpace')) unset($spaceList['project']);
        if(!common::hasPriv('doc', 'teamSpace'))    unset($spaceList['custom']);
        if(!common::hasPriv('api', 'index'))        unset($spaceList['api']);
        if(!common::hasPriv('api', 'create'))       unset($spaceList['api'], $typeList['api']);
        if($this->config->vision == 'lite')         unset($spaceList['api'], $spaceList['product'], $typeList['api']);

        $params = helper::safe64Decode($params);
        parse_str($params, $params);
        $this->view->params    = $params;

        $this->view->objectType = $objectType;
        $this->view->spaceList = $spaceList;
        $this->view->typeList  = $typeList;

        $this->display();
    }

    /**
     * Ajax: 根据对象类型设置下拉菜单。
     * Ajax: Set the drop-down menu according to the object type.
     *
     * @param  string $objectType product|project
     * @param  int    $objectID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(string $objectType, int $objectID, string $module, string $method)
    {
        list($myObjects, $normalObjects, $closedObjects) = $this->doc->getOrderedObjects($objectType, 'nomerge', $objectID);

        $libData = $this->doc->setMenuByType($objectType, $objectID, 0);

        $this->view->objectType    = $objectType;
        $this->view->objectID      = $objectID;
        $this->view->libID         = !empty($libData[1]) ? $libData[1] : 0;
        $this->view->module        = $module;
        $this->view->method        = $method == 'view' ? $objectType.'space' : $method;
        $this->view->normalObjects = $myObjects + $normalObjects;
        $this->view->closedObjects = $closedObjects;
        $this->view->objectsPinYin = common::convert2Pinyin($myObjects + $normalObjects + $closedObjects);

        $this->display();
    }

    /**
     * Ajax: 获取空间的下拉数据。
     * Ajax: Get the space drop down.
     *
     * @param  int    $libID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetSpaceMenu(int $libID, string $module, string $method, string $extra = 'nomine')
    {
        $this->view->libID  = $libID;
        $this->view->module = $module;
        $this->view->method = $method;
        $this->view->extra  = $extra;
        $this->view->spaces = $this->docZen->getAllSpaces($extra);

        $this->display();
    }

    /**
     * Ajax: 获取执行下拉数据。
     * Ajax: Get the execution drop down by the projectID.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function ajaxGetExecution(int $projectID)
    {
        $json  = array();
        $items = array();

        $executionPairs = $this->execution->getPairs($projectID, 'all', 'multiple,leaf,noprefix');
        foreach($executionPairs as $id => $name) $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $json['items']   = $items;
        $json['project'] = $this->project->getByID($projectID);
        return print(json_encode($json));
    }

    /**
     * 编辑一个目录。
     * Edit a catalog.
     *
     * @param  int    $moduleID
     * @param  string $type     doc|api
     * @access public
     * @return void
     */
    public function editCatalog(int $moduleID, string $type)
    {
        echo $this->fetch('tree', 'edit', "moduleID={$moduleID}&type={$type}");
    }

    /**
     * 删除一个目录。
     * Delete a catalog.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function deleteCatalog(int $moduleID)
    {
        echo $this->fetch('tree', 'delete', "moduleID={$moduleID}&confirm=yes");
    }

    /**
     * 设置左侧树中是否显示文档。
     * Set documents are show or hidden in the left tree.
     *
     * @access public
     * @return void
     */
    public function displaySetting()
    {
        $this->loadModel('setting');
        if($_POST)
        {
            $this->setting->setItem($this->app->user->account . '.doc.showDoc', $this->post->showDoc);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $showDoc = $this->setting->getItem('owner=' . $this->app->user->account . '&module=doc&key=showDoc');
        $this->view->showDoc = $showDoc === '0' ? '0' : '1';

        $this->display();
    }

    /**
     * 文档库排序
     * Doclib sorting.
     *
     * @access public
     * @return void
     */
    public function sortDoclib()
    {
        if($_POST)
        {
            $orders = $_POST['orders'];
            if(is_string($orders)) $orders = json_decode($orders, true);

            foreach($orders as $id => $order) $this->doc->updateDoclibOrder($id, (int)$order);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'orders' => $orders));
        }
    }

    /**
     * 文档排序
     * Doc sorting.
     *
     * @access public
     * @return void
     */
    public function sortDoc()
    {
        if($_POST)
        {
            $orders = json_decode($this->post->orders, true);
            asort($orders);
            $orders = array_flip($orders);

            /* Sort by sorted id list. */
            $this->doc->updateDocOrder($orders);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * 目录排序
     * Catalog sorting.
     *
     * @access public
     * @return void
     */
    public function sortCatalog()
    {
        if($_POST)
        {
            $orders = $_POST['orders'];
            $type   = isset($_POST['type']) ? $_POST['type'] : 'doc';
            if(is_string($orders)) $orders = json_decode($orders, true);
            foreach($orders as $id => $order) $this->doc->updateOrder($id, (int)$order, $type);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * 删除文档附件。
     * Delete file for doc.
     *
     * @param  int    $docID
     * @param  int    $fileID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteFile(int $docID, int $fileID, string $confirm = 'no')
    {
        $this->loadModel('file');
        if($confirm == 'no')
        {
            $formUrl = inlink('deleteFile', "docID={$docID}&fileID={$fileID}&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->file->confirmDelete}').then((res) => {if(res) $.ajaxSubmit({url: '$formUrl'});});"));
        }
        else
        {
            $this->doc->updateDocFile($docID, $fileID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $file = $this->file->getById($fileID);
            $this->action->create($file->objectType, $file->objectID, 'deletedFile', '', $file->title);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }
    }

    /**
     * 移动文档库
     * Move Lib.
     *
     * @param  int    $libID
     * @param  string $targetSpace [int]
     * @access public
     * @return void
     */
    public function moveLib(int $libID, string $targetSpace = '')
    {
        $lib = $this->doc->getLibByID($libID);
        if(empty($targetSpace)) $targetSpace = $this->doc->getLibTargetSpace($lib);
        $spaceType = $this->doc->getParamFromTargetSpace($targetSpace, 'type');

        if(!empty($lib->main)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorEditSystemDoc));

        if(!empty($_POST))
        {
            $data = form::data()
                ->setIF($this->post->acl == 'open', 'groups', '')
                ->setIF($this->post->acl == 'open', 'users', '')
                ->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->doc->moveLib($libID, $data);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $spaceTypeChanged = $spaceType != $lib->type;
            return $this->docZen->responseAfterMove($this->post->space, $libID, 0, $spaceTypeChanged);
        }

        $this->docZen->setAclForCreateLib($spaceType);

        $this->view->title        = $this->lang->doc->moveLibAction;
        $this->view->spaces       = $this->doc->getAllSubSpaces('all');
        $this->view->lib          = $lib;
        $this->view->targetSpace  = $targetSpace;
        $this->view->libType      = $spaceType;
        $this->view->hasOthersDoc = $this->doc->hasOthersDoc($lib);
        $this->view->groups       = $this->loadModel('group')->getPairs();
        $this->view->users        = $this->loadModel('user')->getPairs('nocode|noclosed');
        $this->display();
    }

    /**
     * 移动文档
     * Move Document.
     *
     * @param  int    $docID
     * @param  int    $libID
     * @param  string $spaceType
     * @param  string $space
     * @access public
     * @return void
     */
    public function moveDoc(int $docID, int $libID = 0, string $spaceType = '', string $space = '')
    {
        if($spaceType == 'quick')
        {
            $libID = 0;
            $space = $spaceType = '';
        }

        $doc = $this->doc->getByID($docID);
        if(empty($libID)) $libID = (int)$doc->lib;
        $lib = $this->doc->getLibByID($libID);

        if(empty($space))
        {
            if($lib->parent)
            {
                $space = $lib->parent;
            }
            else
            {
                if($lib->product) $space = $lib->product;
                if($lib->project) $space = $lib->project;
            }
        }

        if(empty($spaceType))
        {
            if($lib->parent)
            {
                $spaceType = $this->doc->getSpaceType($space);
            }
            else
            {
                if($lib->product && $lib->type == 'product') $spaceType = 'product';
                if($lib->project && $lib->type == 'project') $spaceType = 'project';
            }
        }

        if(!empty($_POST))
        {
            /* parent带‘m_’前缀为目录。*/
            if(isset($_POST['parent']) && strpos($_POST['parent'], 'm_') !== false)
            {
                $_POST['module'] = str_replace('m_', '', $_POST['parent']);
                $_POST['parent'] = 0;
            }

            $data = form::data()
                ->setIF($this->post->acl == 'open', 'groups', '')
                ->setIF($this->post->acl == 'open', 'users', '')
                ->setIF($this->post->acl == 'open', 'readGroups', '')
                ->setIF($this->post->acl == 'open', 'readUsers', '')
                ->setIF(in_array($spaceType, array('project', 'product')), $spaceType, $space)
                ->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = common::createChanges($doc, $data);
            if($changes)
            {
                $this->doc->doUpdateDoc($docID, $data);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $actionID = $this->loadModel('action')->create('doc', $docID, 'Moved', '', json_encode(array('from' => $doc->lib, 'to' => $data->lib)));
                $this->action->logHistory($actionID, $changes);
            }

            $spaceTypeChanged = $spaceType != $lib->type;
            return $this->docZen->responseAfterMove($this->post->space, $data->lib, $docID, $spaceTypeChanged);
        }

        $projects   = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc');
        $products   = $this->loadModel('product')->getPairs();
        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'multiple,leaf');

        $libPairs = $this->doc->getLibPairs($spaceType, 'withObject', (int)$space, '', $products, $projects, $executions);
        if($spaceType == 'project') $libPairs += $this->doc->getExecutionLibPairsByProject((int)$space, 'withObject', $executions);

        if(!isset($libPairs[$libID])) $libID = (int)key($libPairs);

        $chapterAndDocs = $this->doc->getDocsOfLibs(array($libID), $spaceType, $docID);
        $modulePairs    = empty($libID) ? array() : $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
        if(isset($doc) && !empty($doc->parent) && !isset($chapterAndDocs[$doc->parent])) $chapterAndDocs[$doc->parent] = $this->doc->fetchByID($doc->parent);
        $chapterAndDocs = $this->doc->buildNestedDocs($chapterAndDocs, $modulePairs);

        $this->view->docID      = $docID;
        $this->view->libID      = $libID;
        $this->view->spaceType  = $spaceType;
        $this->view->space      = $space;
        $this->view->doc        = $doc;
        $this->view->spaces     = $this->doc->getAllSubSpaces();
        $this->view->libPairs   = $libPairs;
        $this->view->optionMenu = $chapterAndDocs;
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->users      = $this->loadModel('user')->getPairs('nocode|noclosed');
        $this->display();
    }

    /**
     * 移动文档模板
     * Move document template.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function moveTemplate(int $docID)
    {
        $doc = $this->doc->getByID($docID);
        if(!empty($_POST))
        {
            $this->lang->doc->module = $this->lang->docTemplate->module;
            $data = form::data()
                ->setIF(!isset($_POST['lib']), 'lib', $doc->lib)
                ->setIF(!isset($_POST['module']), 'module', $doc->module)
                ->setIF(!isset($_POST['parent']), 'parent', $doc->parent)
                ->setIF(!isset($_POST['acl']), 'acl', $doc->acl)
                ->get();
            $changes = common::createChanges($doc, $data);
            if($changes)
            {
                $basicInfoChanged = false;
                foreach($changes as $change)
                {
                    if(in_array($change['field'], array('module', 'lib', 'acl'))) $basicInfoChanged = true;
                }
                $this->doc->doUpdateDoc($docID, $data, $basicInfoChanged);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $actionID = $this->loadModel('action')->create('docTemplate', $docID, 'Moved', '', json_encode(array('from' => $doc->lib, 'to' => $data->lib)));
                $this->action->logHistory($actionID, $changes);
            }

            $newDoc = $this->doc->getByID($docID);
            if(!$this->doc->checkPrivDoc($newDoc))
            {
                $link = $this->createLink('doc', 'browseTemplate', "libID={$doc->lib}&type=all&docID=0&orderBy=id_desc&recPerPage=20&pageID=1&mode=list");
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link, 'doc' => $newDoc));
            }

            $docAppAction = array('executeCommand', 'handleMovedDoc', array($docID, '1', $data->lib));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'docApp' => $docAppAction));
        }

        if(!empty($doc->parent))
        {
            $parentDoc      = $this->doc->fetchByID($doc->parent);
            $chapterAndDocs = $this->doc->getDocsOfLibs(array($doc->lib), 'template', $docID, true);
            if(!isset($chapterAndDocs[$doc->parent])) $chapterAndDocs[$doc->parent] = $parentDoc;

            $parentPath  = trim($parentDoc->path, ',') . ",";
            $topTemplate = substr($parentPath, 0, strpos($parentPath, ',')) ?: $doc->parent;
            foreach($chapterAndDocs as $key => $template)
            {
                if(strpos(",{$template->path},", ",{$topTemplate},") === false) unset($chapterAndDocs[$key]);
            }
            $this->view->chapterAndDocs = $this->doc->buildNestedDocs($chapterAndDocs);
        }

        $scopeList = $this->doc->getTemplateScopes();

        $this->view->scopeItems = $this->doc->getScopeItems($scopeList);
        $this->view->doc        = $doc;
        $this->view->docID      = $docID;
        $this->view->modules    = $this->loadModel('tree')->getOptionMenu((int)$doc->lib, 'docTemplate', 0, 'all', 'nodeleted', 'all');
        $this->display();
    }

    /**
     * Batch move document.
     *
     * @param  string $type
     * @param  string $encodeDocIdList
     * @param  int    $spaceID
     * @param  int    $libID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchMoveDoc(string $type, string $encodeDocIdList, int $spaceID, int $libID = 0, int $moduleID = 0)
    {
        $docIdList = json_decode(base64_decode($encodeDocIdList), true);
        if(!$docIdList) $this->locate($this->createLink('doc', 'app', "type={$type}&spaceID={$spaceID}&libID={$libID}&moduleID={$moduleID}"));

        $spaces = $this->doc->getAllSubSpaces();
        if($type == 'quick')
        {
            list($type, $spaceID) = explode('.', key($spaces));
            $spaceID = (int) $spaceID;
        }

        if($_POST)
        {
            $oldDocList = $this->doc->getDocsByIdList($docIdList);

            $data = form::data()
                ->setIF($type == 'mine' || $this->post->acl == 'open', 'groups', '')
                ->setIF($type == 'mine' || $this->post->acl == 'open', 'users', '')
                ->setIF(in_array($type, array('project', 'product')), $type, $spaceID)
                ->get();
            $this->doc->batchMoveDoc($data, $docIdList);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->docZen->recordBatchMoveActions($oldDocList, $data);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'docApp' => array('load', null, null, null, array('noLoading' => true, 'picks' => 'doc'))));
        }

        $projects   = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc');
        $products   = $this->loadModel('product')->getPairs();
        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'multiple,leaf');

        $libPairs = $this->doc->getLibPairs($type, 'withObject', $spaceID, '', $products, $projects, $executions);
        if($type == 'project') $libPairs += $this->doc->getExecutionLibPairsByProject($spaceID, 'withObject', $executions);

        if(!isset($libPairs[$libID])) $libID = (int)key($libPairs);

        $docList = $this->doc->getByIdList($docIdList);
        $docAclList = array_column($docList, 'acl');

        $this->view->title           = $this->lang->doc->batchMove;
        $this->view->encodeDocIdList = $encodeDocIdList;
        $this->view->type            = $type;
        $this->view->spaceID         = $spaceID;
        $this->view->libID           = $libID;
        $this->view->moduleID        = $moduleID;
        $this->view->spaces          = $spaces;
        $this->view->libPairs        = $libPairs;
        $this->view->optionMenu      = $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('nocode|noclosed');
        $this->view->hasOpenDoc      = in_array('open', $docAclList);
        $this->display();
    }

    /**
     * Ajax: 检查对象权限。
     * Ajax check object priv.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxCheckObjectPriv(string $objectType, int $objectID)
    {
        $accounts = $this->post->users;
        $object   = $this->doc->getObjectByID($objectType, $objectID);
        if(empty($object)) return print('');
        if($object->acl == 'open') return print('');

        $this->loadModel('user');
        $userViews = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->in($accounts)->fetchAll('account');
        $userPairs = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs('account', 'realname');
        $denyUsers = array();
        foreach($accounts as $account)
        {
            if(strpos(",{$this->app->company->admins}", ",{$account},") !== false) continue;

            $userView = zget($userViews, $account, '');
            if(empty($userView)) $userView = $this->user->computeUserView($account, true);

            if($objectType == 'product'   && isset($userView->products) && strpos(",{$userView->products},", ",{$objectID},") === false) $denyUsers[$account] = zget($userPairs, $account);
            if($objectType == 'project'   && isset($userView->projects) && strpos(",{$userView->projects},", ",{$objectID},") === false) $denyUsers[$account] = zget($userPairs, $account);
            if($objectType == 'execution' && isset($userView->sprints)  && strpos(",{$userView->sprints},",  ",{$objectID},") === false) $denyUsers[$account] = zget($userPairs, $account);
        }

        if(empty($denyUsers)) return print('');
        if(isset($this->lang->doc->whitelistDeny[$objectType])) return printf($this->lang->doc->whitelistDeny[$objectType], implode('、', $denyUsers));
    }

    /**
     * Ajax: 检查库权限。
     * Ajax check lib priv.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function ajaxCheckLibPriv(int $libID)
    {
        $accounts = $this->post->users;
        $lib      = $this->doc->getLibByID($libID);
        if(empty($lib)) return print('');
        if($lib->acl == 'open') return print('');
        if($lib->acl == 'default' && strpos("product,project,execution", $lib->type) !== false) return $this->ajaxCheckObjectPriv($lib->type, zget($lib, $lib->type, 0));

        $authAccounts[$lib->addedBy] = $lib->addedBy;
        if($lib->groups) $authAccounts += $this->loadModel('group')->getGroupAccounts(explode(',', $lib->groups));
        if($lib->users)
        {
            foreach(array_filter(explode(',', $lib->users)) as $account) $authAccounts[$account] = $account;
        }

        $userPairs = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs('account', 'realname');
        $denyUsers = array();
        foreach($accounts as $account)
        {
            if(strpos(",{$this->app->company->admins}", ",{$account},") !== false) continue;
            if(!isset($authAccounts[$account])) $denyUsers[$account] = zget($userPairs, $account);
        }

        if(empty($denyUsers)) return print('');
        if(isset($this->lang->doc->whitelistDeny['doc'])) return printf($this->lang->doc->whitelistDeny['doc'], implode('、', $denyUsers));
    }

    /**
     * Doc app view.
     * 文档应用视图。
     *
     * @param  string $type
     * @param  int    $spaceID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  mixed  $docID
     * @param  string $mode
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $filterType
     * @param  string $search
     * @param  string $param
     * @access public
     * @access public
     * @return void
     */
    public function app(string $type = 'mine', int $spaceID = 0, int $libID = 0, int $moduleID = 0, mixed $docID = 0, string $mode = '', string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $filterType = '', string $search = '', bool $noSpace = false)
    {
        $isNotDocTab = $this->app->tab != 'doc';
        if(empty($mode))
        {
            $showList = $noSpace || !empty($spaceID);
            $mode = ($isNotDocTab || $type == 'execution' || $showList) ? 'list' : 'home';
        }

        $this->app->loadLang('file');

        /* For product drop menu. */
        if(!$isNotDocTab && $type == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($spaceID);
            $type = 'project';
            $spaceID = $execution->project;
        }
        if($isNotDocTab && in_array($type, array('product', 'project', 'execution')))
        {
            $this->doc->setMenuByType($type, $spaceID, $libID);
            $objectKey = $type . 'ID';
            $this->view->$objectKey = $spaceID;
            $this->view->$type = $this->doc->fetchByID($spaceID, $type);
        }

        if($type == 'mine') $menuType = 'my';
        else $menuType = $type == 'custom' ? 'team' : $type;
        if(isset($this->lang->doc->menu->{$menuType})) $this->lang->doc->menu->{$menuType}['alias'] .= ',' . $this->app->rawMethod;

        $docVersion = 0;
        $isApi      = is_string($docID) && strpos($docID, 'api.') === 0;
        if($isApi) $docID = str_replace('api.', '', $docID);
        if(is_string($docID) && strpos($docID, '_') !== false) list($docID, $docVersion) = explode('_', $docID);

        $this->view->type           = $type;
        $this->view->spaceID        = $spaceID;
        $this->view->libID          = $libID;
        $this->view->moduleID       = $moduleID;
        $this->view->docID          = $isApi ? "api.$docID" : (int)$docID;
        $this->view->docVersion     = (int)$docVersion;
        $this->view->mode           = $mode;
        $this->view->filterType     = $filterType;
        $this->view->search         = $search;
        $this->view->recTotal       = $recTotal;
        $this->view->recPerPage     = $recPerPage;
        $this->view->pageID         = $pageID;
        $this->view->orderBy        = $orderBy;
        $this->view->objectType     = $type;
        $this->view->noSpace        = $noSpace;
        $this->view->objectID       = $spaceID;
        $this->view->users          = $this->dao->select('account,realname,avatar')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');
        $this->view->title          = isset($this->lang->doc->spaceList[$type]) ? $this->lang->doc->spaceList[$type] : $this->lang->doc->common;
        $this->display();
    }

    /**
     * Doc quick access page.
     * 快捷访问页面。
     *
     * @param  string $type        'view'|'collect'|'createdby'|'editedby'
     * @param  int    $docID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function quick(string $type = 'view', int $docID = 0, string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        if(empty($orderBy)) $orderBy = 'id_desc';
        if(!isset($this->config->doc->quickMenu[$type])) $type = 'view';
        $menu = $this->config->doc->quickMenu[$type];

        $currentUser = $this->app->user->account;
        $docs        = $this->doc->getMineList($type, 'all', 0, $orderBy);
        foreach($docs as $doc)
        {
            unset($doc->draft);
            $doc->originLIb   = $doc->lib;
            $doc->lib         = $menu['id'];
            $doc->isCollector = strpos($doc->collector, ',' . $currentUser . ',') !== false;
        }

        $this->view->docs       = $docs;
        $this->view->menu       = $menu;
        $this->view->type       = $type;
        $this->view->docID      = $docID;
        $this->view->orderBy    = $orderBy;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->users      = $this->dao->select('account,realname,avatar')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');
        $this->view->title      = $this->lang->doc->quick;
        $this->display();
    }

    /**
     * Ajax: Get doc space data.
     * Ajax: 获取文档空间数据。
     *
     * @param  string $type
     * @param  int    $space
     * @access public
     * @return void
     */
    public function ajaxGetSpaceData(string $type = 'custom', int $spaceID = 0, string $picks = '', int $libID = 0)
    {
        $this->doc->setMenuByType($type, (int)$spaceID, 0);

        if($type === 'template')
        {
            $modules      = $this->doc->getTemplateModules();
            $moduleNames  = array_column($modules, 'fullName', 'id');
            $templateList = $this->doc->getDocTemplateList(0, 'all');
            $templateList = $this->doc->filterDeletedDocs($templateList);
            $templateList = $this->doc->filterPrivDocs($templateList, 'template');
            foreach($templateList as $template)
            {
                $template->moduleName = zget($moduleNames, $template->module);
            }
            $data = array('spaceID' => $spaceID, 'docs' => array_values($templateList));
            $data['spaces'][] = array('name' => $this->lang->doc->template, 'id' => $spaceID);
            $data['modules']  = $modules;

            $scopeList = $this->doc->getTemplateScopes();
            foreach($scopeList as $scope) $data['libs'][] = array('id' => $scope->id, 'name' => $scope->name, 'space' => $spaceID);
        }
        else
        {
            $noPicks = empty($picks);
            $picks   = $noPicks ? '' : ",$picks,";

            list($spaces, $spaceID) = $this->doc->getSpaces($type, $spaceID);
            $data   = array('spaceID' => (int)$spaceID);
            $libs   = $this->doc->getLibsOfSpace($type, $spaceID);
            $libIds = array_keys($libs);
            foreach($libs as $lib) $lib->order = (int)$lib->order;

            if($noPicks || strpos($picks, ',space,') !== false)  $data['spaces']  = $spaces;
            if($noPicks || strpos($picks, ',lib,') !== false)    $data['libs']    = array_values($libs);
            if($noPicks || strpos($picks, ',module,') !== false) $data['modules'] = array_values($this->doc->getModulesOfLibs($libIds));
            if($noPicks || strpos($picks, ',doc,') !== false)    $data['docs']    = array_values($this->doc->getDocsOfLibs($libIds + array($spaceID), $type));
        }

        $this->send($data);
    }

    /**
     * Ajax: Get doc data.
     * Ajax: 获取文档数据。
     *
     * @param  string|int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function ajaxGetDoc(string|int $docID, int $version = 0, $details = 'no')
    {
        if(is_string($docID) && strpos($docID, 'api.') === 0)
        {
            $apiID = (int)str_replace('api.', '', $docID);
            echo $this->fetch('api', 'ajaxGetApi', "apiID=$apiID&version=$version");
            return;
        }

        $docID = (int)$docID;
        $doc = $this->doc->getByID($docID, $version);
        $doc->lib     = (int)$doc->lib;
        $doc->module  = (int)$doc->module;
        $doc->privs   = array('edit' => common::hasPriv('doc', 'edit', $doc) && $doc->acl == 'open');
        $doc->editors = $this->doc->getEditors($docID);
        $doc->draft   = $doc->status == 'draft' ? $this->doc->getContent($docID, 0) : null;

        $lib        = $this->doc->getLibByID((int)$doc->lib);
        $objectType = $lib->type;
        if(empty($objectType))
        {
            if($lib->execution)   $objectType = 'execution';
            elseif($lib->project) $objectType = 'project';
            elseif($lib->product) $objectType = 'product';
        }

        $this->doc->setDocPriv($doc, $objectType);

        if($details == 'yes')
        {
            $objectID   = $this->doc->getObjectIDByLib($lib, $objectType);
            $object     = in_array($objectType, array('product', 'project', 'execution')) ? $this->doc->getObjectByID($objectType, $objectID) : $this->doc->getLibByID((int)$objectID);

            $doc->libInfo    = $lib;
            $doc->objectType = $objectType;
            $doc->object     = $object;
        }

        if(!empty($doc->rawContent))
        {
            $doc->html    = $doc->content;
            $doc->content = $doc->rawContent;
            unset($doc->rawContent);

            if($doc->contentType == 'doc' && preg_match('/ src="{([0-9]+)(\.(\w+))?}" /', $doc->html))
            {
                $doc->contentType = 'html';
                $doc->content     = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' src="' . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $doc->html);
            }
        }
        if($docID) $this->doc->createAction($docID, 'view');

        $this->send($doc);
    }

    /**
     * Get files by type and objectID.
     * 根据类型和对象ID获取文件列表。
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function ajaxGetFiles(string $type, int $objectID, string $browseType = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 9999, int $pageID = 1)
    {
        if(common::hasPriv('doc', 'showFiles'))
        {
            /* Load pager. */
            $rawMethod = $this->app->rawMethod;
            $this->app->rawMethod = 'showFiles';
            $this->app->loadClass('pager', true);
            $pager = new pager($recTotal, $recPerPage, $pageID);
            $this->app->rawMethod = $rawMethod;

            $files       = $this->doc->getLibFiles($type, $objectID, $browseType, 0, $orderBy, $pager);
            $sourcePairs = $this->doc->getFileSourcePairs($files);
            $files       = $this->docZen->processFiles($files, array(), $sourcePairs, true);

            echo json_encode(array_values($files)); // $this->send($files); not work.
        }
        else
        {
            echo '[]';
        }
    }

    /**
     * Get lib summaries by space type and space list.
     *
     * @param  string $spaceType
     * @param  string $spaceList
     * @access public
     * @return void
     */
    public function ajaxGetLibSummaries(string $spaceType, string $spaceList)
    {
        $libsMap = $this->doc->getLibsOfSpaces($spaceType, $spaceList, 0);
        echo json_encode($libsMap);
    }

    /**
     * Ajax modal for setting doc basic info.
     * 设置文档基本信息的模态框。
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $docID
     * @param  string $isDraft
     * @access public
     */
    public function setDocBasic(string $objectType, int $objectID, int $libID = 0, int $moduleID = 0, int $parentID = 0, int $docID = 0, string $isDraft = 'no', string $modalType = 'doc')
    {
        $this->doc->setMenuByType($objectType, (int)$objectID, (int)$libID);
        $lib      = $libID ? $this->doc->getLibByID($libID) : '';
        $isCreate = empty($docID);

        if($docID) $doc = $this->doc->getByID($docID);

        $title = $this->lang->settings;
        if($modalType == 'doc')
        {
            $title = $parentID ? $this->lang->doc->addSubDoc : $this->lang->doc->create;
            if($objectType == 'template') $title = $parentID ? $this->lang->docTemplate->addSubDocTemplate : $this->lang->docTemplate->create;
            if($isDraft == 'no') $title = $this->lang->settings;
        }
        if($modalType == 'chapter') $title = $isCreate ? $this->lang->doc->addChapter : $this->lang->doc->editChapter;

        if($isCreate)
        {
            if(!$moduleID && $docID)
            {
                $parentDoc = $this->doc->getByID($parentID);
                $moduleID = $parentDoc->module;
            }

            if(empty($objectID) && $lib) $objectID = $this->doc->getObjectIDByLib($lib);
            if($lib && $objectType != 'template') $objectType = $lib->type;

            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($objectType, "{$unclosed}", $libID, $objectID);
            $moduleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
            if(!$libID && !empty($libPairs)) $libID = key($libPairs);
            if(empty($lib) && $libID) $lib = $this->doc->getLibByID($libID);

            if($objectType == 'custom' || $objectType == 'mine') $this->view->spaces = $this->doc->getSubSpacesByType($objectType, true);
            $this->docZen->setObjectsForCreate($objectType, empty($lib) ? null : $lib, $unclosed, $objectID);
            $this->view->optionMenu = empty($libID) ? array() : $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
        }
        else
        {
            $parentID   = (int)$doc->parent;
            $moduleID   = (int)$doc->module;
            $libID      = (int)$doc->lib;
            $lib        = $this->doc->getLibByID($libID);
            if($lib && $objectType != 'template') $objectType = $lib->type;
            $objectID   = $this->doc->getObjectIDByLib($lib);

            $libPairs = $this->doc->getLibs($objectType, '', $libID, $objectID);
            if($objectType == 'custom' || $objectType == 'mine') $this->view->spaces = $this->doc->getSubSpacesByType($objectType, true);
            $this->docZen->setObjectsForEdit($objectType, $objectID);

            $this->view->doc        = $doc;
            $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
        }

        $chapterAndDocs = $this->doc->getDocsOfLibs(array($libID), $objectType, $docID, $objectType == 'template' ? true : false);
        if($objectType == 'template' && !empty($moduleID)) $chapterAndDocs = array_filter($chapterAndDocs, fn($docInfo) => $docInfo->module == $moduleID);
        if(isset($doc) && !empty($doc->parent) && !isset($chapterAndDocs[$doc->parent])) $chapterAndDocs[$doc->parent] = $this->doc->fetchByID($doc->parent);
        $modulePairs = empty($libID) || $modalType == 'chapter' || $objectType == 'template' ? array() : $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);

        if($objectType == 'template' && !empty($parentID))
        {
            $parentDoc   = $this->doc->fetchByID($parentID);
            $parentPath  = trim($parentDoc->path, ',') . ",";
            $topTemplate = substr($parentPath, 0, strpos($parentPath, ',')) ?: $parentID;
            foreach($chapterAndDocs as $key => $template)
            {
                if(strpos(",{$template->path},", ",{$topTemplate},") === false) unset($chapterAndDocs[$key]);
            }
        }
        $this->view->chapterAndDocs = $this->doc->buildNestedDocs($chapterAndDocs, $modulePairs);

        if($objectType == 'template')
        {
            $scopeList = $this->doc->getTemplateScopes();
            $this->view->scopeItems = $this->doc->getScopeItems($scopeList);
        }
        else
        {
            $this->view->users  = $this->user->getPairs('nocode|noclosed|nodeleted');
            $this->view->groups = $this->loadModel('group')->getPairs();
        }

        $docTitle = '';
        if(!empty($doc))
        {
            $docTitle = $doc->title;
        }
        elseif($this->post->title)
        {
            $docTitle = $this->post->title;
        }

        $this->view->docID      = $docID;
        $this->view->docTitle   = $docTitle;
        $this->view->mode       = empty($docID) ? 'create' : 'edit';
        $this->view->libID      = $libID;
        $this->view->moduleID   = $moduleID;
        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->view->lib        = $lib;
        $this->view->libs       = $libPairs;
        $this->view->parentID   = $parentID;
        $this->view->isDraft    = $isDraft == 'yes';
        $this->view->title      = $title;
        $this->view->modalType  = $modalType;
        $this->view->isCreate   = $isCreate;
        $this->display();
    }

    /**
     * AJAX: 通过文档ID获取Confluence子文档。
     * AJAX: Get confluence subdocuments by doc ID.
     *
     * @param  int    $originPageID 当前Confluence文档ID
     * @param  string $title        选择的父页面标题
     * @param  int    $level        展示层级
     * @access public
     * @return void
     */
    public function ajaxGetConfluenceChildren(int $originPageID, string $title, int $level = PHP_INT_MAX)
    {
        $parentID = $this->doc->getDocIdByTitle($originPageID, $title);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $docs = $this->docZen->getDocChildrenByRecursion((int)$parentID, $level);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'data' => $docs));
    }

    /**
     * Ajax: Retrieve corresponding user information in the Zen path through Confluence user ID.
     * Ajax: 通过Confluence用户ID获取在禅道中的对应用户信息。
     *
     * @param  string $username
     * @access public
     * @return void
     */
    public function ajaxGetConfluenceUser(string $username)
    {
        $user = $this->doc->getUserByConfluenceUserID($username);
        return $this->send(array('result' => 'success', 'data'=> $user));
    }

    /**
     * Manage scope.
     * 维护范围。
     *
     * @access public
     * @return void
     */
    public function manageScope()
    {
        $scopeList = $this->doc->getTemplateScopes();

        if(!empty($_POST))
        {
            $scopes = $this->post->scopes;

            $oldScopes = $newScopes = array();
            foreach($scopes as $index => $name)
            {
                if(empty(trim($name))) continue;

                $scopeID = $_POST['id'][$index];
                if($scopeID)
                {
                    $oldScopes[$scopeID] = $name;
                }
                else
                {
                    $newScopes[$index] = $name;
                }
            }

            $deletedScopes = array_diff(array_keys($scopeList), array_keys($oldScopes));
            if(!empty($deletedScopes)) $this->doc->deleteTemplateScopes($deletedScopes);
            if(dao::isError()) return $this->sendError(array('message' => dao::getError()));

            if(!empty($oldScopes)) $this->doc->updateTemplateScopes($oldScopes);
            if(dao::isError()) return $this->sendError(array('message' => dao::getError()));

            if(!empty($newScopes)) $this->doc->insertTemplateScopes($newScopes);
            if(dao::isError()) return $this->sendError(array('message' => dao::getError()));

            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $this->view->scopeList = $scopeList;
        $this->display();
    }

    /**
     * AJAX: Judge can be deleted scope.
     * AJAX: 判断范围是否可以被删除。
     *
     * @param  int    $scopeID
     * @access public
     * @return array
     */
    public function ajaxJudgeCanBeDeleted(int $scopeID = 0)
    {
        if(empty($scopeID)) return $this->sendSuccess(array('message' => 'success'));

        $templates = $this->doc->getScopeTemplates(array($scopeID));
        if(!empty($templates[$scopeID])) return $this->sendError($this->lang->docTemplate->scopeHasTemplateTips);

        $modules = $this->doc->getTemplateModules($scopeID);
        if(!empty($modules)) return $this->sendError($this->lang->docTemplate->scopeHasModuleTips);

        return $this->sendSuccess(array('message' => 'success'));
    }
}
