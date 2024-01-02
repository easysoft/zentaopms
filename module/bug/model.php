<?php
/**
 * The model file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class bugModel extends model
{
    /**
     * Set menu.
     *
     * @param  array  $products
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = 0, $moduleID = 0, $browseType = 'unclosed', $orderBy = '')
    {
        $this->loadModel('product')->setMenu($productID, $branch, $moduleID, 'bug');
        if($this->lang->navGroup->testcase == 'project' and $this->app->methodName == 'browse') $products = array(0 => $this->lang->bug->allProduct) + $products;
        $selectHtml = $this->product->select($products, $productID, 'bug', 'browse', '', $branch, $moduleID, 'bug');

        $pageNav     = '';
        $pageActions = '';
        $isMobile    = $this->app->viewType == 'mhtml';
        if($isMobile)
        {
            $this->app->loadLang('qa');
            $pageNav = html::a(helper::createLink('qa', 'index'), $this->lang->qa->index) . $this->lang->colon;
        }
        $pageNav .= $selectHtml;

        $this->lang->modulePageNav = $pageNav;
        $this->lang->TRActions     = $pageActions;
    }

    /**
     * Create a bug.
     *
     * @param  string $from   object that is transfered to bug.
     * @param  string $extras.
     * @access public
     * @return array|bool
     */
    public function create($from = '', $extras = '')
    {
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);

        $now = helper::now();
        $bug = fixer::input('post')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setDefault('project,execution,story,task,duplicateBug,linkBug', 0)
            ->setDefault('openedBuild', '')
            ->setDefault('notifyEmail', '')
            ->setDefault('deadline', '0000-00-00')
            ->setIF($this->lang->navGroup->bug != 'qa', 'project', $this->session->project)
            ->setIF(strpos($this->config->bug->create->requiredFields, 'deadline') !== false, 'deadline', $this->post->deadline)
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF(strpos($this->config->bug->create->requiredFields, 'execution') !== false, 'execution', $this->post->execution)
            ->stripTags($this->config->bug->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product,execution,module,severity')
            ->trim('title')
            ->join('openedBuild', ',')
            ->join('mailto', ',')
            ->join('os', ',')
            ->join('browser', ',')
            ->remove('files,labels,uid,oldTaskID,contactListMenu,region,lane,ticket,deleteFiles,resultFiles')
            ->get();

        /* Check repeat bug. */
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");
        if($result and $result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->checkIF($bug->notifyEmail, 'notifyEmail', 'email')
            ->batchCheck($this->config->bug->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();

            if(isset($_POST['resultFiles']))
            {
                $resultFiles = $_POST['resultFiles'];
                if(isset($_POST['deleteFiles']))
                {
                    foreach($_POST['deleteFiles'] as $deletedCaseFileID) $resultFiles = trim(str_replace(",$deletedCaseFileID,", ',', ",$resultFiles,"), ',');
                }
                $files = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($resultFiles)->fetchAll('id');
                foreach($files as $file)
                {
                    unset($file->id);
                    $file->objectType = 'bug';
                    $file->objectID   = $bugID;
                    $this->dao->insert(TABLE_FILE)->data($file)->exec();
                }
            }

            $this->file->updateObjectID($this->post->uid, $bugID, 'bug');
            $this->file->saveUpload('bug', $bugID);
            empty($bug->case) ? $this->loadModel('score')->create('bug', 'create', $bugID) : $this->loadModel('score')->create('bug', 'createFormCase', $bug->case);

            if($bug->execution)
            {
                $this->loadModel('kanban');

                $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
                if(!empty($_POST['lane'])) $laneID = $_POST['lane'];

                $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'unconfirmed');
                if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

                if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($bug->execution, $laneID, $columnID, 'bug', $bugID);
                if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($bug->execution, 'bug');
            }

            /* Callback the callable method to process the related data for object that is transfered to bug. */
            if($from && is_callable(array($this, $this->config->bug->fromObjects[$from]['callback']))) call_user_func(array($this, $this->config->bug->fromObjects[$from]['callback']), $bugID);

            return array('status' => 'created', 'id' => $bugID);
        }
        return false;
    }

    /**
     * Batch create
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $extra
     * @access public
     * @return void
     */
    public function batchCreate($productID, $branch = 0, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        /* Load module and init vars. */
        $this->loadModel('action');
        $this->loadModel('kanban');
        $branch    = (int)$branch;
        $productID = (int)$productID;
        $now       = helper::now();
        $actions   = array();
        $data      = fixer::input('post')->get();

        $result = $this->loadModel('common')->removeDuplicate('bug', $data, "product={$productID}");
        $data   = $result['data'];

        /* Get pairs(moduleID => moduleOwner) for bug. */
        $stmt         = $this->app->dbQuery($this->loadModel('tree')->buildMenuQuery($productID, 'bug', $startModuleID = 0, $branch));
        $moduleOwners = array();
        while($module = $stmt->fetch()) $moduleOwners[$module->id] = $module->owner;

        $module    = 0;
        $project   = 0;
        $execution = 0;
        $type      = '';
        $pri       = 0;
        foreach($data->title as $i => $title)
        {
            if(empty($title) and $this->common->checkValidRow('bug', $data, $i))
            {
                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->bug->title);
                return false;
            }

            $oses     = array_filter($data->oses[$i]);
            $browsers = array_filter($data->browsers[$i]);

            if($data->modules[$i]    != 'ditto') $module    = (int)$data->modules[$i];
            if($data->projects[$i]   != 'ditto') $project   = (int)$data->projects[$i];
            if($data->executions[$i] != 'ditto') $execution = (int)$data->executions[$i];
            if($data->types[$i]      != 'ditto') $type      = $data->types[$i];
            if($data->pris[$i]       != 'ditto') $pri       = $data->pris[$i];

            $data->modules[$i]    = (int)$module;
            $data->projects[$i]   = (int)$project;
            $data->executions[$i] = (int)$execution;
            $data->types[$i]      = $type;
            $data->pris[$i]       = $pri;
            $data->oses[$i]       = implode(',', $oses);
            $data->browsers[$i]   = implode(',', $browsers);
        }

        /* Get bug data. */
        if(isset($data->uploadImage)) $this->loadModel('file');
        $extendFields = $this->getFlowExtendFields();
        $bugs = array();
        foreach($data->title as $i => $title)
        {
            $title = trim($title);
            if(empty($title)) continue;

            $bug = new stdClass();
            $bug->openedBy    = $this->app->user->account;
            $bug->openedDate  = $now;
            $bug->product     = (int)$productID;
            $bug->branch      = isset($data->branches) ? (int)$data->branches[$i] : 0;
            $bug->module      = (int)$data->modules[$i];
            $bug->project     = (int)$data->projects[$i];
            $bug->execution   = (int)$data->executions[$i];
            $bug->openedBuild = !empty($data->openedBuilds[$i]) ? implode(',', $data->openedBuilds[$i]) : '';
            $bug->color       = $data->color[$i];
            $bug->title       = $title;
            $bug->deadline    = $data->deadlines[$i];
            $bug->steps       = nl2br($data->stepses[$i]);
            $bug->type        = $data->types[$i];
            $bug->pri         = $data->pris[$i];
            $bug->severity    = $data->severities[$i];
            $bug->os          = $data->oses[$i];
            $bug->browser     = $data->browsers[$i];
            $bug->keywords    = $data->keywords[$i];

            if(isset($data->lanes[$i])) $bug->laneID = $data->lanes[$i];

            /* Assign the bug to the person in charge of the module. */
            if(!empty($moduleOwners[$bug->module]))
            {
                $bug->assignedTo   = $moduleOwners[$bug->module];
                $bug->assignedDate = $now;
            }

            foreach($extendFields as $extendField)
            {
                $bug->{$extendField->field} = $this->post->{$extendField->field}[$i];
                if(is_array($bug->{$extendField->field})) $bug->{$extendField->field} = join(',', $bug->{$extendField->field});

                $bug->{$extendField->field} = htmlSpecialString($bug->{$extendField->field});
            }

            /* Required field check. */
            foreach(explode(',', $this->config->bug->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field and empty($bug->$field))
                {
                    dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->bug->$field);
                    return false;
                }
            }

            $bugs[$i] = $bug;
        }

        /* When the bug is created by uploading an image, add the image to the step of the bug. */
        foreach($bugs as $i => $bug)
        {
            $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
            if(isset($bug->laneID))
            {
                $laneID = $bug->laneID;
                unset($bug->laneID);
            }

            if(!empty($data->uploadImage[$i]))
            {
                $fileName = $data->uploadImage[$i];
                $file     = $this->session->bugImagesFile[$fileName];

                $realPath = $file['realpath'];
                unset($file['realpath']);
                if(rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
                {
                    if(in_array($file['extension'], $this->config->file->imageExtensions))
                    {
                        $file['addedBy']    = $this->app->user->account;
                        $file['addedDate']  = $now;
                        $this->dao->insert(TABLE_FILE)->data($file)->exec();

                        $fileID = $this->dao->lastInsertID();
                        $bug->steps .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
                    }
                }
                else
                {
                    unset($file);
                }
            }

            if($this->lang->navGroup->bug != 'qa') $bug->project = $this->session->project;
            $this->dao->insert(TABLE_BUG)->data($bug)
                ->autoCheck()
                ->batchCheck($this->config->bug->create->requiredFields, 'notempty')
                ->checkFlow()
                ->exec();
            if(dao::isError()) return false;

            $bugID = $this->dao->lastInsertID();

            $this->executeHooks($bugID);

            if($bug->execution)
            {
                $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'unconfirmed');
                if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

                if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($bug->execution, $laneID, $columnID, 'bug', $bugID);
                if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($bug->execution, 'bug');

            }
            /* When the bug is created by uploading the image, add the image to the file of the bug. */
            $this->loadModel('score')->create('bug', 'create', $bugID);
            if(!empty($data->uploadImage[$i]) and !empty($file))
            {
                $file['objectType'] = 'bug';
                $file['objectID']   = $bugID;
                $file['addedBy']    = $this->app->user->account;
                $file['addedDate']  = $now;
                $this->dao->insert(TABLE_FILE)->data($file)->exec();
                unset($file);
            }

            if(dao::isError())
            {
                dao::$errors['message'][] = 'bug#' . ($i) . dao::getError(true);
                return false;
            }
            $actions[$bugID] = $this->action->create('bug', $bugID, 'Opened');
        }

        /* Remove upload image file and session. */
        if(!empty($data->uploadImage) and $this->session->bugImagesFile)
        {
            $classFile = $this->app->loadClass('zfile');
            $file = current($_SESSION['bugImagesFile']);
            $realPath = dirname($file['realpath']);
            if(is_dir($realPath)) $classFile->removeDir($realPath);
            unset($_SESSION['bugImagesFile']);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchCreate');
        return $actions;
    }

    /**
     * Create bug from gitlab issue.
     *
     * @param  object    $bug
     * @param  int       $executionID
     * @access public
     * @return int|bool
     */
    public function createBugFromGitlabIssue($bug, $executionID)
    {
        $bug->openedBy     = $this->app->user->account;
        $bug->openedDate   = helper::now();
        $bug->assignedDate = isset($bug->assignedTo) ? helper::now() : 0;
        $bug->openedBuild  = 'trunk';
        $bug->story        = 0;
        $bug->task         = 0;
        $bug->pri          = 3;
        $bug->severity     = 3;
        $bug->project      = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');

        $this->dao->insert(TABLE_BUG)->data($bug, $skip = 'gitlab,gitlabProject')->autoCheck()->batchCheck($this->config->bug->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();

        return false;
    }

    /**
     * Get bugs.
     *
     * @param  array       $productIDList
     * @param  array       $executions
     * @param  int|string  $branch
     * @param  string      $browseType
     * @param  int         $moduleID
     * @param  int         $queryID
     * @param  string      $sort
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getBugs($productIDList, $executions, $branch, $browseType, $moduleID, $queryID, $sort, $pager, $projectID)
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->bugBrowseType and $this->session->bugBrowseType != 'bysearch') ? $this->session->bugBrowseType : $browseType;
        $browseType = $browseType == 'bybranch' ? 'bymodule' : $browseType;

        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        if(strpos($sort, 'severity_') !== false) $sort = str_replace('severity_', 'severityOrder_', $sort);

        /* Get bugs by browse type. */
        $bugs = array();
        if($browseType == 'all')               $bugs = $this->getAllBugs($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'bymodule')      $bugs = $this->getModuleBugs($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'assigntome')    $bugs = $this->getByAssigntome($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'openedbyme')    $bugs = $this->getByOpenedbyme($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'resolvedbyme')  $bugs = $this->getByResolvedbyme($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'assigntonull')  $bugs = $this->getByAssigntonull($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'unconfirmed')   $bugs = $this->getUnconfirmed($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'unresolved')    $bugs = $this->getByStatus($productIDList, $branch, $modules, $executions, 'unresolved', $sort, $pager, $projectID);
        elseif($browseType == 'unclosed')      $bugs = $this->getByStatus($productIDList, $branch, $modules, $executions, 'unclosed', $sort, $pager, $projectID);
        elseif($browseType == 'toclosed')      $bugs = $this->getByStatus($productIDList, $branch, $modules, $executions, 'toclosed', $sort, $pager, $projectID);
        elseif($browseType == 'longlifebugs')  $bugs = $this->getByLonglifebugs($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'postponedbugs') $bugs = $this->getByPostponedbugs($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'needconfirm')   $bugs = $this->getByNeedconfirm($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'bysearch')      $bugs = $this->getBySearch($productIDList, $branch, $queryID, $sort, '', $pager, $projectID);
        elseif($browseType == 'overduebugs')   $bugs = $this->getOverdueBugs($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'assignedbyme')  $bugs = $this->getByAssignedbyme($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);
        elseif($browseType == 'review')        $bugs = $this->getReviewBugs($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID);

        return $this->checkDelayedBugs($bugs);
    }

    /**
     * Check delay bugs.
     *
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function checkDelayedBugs($bugs)
    {
        foreach ($bugs as $bug) $bug = $this->checkDelayBug($bug);

        return $bugs;
    }

    /**
     * Check delay bug.
     *
     * @param  array  $bug
     * @access public
     * @return array
     */
    public function checkDelayBug($bug)
    {
        /* Delayed or not? */
        if(!helper::isZeroDate($bug->deadline))
        {
            if($bug->resolvedDate and !helper::isZeroDate($bug->resolvedDate))
            {
                $delay = helper::diffDate(substr($bug->resolvedDate, 0, 10), $bug->deadline);
            }
            elseif($bug->status == 'active')
            {
                $delay = helper::diffDate(helper::today(), $bug->deadline);
            }

            if(isset($delay) and $delay > 0) $bug->delay = $delay;
        }

        return $bug;
    }

    /**
     * Check bug execution priv.
     *
     * @param  object    $bug
     * @access public
     * @return void
     */
    public function checkBugExecutionPriv($bug)
    {
        if($bug->execution and !$this->loadModel('execution')->checkPriv($bug->execution))
        {
            echo(js::alert($this->lang->bug->executionAccessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(helper::createLink('bug', 'index', '')));
            if($this->app->tab == 'my') print(js::reload('parent'));

            return print(js::locate('back'));
        }
    }

    /**
     * Get bugs of a module.
     *
     * @param  int|array       $productIDList
     * @param  int|string      $branch
     * @param  string|array    $moduleIdList
     * @param  array           $executions
     * @param  string          $orderBy
     * @param  object          $pager
     * @param  int             $projectID
     * @access public
     * @return array
     */
    public function getModuleBugs($productIDList, $branch = 0, $moduleIdList = 0, $executions = array(), $orderBy = 'id_desc', $pager = null, $projectID = 0)
    {
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bug list of a plan.
     *
     * @param  int    $planID
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getPlanBugs($planID, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        if(strpos($orderBy, 'pri_') !== false) $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $bugs = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_BUG)
            ->where('plan')->eq((int)$planID)
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
    }

    /**
     * Get info of a bug.
     *
     * @param  int    $bugID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getById($bugID, $setImgSize = false)
    {
        $bug = $this->dao->select('t1.*, t2.name AS executionName, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.name AS taskName, t5.title AS planName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_TASK)->alias('t4')->on('t1.task = t4.id')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t5')->on('t1.plan = t5.id')
            ->where('t1.id')->eq((int)$bugID)->fetch();
        if(!$bug) return false;

        if(!empty($bug->project)) $bug->projectName = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($bug->project)->fetch('name');

        $bug = $this->loadModel('file')->replaceImgURL($bug, 'steps');
        if($setImgSize) $bug->steps = $this->file->setImgSize($bug->steps);
        foreach($bug as $key => $value) if(strpos($key, 'Date') !== false && $value && !(int)substr(is_null($value) ? '' : $value, 0, 4)) $bug->$key = '';

        if($bug->duplicateBug) $bug->duplicateBugTitle = $this->dao->findById($bug->duplicateBug)->from(TABLE_BUG)->fields('title')->fetch('title');
        if($bug->case)         $bug->caseTitle         = $this->dao->findById($bug->case)->from(TABLE_CASE)->fields('title')->fetch('title');
        if($bug->linkBug)      $bug->linkBugTitles     = $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->in($bug->linkBug)->fetchPairs();
        if($bug->toStory > 0)  $bug->toStoryTitle      = $this->dao->findById($bug->toStory)->from(TABLE_STORY)->fields('title')->fetch('title');
        if($bug->toTask > 0)   $bug->toTaskTitle       = $this->dao->findById($bug->toTask)->from(TABLE_TASK)->fields('name')->fetch('name');
        $bug->linkMRTitles = $this->loadModel('mr')->getLinkedMRPairs($bugID, 'bug');

        $bug->toCases = array();
        $toCases      = $this->dao->select('id, title')->from(TABLE_CASE)->where('`fromBug`')->eq($bugID)->fetchAll();
        foreach($toCases as $toCase) $bug->toCases[$toCase->id] = $toCase->title;

        $bug->files = $this->loadModel('file')->getByObject('bug', $bugID);

        return $this->checkDelayBug($bug);
    }

    /**
     * Get bug list.
     *
     * @param  int|array|string    $bugIDList
     * @param  string              $fields
     * @access public
     * @return array
     */
    public function getByList($bugIDList = 0, $fields = '*')
    {
        return $this->dao->select($fields)->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->beginIF($bugIDList)->andWhere('id')->in($bugIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get active bugs.
     *
     * @param  array    $products
     * @param  int      $branch
     * @param  array    $executions
     * @param  array    $excludeBugs
     * @param  object   $pager
     * @access public
     * @return array
     */
    public function getActiveBugs($products, $branch, $executions, $excludeBugs, $pager = null, $orderBy = 'id desc')
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('status')->eq('active')
            ->andWhere('tostory')->eq(0)
            ->andWhere('toTask')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->beginIF($branch !== '' and $branch !== 'all')->andWhere('branch')->in("0,$branch")->fi()
            ->beginIF(!empty($executions))->andWhere('execution')->in($executions)->fi()
            ->beginIF($excludeBugs)->andWhere('id')->notIN($excludeBugs)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get active and postponed bugs.
     *
     * @param  int    $products
     * @param  int    $executionID
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getActiveAndPostponedBugs($products, $executionID, $pager = null)
    {
        return $this->dao->select('t1.*')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.product = t2.product')
            ->where("((t1.status = 'resolved' AND t1.resolution = 'postponed') OR (t1.status = 'active'))")
            ->andWhere('t1.toTask')->eq(0)
            ->andWhere('t1.toStory')->eq(0)
            ->beginIF(!empty($products))->andWhere('t1.product')->in($products)->fi()
            ->beginIF(empty($products))->andWhere('t1.execution')->eq($executionID)->fi()
            ->andWhere('t2.project')->eq($executionID)
            ->andWhere("(t2.branch = '0' OR t1.branch = '0' OR t2.branch = t1.branch)")
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get module owner.
     *
     * @param  int    $moduleID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getModuleOwner($moduleID, $productID)
    {
        $users = $this->loadModel('user')->getPairs('nodeleted');
        $owner = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('QD');
        $owner = isset($users[$owner]) ? $owner : '';

        if($moduleID)
        {
            $module = $this->dao->findByID($moduleID)->from(TABLE_MODULE)->andWhere('root')->eq($productID)->fetch();
            if(empty($module)) return $owner;

            if($module->owner and isset($users[$module->owner])) return $module->owner;

            $moduleIDList = explode(',', trim(str_replace(",$module->id,", ',', $module->path), ','));
            krsort($moduleIDList);
            if($moduleIDList)
            {
                $modules = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($moduleIDList)->andWhere('deleted')->eq(0)->fetchAll('id');
                foreach($moduleIDList as $moduleID)
                {
                    if(isset($modules[$moduleID]))
                    {
                        $module = $modules[$moduleID];
                        if($module->owner and isset($users[$module->owner])) return $module->owner;
                    }
                }
            }
        }

        return $owner;
    }

    /**
     * Update a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function update($bugID)
    {
        $oldBug = $this->getById($bugID);
        if(!empty($_POST['lastEditedDate']) and $oldBug->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }
        $now = helper::now();
        $bug = fixer::input('post')
            ->add('id', $bugID)
            ->cleanInt('product,module,severity,project,execution,story,task,branch')
            ->stripTags($this->config->bug->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('module,execution,story,task,duplicateBug,branch', 0)
            ->setDefault('product', $oldBug->product)
            ->setDefault('openedBuild', '')
            ->setDefault('os', '')
            ->setDefault('browser', '')
            ->setDefault('plan', 0)
            ->setDefault('deadline', '0000-00-00')
            ->setDefault('resolvedDate', '')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('mailto', '')
            ->setDefault('deleteFiles', array())
            ->add('lastEditedDate', $now)
            ->setIF(strpos($this->config->bug->edit->requiredFields, 'deadline') !== false, 'deadline', $this->post->deadline)
            ->join('openedBuild', ',')
            ->join('mailto', ',')
            ->join('linkBug', ',')
            ->join('os', ',')
            ->join('browser', ',')
            ->setIF($this->post->assignedTo  != $oldBug->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->resolvedBy  != '' and $this->post->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($this->post->resolution  != '' and $this->post->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($this->post->resolution  != '' and $this->post->resolvedBy   == '', 'resolvedBy',   $this->app->user->account)
            ->setIF($this->post->closedBy    != '' and $this->post->closedDate   == '', 'closedDate',   $now)
            ->setIF($this->post->closedDate  != '' and $this->post->closedBy     == '', 'closedBy',     $this->app->user->account)
            ->setIF($this->post->closedBy    != '' or  $this->post->closedDate   != '', 'assignedTo',   'closed')
            ->setIF($this->post->closedBy    != '' or  $this->post->closedDate   != '', 'assignedDate', $now)
            ->setIF($this->post->resolution  != '' or  $this->post->resolvedDate != '', 'status',       'resolved')
            ->setIF($this->post->closedBy    != '' or  $this->post->closedDate   != '', 'status',       'closed')
            ->setIF(($this->post->resolution != '' or  $this->post->resolvedDate != '') and $this->post->assignedTo == '', 'assignedTo', $oldBug->openedBy)
            ->setIF(($this->post->resolution != '' or  $this->post->resolvedDate != '') and $this->post->assignedTo == '', 'assignedDate', $now)
            ->setIF($this->post->assignedTo  == '' and $oldBug->status           == 'closed', 'assignedTo', 'closed')
            ->setIF($this->post->resolution  == '' and $this->post->resolvedDate =='', 'status', 'active')
            ->setIF($this->post->resolution  != '', 'confirmed', 1)
            ->setIF($this->post->resolution  != '' and $this->post->resolution != 'duplicate', 'duplicateBug', 0)
            ->setIF($this->post->story != false and $this->post->story != $oldBug->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF(!$this->post->linkBug, 'linkBug', '')
            ->setIF($this->post->case === '', 'case', 0)
            ->setIF($this->post->testtask === '', 'testtask', 0)
            ->remove('comment,files,labels,uid,contactListMenu')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_BUG)->data($bug, 'deleteFiles')
            ->autoCheck()
            ->batchCheck($this->config->bug->edit->requiredFields, 'notempty')
            ->checkIF($bug->resolvedBy, 'resolution',  'notempty')
            ->checkIF($bug->closedBy,   'resolution',  'notempty')
            ->checkIF($bug->notifyEmail, 'notifyEmail', 'email')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF($bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->checkFlow()
            ->where('id')->eq((int)$bugID)
            ->exec();

        if(!dao::isError())
        {
            /* Link bug to build and release. */
            if($bug->resolution == 'fixed' and !empty($bug->resolvedBuild) and $oldBug->resolvedBuild != $bug->resolvedBuild)
            {
                if(!empty($oldBug->resolvedBuild)) $this->loadModel('build')->unlinkBug($oldBug->resolvedBuild, (int)$bugID);
                $this->linkBugToBuild($bugID, $bug->resolvedBuild);
            }

            if($bug->plan != $oldBug->plan)
            {
                $this->loadModel('action');
                if(!empty($oldBug->plan)) $this->action->create('productplan', $oldBug->plan, 'unlinkbug', '', $bugID);
                if(!empty($bug->plan)) $this->action->create('productplan', $bug->plan, 'linkbug', '', $bugID);
            }

            $linkBugs    = explode(',', $bug->linkBug);
            $oldLinkBugs = explode(',', $oldBug->linkBug);
            $addBugs     = array_diff($linkBugs, $oldLinkBugs);
            $removeBugs  = array_diff($oldLinkBugs, $linkBugs);
            $changeBugs  = array_merge($addBugs, $removeBugs);
            $changeBugs  = $this->dao->select('id,linkbug')->from(TABLE_BUG)->where('id')->in(array_filter($changeBugs))->fetchPairs();
            foreach($changeBugs as $changeBugID => $changeBug)
            {
                if(in_array($changeBugID, $addBugs) and empty($changeBug))  $this->dao->update(TABLE_BUG)->set('linkBug')->eq($bugID)->where('id')->eq((int)$changeBugID)->exec();
                if(in_array($changeBugID, $addBugs) and !empty($changeBug)) $this->dao->update(TABLE_BUG)->set('linkBug')->eq("$changeBug,$bugID")->where('id')->eq((int)$changeBugID)->exec();
                if(in_array($changeBugID, $removeBugs))
                {
                    $linkBugs = explode(',', $changeBug);
                    unset($linkBugs[array_search($bugID, $linkBugs)]);
                    $this->dao->update(TABLE_BUG)->set('linkBug')->eq(implode(',', $linkBugs))->where('id')->eq((int)$changeBugID)->exec();
                }
            }

            if(!empty($bug->resolvedBy)) $this->loadModel('score')->create('bug', 'resolve', $bugID);

            if($bug->execution and $bug->status != $oldBug->status) $this->loadModel('kanban')->updateLane($bug->execution, 'bug');

            if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

            $this->file->processFile4Object('bug', $oldBug, $bug);
            return common::createChanges($oldBug, $bug);
        }
    }

    /**
     * Batch update bugs.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $bugs        = array();
        $allChanges  = array();
        $now         = helper::now();
        $data        = fixer::input('post')->get();
        $bugIDList   = $this->post->bugIDList ? $this->post->bugIDList : array();
        $unlinkPlans = array();
        $link2Plans  = array();

        if(!empty($bugIDList))
        {
            /* Process the data if the value is 'ditto'. */
            foreach($bugIDList as $bugID)
            {
                if(!isset($data->assignedTos[$bugID])) $data->assignedTos[$bugID] = 'closed';
                if($data->types[$bugID]       == 'ditto') $data->types[$bugID]       = isset($prev['type'])       ? $prev['type']       : '';
                if($data->severities[$bugID]  == 'ditto') $data->severities[$bugID]  = isset($prev['severity'])   ? $prev['severity']   : 3;
                if($data->pris[$bugID]        == 'ditto') $data->pris[$bugID]        = isset($prev['pri'])        ? $prev['pri']        : 0;
                if($data->plans[$bugID]       == 'ditto') $data->plans[$bugID]       = isset($prev['plan'])       ? $prev['plan'] : '';
                if($data->assignedTos[$bugID] == 'ditto') $data->assignedTos[$bugID] = isset($prev['assignedTo']) ? $prev['assignedTo'] : '';
                if($data->resolvedBys[$bugID] == 'ditto') $data->resolvedBys[$bugID] = isset($prev['resolvedBy']) ? $prev['resolvedBy'] : '';
                if($data->resolutions[$bugID] == 'ditto') $data->resolutions[$bugID] = isset($prev['resolution']) ? $prev['resolution'] : '';
                if(isset($data->branches[$bugID]) and $data->branches[$bugID] == 'ditto') $data->branches[$bugID] = isset($prev['branch']) ? $prev['branch'] : 0;

                $prev['type']       = $data->types[$bugID];
                $prev['severity']   = $data->severities[$bugID];
                $prev['pri']        = $data->pris[$bugID];
                $prev['branch']     = isset($data->branches[$bugID]) ? $data->branches[$bugID] : '';
                $prev['plan']       = $data->plans[$bugID];
                $prev['assignedTo'] = $data->assignedTos[$bugID];
                $prev['resolvedBy'] = $data->resolvedBys[$bugID];
                $prev['resolution'] = $data->resolutions[$bugID];
            }

            /* Initialize bugs from the post data.*/
            $extendFields = $this->getFlowExtendFields();
            $oldBugs = $bugIDList ? $this->getByList($bugIDList) : array();
            foreach($bugIDList as $bugID)
            {
                $oldBug = $oldBugs[$bugID];

                $os           = array_filter($data->os[$bugID]);
                $browsers     = array_filter($data->browsers[$bugID]);
                $duplicateBug = $data->duplicateBugs[$bugID] ? $data->duplicateBugs[$bugID] : $oldBug->duplicateBug;

                $bug = new stdclass();
                $bug->id             = $bugID;
                $bug->lastEditedBy   = $this->app->user->account;
                $bug->lastEditedDate = $now;
                $bug->type           = $data->types[$bugID];
                $bug->severity       = $data->severities[$bugID];
                $bug->pri            = $data->pris[$bugID];
                $bug->color          = $data->colors[$bugID];
                $bug->title          = $data->titles[$bugID];
                $bug->plan           = empty($data->plans[$bugID]) ? 0 : $data->plans[$bugID];
                $bug->branch         = empty($data->branches[$bugID]) ? 0 : $data->branches[$bugID];
                $bug->module         = $data->modules[$bugID];
                $bug->assignedTo     = $oldBug->status == 'closed' ? $oldBug->assignedTo : $data->assignedTos[$bugID];
                $bug->deadline       = $data->deadlines[$bugID];
                $bug->resolvedBy     = $data->resolvedBys[$bugID];
                $bug->keywords       = $data->keywords[$bugID];
                $bug->os             = implode(',', $os);
                $bug->browser        = implode(',', $browsers);
                $bug->resolution     = $data->resolutions[$bugID];
                $bug->duplicateBug   = ($bug->resolution  != '' and $bug->resolution != 'duplicate') ? 0 : $duplicateBug;

                if($bug->assignedTo != $oldBug->assignedTo) $bug->assignedDate = $now;
                if($bug->resolution != '') $bug->confirmed = 1;
                if(($bug->resolvedBy != '' or $bug->resolution != '') and strpos(',resolved,closed,', ",{$oldBug->status},") === false)
                {
                    $bug->resolvedDate = $now;
                    $bug->status       = 'resolved';
                }
                if($bug->resolution != '' and $bug->resolvedBy == '') $bug->resolvedBy = $this->app->user->account;
                if($bug->resolution != '' and $bug->assignedTo == '')
                {
                    $bug->assignedTo   = $oldBug->openedBy;
                    $bug->assignedDate = $now;
                }

                foreach($extendFields as $extendField)
                {
                    $bug->{$extendField->field} = $this->post->{$extendField->field}[$bugID];
                    if(is_array($bug->{$extendField->field})) $bug->{$extendField->field} = join(',', $bug->{$extendField->field});

                    $bug->{$extendField->field} = htmlSpecialString($bug->{$extendField->field});
                }

                if($bug->plan != $oldBug->plan)
                {
                    if($bug->plan != $oldBug->plan and !empty($oldBug->plan)) $unlinkPlans[$oldBug->plan] = empty($unlinkPlans[$oldBug->plan]) ? $bugID : "{$unlinkPlans[$oldBug->plan]},$bugID";
                    if($bug->plan != $oldBug->plan and !empty($bug->plan))    $link2Plans[$bug->plan]  = empty($link2Plans[$bug->plan]) ? $bugID : "{$link2Plans[$bug->plan]},$bugID";
                }

                $bugs[$bugID] = $bug;
                unset($bug);
            }

            /* Update bugs. */
            foreach($bugs as $bugID => $bug)
            {
                $oldBug = $oldBugs[$bugID];

                $this->dao->update(TABLE_BUG)->data($bug)
                    ->autoCheck()
                    ->batchCheck($this->config->bug->edit->requiredFields, 'notempty')
                    ->checkIF($bug->resolvedBy, 'resolution', 'notempty')
                    ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
                    ->checkFlow()
                    ->where('id')->eq((int)$bugID)
                    ->exec();

                if(!dao::isError())
                {
                    if(!empty($bug->resolvedBy)) $this->loadModel('score')->create('bug', 'resolve', $bug);

                    $this->executeHooks($bugID);

                    $allChanges[$bugID] = common::createChanges($oldBug, $bug);

                    if($this->config->edition != 'open' && $oldBug->feedback && !isset($feedbacks[$oldBug->feedback]))
                    {
                        $feedbacks[$oldBug->feedback] = $oldBug->feedback;
                        $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);
                    }
                }
                else
                {
                    return helper::end(js::error('bug#' . $bugID . dao::getError(true)));
                }
            }
        }
        if(!dao::isError())
        {
            $this->loadModel('score')->create('ajax', 'batchEdit');

            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $bugs) $this->action->create('productplan', $planID, 'unlinkbug', '', $bugs);
            foreach($link2Plans as $planID => $bugs) $this->action->create('productplan', $planID, 'linkbug', '', $bugs);
        }
        return $allChanges;
    }

    /**
     * Batch active bugs.
     *
     * @access public
     * @return array
     */
    public function batchActivate()
    {
        $now  = helper::now();
        $data = fixer::input('post')->get();

        $activateBugs = array();
        $bugIDList    = $data->bugIDList ? $data->bugIDList : array();

        if(empty($bugIDList)) return $activateBugs;

        $extendFields = $this->getFlowExtendFields();
        foreach($bugIDList as $bugID)
        {
            if($data->statusList[$bugID] == 'active') continue;

            $activateBugs[$bugID]['assignedTo']  = $data->assignedToList[$bugID];
            $activateBugs[$bugID]['openedBuild'] = $data->openedBuildList[$bugID];
            $activateBugs[$bugID]['comment']     = $data->commentList[$bugID];

            $activateBugs[$bugID]['activatedDate']  = $now;
            $activateBugs[$bugID]['assignedDate']   = $now;
            $activateBugs[$bugID]['resolution']     = '';
            $activateBugs[$bugID]['status']         = 'active';
            $activateBugs[$bugID]['resolvedDate']   = '0000-00-00';
            $activateBugs[$bugID]['resolvedBy']     = '';
            $activateBugs[$bugID]['resolvedBuild']  = '';
            $activateBugs[$bugID]['closedBy']       = '';
            $activateBugs[$bugID]['closedDate']     = '0000-00-00';
            $activateBugs[$bugID]['duplicateBug']   = 0;
            $activateBugs[$bugID]['toTask']         = 0;
            $activateBugs[$bugID]['toStory']        = 0;
            $activateBugs[$bugID]['lastEditedBy']   = $this->app->user->account;
            $activateBugs[$bugID]['lastEditedDate'] = $now;

            foreach($extendFields as $extendField)
            {
                $postFieldData = $this->post->{$extendField->field};

                if(is_array($postFieldData[$bugID])) $postFieldData[$bugID] = join(',', $postFieldData[$bugID]);

                $activateBugs[$bugID][$extendField->field] = htmlSpecialString($postFieldData[$bugID]);
            }
        }

        /* Update bugs. */
        foreach($activateBugs as $bugID => $bug)
        {
            $this->dao->update(TABLE_BUG)->data($bug, $skipFields = 'comment')->autoCheck()->where('id')->eq((int)$bugID)->exec();
            if(dao::isError()) return print(js::error('bug#' . $bugID . dao::getError(true)));

            $this->dao->update(TABLE_BUG)->set('activatedCount = activatedCount + 1')->where('id')->eq((int)$bugID)->exec();

            $this->executeHooks($bugID);
        }

        return $activateBugs;
    }

    /**
     * Assign a bug to a user again.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function assign($bugID)
    {
        $now = helper::now();
        $oldBug = $this->getById($bugID);
        if($oldBug->status == 'closed') return array();

        $bug = fixer::input('post')
            ->add('id', $bugID)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->setDefault('mailto', '')
            ->stripTags($this->config->bug->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment,showModule')
            ->join('mailto', ',')
            ->get();

        if($this->app->rawMethod == 'batchassignto') unset($bug->mailto);

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->assignto['id'], $this->post->uid);
        $this->dao->update(TABLE_BUG)
            ->data($bug)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($bugID)->exec();

        if(!dao::isError()) return common::createChanges($oldBug, $bug);
    }

    /**
     * Confirm a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function confirm($bugID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $now    = helper::now();
        $oldBug = $this->getById($bugID);

        $bug = fixer::input('post')
            ->add('id', $bugID)
            ->setDefault('confirmed', 1)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->setDefault('mailto', '')
            ->stripTags($this->config->bug->editor->confirmbug['id'], $this->config->allowedTags)
            ->remove('comment')
            ->join('mailto', ',')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->confirmbug['id'], $this->post->uid);
        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->checkFlow()->where('id')->eq($bugID)->exec();

        if(!dao::isError())
        {
            $this->loadModel('score')->create('bug', 'confirmBug', $oldBug);
            if($oldBug->execution)
            {
                $this->loadModel('kanban');
                if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bugID);
                if(isset($output['toColID'])) $this->kanban->moveCard($bugID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID'], $oldBug->execution);
            }
            return common::createChanges($oldBug, $bug);
        }
    }

    /**
     * Batch confirm bugs.
     *
     * @param  array $bugIDList
     * @access public
     * @return void
     */
    public function batchConfirm($bugIDList)
    {
        $now  = helper::now();
        $bugs = $this->getByList($bugIDList);
        foreach($bugIDList as $bugID)
        {
            if($bugs[$bugID]->confirmed) continue;

            $bug = new stdclass();
            $bug->assignedTo     = $this->app->user->account;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->confirmed      = 1;

            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
            $this->executeHooks($bugID);
        }
    }

    /**
     * Resolve a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function resolve($bugID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $now    = helper::now();
        $oldBug = $this->getById($bugID);
        $bug    = fixer::input('post')
            ->add('id', $bugID)
            ->add('status',    'resolved')
            ->add('confirmed', 1)
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('resolvedBy',     $this->app->user->account)
            ->setDefault('assignedDate',   $now)
            ->setDefault('resolvedDate',   $now)
            ->setDefault('assignedTo',     $oldBug->openedBy)
            ->setDefault('duplicateBug',   0)
            ->removeIF($this->post->resolution != 'duplicate', 'duplicateBug')
            ->stripTags($this->config->bug->editor->resolve['id'], $this->config->allowedTags)
            ->remove('files,labels')
            ->get();
        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->resolve['id'], $this->post->uid);

        /* Set comment lang for alert error. */
        $this->lang->bug->comment = $this->lang->comment;

        /* Can create build when resolve bug. */
        if(isset($bug->createBuild))
        {
            /* Check required fields. */
            foreach(explode(',', $this->config->bug->resolve->requiredFields) as $requiredField)
            {
                if($requiredField == 'resolvedBuild') continue;
                if(!isset($_POST[$requiredField]) or strlen(trim($_POST[$requiredField])) == 0)
                {
                    $fieldName = $requiredField;
                    if(isset($this->lang->bug->$requiredField)) $fieldName = $this->lang->bug->$requiredField;
                    dao::$errors[] = sprintf($this->lang->error->notempty, $fieldName);
                }
            }

            if($bug->resolution == 'duplicate' and !$this->post->duplicateBug) dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->bug->duplicateBug);

            if(empty($bug->buildName)) dao::$errors['buildName'][] = sprintf($this->lang->error->notempty, $this->lang->bug->placeholder->newBuildName);
            if(empty($bug->buildExecution))
            {
                $executionLang = $this->lang->bug->execution;
                if($oldBug->execution)
                {
                    $execution = $this->loadModel('execution')->getByID($oldBug->execution);
                    if($execution->type == 'kanban') $executionLang = $this->lang->bug->kanban;
                }
                dao::$errors['buildExecution'][] = sprintf($this->lang->error->notempty, $executionLang);
            }
            if(dao::isError()) return false;

            $buildData = new stdclass();
            $buildData->product     = (int)$oldBug->product;
            $buildData->branch      = (int)$oldBug->branch;
            $buildData->project     = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($bug->buildExecution)->fetch('project');
            $buildData->execution   = $bug->buildExecution;
            $buildData->name        = $bug->buildName;
            $buildData->date        = date('Y-m-d');
            $buildData->builder     = $this->app->user->account;
            $buildData->createdBy   = $this->app->user->account;
            $buildData->createdDate = helper::now();

            $this->lang->build->name = $this->lang->bug->placeholder->newBuildName;
            $this->dao->insert(TABLE_BUILD)->data($buildData)->autoCheck()
                ->check('name', 'unique', "product = {$buildData->product} AND branch = {$buildData->branch} AND deleted = '0'")
                ->exec();
            if(dao::isError()) return false;
            $buildID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('build', $buildID, 'opened');
            $bug->resolvedBuild = $buildID;
        }

        if($bug->resolvedBuild and $bug->resolvedBuild != 'trunk')
        {
            $testtaskID = (int) $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($bug->resolvedBuild)->orderBy('id_desc')->limit(1)->fetch('id');
            if($testtaskID and empty($oldBug->testtask)) $bug->testtask = $testtaskID;
        }

        $this->dao->update(TABLE_BUG)->data($bug, 'buildName,createBuild,buildExecution,comment')
            ->autoCheck()
            ->batchCheck($this->config->bug->resolve->requiredFields, 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF($bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->checkFlow()
            ->where('id')->eq((int)$bugID)
            ->exec();

        if(!dao::isError())
        {
            $this->loadModel('score')->create('bug', 'resolve', $oldBug);
            if($oldBug->execution)
            {
                $this->loadModel('kanban');
                if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bugID);
                if(isset($output['toColID'])) $this->kanban->moveCard($bugID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
            }

            /* Link bug to build and release. */
            $this->linkBugToBuild($bugID, $bug->resolvedBuild);

            if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

            return common::createChanges($oldBug, $bug);
        }

        return false;
    }

    /**
     * Batch change branch.
     *
     * @param  array  $bugIDList
     * @param  int    $branchID
     * @param  array  $oldBugs
     * @access public
     * @return array
     */
    public function batchChangeBranch($bugIDList, $branchID, $oldBugs)
    {
        $now        = helper::now();
        $allChanges = array();
        foreach($bugIDList as $bugID)
        {
            $oldBug = $oldBugs[$bugID];

            $bug = new stdclass();
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->branch         = $branchID;

            $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
            if(!dao::isError()) $allChanges[$bugID] = common::createChanges($oldBug, $bug);
        }
        return $allChanges;
    }

    /**
     * Batch change the module of bug.
     *
     * @param  array  $bugIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($bugIDList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldBugs    = $this->getByList($bugIDList);
        foreach($bugIDList as $bugID)
        {
            $oldBug = $oldBugs[$bugID];
            if($moduleID == $oldBug->module) continue;

            $bug = new stdclass();
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->module         = $moduleID;

            $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
            if(!dao::isError()) $allChanges[$bugID] = common::createChanges($oldBug, $bug);
        }
        return $allChanges;
    }

    /**
     * Batch change the plan of bug.
     *
     * @param  array  $bugIDList
     * @param  int    $planID
     * @access public
     * @return array
     */
    public function batchChangePlan($bugIDList, $planID)
    {
        $now         = helper::now();
        $allChanges  = array();
        $oldBugs     = $this->getByList($bugIDList);
        $unlinkPlans = array();
        $link2Plans  = array();
        foreach($bugIDList as $bugID)
        {
            $oldBug = $oldBugs[$bugID];
            if($planID == $oldBug->plan) continue;
            $unlinkPlans[$oldBug->plan] = empty($unlinkPlans[$oldBug->plan]) ? $bugID : "{$unlinkPlans[$oldBug->plan]},$bugID";
            $link2Plans[$planID]        = empty($link2Plans[$planID]) ? $bugID : "{$link2Plans[$planID]},$bugID";

            $bug = new stdclass();
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->plan           = $planID;

            $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
            if(!dao::isError()) $allChanges[$bugID] = common::createChanges($oldBug, $bug);
        }
        if(!dao::isError())
        {
            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $bugs) $this->action->create('productplan', $planID, 'unlinkbug', '', $bugs);
            foreach($link2Plans as $planID => $bugs) $this->action->create('productplan', $planID, 'linkbug', '', $bugs);
        }
        return $allChanges;
    }

    /**
     * Batch resolve bugs.
     *
     * @param  array    $bugIDList
     * @param  string   $resolution
     * @param  string   $resolvedBuild
     * @access public
     * @return void
     */
    public function batchResolve($bugIDList, $resolution, $resolvedBuild)
    {
        $now  = helper::now();
        $bugs = $this->getByList($bugIDList);

        $bug       = reset($bugs);
        $productID = $bug->product;
        $users     = $this->loadModel('user')->getPairs();
        $product   = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        $stmt      = $this->dao->query($this->loadModel('tree')->buildMenuQuery($productID, 'bug'));
        $modules   = array();
        while($module = $stmt->fetch()) $modules[$module->id] = $module;

        $changes = array();
        foreach($bugIDList as $i => $bugID)
        {
            $oldBug = $bugs[$bugID];
            if($oldBug->resolution == 'fixed')
            {
                unset($bugIDList[$i]);
                continue;
            }
            if($oldBug->status != 'active') continue;

            $assignedTo = $oldBug->openedBy;
            if(!isset($users[$assignedTo]))
            {
                $assignedTo = '';
                $module     = isset($modules[$oldBug->module]) ? $modules[$oldBug->module] : '';
                while($module)
                {
                    if($module->owner and isset($users[$module->owner]))
                    {
                        $assignedTo = $module->owner;
                        break;
                    }
                    $module = isset($modules[$module->parent]) ? $modules[$module->parent] : '';
                }
                if(empty($assignedTo)) $assignedTo = $product->QD;
            }

            $bug = new stdClass();
            $bug->resolution     = $resolution;
            $bug->resolvedBuild  = $resolution == 'fixed' ? $resolvedBuild : '';
            $bug->resolvedBy     = $this->app->user->account;
            $bug->resolvedDate   = $now;
            $bug->status         = 'resolved';
            $bug->confirmed      = 1;
            $bug->assignedTo     = $assignedTo;
            $bug->assignedDate   = $now;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;

            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
            $this->executeHooks($bugID);

            if($oldBug->execution) $this->loadModel('kanban')->updateLane($oldBug->execution, 'bug');
            $changes[$bugID] = common::createChanges($oldBug, $bug);

            if($this->config->edition != 'open' && $oldBug->feedback && !isset($feedbacks[$oldBug->feedback]))
            {
                $feedbacks[$oldBug->feedback] = $oldBug->feedback;
                $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);
            }
        }

        /* Link bug to build and release. */
        $this->linkBugToBuild($bugIDList, $resolvedBuild);

        return $changes;
    }

    /**
     * Activate a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function activate($bugID, $extra)
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $bugID      = (int)$bugID;
        $oldBug     = $this->getById($bugID);
        $solveBuild = $this->dao->select('id')
            ->from(TABLE_BUILD)
            ->where("CONCAT(',', bugs, ',')")->like("%,{$bugID},%")
            ->fetch('id');
        $now = helper::now();
        $bug = fixer::input('post')
            ->setDefault('assignedTo',     $oldBug->resolvedBy)
            ->setDefault('assignedDate',   $now)
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('activatedDate',  $now)
            ->setDefault('activatedCount', (int)$oldBug->activatedCount)
            ->stripTags($this->config->bug->editor->activate['id'], $this->config->allowedTags)
            ->add('id', $bugID)
            ->add('resolution', '')
            ->add('status', 'active')
            ->add('resolvedDate', '0000-00-00')
            ->add('resolvedBy', '')
            ->add('resolvedBuild', '')
            ->add('closedBy', '')
            ->add('closedDate', '0000-00-00')
            ->add('duplicateBug', 0)
            ->add('toTask', 0)
            ->add('toStory', 0)
            ->join('openedBuild', ',')
            ->remove('comment,files,labels')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->activate['id'], $this->post->uid);
        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->checkFlow()->where('id')->eq((int)$bugID)->exec();
        $this->dao->update(TABLE_BUG)->set('activatedCount = activatedCount + 1')->where('id')->eq((int)$bugID)->exec();

        if($solveBuild)
        {
            $this->loadModel('build');
            $build = $this->build->getByID($solveBuild);
            $build->bugs = trim(str_replace(",$bugID,", ',', ",$build->bugs,"), ',');
            $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$solveBuild)->exec();
        }

        if($oldBug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bugID);
            if(isset($output['toColID'])) $this->kanban->moveCard($bugID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        }
        $bug->activatedCount += 1;
        return common::createChanges($oldBug, $bug);
    }

    /**
     * Close a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function close($bugID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $now    = helper::now();
        $oldBug = $this->getById($bugID);
        $bug    = fixer::input('post')
            ->add('id', $bugID)
            ->add('status',     'closed')
            ->add('confirmed',  1)
            ->setDefault('assignedDate',   $now)
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('closedBy',       $this->app->user->account)
            ->setDefault('closedDate',     $now)
            ->stripTags($this->config->bug->editor->close['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->checkFlow()->where('id')->eq((int)$bugID)->exec();
        if($oldBug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bugID);
            if(isset($output['toColID'])) $this->kanban->moveCard($bugID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        }

        if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

        return common::createChanges($oldBug, $bug);
    }

    /**
     * Get bugs to link.
     *
     * @param  int    $bugID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  object $pager
     * @param  string $excludeBugs
     * @access public
     * @return array
     */
    public function getBugs2Link($bugID, $browseType = 'bySearch', $queryID = 0, $pager = null, $excludeBugs = '')
    {
        $bug       = $this->getById($bugID);
        $bugIDList = $bug->id . ',' . $bug->linkBug . ',' . $excludeBugs;

        if($browseType == 'bySearch')
        {
            return $this->getBySearch($bug->product, 'all', $queryID, 'id', $bugIDList, $pager);
        }
        else
        {
            return $this->dao->select('*')->from(TABLE_BUG)
                ->where('deleted')->eq('0')
                ->andWhere('id')->notin($bugIDList)
                ->andWhere('product')->eq($bug->product)
                ->beginIF($bug->project)->andWhere('project')->eq($bug->project)->fi()
                ->beginIF($bug->execution)->andWhere('execution')->eq($bug->execution)->fi()
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }
    }

    /**
     * Get statistic.
     *
     * @param  int    $productID
     * @param  string $endDate
     * @param  int    $days
     * @access public
     * @return void
     */
    public function getStatistic($productID = 0, $endDate = '', $days = 30)
    {
        $startDate = '';
        if(empty($endDate)) $endDate = date('Y-m-d');

        $dateArr = array();
        for($day = $days - 1; $day >= 0; $day--)
        {
            $time = strtotime(-$day . ' day', strtotime($endDate));
            $date = date('m/d', $time);
            $dateArr[$date] = new stdClass();
            $dateArr[$date]->num  = 0;
            $dateArr[$date]->date = $date;

            if($day == $days -1) $startDate = date('Y-m-d', $time) . ' 00:00:00';
        }

        $dateFields = array('openedDate', 'resolvedDate', 'closedDate');
        $staticData = array();
        foreach($dateFields as $field)
        {
            $bugCount = $this->dao->select("count(id) as num, date_format($field, '%m/%d') as date")->from(TABLE_BUG)
                ->where('product')->eq($productID)
                ->andWhere($field)->ne('0000-00-00 00:00:00')
                ->andWhere('deleted')->eq(0)
                ->andWhere($field)->between($startDate, $endDate . ' 23:50:59')
                ->groupBy('date')
                ->fetchAll('date');
            $staticData[$field] = array_merge($dateArr, $bugCount);
        }
        return $staticData;
    }

    /**
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function buildSearchForm($productID, $products, $queryID, $actionURL, $branch = 0)
    {
        $projectID     = $this->lang->navGroup->bug == 'qa' ? 0 : $this->session->project;
        $productParams = ($productID and isset($products[$productID])) ? array($productID => $products[$productID]) : $products;
        $productParams = $productParams + array('all' => $this->lang->all);
        $projectParams = $this->getProjects($productID);
        $projectParams = $projectParams + array('all' => $this->lang->bug->allProject);

        /* Get all modules. */
        $modules = array();
        $this->loadModel('tree');
        if($productID) $modules = $this->tree->getOptionMenu($productID, 'bug', 0, $branch);
        if(!$productID)
        {
            foreach($products as $id => $productName) $modules += $this->tree->getOptionMenu($id, 'bug');
        }

        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['params']['project']['values']       = $projectParams;
        $this->config->bug->search['params']['product']['values']       = $productParams;
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs($productID);
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($productID, 0, 'id_desc', $projectID);
        $this->config->bug->search['params']['severity']['values']      = array(0 => '') + $this->lang->bug->severityList; //Fix bug #939.
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($productID, 'all', 'withbranch|releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = $this->lang->product->branch;
            $this->config->bug->search['params']['branch']['values']  = array('' => '', 0 => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, 'noempty') + array('all' => $this->lang->branch->all);
        }

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * Process the openedBuild and resolvedBuild fields for bugs.
     *
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function processBuildForBugs($bugs)
    {
        $productIdList = array();
        foreach($bugs as $bug) $productIdList[$bug->product] = $bug->product;
        $builds = $this->loadModel('build')->getBuildPairs(array_unique($productIdList), 'all', 'noterminate, nodone, hasdeleted');

        /* Process the openedBuild and resolvedBuild fields. */
        foreach($bugs as $key => $bug)
        {
            $openBuildIdList = explode(',', $bug->openedBuild);
            $openedBuild = '';
            foreach($openBuildIdList as $buildID)
            {
                $openedBuild .= isset($builds[$buildID]) ? $builds[$buildID] : $buildID;
                $openedBuild .= ',';
            }
            $bug->openedBuild   = rtrim($openedBuild, ',');
            $bug->resolvedBuild = isset($builds[$bug->resolvedBuild]) ? $builds[$bug->resolvedBuild] : $bug->resolvedBuild;
        }
        return $bugs;
    }

    /**
     * Extract accounts from some bugs.
     *
     * @param  int    $bugs
     * @access public
     * @return array
     */
    public function extractAccountsFromList($bugs)
    {
        $accounts = array();
        foreach($bugs as $bug)
        {
            if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
            if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
            if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
            if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
            if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /**
     * Extract accounts from a bug.
     *
     * @param  object    $bug
     * @access public
     * @return array
     */
    public function extractAccountsFromSingle($bug)
    {
        $accounts = array();
        if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
        if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
        if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
        if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
        if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        return array_unique($accounts);
    }

    /**
     * Get user bugs.
     *
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $limit
     * @param  object $pager
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getUserBugs($account, $type = 'assignedTo', $orderBy = 'id_desc', $limit = 0, $pager = null, $executionID = 0, $queryID = 0)
    {
        $moduleName = $this->app->rawMethod == 'work' ? 'workBug' : 'contributeBug';
        $queryName  = $moduleName . 'Query';
        $formName   = $moduleName . 'Form';
        $bugIDList  = array();
        if($moduleName == 'contributeBug')
        {
            $bugsAssignedByMe = $this->loadModel('my')->getAssignedByMe($account, 0, '', $orderBy, 'bug');
            foreach($bugsAssignedByMe as $bugID => $bug) $bugIDList[$bugID] = $bugID;
        }

        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($formName, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->$queryName == false) $this->session->set($queryName, ' 1 = 1');
        }
        $query = $this->session->$queryName;
        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);

        if($type != 'bySearch' and !$this->loadModel('common')->checkField(TABLE_BUG, $type)) return array();
        return $this->dao->select("t1.*, t2.name AS productName, t2.shadow, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) AS severityOrder")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($type == 'bySearch')->andWhere($query)->fi()
            ->beginIF($executionID)->andWhere('t1.execution')->eq($executionID)->fi()
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all' and $type != 'bySearch')->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'bySearch' and $moduleName == 'workBug')->andWhere("t1.assignedTo")->eq($account)->fi()
            ->beginIF($type == 'assignedTo' and $moduleName == 'workBug')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'bySearch' and $moduleName == 'contributeBug')
            ->andWhere('t1.openedBy', 1)->eq($account)
            ->orWhere('t1.closedBy')->eq($account)
            ->orWhere('t1.resolvedBy')->eq($account)
            ->orWhere('t1.id')->in($bugIDList)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get bug pairs of a user.
     *
     * @param  int       $account
     * @param  bool      $appendProduct
     * @param  int       $limit
     * @param  array     $skipProductIDList
     * @param  array     $skipExecutionIDList
     * @param  int|array $appendBugID
     * @access public
     * @return array
     */
    public function getUserBugPairs($account, $appendProduct = true, $limit = 0, $skipProductIDList = array(), $skipExecutionIDList = array(), $appendBugID = 0)
    {
        $deletedProjectIDList = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq(1)->fetchPairs('id', 'id');

        $bugs = array();
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as product')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.status')->ne('closed')
            ->beginIF(!empty($deletedProjectIDList))->andWhere('t1.execution')->notin($deletedProjectIDList)->fi()
            ->beginIF(!empty($skipProductIDList))->andWhere('t1.product')->notin($skipProductIDList)->fi()
            ->beginIF(!empty($skipExecutionIDList))->andWhere('t1.execution')->notin($skipExecutionIDList)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF(!empty($appendBugID))->orWhere('t1.id')->in($appendBugID)->fi()
            ->orderBy('id desc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->query();
        while($bug = $stmt->fetch())
        {
            if($appendProduct) $bug->title = $bug->product . ' / ' . $bug->title;
            $bugs[$bug->id] = $bug->title;
        }
        return $bugs;
    }

    /**
     * Get bugs of a project.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $build
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  string $excludeBugs
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectBugs($projectID, $productID = 0, $branchID = 0, $build = 0, $type = '', $param = 0, $orderBy = 'id_desc', $excludeBugs = '', $pager = null)
    {
        $type = strtolower($type);
        if(strpos($orderBy, 'pri_') !== false) $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        if(strpos($orderBy, 'severity_') !== false) $orderBy = str_replace('severity_', 'severityOrder_', $orderBy);

        if($type == 'bysearch')
        {
            $queryID = (int)$param;
            if($this->session->projectBugQuery == false) $this->session->set('projectBugQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('projectBugQuery', $query->sql);
                    $this->session->set('projectBugForm', $query->form);
                }
            }

            $bugQuery = $this->getBugQuery($this->session->projectBugQuery);

            $bugs = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
                ->where($bugQuery)
                ->andWhere('project')->eq((int)$projectID)
                ->andWhere('deleted')->eq(0)
                ->beginIF($excludeBugs)->andWhere('id')->notIN($excludeBugs)->fi()
                ->beginIF(!empty($productID) and strpos($bugQuery, 'product') === false and strpos($bugQuery, '`product` IN') === false)->andWhere('product')->eq($productID)->fi()
                ->beginIF(!empty($productID) and strpos($bugQuery, 'product') === false and strpos($bugQuery, '`product` IN') === false and $branchID != 'all')->andWhere('branch')->eq($branchID)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $bugs = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) as severityOrder")->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module=t2.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF(empty($build))->andWhere('t1.project')->eq($projectID)->fi()
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF(!empty($productID) and $branchID != 'all')->andWhere('t1.branch')->eq($branchID)->fi()
                ->beginIF($type == 'unresolved')->andWhere('t1.status')->eq('active')->fi()
                ->beginIF($type == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
                ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
                ->beginIF($type == 'openedbyme')->andWhere('t1.openedBy')->eq($this->app->user->account)->fi()
                ->beginIF(!empty($param))->andWhere('t2.path')->like("%,$param,%")->andWhere('t2.deleted')->eq(0)->fi()
                ->beginIF($build)->andWhere("CONCAT(',', t1.openedBuild, ',') like '%,$build,%'")->fi()
                ->beginIF($excludeBugs)->andWhere('t1.id')->notIN($excludeBugs)->fi()
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);

        return $bugs;
    }

    /**
     * Get bugs of a execution.
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  int          $branchID
     * @param  string|array $builds
     * @param  string       $type
     * @param  int          $param
     * @param  string       $orderBy
     * @param  string       $excludeBugs
     * @param  object       $pager
     * @access public
     * @return array
     */
    public function getExecutionBugs($executionID, $productID = 0, $branchID = 'all', $builds = 0, $type = '', $param = 0, $orderBy = 'id_desc', $excludeBugs = '', $pager = null)
    {
        $type = strtolower($type);
        if(strpos($orderBy, 'pri_') !== false) $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        if(strpos($orderBy, 'severity_') !== false) $orderBy = str_replace('severity_', 'severityOrder_', $orderBy);

        if($type == 'bysearch')
        {
            $queryID = (int)$param;
            if($this->session->executionBugQuery == false) $this->session->set('executionBugQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('executionBugQuery', $query->sql);
                    $this->session->set('executionBugForm', $query->form);
                }
            }

            $bugQuery = $this->getBugQuery($this->session->executionBugQuery);

            $bugs = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
                ->where($bugQuery)
                ->andWhere('execution')->eq((int)$executionID)
                ->andWhere('deleted')->eq(0)
                ->beginIF($excludeBugs)->andWhere('id')->notIN($excludeBugs)->fi()
                ->beginIF(!empty($productID) and strpos($bugQuery, 'product') === false and strpos($bugQuery, '`product` IN') === false)->andWhere('product')->eq($productID)->fi()
                ->beginIF(!empty($productID) and $branchID !== 'all' and strpos($bugQuery, 'product') === false and strpos($bugQuery, '`product` IN') === false)->andWhere('branch')->eq($branchID)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $condition = '';
            if($builds)
            {
                if(!is_array($builds)) $builds = explode(',', $builds);

                $conditions = array();
                foreach($builds as $build)
                {
                    if($build) $conditions[] = "FIND_IN_SET('$build', t1.openedBuild)";
                }
                $condition = join(' OR ', $conditions);
                $condition = "($condition)";
            }
            $bugs = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) as severityOrder")->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module=t2.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF(!empty($productID) and $branchID !== 'all')->andWhere('t1.branch')->eq($branchID)->fi()
                ->beginIF(empty($builds))->andWhere('t1.execution')->eq($executionID)->fi()
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF($type == 'unresolved')->andWhere('t1.status')->eq('active')->fi()
                ->beginIF($type == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
                ->beginIF($condition)->andWhere("$condition")->fi()
                ->beginIF(!empty($param))->andWhere('t2.path')->like("%,$param,%")->andWhere('t2.deleted')->eq(0)->fi()
                ->beginIF($excludeBugs)->andWhere('t1.id')->notIN($excludeBugs)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);

        return $bugs;
    }

    /**
     * Get product left bugs.
     *
     * @param  int|string $buildIdList
     * @param  int        $productID
     * @param  int        $branch
     * @param  string     $linkedBugs
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getProductLeftBugs($buildIdList, $productID, $branch = '', $linkedBugs = '', $pager = null)
    {
        $executionIdList = $this->getLinkedExecutionByIdList($buildIdList);
        if(empty($executionIdList)) return array();

        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->fetchAll();
        $minBegin   = '';
        $maxEnd     = '';
        foreach($executions as $execution)
        {
            if(empty($minBegin) or $minBegin > $execution->begin) $minBegin = $execution->begin;
            if(empty($maxEnd)   or $maxEnd   < $execution->end)   $maxEnd   = $execution->end;
        }

        $beforeBuilds = $this->dao->select('t1.id')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.status')->ne('done')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.date')->lt($minBegin)
            ->fetchPairs('id', 'id');

        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->andWhere('toStory')->eq(0)
            ->andWhere('openedDate')->ge($minBegin)
            ->andWhere('openedDate')->le($maxEnd)
            ->andWhere("(status = 'active' OR resolvedDate > '{$maxEnd}')")
            ->andWhere('openedBuild')->notin($beforeBuilds)
            ->beginIF($linkedBugs)->andWhere('id')->notIN($linkedBugs)->fi()
            ->beginIF($branch !== '')->andWhere('branch')->in("0,$branch")->fi()
            ->page($pager)
            ->fetchAll();

        return $bugs;
    }

    /**
     * get Product Bug Pairs
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $search
     * @param  int        $limit
     * @access public
     * @return void
     */
    public function getProductBugPairs($productID, $branch = '', $search = '', $limit = 0)
    {
        $bugs = $this->dao->select("id, CONCAT(id, ':', title) AS title")->from(TABLE_BUG)
            ->where('product')->eq((int)$productID)
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->beginIF($branch !== '')->andWhere('branch')->in($branch)->fi()
            ->beginIF(strlen(trim($search)))
            ->andWhere('id', true)->like('%' . $search . '%')
            ->orWhere('title')->like('%' . $search . '%')
            ->markRight(1)
            ->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchPairs();

        return array('' => '') + $bugs;
    }

    /**
     * get Product member pairs.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function getProductMemberPairs($productID, $branchID = '')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getTeamMembersPairs();

        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, $branchID);

        $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->in(array_keys($projects))
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t2.deleted')->eq(0)
            ->fi()
            ->fetchAll('account');

        if(!$users) return array('' => '');

        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $users[$account] =  $firstLetter . ($user->realname ? $user->realname : $user->account);
        }

        $users = $this->loadModel('user')->processAccountSort($users);
        return array('' => '') + $users;
    }

    /**
     * Get bugs according to buildID and productID.
     *
     * @param  int|string $buildIdList
     * @param  int        $productID
     * @param  string     $branch
     * @param  string     $linkedBugs
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getReleaseBugs($buildIdList, $productID, $branch = 0, $linkedBugs = '', $pager = null)
    {
        $executionIdList = $this->getLinkedExecutionByIdList($buildIdList);
        if(empty($executionIdList)) return array();

        $executions = $this->dao->select('id,type,begin')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->fetchAll('id');
        $condition  = 'execution NOT ' . helper::dbIN($executionIdList);
        $minBegin   = '';
        foreach($executions as $execution)
        {
            if(empty($minBegin) or $minBegin > $execution->begin) $minBegin = $execution->begin;
            $condition .= " OR (`execution` = '{$execution->id}' AND openedDate < '{$execution->begin}')";
        }

        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('resolvedDate')->ge($minBegin)
            ->andWhere('resolution')->ne('postponed')
            ->andWhere('product')->eq($productID)
            ->beginIF($linkedBugs)->andWhere('id')->notIN($linkedBugs)->fi()
            ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
            ->andWhere("($condition)")
            ->andWhere('deleted')->eq(0)
            ->orderBy('openedDate ASC')
            ->page($pager)
            ->fetchAll('id');
        return $bugs;
    }

    /**
     * Get linked execution by build id list.
     *
     * @param  string $buildIdList
     * @access public
     * @return array
     */
    public function getLinkedExecutionByIdList($buildIdList)
    {
        $builds = $this->dao->select('id,execution,builds')->from(TABLE_BUILD)->where('id')->in($buildIdList)->fetchAll('id');

        $executionIdList   = array();
        $linkedBuildIdList = array();
        foreach($builds as $build)
        {
            if($build->builds) $linkedBuildIdList = array_merge($linkedBuildIdList, explode(',', $build->builds));

            if(empty($build->execution)) continue;
            $executionIdList[$build->execution] = $build->execution;
        }
        if($linkedBuildIdList)
        {
            $linkedBuilds = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in(array_unique($linkedBuildIdList))->fetchAll('id');
            foreach($linkedBuilds as $build)
            {
                if(empty($build->execution)) continue;
                $executionIdList[$build->execution] = $build->execution;
            }
        }
        return $executionIdList;
    }

    /**
     * Get bugs of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getStoryBugs($storyID, $executionID = 0)
    {
        return $this->dao->select('id, title, pri, type, status, assignedTo, resolvedBy, resolution')
            ->from(TABLE_BUG)
            ->where('story')->eq((int)$storyID)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get case bugs.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function getCaseBugs($runID, $caseID = 0, $version = 0)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('1=1')
            ->beginIF($runID)->andWhere('`result`')->eq($runID)->fi()
            ->beginIF($runID == 0 and $caseID)->andWhere('`case`')->eq($caseID)->fi()
            ->beginIF($version)->andWhere('`caseVersion`')->eq($version)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get counts of some stories' bugs.
     *
     * @param  array  $stories
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function getStoryBugCounts($stories, $executionID = 0)
    {
        if(empty($stories)) return array();
        $bugCounts = $this->dao->select('story, COUNT(*) AS bugs')
            ->from(TABLE_BUG)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->groupBy('story')
            ->fetchPairs();
        foreach($stories as $storyID) if(!isset($bugCounts[$storyID])) $bugCounts[$storyID] = 0;
        return $bugCounts;
    }

    /**
     * Get bug info from a result.
     *
     * @param  int    $resultID
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return array
     */
    public function getBugInfoFromResult($resultID, $caseID = 0, $version = 0, $stepIdList = '')
    {
        $title    = '';
        $bugSteps = '';
        $steps    = explode('_', trim($stepIdList, '_'));

        $result = $this->dao->findById($resultID)->from(TABLE_TESTRESULT)->fetch();
        if($caseID > 0)
        {
            $run = new stdclass();
            $run->case = $this->loadModel('testcase')->getById($caseID, $result->version);
        }
        else
        {
            $run = $this->loadModel('testtask')->getRunById($result->run);
        }

        $title       = $run->case->title;
        $caseSteps   = $run->case->steps;
        $stepResults = unserialize($result->stepResults);
        if($run->case->precondition != '')
        {
            $bugSteps = "<p>[" . $this->lang->testcase->precondition . "]</p>" . "\n" . $run->case->precondition;
        }

        if(!empty($stepResults))
        {
            $bugStep   = '';
            $bugResult = isset($stepResults[0]) ? $stepResults[0]['real'] : '';
            $bugExpect = '';
            foreach($steps as $stepId)
            {
                if(!isset($caseSteps[$stepId])) continue;
                $step = $caseSteps[$stepId];

                $i = $this->getCaseStepIndex($step);

                $stepDesc   = str_replace("\n", "<br />", $step->desc);
                $stepExpect = str_replace("\n", "<br />", $step->expect);
                $stepResult = (!isset($stepResults[$stepId]) or empty($stepResults[$stepId]['real'])) ? '' : $stepResults[$stepId]['real'];

                $bugStep   .= $i . '. ' . $stepDesc . "<br />";
                $bugResult .= $i . '. ' . $stepResult . "<br />";
                $bugExpect .= $i . '. ' . $stepExpect . "<br />";
            }

            $bugSteps .= $bugStep   ? str_replace('<br/>', '', $this->lang->bug->tplStep)   . $bugStep   : $this->lang->bug->tplStep;
            $bugSteps .= $bugResult ? str_replace('<br/>', '', $this->lang->bug->tplResult) . $bugResult : $this->lang->bug->tplResult;
            $bugSteps .= $bugExpect ? str_replace('<br/>', '', $this->lang->bug->tplExpect) . $bugExpect : $this->lang->bug->tplExpect;
        }
        else
        {
            $bugSteps .= $this->lang->bug->tplStep;
            $bugSteps .= $this->lang->bug->tplResult;
            $bugSteps .= $this->lang->bug->tplExpect;
        }

        if(!empty($run->task)) $testtask = $this->loadModel('testtask')->getById($run->task);
        $executionID = isset($testtask->execution) ? $testtask->execution : 0;

        if(!$executionID and $caseID > 0) $executionID = isset($run->case->execution) ? $run->case->execution : 0; // Fix feedback #1043.
        if(!$executionID and $this->app->tab == 'execution') $executionID = $this->session->execution;

        return array('title' => $title, 'steps' => $bugSteps, 'storyID' => $run->case->story, 'moduleID' => $run->case->module, 'version' => $run->case->version, 'executionID' => $executionID);
    }

    /**
     * Get report data of bugs per execution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerExecution()
    {
        $datas = $this->dao->select('execution as name, count(execution) as value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('execution')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $executions = $this->loadModel('execution')->getPairs($this->session->project);

        $maxLength = 12;
        if(common::checkNotCN()) $maxLength = 22;
        foreach($datas as $executionID => $data)
        {
            $data->name  = isset($executions[$executionID]) ? $executions[$executionID] : $this->lang->report->undefined;
            $data->title = $data->name;
            if(mb_strlen($data->name, 'UTF-8') > $maxLength) $data->name = mb_substr($data->name, 0, $maxLength, 'UTF-8') . '...';
        }
        return $datas;
    }

    /**
     * Get report data of bugs per build.
     *
     * @access public
     * @return void
     */
    public function getDataOfBugsPerBuild()
    {
        $datas = $this->dao->select('openedBuild as name, count(openedBuild) as value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('openedBuild')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        /* Judge if all product or not. */
        $products = $this->session->product;
        preg_match('/`product` IN \((?P<productIdList>.+)\)/', $this->reportCondition(), $matchs);
        if(!empty($matchs) and isset($matchs['productIdList'])) $products = str_replace('\'', '', $matchs['productIdList']);
        $builds = $this->loadModel('build')->getBuildPairs($products, $branch = 0, $params = 'hasdeleted');

        /* Deal with the situation that a bug maybe associate more than one openedBuild. */
        foreach($datas as $buildIDList => $data)
        {
            $openBuildIDList = explode(',', $buildIDList);
            if(count($openBuildIDList) > 1)
            {
                foreach($openBuildIDList as $buildID)
                {
                    if(isset($datas[$buildID]))
                    {
                        $datas[$buildID]->value += $data->value;
                    }
                    else
                    {
                        if(!isset($datas[$buildID])) $datas[$buildID] = new stdclass();
                        $datas[$buildID]->name  = $buildID;
                        $datas[$buildID]->value = $data->value;
                    }
                }
                unset($datas[$buildIDList]);
            }
        }

        $this->app->loadLang('report');
        foreach($datas as $buildID => $data)
        {
            $data->name = isset($builds[$buildID]) ? $builds[$buildID] : $this->lang->report->undefined;
        }
        ksort($datas);
        return $datas;
    }

    /**
     * Get report data of bugs per module
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerModule()
    {
        $datas = $this->dao->select('module as name, count(module) as value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('module')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas), true, true);
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        return $datas;
    }

    /**
     * Get report data of opened bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerDay()
    {
        return $this->dao->select("DATE_FORMAT(openedDate, '%Y-%m-%d') AS name, COUNT(*) AS value")->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('openedDate')->fetchAll();
    }

    /**
     * Get report data of resolved bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerDay()
    {
        return $this->dao->select("DATE_FORMAT(resolvedDate, '%Y-%m-%d') AS name, COUNT(*) AS value")->from(TABLE_BUG)
            ->where($this->reportCondition())->groupBy('name')
            ->having('name != 0000-00-00')
            ->orderBy('resolvedDate')
            ->fetchAll();
    }

    /**
     * Get report data of closed bugs per day.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerDay()
    {
        return $this->dao->select("DATE_FORMAT(closedDate, '%Y-%m-%d') AS name, COUNT(*) AS value")->from(TABLE_BUG)
            ->where($this->reportCondition())->groupBy('name')
            ->having('name != 0000-00-00')
            ->orderBy('closedDate')->fetchAll();
    }

    /**
     * Get report data of openeded bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerUser()
    {
        $datas = $this->dao->select('openedBy AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of resolved bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerUser()
    {
        $datas = $this->dao->select('resolvedBy AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)->where($this->reportCondition())
            ->andWhere('resolvedBy')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of closed bugs per user.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerUser()
    {
        $datas = $this->dao->select('closedBy AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)
            ->where($this->reportCondition())
            ->andWhere('closedBy')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of bugs per severity.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerSeverity()
    {
        $datas = $this->dao->select('severity AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $severity => $data) if(isset($this->lang->bug->severityList[$severity])) $data->name = $this->lang->bug->report->bugsPerSeverity->graph->xAxisName . ':' . $this->lang->bug->severityList[$severity];
        return $datas;
    }

    /**
     * Get report data of bugs per resolution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerResolution()
    {
        $datas = $this->dao->select('resolution AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)
            ->where($this->reportCondition())
            ->andWhere('resolution')->ne('')
            ->groupBy('name')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $resolution => $data) if(isset($this->lang->bug->resolutionList[$resolution])) $data->name = $this->lang->bug->resolutionList[$resolution];
        return $datas;
    }

    /**
     * Get report data of bugs per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerStatus()
    {
        $datas = $this->dao->select('status AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) if(isset($this->lang->bug->statusList[$status])) $data->name = $this->lang->bug->statusList[$status];
        return $datas;
    }

    /**
     * Get report data of bugs per pri
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerPri()
    {
        $datas = $this->dao->select('pri AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) $data->name = $this->lang->bug->report->bugsPerPri->graph->xAxisName . ':' . zget($this->lang->bug->priList, $data->name);
        return $datas;
    }

    /**
     * Get report data of bugs per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerActivatedCount()
    {
        $datas = $this->dao->select('activatedCount AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $data) $data->name = $this->lang->bug->report->bugsPerActivatedCount->graph->xAxisName . ':' . $data->name;
        return $datas;
    }

    /**
     * Get report data of bugs per type.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerType()
    {
        $datas = $this->dao->select('type AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $type => $data) if(isset($this->lang->bug->typeList[$type])) $data->name = $this->lang->bug->typeList[$type];
        return $datas;
    }

    /**
     * getDataOfBugsPerAssignedTo
     *
     * @access public
     * @return void
     */
    public function getDataOfBugsPerAssignedTo()
    {
        $datas = $this->dao->select('assignedTo AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)->where($this->reportCondition())
            ->groupBy('name')
            ->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string    $chartType
     * @access public
     * @return void
     */
    public function mergeChartOption($chartType)
    {
        $chartOption  = $this->lang->bug->report->$chartType;
        $commonOption = $this->lang->bug->report->options;

        $chartOption->graph->caption = $this->lang->bug->report->charts[$chartType];
        if(!isset($chartOption->type))   $chartOption->type   = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* 合并配置。*/
        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
    }

    /**
     * Return the file => label pairs of some fields.
     *
     * @param  string    $fields
     * @access public
     * @return array
     */
    public function getFieldPairs($fields)
    {
        $fields = explode(',', $fields);
        foreach($fields as $key => $field)
        {
            $field = trim($field);
            $fields[$field] = $this->lang->bug->$field;
            unset($fields[$key]);
        }
        return $fields;
    }

    /**
     * Get all bugs.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getAllBugs($productIDList, $branch, $modules, $executions, $orderBy, $pager = null, $projectID = 0)
    {
        $bugs = $this->dao->select("t1.*, t2.title as planTitle, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) as severityOrder")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->where('t1.product')->in($productIDList)
            ->beginIF($this->app->tab !== 'qa')->andWhere('t1.execution')->in(array_keys($executions))->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
    }

    /**
     * Get bugs of assign to me.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getByAssigntome($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('assignedTo')->eq($this->app->user->account)
            ->andWhere('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of opened by me.
     *
     * @param  array      $productIDList
     * @param  int|string $branch
     * @param  array      $modules
     * @param  array      $executions
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  int        $projectID
     * @access public
     * @return array
     */
    public function getByOpenedbyme($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*,IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('openedBy')->eq($this->app->user->account)
            ->andWhere('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of resolved by me.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getByResolvedbyme($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*,IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('resolvedBy')->eq($this->app->user->account)
            ->andWhere('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of nobody to do.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getByAssigntonull($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {

        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('assignedTo')->eq('')
            ->andWhere('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get unconfirmed bugs.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return void
     */
    public function getUnconfirmed($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('product')->in($productIDList)
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere('confirmed')->eq(0)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs the overdueBugs is active or unclosed.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $status
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getOverdueBugs($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('product')->in($productIDList)
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->andWhere('status')->eq('active')
            ->andWhere('deleted')->eq(0)
            ->andWhere('deadline')->ne('0000-00-00')
            ->andWhere('deadline')->lt(helper::today())
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs the status is active or unclosed.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $status
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getByStatus($productIDList, $branch, $modules, $executions, $status, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('product')->in($productIDList)
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($status == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($status == 'unresolved')->andWhere('status')->eq('active')->fi()
            ->beginIF($status == 'toclosed')->andWhere('status')->eq('resolved')->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get unclosed bugs for long time.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getByLonglifebugs($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        $lastEditedDate = date(DT_DATE1, time() - $this->config->bug->longlife * 24 * 3600);
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('lastEditedDate')->lt($lastEditedDate)
            ->andWhere('product')->in($productIDList)
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->andWhere('openedDate')->lt($lastEditedDate)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->andWhere('status')->ne('closed')->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get postponed bugs.
     *
     * @param  array      $productIDList
     * @param  int|sting  $branch
     * @param  array      $modules
     * @param  array      $executions
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  int        $projectID
     * @access public
     * @return array
     */
    public function getByPostponedbugs($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('resolution')->eq('postponed')
            ->andWhere('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs need confirm.
     *
     * @param  array      $productIDList
     * @param  int|string $branch
     * @param  array      $modules
     * @param  array      $executions
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  int        $projectID
     * @access public
     * @return array
     */
    public function getByNeedconfirm($productIDList, $branch, $modules, $executions, $orderBy, $pager, $projectID)
    {
        return $this->dao->select("t1.*, t2.title AS storyTitle, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) as severityOrder")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where("t2.status = 'active'")
            ->andWhere('t1.product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('t1.execution')->in(array_keys($executions))->fi()
            ->andWhere('t2.version > t1.storyVersion')
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get by assigned by me.
     * @param  array      $productIDList
     * @param  int|string $branch
     * @param  array      $modules
     * @param  array      $executions
     * @param  string     $sort
     * @param  object     $pager
     * @param  int        $projectID
     *
     * @access public
     * @return array
     */
    public function getByAssignedbyme($productIDList, $branch, $modules, $executions, $sort, $pager, $projectID)
    {
        $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('bug')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($this->app->user->account)->fetchPairs('objectID', 'objectID');
        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('product')->in($productIDList)
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in(array_keys($executions))->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('closed')
            ->andWhere('id')->in($actionIDList)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($sort)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get by Sonarqube id.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return array
     */
    public function getBySonarqubeID($sonarqubeID)
    {
        return $this->dao->select('issueKey')->from(TABLE_BUG)
            ->where('issueKey')->like("$sonarqubeID:%")
            ->fetchPairs();
    }

    /**
     * Get bugs by search.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  string      $excludeBugs
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getBySearch($productIDList, $branch = 0, $queryID = 0, $orderBy = '', $excludeBugs = '', $pager = null, $projectID = 0)
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

        $bugQuery = $this->getBugQuery($this->session->bugQuery);

        /* If search criteria don't have products, append the selected product from the top left dropdown-menu. */
        if(is_array($productIDList)) $productIDList = implode(',', $productIDList);
        if(strpos($bugQuery, '`product`') === false)
        {
            $bugQuery .= ' AND `product` IN (' . $productIDList . ')';
        }
        else
        {
            $productParis  = $this->loadModel('product')->getPairs('', 0, '', 'all');
            $productIDList = array_keys($productParis);

            if(!empty($productIDList))
            {
                $productIDList = implode(',', $productIDList);
                $bugQuery     .= ' AND `product` IN (' . $productIDList . ')';
            }
        }

        $allBranch = "`branch` = 'all'";
        $branch    = trim($branch, ',');
        if(strpos($branch, ',') !== false) $branch = str_replace(',', "','", $branch);
        if($branch !== 'all' and strpos($bugQuery, '`branch` =') === false) $bugQuery .= " AND `branch` in('0','$branch')";
        if(strpos($bugQuery, $allBranch) !== false) $bugQuery = str_replace($allBranch, '1', $bugQuery);

        $bugs = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)->where($bugQuery)
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->beginIF($excludeBugs)->andWhere('id')->notIN($excludeBugs)->fi()
            ->beginIF($projectID)
            ->andWhere('project', true)->eq($projectID)
            ->orWhere('project')->eq(0)
            ->andWhere('openedBuild')->eq('trunk')
            ->markRight(1)
            ->fi()

            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
        return $bugs;
    }

    /**
     * Get bugs to review.
     *
     * @param  array       $productIDList
     * @param  int|string  $branch
     * @param  array       $modules
     * @param  array       $executions
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int         $projectID
     * @access public
     * @return array
     */
    public function getReviewBugs($productIDList, $branch, $modules, $executions, $orderBy, $pager = null, $projectID = 0)
    {
        $bugs = $this->dao->select("t1.*, t2.title as planTitle, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->where('t1.product')->in($productIDList)
            ->beginIF($this->app->tab !== 'qa')->andWhere('t1.execution')->in(array_keys($executions))->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->beginIF(!$this->app->user->admin)->andWhere('t1.project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
    }

    /**
     * Get bug query.
     *
     * @param  string $bugQuery
     * @access public
     * @return string
     */
    public function getBugQuery($bugQuery)
    {
        $allProduct = "`product` = 'all'";
        if(strpos($bugQuery, $allProduct) !== false)
        {
            $products = $this->app->user->view->products;
            $bugQuery = str_replace($allProduct, '1', $bugQuery);
            $bugQuery = $bugQuery . ' AND `product` ' . helper::dbIN($products);
        }

        $allProject = "`project` = 'all'";
        if(strpos($bugQuery, $allProject) !== false)
        {
            $projectIDList = $this->getAllProjectIds();
            if(is_array($projectIDList)) $projectIDList = implode(',', $projectIDList);
            $bugQuery = str_replace($allProject, '1', $bugQuery);
            $bugQuery = $bugQuery . ' AND `project` in (' . $projectIDList . ')';
        }

        /* Fix bug #2878. */
        if(strpos($bugQuery, ' `resolvedDate` ') !== false) $bugQuery = str_replace(' `resolvedDate` ', " `resolvedDate` != '0000-00-00 00:00:00' AND `resolvedDate` ", $bugQuery);
        if(strpos($bugQuery, ' `closedDate` ') !== false)   $bugQuery = str_replace(' `closedDate` ', " `closedDate` != '0000-00-00 00:00:00' AND `closedDate` ", $bugQuery);
        if(strpos($bugQuery, ' `story` ') !== false)
        {
            preg_match_all("/`story`[ ]+(NOT *)?LIKE[ ]+'%([^%]*)%'/Ui", $bugQuery, $out);
            if(!empty($out[2]))
            {
                foreach($out[2] as $searchValue)
                {
                    $story = $this->dao->select('id')->from(TABLE_STORY)->alias('t1')
                        ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
                        ->where('t1.title')->like("%$searchValue%")
                        ->orWhere('t1.keywords')->like("%$searchValue%")
                        ->orWhere('t2.spec')->like("%$searchValue%")
                        ->orWhere('t2.verify')->like("%$searchValue%")
                        ->fetchPairs('id');
                    if(empty($story)) $story = array(0);

                    $bugQuery = preg_replace("/`story`[ ]+(NOT[ ]*)?LIKE[ ]+'%$searchValue%'/Ui", '`story` $1 IN (' . implode(',', $story) .')', $bugQuery);
                }
            }
            $bugQuery .= ' AND `story` != 0';
        }
        return $bugQuery;
    }

    /**
     * Form customed bugs.
     *
     * @param  array    $bugs
     * @access public
     * @return array
     */
    public function formCustomedBugs($bugs)
    {
        /* Get related objects id lists. */
        $relatedModuleIdList   = array();
        $relatedStoryIdList    = array();
        $relatedTaskIdList     = array();
        $relatedCaseIdList     = array();
        $relatedExecutionIdList  = array();

        foreach($bugs as $bug)
        {
            $relatedModuleIdList[$bug->module]       = $bug->module;
            $relatedStoryIdList[$bug->story]         = $bug->story;
            $relatedTaskIdList[$bug->task]           = $bug->task;
            $relatedCaseIdList[$bug->case]           = $bug->case;
            $relatedExecutionIdList[$bug->execution] = $bug->execution;

            /* Get related objects title or names. */
            $relatedModules    = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedStories    = $this->dao->select('id, title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedTasks      = $this->dao->select('id, name')->from(TABLE_TASK)->where('id')->in($relatedTaskIdList)->fetchPairs();
            $relatedCases      = $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
            $relatedExecutions = $this->dao->select('id, name')->from(TABLE_EXECUTION)->where('id')->in($relatedExecutionIdList)->fetchPairs();

            /* fill some field with useful value. */
            if(isset($relatedModules[$bug->module]))       $bug->module    = $relatedModules[$bug->module];
            if(isset($relatedStories[$bug->story]))        $bug->story     = $relatedStories[$bug->story];
            if(isset($relatedTasks[$bug->task]))           $bug->task      = $relatedTasks[$bug->task];
            if(isset($relatedCases[$bug->case]))           $bug->case      = $relatedCases[$bug->case];
            if(isset($relatedExecutions[$bug->execution])) $bug->execution = $relatedExecutions[$bug->execution];
        }
        return $bugs;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  string $bug
     * @param  string $action
     * @param  string $module
     * @access public
     * @return void
     */
    public static function isClickable($object, $action, $module = 'bug')
    {
        $action = strtolower($action);

        if($module == 'bug' && $action == 'confirmbug') return $object->status == 'active' and $object->confirmed == 0;
        if($module == 'bug' && $action == 'resolve')    return $object->status == 'active';
        if($module == 'bug' && $action == 'close')      return $object->status == 'resolved';
        if($module == 'bug' && $action == 'activate')   return $object->status != 'active';
        if($module == 'bug' && $action == 'tostory')    return $object->status == 'active';

        return true;
    }

    /**
     * Get report condition from session.
     *
     * @access public
     * @return void
     */
    public function reportCondition()
    {
        if(isset($_SESSION['bugQueryCondition']))
        {
            if(!$this->session->bugOnlyCondition) return 'id in (' . preg_replace('/SELECT .* FROM/', 'SELECT t1.id FROM', $this->session->bugQueryCondition) . ')';
            return $this->session->bugQueryCondition;
        }
        return true;
    }

    /**
     * Link bug to build and release
     *
     * @param  string|array    $bugs
     * @param  int    $resolvedBuild
     * @access public
     * @return bool
     */
    public function linkBugToBuild($bugs, $resolvedBuild)
    {
        if(empty($resolvedBuild) or $resolvedBuild == 'trunk') return true;
        if(is_array($bugs)) $bugs = join(',', $bugs);

        $build     = $this->dao->select('id,product,bugs')->from(TABLE_BUILD)->where('id')->eq($resolvedBuild)->fetch();
        $buildBugs = $build->bugs . ',' . $bugs;
        $buildBugs = explode(',', trim($buildBugs, ','));
        $buildBugs = array_unique($buildBugs);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq(join(',', $buildBugs))->where('id')->eq($resolvedBuild)->exec();

        $release = $this->dao->select('id,bugs')->from(TABLE_RELEASE)->where('product')->eq($build->product)->andWhere("(FIND_IN_SET('$resolvedBuild', build) or shadow = $resolvedBuild)")->andWhere('deleted')->eq('0')->fetch();
        if($release)
        {
            $releaseBugs = $release->bugs . ',' . $bugs;
            $releaseBugs = explode(',', trim($releaseBugs, ','));
            $releaseBugs = array_unique($releaseBugs);
            $this->dao->update(TABLE_RELEASE)->set('bugs')->eq(join(',', $releaseBugs))->where('id')->eq($release->id)->exec();
        }

        return true;
    }

    /**
     * Print cell data.
     *
     * @param  object $col
     * @param  object $bug
     * @param  array  $users
     * @param  array  $builds
     * @param  array  $branches
     * @param  array  $modulePairs
     * @param  array  $executions
     * @param  array  $plans
     * @param  array  $stories
     * @param  array  $tasks
     * @param  string $mode
     * @param  array  $projectPairs
     *
     * @access public
     * @return void
     */
    public function printCell($col, $bug, $users, $builds, $branches, $modulePairs, $executions = array(), $plans = array(), $stories = array(), $tasks = array(), $mode = 'datatable', $projectPairs = array())
    {
        /* Check the product is closed. */
        $canBeChanged = common::canBeChanged('bug', $bug);

        $canBatchEdit         = ($canBeChanged and common::hasPriv('bug', 'batchEdit'));
        $canBatchConfirm      = ($canBeChanged and common::hasPriv('bug', 'batchConfirm'));
        $canBatchClose        = common::hasPriv('bug', 'batchClose');
        $canBatchActivate     = ($canBeChanged and common::hasPriv('bug', 'batchActivate'));
        $canBatchChangeBranch = ($canBeChanged and common::hasPriv('bug', 'batchChangeBranch'));
        $canBatchChangeModule = ($canBeChanged and common::hasPriv('bug', 'batchChangeModule'));
        $canBatchResolve      = ($canBeChanged and common::hasPriv('bug', 'batchResolve'));
        $canBatchAssignTo     = ($canBeChanged and common::hasPriv('bug', 'batchAssignTo'));

        $canBatchAction = ($canBatchEdit or $canBatchConfirm or $canBatchClose or $canBatchActivate or $canBatchChangeBranch or $canBatchChangeModule or $canBatchResolve or $canBatchAssignTo);

        $canView = common::hasPriv('bug', 'view');

        $hasCustomSeverity = false;
        foreach($this->lang->bug->severityList as $severityKey => $severityValue)
        {
            if(!empty($severityKey) and (string)$severityKey != (string)$severityValue)
            {
                $hasCustomSeverity = true;
                break;
            }
        }

        $bugLink     = helper::createLink('bug', 'view', "bugID=$bug->id");
        $account     = $this->app->user->account;
        $id          = $col->id;
        $os          = '';
        $browser     = '';
        $osList      = explode(',', $bug->os);
        $browserList = explode(',', $bug->browser);
        foreach($osList as $value)
        {
            if(empty($value)) continue;
            $os .= $this->lang->bug->osList[$value] . ',';
        }
        foreach($browserList as $value)
        {
            if(empty($value)) continue;
            $browser .= zget($this->lang->bug->browserList, $value) . ',';
        }
        $os      = trim($os, ',');
        $browser = trim($browser, ',');
        if($col->show)
        {
            $class = "c-$id";
            $title = '';
            switch($id)
            {
                case 'id':
                    $class .= ' cell-id';
                    break;
                case 'status':
                    $class .= ' bug-' . $bug->status;
                    $title  = "title='" . $this->processStatus('bug', $bug) . "'";
                    break;
                case 'confirmed':
                    $class .= ' text-center';
                    break;
                case 'title':
                    $class .= ' text-left';
                    $title  = "title='{$bug->title}'";
                    break;
                case 'type':
                    $title  = "title='" . zget($this->lang->bug->typeList, $bug->type) . "'";
                    break;
                case 'assignedTo':
                    $class .= ' has-btn text-left';
                    if($bug->assignedTo == $account) $class .= ' red';
                    break;
                case 'resolvedBy':
                    $class .= ' c-user';
                    $title  = "title='" . zget($users, $bug->resolvedBy) . "'";
                    break;
                case 'openedBy':
                    $class .= ' c-user';
                    $title  = "title='" . zget($users, $bug->openedBy) . "'";
                    break;
                case 'project':
                    $title = "title='" . zget($projectPairs, $bug->project, '') . "'";
                    break;
                case 'plan':
                    $title = "title='" . zget($plans, $bug->plan, '') . "'";
                    break;
                case 'execution':
                    $title = "title='" . zget($executions, $bug->execution) . "'";
                    break;
                case 'resolvedBuild':
                    $class .= ' text-ellipsis';
                    $title  = "title='" . $bug->resolvedBuild . "'";
                    break;
                case 'os':
                    $class .= ' text-ellipsis';
                    $title  = "title='" . $os . "'";
                    break;
                case 'keywords':
                    $class .= ' text-left';
                    $title  = "title='{$bug->keywords}'";
                    break;
                case 'browser':
                    $class .= ' text-ellipsis';
                    $title  = "title='" . $browser . "'";
                    break;
                case 'deadline':
                    $class .= ' text-center';
                    break;
            }

            if($id == 'deadline' && isset($bug->delay) && $bug->status == 'active') $class .= ' delayed';
            if(strpos(',type,execution,story,plan,task,openedBuild,', ",{$id},") !== false) $class .= ' text-ellipsis';

            echo "<td class='" . $class . "' $title>";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('bug', $bug, $id);
            switch($id)
            {
            case 'id':
                if($canBatchAction)
                {
                    echo html::checkbox('bugIDList', array($bug->id => '')) . html::a(helper::createLink('bug', 'view', "bugID=$bug->id"), sprintf('%03d', $bug->id), '', "data-app='{$this->app->tab}'");
                }
                else
                {
                    printf('%03d', $bug->id);
                }
                break;
            case 'severity':
                $severityValue     = zget($this->lang->bug->severityList, $bug->severity);
                $hasCustomSeverity = !is_numeric($severityValue);
                if($hasCustomSeverity)
                {
                    echo "<span class='label-severity-custom' data-severity='{$bug->severity}' title='" . $severityValue . "'>" . $severityValue . "</span>";
                }
                else
                {
                    echo "<span class='label-severity' data-severity='{$bug->severity}' title='" . $severityValue . "'></span>";
                }
                break;
            case 'pri':
                if($bug->pri)
                {
                    echo "<span class='label-pri label-pri-" . $bug->pri . "' title='" . zget($this->lang->bug->priList, $bug->pri, $bug->pri) . "'>";
                    echo zget($this->lang->bug->priList, $bug->pri, $bug->pri);
                    echo "</span>";
                }
                break;
            case 'confirmed':
                $class = 'confirm' . $bug->confirmed;
                echo "<span class='$class' title='" . zget($this->lang->bug->confirmedList, $bug->confirmed, $bug->confirmed) . "'>" . zget($this->lang->bug->confirmedList, $bug->confirmed, $bug->confirmed) . "</span> ";
                break;
            case 'title':
                $showBranch = isset($this->config->bug->browse->showBranch) ? $this->config->bug->browse->showBranch : 1;
                if(isset($branches[$bug->branch]) and $showBranch) echo "<span class='label label-outline label-badge' title={$branches[$bug->branch]}>{$branches[$bug->branch]}</span> ";
                if($bug->module and isset($modulePairs[$bug->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$bug->module]}</span> ";
                echo $canView ? html::a($bugLink, $bug->title, null, "style='color: $bug->color' data-app={$this->app->tab}") : "<span style='color: $bug->color'>{$bug->title}</span>";
                if($bug->case) echo html::a(helper::createLink('testcase', 'view', "caseID=$bug->case&version=$bug->caseVersion"), "[" . $this->lang->testcase->common  . "#$bug->case]", '', "class='bug' title='$bug->case'");
                break;
            case 'branch':
                echo zget($branches, $bug->branch, '');
                break;
            case 'project':
                echo zget($projectPairs, $bug->project, '');
                break;
            case 'execution':
                echo zget($executions, $bug->execution, '');
                break;
            case 'plan':
                echo zget($plans, $bug->plan, '');
                break;
            case 'story':
                if(isset($stories[$bug->story]))
                {
                    $story = $stories[$bug->story];
                    echo common::hasPriv('story', 'view') ? html::a(helper::createLink('story', 'view', "storyID=$story->id", 'html', true), $story->title, '', "class='iframe'") : $story->title;
                }
                break;
            case 'task':
                if(isset($tasks[$bug->task]))
                {
                    $task = $tasks[$bug->task];
                    echo common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "taskID=$task->id", 'html', true), $task->name, '', "class='iframe'") : $task->name;
                }
                break;
            case 'toTask':
                if(isset($tasks[$bug->toTask]))
                {
                    $task = $tasks[$bug->toTask];
                    echo common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "taskID=$task->id", 'html', true), $task->name, '', "class='iframe'") : $task->name;
                }
                break;
            case 'type':
                echo zget($this->lang->bug->typeList, $bug->type);
                break;
            case 'status':
                echo "<span class='status-bug status-{$bug->status}'>";
                echo $this->processStatus('bug', $bug);
                echo  '</span>';
                break;
            case 'activatedCount':
                echo $bug->activatedCount;
                break;
            case 'activatedDate':
                echo helper::isZeroDate($bug->activatedDate) ? '' : substr($bug->activatedDate, 5, 11);
                break;
            case 'keywords':
                echo $bug->keywords;
                break;
            case 'os':
                echo $os;
                break;
            case 'browser':
                echo $browser;
                break;
            case 'mailto':
                $mailto = explode(',', $bug->mailto);
                foreach($mailto as $account)
                {
                    $account = trim($account);
                    if(empty($account)) continue;
                    echo zget($users, $account) . " &nbsp;";
                }
                break;
            case 'found':
                echo zget($users, $bug->found);
                break;
            case 'openedBy':
                echo zget($users, $bug->openedBy);
                break;
            case 'openedDate':
                echo helper::isZeroDate($bug->openedDate) ? '' : substr($bug->openedDate, 5, 11);
                break;
            case 'openedBuild':
                echo $bug->openedBuild;
                break;
            case 'assignedTo':
                $this->printAssignedHtml($bug, $users);
                break;
            case 'assignedDate':
                echo helper::isZeroDate($bug->assignedDate) ? '' : substr($bug->assignedDate, 5, 11);
                break;
            case 'deadline':
                echo helper::isZeroDate($bug->deadline) ? '' : '<span>' . substr($bug->deadline, 5, 11) . '</span>';
                break;
            case 'resolvedBy':
                echo zget($users, $bug->resolvedBy, $bug->resolvedBy);
                break;
            case 'resolution':
                echo zget($this->lang->bug->resolutionList, $bug->resolution);
                break;
            case 'resolvedDate':
                echo helper::isZeroDate($bug->resolvedDate) ? '' : substr($bug->resolvedDate, 5, 11);
                break;
            case 'resolvedBuild':
                echo $bug->resolvedBuild;
                break;
            case 'closedBy':
                echo zget($users, $bug->closedBy);
                break;
            case 'closedDate':
                echo helper::isZeroDate($bug->closedDate) ? '' : substr($bug->closedDate, 5, 11);
                break;
            case 'lastEditedBy':
                echo zget($users, $bug->lastEditedBy);
                break;
            case 'lastEditedDate':
                echo helper::isZeroDate($bug->lastEditedDate) ? '' : substr($bug->lastEditedDate, 5, 11);
                break;
            case 'actions':
                echo $this->buildOperateMenu($bug, 'browse');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Print assigned html.
     *
     * @param  object $bug
     * @param  array  $users
     * @param  bool   $output
     * @access public
     * @return void
     */
    public function printAssignedHtml($bug, $users, $output = true)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = !empty($bug->assignedTo) ? zget($users, $bug->assignedTo) : $this->lang->bug->noAssigned;
        if(empty($bug->assignedTo)) $btnClass = $btnTextClass = 'assigned-none';
        if($bug->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($bug->assignedTo) and $bug->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $bug->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';

        $assignToLink = helper::createLink('bug', 'assignTo', "bugID=$bug->id", '', true);
        $modalToggle  = $bug->assignedTo == 'closed' ? '' : "data-toggle='modal'";
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $bug->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass' $modalToggle");

        $html = !common::hasPriv('bug', 'assignTo', $bug) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
        if(!$output) return $html;

        echo $html;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $bug
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($bug)
    {
        /* Set toList and ccList. */
        $toList = $bug->assignedTo;
        $ccList = $bug->mailto ? trim($bug->mailto, ',') : '';
        if(empty($toList))
        {
            if(empty($ccList)) return false;
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
        elseif($bug->status == 'closed')
        {
            $ccList .= ',' . $bug->resolvedBy;
        }

        return array($toList, $ccList);
    }

    /**
     * Summary
     *
     * @param  array    $bugs
     * @access public
     * @return string
     */
    public function summary($bugs)
    {
        $unresolved = 0;
        foreach($bugs as $bug)
        {
            if($bug->status != 'resolved' && $bug->status != 'closed') $unresolved++;
        }

        return sprintf($this->lang->bug->summary, count($bugs), $unresolved);
    }

    /**
     * Get project list.
     *
     * @param  int $productID
     * @access public
     * @return array
     */
    public function getProjects($productID)
    {
        return $this->dao->select('t1.id,t1.name')
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftjoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.product')->eq($productID)
            ->fetchPairs();
    }

    /**
     * Get ID list of all projects.
     *
     * @access public
     * @return array
     */
    public function getAllProjectIds()
    {
        return $this->dao->select('id')
            ->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id');
    }

    /**
     * Build bug menu.
     *
     * @param  object $bug
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($bug, $type = 'view')
    {
        $menu          = '';
        $params        = "bugID=$bug->id";
        $extraParams   = "extras=bugID=$bug->id";
        if($this->app->tab == 'project')   $extraParams .= ",projectID={$bug->project}";
        if($this->app->tab == 'execution') $extraParams .= ",executionID={$bug->execution}";
        $copyParams    = "productID=$bug->product&branch=$bug->branch&$extraParams";
        $convertParams = "productID=$bug->product&branch=$bug->branch&moduleID=0&from=bug&bugID=$bug->id";
        $toStoryParams = "product=$bug->product&branch=$bug->branch&module=0&story=0&execution=0&bugID=$bug->id";

        $menu .= $this->buildMenu('bug', 'confirmBug', $params, $bug, $type, 'ok', '', "iframe", true);
        if($type == 'view' and $bug->status != 'closed') $menu .= $this->buildMenu('bug', 'assignTo', $params, $bug, $type, '', '', "iframe", true);
        $menu .= $this->buildMenu('bug', 'resolve', $params, $bug, $type, 'checked', '', "iframe showinonlybody", true);
        $menu .= $this->buildMenu('bug', 'close', $params, $bug, $type, '', '', "text-danger iframe showinonlybody", true);
        if($type == 'view') $menu .= $this->buildMenu('bug', 'activate', $params, $bug, $type, '', '', "text-success iframe showinonlybody", true);
        if($type == 'view' && $this->app->tab != 'product')
        {
            $tab   = $this->app->tab == 'qa' ? 'product' : $this->app->tab;
            if($tab == 'product')
            {
                $product = $this->loadModel('product')->getByID($bug->product);
                if(!empty($product->shadow)) $tab = 'project';
            }
            $menu .= $this->buildMenu('bug', 'toStory', $toStoryParams, $bug, $type, $this->lang->icons['story'], '', '', '', "data-app='$tab' id='tostory'", $this->lang->bug->toStory);
            if(common::hasPriv('task', 'create') and !isonlybody()) $menu .= html::a('#toTask', "<i class='icon icon-check'></i><span class='text'>{$this->lang->bug->toTask}</span>", '', "data-app='qa' data-toggle='modal' class='btn btn-link'");
            $menu .= $this->buildMenu('bug', 'createCase', $convertParams, $bug, $type, 'sitemap');
        }
        if($type == 'view')
        {
            $menu .= "<div class='divider'></div>";
            $menu .= $this->buildFlowMenu('bug', $bug, $type, 'direct');
            $menu .= "<div class='divider'></div>";
        }
        $menu .= $this->buildMenu('bug', 'edit', $params, $bug, $type);
        if($this->app->tab != 'product') $menu .= $this->buildMenu('bug', 'create', $copyParams, $bug, $type, 'copy');
        if($type == 'view') $menu .= $this->buildMenu('bug', 'delete', $params, $bug, $type, 'trash', 'hiddenwin', "showinonlybody");

        return $menu;
    }

    /**
     * Get related objects id lists.
     *
     * @param  int    $object
     * @param  string $pairs
     * @access public
     * @return void
     */
    public function getRelatedObjects($object, $pairs = '')
    {
        /* Get bugs. */
        $bugs = $this->loadModel('transfer')->getQueryDatas('bug');

        /* Get related objects id lists. */
        $relatedObjectIdList = array();
        $relatedObjects      = array();

        foreach($bugs as $bug)
        {
            if(is_numeric($bug->$object)) $relatedObjectIdList[$bug->$object]  = $bug->$object;
        }

        if($object == 'openedBuild' or $object == 'resolvedBuild') $object = 'build';

        /* Get related objects title or names. */
        $table = $this->config->objectTables[$object];
        if($table) $relatedObjects = $this->dao->select($pairs)->from($table)->where('id')->in($relatedObjectIdList)->fetchPairs();

        if(in_array($object, array('build','resolvedBuild'))) $relatedObjects = array('trunk' => $this->lang->trunk) + $relatedObjects;
        return array('' => '', 0 => '') + $relatedObjects;
    }

    /**
     * Return index of a case's step.
     *
     * @param  object    $caseStep
     * @access public
     * @return int
     */
    public function getCaseStepIndex($caseStep)
    {
        static $index     = 0;
        static $stepIndex = 0;
        static $itemIndex = 0;
        static $groupID   = 0;

        if($caseStep->type == 'item')
        {
            if($groupID and $caseStep->parent == $groupID)
            {
                $itemIndex ++;
                $index = $stepIndex . '.' . $itemIndex;
            }
            else
            {
                $stepIndex ++;
                $index = $stepIndex;
            }
        }
        else
        {
            if($caseStep->type == 'group') $groupID = $caseStep->id;
            $stepIndex ++;
            $itemIndex = 0;
            $index = $stepIndex;
        }

        return $index;
    }

    /**
     * Convert array to object array.
     *
     * @param  int    $data
     * @access public
     * @return array
     */
    public function convertArrayToObjectArray($data)
    {
        return array_map(function($key, $value)
        {
            return (object) array('value' => $key, 'text' => $value);
        }, array_keys($data), array_values($data));
    }

    /**
     * Generate columns used in datatable.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function generateCol($orderBy)
    {
        $setting   = $this->loadModel('datatable')->getSetting('bug');
        $fieldList = $this->config->bug->datatable->fieldList;

        if(empty($setting))
        {
            $setting = $this->config->bug->datatable->defaultField;
            $order   = 1;
            foreach($setting as $key => $value)
            {
                $id  = $value;
                $set = new stdclass();;
                $set->order = $order++;
                $set->show  = true;
                $set->name  = $value;
                $set->title = $fieldList[$id]['title'];

                $sortType = '';
                if(strpos($orderBy, $id) !== false)
                {
                    $sort = str_replace("{$id}_", '', $orderBy);
                    $sortType = $sort == 'asc' ? 'up' : 'down';
                }

                if(isset($fieldList[$id]['checkbox'])) $set->checkbox = $fieldList[$id]['checkbox'];
                if(isset($fieldList[$id]['fixed']))    $set->fixed    = $fieldList[$id]['fixed'];
                if(isset($fieldList[$id]['width']))    $set->width    = $fieldList[$id]['width'];
                if(isset($fieldList[$id]['type']))     $set->type     = $fieldList[$id]['type'];
                if(isset($fieldList[$id]['sortType'])) $set->sortType = $fieldList[$id]['sortType'];
                if(isset($fieldList[$id]['flex']))     $set->flex     = $fieldList[$id]['flex'];
                if(isset($fieldList[$id]['minWidth'])) $set->minWidth = $fieldList[$id]['minWidth'];
                if(isset($fieldList[$id]['maxWidth'])) $set->maxWidth = $fieldList[$id]['maxWidth'];
                if(isset($fieldList[$id]['pri']))      $set->pri      = $fieldList[$id]['pri'];

                if($sortType) $set->sortType = $sortType;

                $setting[$key] = $set;
            }
        }
        else
        {
            foreach($setting as $key => $set)
            {
                if(empty($set->show))
                {
                    unset($setting[$key]);
                    continue;
                }

                $sortType = '';
                if(strpos($orderBy, $set->id) !== false)
                {
                    $sort = str_replace("{$set->id}_", '', $orderBy);
                    $sortType = $sort == 'asc' ? 'up' : 'down';
                }

                $set->name  = $set->id;
                $set->title = $fieldList[$set->id]['title'];

                if(isset($fieldList[$set->id]['checkbox'])) $set->checkbox = $fieldList[$set->id]['checkbox'];
                if(isset($fieldList[$set->id]['fixed']))    $set->fixed    = $fieldList[$set->id]['fixed'];
                if(isset($fieldList[$set->id]['type']))     $set->type     = $fieldList[$set->id]['type'];
                if(isset($fieldList[$set->id]['sortType'])) $set->sortType = $fieldList[$set->id]['sortType'];
                if(isset($fieldList[$set->id]['flex']))     $set->flex     = $fieldList[$set->id]['flex'];
                if(isset($fieldList[$set->id]['minWidth'])) $set->minWidth = $fieldList[$set->id]['minWidth'];
                if(isset($fieldList[$set->id]['maxWidth'])) $set->maxWidth = $fieldList[$set->id]['maxWidth'];
                if(isset($fieldList[$set->id]['pri']))      $set->pri      = $fieldList[$set->id]['pri'];

                if($sortType) $set->sortType = $sortType;

                if(isset($set->width)) $set->width = str_replace('px', '', $set->width);

                unset($set->id);
            }
        }

        foreach($setting as $key => $set)
        {
            if(isset($set->fixed) && $set->fixed != 'left' && $set->fixed != 'right') unset($set->fixed);
        }

        usort($setting, array('datatableModel', 'sortCols'));

        return $setting;
    }

    /**
     * Generate rows displayed in datatable.
     *
     * @param  array  $bugs
     * @param  array  $branches
     * @param  array  $modulePairs
     * @param  array  $projectPairs
     * @param  array  $plans
     * @param  array  $executions
     * @param  array  $stories
     * @param  array  $tasks
     * @param  array  $users
     * @access public
     * @return array
     */
    public function generateRow($bugs, $branches, $modulePairs, $projectPairs, $plans, $executions, $stories, $tasks, $users)
    {
        $rows         = array();
        $userFields   = array('openedBy', 'resolvedBy', 'closedBy', 'lastEditedBy');
        $dateFields   = array('activatedDate', 'openedDate', 'assignedDate', 'deadline', 'resolvedDate', 'closedDate', 'lastEditedDate');
        $canViewBug   = common::hasPriv('bug',   'view');
        $canViewCase  = common::hasPriv('case',  'view');
        $canViewTask  = common::hasPriv('task',  'view');
        $canViewStory = common::hasPriv('story', 'view');

        foreach($bugs as $bug)
        {
            $bug->actions = '<div class="c-actions">' . $this->buildOperateMenu($bug, 'browse') . '</div>';

            $severityValue = zget($this->lang->bug->severityList, $bug->severity);
            $severityClass = !is_numeric($severityValue) ? 'label-severity-custom' : 'label-severity';
            $bugSeverity   = !is_numeric($severityValue) ? $severityValue : '';
            $bug->severity = "<div class='c-severity'><span class='{$severityClass}' data-severity='{$bug->severity}' title='{$severityValue}'>{$bugSeverity}</span></div>";

            $bugPri   = zget($this->lang->bug->priList, $bug->pri);
            $bug->pri = $bug->pri ? "<span class='label-pri label-pri-{$bug->pri}' title='{$bugPri}'>{$bugPri}</span>" : '';

            $bugConfirmed   = zget($this->lang->bug->confirmedList, $bug->confirmed);
            $bug->confirmed = "<span class='confirm{$bug->confirmed}' title='{$bugConfirmed}'>{$bugConfirmed}</span>";

            $bugTitle   = '';
            $showBranch = isset($this->config->bug->browse->showBranch) ? $this->config->bug->browse->showBranch : 1;
            if($showBranch && !empty($branches[$bug->branch])) $bugTitle .= "<span class='label label-outline label-badge' title='{$branches[$bug->branch]}'>{$branches[$bug->branch]}</span> ";
            if($bug->module && !empty($modulePairs[$bug->module])) $bugTitle .= "<span class='label label-gray label-badge' title='{$modulePairs[$bug->module]}'>{$modulePairs[$bug->module]}</span> ";
            $bugTitle .= $canViewBug ? html::a(helper::createLink('bug', 'view', "bugID={$bug->id}"), $bug->title, null, "style='color: {$bug->color}' data-app='{$this->app->tab}' title='{$bug->title}'") : "<span style='color: {$bug->color}' title='{$bug->title}'>{$bug->title}</span>";
            if($bug->case)
            {
                $bugCase   = "[{$this->lang->testcase->common}#{$bug->case}]";
                $bugTitle .= $canViewCase ? html::a(helper::createLink('testcase', 'view', "caseID={$bug->case}&version={$bug->caseVersion}"), $bugCase, '', "class='bug' title='{$bug->case}'") : $bugCase;
            }
            $bug->title = $bugTitle;

            $bug->story = zget($stories, $bug->story, '');
            if($bug->story)
            {
                $bug->story = $canViewStory ? html::a(helper::createLink('story', 'view', "storyID={$bug->story->id}", 'html', true), $bug->story->title, '', "class='iframe' title='{$bug->story->title}' data-toggle='modal'") : "<span title='{$bug->story->title}'>{$bug->story->title}</span>";
            }

            $bug->task = zget($tasks, $bug->task, '');
            if($bug->task)
            {
                $bug->task = $canViewTask ? html::a(helper::createLink('task', 'view', "taskID={$bug->task->id}", 'html', true), $bug->task->name, '', "class='iframe' title='{$bug->task->name}' data-toggle='modal'") : "<span title='{$bug->task->name}'>{$bug->task->name}</span>";
            }

            $bug->toTask = zget($tasks, $bug->toTask, '');
            if($bug->toTask)
            {
                $bug->toTask = $canViewTask ? html::a(helper::createLink('task', 'view', "taskID={$bug->toTask->id}", 'html', true), $bug->toTask->name, '', "class='iframe' title='{$bug->toTask->name}' data-toggle='modal'") : "<span title='{$bug->toTask->name}'>{$bug->toTask->name}</span>";
            }

            $status      = $bug->status;
            $bugStatus   = $this->processStatus('bug', $bug);
            $bug->status = "<span class='status-bug status-{$bug->status}' title='{$bugStatus}'> {$bugStatus}</span>";

            $bugOS = array();
            if($bug->os)
            {
                foreach(explode(',', $bug->os) as $os)
                {
                    $os = trim($os);
                    if($os) $bugOS[] = zget($this->lang->bug->osList, $os, '');
                }
            }
            $bug->os = implode(',', $bugOS);

            $bugBrowser = array();
            if($bug->browser)
            {
                foreach(explode(',', $bug->browser) as $browser)
                {
                    $browser = trim($browser);
                    if($browser) $bugBrowser[] = zget($this->lang->bug->browserList, $browser, '');
                }
            }
            $bug->browser = implode(',', $bugBrowser);

            $bugMailto = array();
            if($bug->mailto)
            {
                foreach(explode(',', $bug->mailto) as $account)
                {
                    $account = trim($account);
                    if($account) $bugMailto[] = zget($users, $account);
                }
            }
            $bug->mailto = implode(' ', $bugMailto);

            $bug->branch     = zget($branches, $bug->branch, '');
            $bug->project    = zget($projectPairs, $bug->project, '');
            $bug->plan       = zget($plans, $bug->plan, '');
            $bug->execution  = zget($executions, $bug->execution, '');
            $bug->type       = zget($this->lang->bug->typeList, $bug->type, '');
            $bug->resolution = zget($this->lang->bug->resolutionList, $bug->resolution, '');
            $bug->assignedTo = $this->printAssignedHtml($bug, $users, false);

            foreach($userFields as $field) $bug->$field = zget($users, $bug->$field);
            foreach($dateFields as $field) $bug->$field = (empty($bug->$field) || helper::isZeroDate($bug->$field)) ? '' : substr($bug->$field, 5, 11);

            foreach(array_merge(array('os', 'browser', 'mailto', 'branch', 'project', 'plan', 'execution', 'type', 'resolution', 'keywords', 'openedBuild', 'resolvedBuild', 'activatedCount'), $userFields, $dateFields) as $field)
            {
                if(empty($bug->$field)) continue;

                $class = ($field == 'deadline' && isset($bug->delay) && $status == 'active') ? "class='delayed'" : "class='deadline-padding'";
                $bug->$field = "<span $class title='{$bug->$field}'>{$bug->$field}</span>";
            }

            if($this->config->edition != 'open')
            {
                $this->loadModel('flow');
                $extendFields = $this->loadModel('workflowfield')->getList('bug');
                foreach($extendFields as $fieldCode => $field)
                {
                    if(isset($field->buildin) && $field->buildin == 0)
                    {
                        $bug->$fieldCode = $this->flow->printFlowCell('bug', $bug, $fieldCode, true);
                    }
                }
            }
            $rows[] = $bug;
        }
        return $rows;
    }
}
