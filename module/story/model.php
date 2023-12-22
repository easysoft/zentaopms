<?php
declare(strict_types=1);
/**
 * The model file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: model.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class storyModel extends model
{
    /**
     * Get a story by id.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  bool   $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $storyID, int $version = 0, bool $setImgSize = false): object|false
    {
        $story = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")->fetch();
        if(!$story) return false;

        $story->children = array();
        if($version == 0) $version = $story->version;

        $this->loadModel('file');
        $spec = $this->dao->select('title,spec,verify,files')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title  = !empty($spec->title)  ? $spec->title  : '';
        $story->spec   = !empty($spec->spec)   ? $spec->spec   : '';
        $story->verify = !empty($spec->verify) ? $spec->verify : '';
        $story->files  = !empty($spec->files)  ? $this->file->getByIdList($spec->files) : array();
        $story->stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchPairs('branch', 'stage');

        $story = $this->file->replaceImgURL($story, 'spec,verify');
        if($setImgSize) $story->spec   = $this->file->setImgSize($story->spec);
        if($setImgSize) $story->verify = $this->file->setImgSize($story->verify);

        $twinsIdList = $storyID . ($story->twins ? ",{$story->twins}" : '');
        $story->executions = $this->dao->select('t1.project, t2.name, t2.status, t2.type, t2.multiple')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.type')->in('sprint,stage,kanban')
            ->andWhere('t1.story')->in($twinsIdList)
            ->orderBy('t1.`order` DESC')
            ->fetchAll('project');

        $story->tasks = $this->dao->select('id,name,assignedTo,execution,project,status,consumed,`left`,type')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('story')->in($twinsIdList)->orderBy('id DESC')->fetchGroup('execution');

        if($story->toBug)          $story->toBugTitle = $this->dao->findById($story->toBug)->from(TABLE_BUG)->fetch('title');
        if($story->parent > 0)     $story->parentName = $this->dao->findById($story->parent)->from(TABLE_STORY)->fetch('title');
        if($story->fromStory)      $story->sourceName = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($story->fromStory)->fetch('title');
        if($story->parent == '-1') $story->children   = $this->dao->select('*')->from(TABLE_STORY)->where('parent')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll('id');
        if($story->plan)
        {
            $plans = $this->dao->select('id,title,branch')->from(TABLE_PRODUCTPLAN)->where('id')->in($story->plan)->fetchAll('id');
            foreach($plans as $planID => $plan)
            {
                $story->planTitle[$planID] = $plan->title;
                if($plan->branch and !isset($story->stages[$plan->branch]) and empty($story->branch)) $story->stages[$plan->branch] = 'planned';
            }
        }

        $extraStories = array();
        if($story->duplicateStory) $extraStories = array($story->duplicateStory);
        if($story->linkStories)    $extraStories = array_merge($extraStories, explode(',', $story->linkStories));
        if($story->childStories)   $extraStories = array_merge($extraStories, explode(',', $story->childStories));

        $extraStories = array_unique($extraStories);
        if(!empty($extraStories)) $story->extraStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($extraStories)->fetchPairs();

        $linkStoryField = $story->type == 'story' ? 'linkStories' : 'linkRequirements';
        if($story->{$linkStoryField}) $story->linkStoryTitles = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($story->{$linkStoryField})->fetchPairs();

        $story->openedDate     = helper::isZeroDate($story->openedDate)     ? '' : substr($story->openedDate,     0, 19);
        $story->assignedDate   = helper::isZeroDate($story->assignedDate)   ? '' : substr($story->assignedDate,   0, 19);
        $story->reviewedDate   = helper::isZeroDate($story->reviewedDate)   ? '' : substr($story->reviewedDate,   0, 19);
        $story->closedDate     = helper::isZeroDate($story->closedDate)     ? '' : substr($story->closedDate,     0, 19);
        $story->lastEditedDate = helper::isZeroDate($story->lastEditedDate) ? '' : substr($story->lastEditedDate, 0, 19);

        return $story;
    }

    /**
     * Get story pairs.
     *
     * @param  int    $productID
     * @param  int    $planID
     * @param  string $field
     * @access public
     * @return array
     */
    public function getPairs(int $productID = 0, int $planID = 0, string $field = 'title'): array
    {
        return $this->dao->select("id, {$field}")->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($planID)->andWhere("CONCAT(',', plan, ',')")->like("%,{$planID},%")->fi()
            ->fetchPairs();
    }

    /**
     * Get stories by idList.
     *
     * @param  array|string $storyIdList
     * @param  string       $mode     all
     * @access public
     * @return array
     */
    public function getByList(array|string $storyIdList, string $mode = ''): array
    {
        if(empty($storyIdList)) return array();
        return $this->dao->select('t1.*, t2.spec, t2.verify, t3.name as productTitle, t3.deleted as productDeleted')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.version=t2.version')
            ->andWhere('t1.id')->in($storyIdList)
            ->beginIF($mode != 'all')->andWhere('t1.deleted')->eq('0')->fi()
            ->beginIF($this->config->vision == 'or')->andWhere('t1.vision')->eq('or')->fi()
            ->fetchAll('id');
    }

    /**
     * 获取指定的需求 id name 的键值对。
     * Get pairs by list.
     *
     * @param  array|string $storyIdList
     * @access public
     * @return array
     */
    public function getPairsByList(array|string $storyIdList): array
    {
        $storyPairs = $this->dao->select('id, title')->from(TABLE_STORY)->where('id')->in($storyIdList)->beginIF($this->config->vision == 'or')->andWhere('t1.vision')->eq('or')->fi()->fetchPairs();

        return array(0 => '') + $storyPairs;
    }

    /**
     * 获取执行中已经为该需求创建了测试类型任务的需求ID的键值对。
     * Get the key-value pairs for story ID which the test type task has been created for this story in the execution.
     *
     * @param  array  $storyIdList
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getTestStories(array $storyIdList, int $executionID): array
    {
        return $this->dao->select('story')->from(TABLE_TASK)
            ->where('execution')->eq($executionID)
            ->andWhere('type')->eq('test')
            ->andWhere('story')->in($storyIdList)
            ->andWhere('deleted')->eq('0')
            ->fetchPairs('story', 'story');
    }

    /**
     * Get story specs.
     *
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getStorySpecs(array $storyIdList): array
    {
        return $this->dao->select('story,spec,verify')->from(TABLE_STORYSPEC)
            ->where('story')->in($storyIdList)
            ->orderBy('version')
            ->fetchAll('story');
    }

    /**
     * Get affected things.
     *
     * @param  object  $story
     * @access public
     * @return object
     */
    public function getAffectedScope(object $story): object
    {
        $users = $this->loadModel('user')->getPairs('pofirst|nodeleted', "{$story->lastEditedBy},{$story->openedBy},{$story->assignedTo}");

        $story = $this->storyTao->getAffectedProjects($story, $users);
        $story = $this->storyTao->getAffectedBugs($story, $users);
        $story = $this->storyTao->getAffectedCases($story, $users);
        $story = $this->storyTao->getAffectedTwins($story, $users);

        return $story;
    }

    /**
     *  Get requirements for story.
     *
     *  @param  int     $productID
     *  @access public
     *  @return array
     */
    public function getRequirements(int $productID): array
    {
        return $this->dao->select('id,title')->from(TABLE_STORY)
           ->where('deleted')->eq(0)
           ->andWhere('product')->eq($productID)
           ->andWhere('type')->eq('requirement')
           ->andWhere('status')->notIN('draft,closed')
           ->fetchPairs('id', 'title');
    }

    /**
     * Get stories list of a execution.
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string       $orderBy
     * @param  string       $type
     * @param  int|string   $param
     * @param  string       $storyType
     * @param  array|string $excludeStories
     * @param  object|null  $pager
     * @access public
     * @return array
     */
    public function getExecutionStories(int $executionID = 0, int $productID = 0, string $orderBy = 't1.`order`_desc', string $type = 'byModule', string $param = '0', string $storyType = 'story', array|string $excludeStories = '', object|null $pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStories();

        if(empty($executionID)) return array();

        /* 格式化参数。 */
        $orderBy = str_replace('branch_', 't2.branch_', $orderBy);
        $type    = strtolower($type);
        if(is_string($excludeStories))$excludeStories = explode(',', $excludeStories);

        /* 获取需求。 */
        if($type == 'bysearch') $stories = $this->storyTao->getExecutionStoriesBySearch($executionID, (int)$param, $productID, $orderBy, $storyType, $excludeStories, $pager);
        if($type != 'bysearch')
        {
            /* 根据请求类型和参数，获取查询要用到的条件。 */
            $execution    = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch();
            $modules      = $this->storyTao->getModules4ExecutionStories($type, $param);
            $storyIdList  = $this->storyTao->getIdListOfExecutionsByProjectID($type, $executionID);
            $productParam = ($type == 'byproduct' and $param)        ? $param : $this->cookie->storyProductParam;
            $branchParam  = ($type == 'bybranch'  and $param !== '') ? $param : (string)$this->cookie->storyBranchParam;
            if(str_contains($branchParam, ',')) list($productParam, $branchParam) = explode(',', $branchParam);

            /* 设置查询需求的公共 DAO 变量。 */
            $type     = (strpos('bymodule|byproduct', $type) !== false and $this->session->storyBrowseType) ? $this->session->storyBrowseType : $type;
            $storyDAO = $this->dao->select("DISTINCT t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
                ->where('t1.project')->eq($executionID)
                ->andWhere('t2.type')->eq($storyType)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t3.deleted')->eq(0)
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->beginIF($this->session->storyBrowseType and strpos('changing|', $this->session->storyBrowseType) !== false)->andWhere('t2.status')->in($this->storyTao->getUnclosedStatusKeys())->fi()
                ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi();

            /* 根据传入的 ID 是项目还是执行分别查询需求。 */
            if($execution->type == 'project') $stories = $this->storyTao->fetchProjectStories($storyDAO, $productID, $type, $branchParam, $storyIdList, $orderBy, $pager);
            if($execution->type != 'project') $stories = $this->storyTao->fetchExecutionStories($storyDAO, (int)$productParam, $orderBy, $pager);
        }

        $stories = $this->storyTao->fixBranchStoryStage($stories);
        return $this->storyTao->mergePlanTitleAndChildren($productID, $stories, $storyType);
    }

    /**
     * 获取多个执行的关联需求。
     * get execution stories by execution id list.
     *
     * @param  string       $executionIdList
     * @param  int          $productID
     * @param  string       $orderBy
     * @param  string       $type            bybranch
     * @param  string       $param
     * @param  string       $storyType       story|requirement
     * @param  array|string $excludeStories
     * @param  object|null  $pager
     * @access public
     * @return void
     */
    public function batchGetExecutionStories(string $executionIdList = '', int $productID = 0, string $orderBy = 't1.`order`_desc', string $type = 'byModule', string $param = '0', string $storyType = 'story', array|string $excludeStories = '', object|null $pager = null): array
    {
        if(empty($executionIdList)) return array();

        /* 格式化参数。 */
        $type = strtolower($type);
        if(is_string($excludeStories))$excludeStories = explode(',', $excludeStories);
        $executions   = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($executionIdList)->fetchAll('id');
        $hasProject   = false;
        $hasExecution = false;
        foreach($executions as $execution)
        {
            if($execution->type == 'project') $hasProject   = true;
            if($execution->type != 'project') $hasExecution = true;
        }

        $modules        = $this->storyTao->getModules4ExecutionStories($type, $param);
        $unclosedStatus = $this->lang->story->statusList;
        unset($unclosedStatus['closed']);

        $productParam = '';
        $branchParam  = ($type == 'bybranch'  and $param !== '') ? $param : (string)$this->cookie->storyBranchParam;
        if(strpos($branchParam, ',') !== false) list($productParam, $branchParam) = explode(',', $branchParam);

        $stories = $this->dao->select("distinct t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->in($executionIdList)
            ->andWhere('t2.type')->eq($storyType)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
            ->beginIF($this->session->storyBrowseType and strpos('changing|', $this->session->storyBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->beginIF($hasProject)
            ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($type == 'bybranch' and $branchParam !== '')->andWhere('t2.branch')->in("0,$branchParam")->fi()
            ->fi()
            ->beginIF($hasExecution)
            ->beginIF(!empty($productParam))->andWhere('t1.product')->eq($productParam)->fi()
            ->beginIF($this->session->executionStoryBrowseType and strpos('changing|', $this->session->executionStoryBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
            ->fi()
            ->orderBy($orderBy)
            ->page($pager, 't2.id')
            ->fetchAll('id');

        return $this->storyTao->mergePlanTitleAndChildren($productID, $stories, $storyType);
    }

    /**
     * 获取执行下的需求键值对。
     * Get stories pairs of a execution.
     *
     * @param  int              $executionID
     * @param  int              $productID
     * @param  string|int       $branch       0|all|integer
     * @param  array|string|int $moduleIdList
     * @param  string           $type         full|short
     * @param  string           $status       all|noclosed|changing|active|draft|closed|reviewing
     * @access public
     * @return array
     */
    public function getExecutionStoryPairs(int $executionID = 0, int $productID = 0, string|int $branch = 'all', array|string|int $moduleIdList = '', string $type = 'full', string $status = 'all'): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStoryPairs();

        $stories = $this->dao->select('t2.id, t2.title, t2.module, t2.pri, t2.estimate, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq($executionID)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('story')
            ->beginIF($productID)->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($branch !== 'all')->andWhere('t2.branch')->in("0,$branch")->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->beginIF($status == 'unclosed')->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF($status == 'review')->andWhere('t2.status')->in('draft,changing')->fi()
            ->beginIF($status == 'active')->andWhere('t2.status')->eq('active')->fi()
            ->orderBy('t1.`order` desc, t1.`story` desc')
            ->fetchAll('id');
        return empty($stories) ? array() : $this->formatStories($stories, $type);
    }

    /**
     * Get stories list of a plan.
     *
     * @param  int    $planID
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPlanStories(int $planID, string $status = 'all', string $orderBy = 'id_desc', object|null $pager = null): array
    {
        if(strpos($orderBy, 'module') !== false)
        {
            $orderBy = (strpos($orderBy, 'module_asc') !== false) ? 't3.path asc' : 't3.path desc';
            $stories = $this->dao->select('distinct t1.story, t1.plan, t1.order, t2.*')
                ->from(TABLE_PLANSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_MODULE)->alias('t3')->on('t2.module = t3.id')
                ->where('t1.plan')->eq($planID)
                ->beginIF($status and $status != 'all')->andWhere('t2.status')->in($status)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $stories = $this->dao->select("distinct t1.story, t1.plan, t1.order, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder")
                ->from(TABLE_PLANSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where('t1.plan')->eq($planID)
                ->beginIF($status and $status != 'all')->andWhere('t2.status')->in($status)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        return $stories;
    }

    /**
     * Get stories by plan id list.
     *
     * @param  string|array $planIdList
     * @access public
     * @return array
     */
    public function getStoriesByPlanIdList(array|string $planIdList = ''): array
    {
        return $this->dao->select('t1.plan as planID, t2.*')->from(TABLE_PLANSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t2.deleted')->eq(0)
            ->beginIF($planIdList)->andWhere('t1.plan')->in($planIdList)->fi()
            ->fetchGroup('planID', 'id');
    }

    /**
     * Create a story.
     *
     * @param  object $story
     * @param  int    $executionID
     * @param  int    $bugID
     * @param  string $extra
     * @param  int    $todoID
     *
     * @access public
     * @return int|false the id of the created story or false when error.
     */
    public function create(object $story, int $executionID = 0, int $bugID = 0, string $extra = '', int $todoID = 0): int|false
    {
        if(commonModel::isTutorialMode()) return false;

        $storyID = $this->storyTao->doCreateStory($story);
        if(!$storyID) return false;

        /* Upload files. */
        $this->loadModel('action');
        $this->loadModel('file')->updateObjectID($this->post->uid, $storyID, $story->type);
        $files = $this->file->saveUpload($story->type, $storyID, 1);

        /* Add story spec verify. */
        $this->storyTao->doCreateSpec($storyID, $story, $files);

        /* Create actions. */
        $action = $bugID == 0 ? 'Opened' : 'Frombug';
        $extra  = $bugID == 0 ? '' : $bugID;
        $this->action->create('story', $storyID, $action, '', $extra);

        if($executionID) $this->storyTao->linkToExecutionForCreate($executionID, $storyID, $story, $extra);
        if($bugID)       $this->storyTao->closeBugWhenToStory($bugID, $storyID);
        if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($storyID, $story->reviewer);
        if(!empty($story->URS))      $this->storyTao->doCreateURRelations($storyID, $story->URS);
        if(!empty($story->parent))   $this->subdivide($story->parent, array($storyID));
        if(!empty($story->plan))
        {
            $this->updateStoryOrderOfPlan($storyID, (string)$story->plan); // Set story order in this plan.
            $this->action->create('productplan', $story->plan, 'linkstory', '', $storyID);
        }

        $this->setStage($storyID);
        $this->loadModel('score')->create('story', 'create',$storyID);

        /* Record submit review action. */
        if($story->status == 'reviewing') $this->action->create('story', $storyID, 'submitReview');

        if($todoID > 0)
        {
            $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
            $this->action->create('todo', $todoID, 'finished', '', "STORY:$storyID");

            if(in_array($this->config->edition, array('max', 'ipd')))
            {
                $todo = $this->dao->select('type, idvalue')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
                if($todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, 'done');
            }
        }


        return $storyID;
    }

    /**
     * 创建孪生需求。
     * Create twins stories.
     *
     * @param  object $storyData
     * @param  int    $objectID
     * @param  int    $bugID
     * @param  string $extra
     * @param  int    $todoID
     *
     * @access public
     * @return int|false
     */
    public function createTwins(object $storyData, int $objectID, int $bugID, string $extra = '', int $todoID = 0): int|false
    {
        if(empty($storyData->branches)) return $this->create($storyData, $objectID, $bugID, $extra, $todoID);

        $storyIdList = array();
        $mainStoryID = 0;
        foreach($storyData->branches as $key => $branchID)
        {
            $storyData->branch = (int)$branchID;
            $storyData->module = $storyData->modules[$key];
            $storyData->plan   = $storyData->plans[$key];

            $storyID = $this->create($storyData, $objectID, $bugID, $extra, $todoID);
            $storyIdList[$storyID] = $storyID;
            if(empty($mainStoryID)) $mainStoryID = $storyID;
        }

        $this->storyTao->updateTwins($storyIdList);
        return $mainStoryID;
    }

    /**
     * Create story from gitlab issue.
     *
     * @param  object    $story
     * @param  int       $executionID
     * @access public
     * @return int
     */
    public function createStoryFromGitlabIssue(object $story, int $executionID): int|false
    {
        $story->status       = 'active';
        $story->stage        = 'projected';
        $story->openedBy     = $this->app->user->account;
        $story->version      = 1;
        $story->pri          = 3;
        $story->assignedDate = isset($story->assignedTo) ? helper::now() : null;

        if(isset($story->execution)) unset($story->execution);

        $requiredFields = $this->config->story->create->requiredFields;
        $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify,gitlab,gitlabProject')->autoCheck()->batchCheck($requiredFields, 'notempty')->exec();
        if(dao::isError()) return false;

        $storyID = $this->dao->lastInsertID();

        $data          = new stdclass();
        $data->story   = $storyID;
        $data->version = 1;
        $data->title   = $story->title;
        $data->spec    = $story->spec;
        $data->verify  = $story->spec;
        $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();

        /* Link story to execution. */
        $this->linkStory($executionID, $story->product, $storyID);

        return $storyID;
    }

    /**
     * Batch create stories.
     *
     * @param  array  $stories
     * @param  int    $productID
     * @param  string $branch
     * @param  string $type
     * @param  int    $URID
     * @access public
     * @return array
     */
    public function batchCreate(array $stories, int $productID = 0, string $branch = '', string $type = 'story', int $URID = 0): array
    {
        $this->loadModel('action');
        $storyIdList = array();
        $link2Plans  = array();
        foreach($stories as $i => $story)
        {
            $storyID = $this->storyTao->doCreateStory($story);
            if(!$storyID) return array();

            $this->storyTao->doCreateSpec($storyID, $story);

            /* Update product plan stories order. */
            if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($storyID, $story->reviewer);
            if($story->plan)
            {
                $this->updateStoryOrderOfPlan($storyID, (string)$story->plan);
                $link2Plans[$story->plan] = empty($link2Plans[$story->plan]) ? $storyID : "{$link2Plans[$story->plan]},$storyID";
            }

            $this->setStage($storyID);
            $this->executeHooks($storyID);

            $this->action->create('story', $storyID, 'Opened', '');
            $storyIdList[$i] = $storyID;
        }

        if(!dao::isError())
        {
            /* Remove upload image file and session. */
            if($this->session->storyImagesFile)
            {
                $file     = current($_SESSION['storyImagesFile']);
                $realPath = dirname($file['realpath']);
                if(is_dir($realPath)) $this->app->loadClass('zfile')->removeDir($realPath);
                unset($_SESSION['storyImagesFile']);
            }

            $this->loadModel('score')->create('story', 'create',$storyID);
            $this->score->create('ajax', 'batchCreate');

            if($URID && $storyIdList) $this->subdivide($URID, $storyIdList);
            foreach($link2Plans as $planID => $stories) $this->action->create('productplan', $planID, 'linkstory', '', $stories);
        }

        return $storyIdList;
    }

    /**
     * Change a story.
     *
     * @param  int    $storyID
     * @param  object $story
     * @access public
     * @return array  the change of the story.
     */
    public function change(int $storyID, object $story): array|false
    {
        $oldStory = $this->getById($storyID);
        $this->dao->update(TABLE_STORY)->data($story, 'spec,verify,deleteFiles,relievedTwins,reviewer,reviewerHasChanged')
            ->autoCheck()
            ->batchCheck($this->config->story->change->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)->exec();
        if(dao::isError()) return false;

        $specChanged = $oldStory->version != $story->version;
        $this->loadModel('file')->updateObjectID($this->post->uid, $storyID, 'story');
        if($specChanged)
        {
            $addedFiles = $this->file->saveUpload($oldStory->type, $storyID, $story->version);
            $addedFiles = empty($addedFiles) ? '' : implode(',', array_keys($addedFiles)) . ',';
            $storyFiles = $oldStory->files = implode(',', array_keys($oldStory->files));
            foreach($story->deleteFiles as $fileID) $storyFiles = str_replace(",$fileID,", ',', ",$storyFiles,");

            $story->files = $addedFiles . trim($storyFiles, ',');
            $this->storyTao->doCreateSpec($storyID, $story, $story->files);
            if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($storyID, $story->reviewer, $story->version);

            /* Sync twins. */
            if(!isset($story->relievedTwins) and !empty($oldStory->twins))
            {
                foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                {
                    $this->storyTao->doCreateSpec($twinID, $story, $story->files);
                    if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($twinID, $story->reviewer, $story->version);
                }
            }

            /* IF is story and has changed, update its relation version to new. */
            if($oldStory->type == 'story')
            {
                $newStory = $this->fetchById($storyID);
                $this->dao->update(TABLE_STORY)->set('URChanged')->eq(0)->where('id')->eq($oldStory->id)->exec();
                $this->updateStoryVersion($newStory);
            }
            else
            {
                /* IF is requirement changed, notify its relation. */
                $relations = $this->storyTao->getRelation($storyID, 'requirement');
                $this->dao->update(TABLE_STORY)->set('URChanged')->eq(1)->where('id')->in($relations)->exec();
            }

            if($story->reviewerHasChanged)
            {
                $oldStoryReviewers   = $this->getReviewerPairs($storyID, $oldStory->version);
                $oldStory->reviewers = implode(',', array_keys($oldStoryReviewers));
                $story->reviewers    = implode(',', $story->reviewer);
            }
        }

        $changes = common::createChanges($oldStory, $story);
        if(isset($story->relievedTwins))
        {
            $this->dbh->exec("UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins, ',$storyID,', ',') WHERE `product` = $oldStory->product");
            $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();
            if(!dao::isError()) $this->loadModel('action')->create('story', $storyID, 'relieved');
        }
        elseif(!empty($oldStory->twins))
        {
            $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Changed');
        }

        return $changes;
    }

    /**
     * Update a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array  the changes of the story.
     */
    public function update(int $storyID, object $story, string $comment = ''): bool
    {
        $oldStory = $this->getByID($storyID);

        /* Relieve twins when change product. */
        if(!empty($oldStory->twins) and $story->product != $oldStory->product)
        {
            $this->dbh->exec("UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins, ',$storyID,', ',') WHERE `product` = $oldStory->product");
            $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();
            $oldStory->twins = '';
        }

        $this->dao->update(TABLE_STORY)->data($story, 'reviewer,spec,verify,deleteFiles,finalResult')
            ->autoCheck()
            ->batchCheck($this->config->story->edit->requiredFields, 'notempty')
            ->checkIF(!empty($story->closedBy), 'closedReason', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->notifyEmail, 'notifyEmail', 'email')
            ->checkFlow()
            ->where('id')->eq((int)$storyID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');
        $this->loadModel('file')->updateObjectID($this->post->uid, $storyID, 'story');
        $addedFiles = $this->file->saveUpload($oldStory->type, $storyID, $oldStory->version);
        $this->storyTao->doUpdateSpec($storyID, $story, $oldStory, $addedFiles);
        $this->storyTao->doUpdateLinkStories($storyID, $story, $oldStory);

        $changed = $story->parent != $oldStory->parent;
        if($changed) $this->doChangeParent($storyID, $story, $oldStory->parent);
        if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent, !$changed);
        if($story->parent > 0)
        {
            $this->dao->update(TABLE_STORY)->set('parent')->eq('-1')->where('id')->eq($story->parent)->exec();
            $this->updateParentStatus($storyID, $story->parent, !$changed);
        }

        /* Set new stage and update story sort of plan when story plan has changed. */
        if($oldStory->plan != $story->plan)
        {
            $this->updateStoryOrderOfPlan($storyID, (string)$story->plan, $oldStory->plan); // Insert a new story sort in this plan.
            if(empty($oldStory->plan) or empty($story->plan)) $this->setStage($storyID); // Set new stage for this story.
            if(!empty($oldStory->plan)) $this->action->create('productplan', (int)$oldStory->plan, 'unlinkstory', '', $storyID);
            if(!empty($story->plan))    $this->action->create('productplan', (int)$story->plan, 'linkstory', '', $storyID);
        }

        if(isset($story->stage) and $oldStory->stage != $story->stage)
        {
            $executionIdList = $this->dao->select('t1.project')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.story')->eq($storyID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.type')->in('sprint,stage,kanban')
                ->fetchPairs('project', 'project');

            $this->loadModel('kanban');
            foreach($executionIdList as $executionID) $this->kanban->updateLane($executionID, 'story', $storyID);
        }

        unset($oldStory->parent, $story->parent);
        if(in_array($this->config->edition, array('max', 'ipd')) && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);

        $changes = common::createChanges($oldStory, $story);
        if(!empty($comment) or !empty($changes))
        {
            $action   = !empty($changes) ? 'Edited' : 'Commented';
            $actionID = $this->action->create('story', $storyID, $action, $comment);
            $this->action->logHistory($actionID, $changes);
        }

        if(isset($story->closedReason) and $story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');
        if(!empty($oldStory->twins)) $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Edited');

        return true;
    }

    /**
     * Update story product.
     *
     * @param  int    $storyID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function updateStoryProduct(int $storyID, int $productID): void
    {
        $this->dao->update(TABLE_STORY)->set('product')->eq($productID)->where('id')->eq($storyID)->exec();
        $this->dao->update(TABLE_PROJECTSTORY)->set('product')->eq($productID)->where('story')->eq($storyID)->exec();
        $storyProjects  = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->orderBy('project')->fetchPairs('project', 'project');
        $linkedProjects = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('project')->in($storyProjects)->andWhere('product')->eq($productID)->orderBy('project')->fetchPairs('project','project');
        $unlinkedProjects = array_diff($storyProjects, $linkedProjects);
        foreach($unlinkedProjects as $projectID)
        {
            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $productID;
            $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
        }
    }

    /**
     * Update parent status.
     *
     * @param  int    $storyID
     * @param  int    $parentID
     * @param  bool   $createAction
     * @access public
     * @return object|bool
     */
    public function updateParentStatus(int $storyID, int $parentID = 0, bool $createAction = true): object|bool
    {
        $childStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(empty($parentID)) $parentID = $childStory->parent;
        if($parentID <= 0) return true;

        $childrenStatus = $this->dao->select('id,status')->from(TABLE_STORY)->where('parent')->eq($parentID)->andWhere('deleted')->eq(0)->fetchPairs('status', 'status');
        if(empty($childrenStatus))
        {
            $this->dao->update(TABLE_STORY)->set('parent')->eq('0')->where('id')->eq($parentID)->exec();
            return true;
        }

        $oldParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->andWhere('deleted')->eq(0)->fetch();
        if(empty($oldParentStory))
        {
            $this->dao->update(TABLE_STORY)->set('parent')->eq('0')->where('id')->eq($storyID)->exec();
            return true;
        }
        if($oldParentStory->parent != '-1') $this->dao->update(TABLE_STORY)->set('parent')->eq('-1')->where('id')->eq($parentID)->exec();
        $this->computeEstimate($parentID);

        $status = $oldParentStory->status;
        if(count($childrenStatus) == 1 and current($childrenStatus) == 'closed') $status = current($childrenStatus); // Close parent story.
        if($oldParentStory->status == 'closed') $status = $this->getActivateStatus($parentID); // Activate parent story.

        $action    = '';
        $preStatus = '';
        if($status and $oldParentStory->status != $status)
        {
            $story = $this->storyTao->doUpdateParentStatus($parentID, $status);
            if(dao::isError()) return false;
            if(!$createAction) return $story;

            if(strpos('launched,active,draft,changing', $status) !== false) $action = 'Activated';
            if($status == 'closed')
            {
                /* Record the status before closed. */
                $action    = 'closedbysystem';
                $preStatus = $oldParentStory->status;
                $isChanged = $oldParentStory->changedBy ? true : false;
                if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';
            }

            if(in_array($this->config->edition, array('max', 'ipd')) && $oldParentStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldParentStory->feedback, $newParentStory->status, $oldParentStory->status);
        }
        else
        {
            $action = 'Edited';
            if(dao::isError()) return false;
        }

        $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->fetch();
        $changes        = common::createChanges($oldParentStory, $newParentStory);
        if($action and $changes)
        {
            $actionID = $this->loadModel('action')->create('story', $parentID, $action, '', $preStatus, '', false);
            $this->action->logHistory($actionID, $changes);
        }
        return true;
    }

    /**
     * 如果软件需求变更了，则更新软件需求和用户需求关系表中的版本。
     * If the story changed, update the version filed of the requirement in relation table.
     *
     * @param  object $story
     * @access public
     * @return void
     */
    public function updateStoryVersion(object $story): void
    {
        $changedStories = $this->getChangedStories($story);
        if(empty($changedStories)) return;

        foreach($changedStories as $changedStory)
        {
            $this->dao->update(TABLE_RELATION)
                ->set('AVersion')->eq($changedStory->version)
                ->where('AType')->eq('requirement')
                ->andWhere('BType')->eq('story')
                ->andWhere('relation')->eq('subdivideinto')
                ->andWhere('AID')->eq($changedStory->id)
                ->andWhere('BID')->eq($story->id)
                ->exec();
        }
    }

    /**
     * Update the story order of plan.
     *
     * @param  int    $storyID
     * @param  string $planIdList
     * @param  string $oldPlanIdList
     * @access public
     * @return void
     */
    public function updateStoryOrderOfPlan(int $storyID, string $planIdList = '', string $oldPlanIdList = ''): void
    {
        $planIdList    = $planIdList    ? explode(',', $planIdList)    : array();
        $oldPlanIdList = $oldPlanIdList ? explode(',', $oldPlanIdList) : array();

        /* Get the ids to be inserted and deleted by comparing plan ids. */
        $plansTobeInsert = array_diff($planIdList,    $oldPlanIdList);
        $plansTobeDelete = array_diff($oldPlanIdList, $planIdList);

        /* Delete old story sort of plan. */
        if(!empty($plansTobeDelete)) $this->dao->delete()->from(TABLE_PLANSTORY)->where('story')->eq($storyID)->andWhere('plan')->in($plansTobeDelete)->exec();
        if(!empty($plansTobeInsert))
        {
            /* Get last story order of plan list. */
            $maxOrders = $this->dao->select('plan, max(`order`) as `order`')->from(TABLE_PLANSTORY)->where('plan')->in($plansTobeInsert)->groupBy('plan')->fetchPairs();
            foreach($plansTobeInsert as $planID)
            {
                /* Set story order in new plan. */
                $data = new stdClass();
                $data->plan  = $planID;
                $data->story = $storyID;
                $data->order = zget($maxOrders, $planID, 0) + 1;

                $this->dao->replace(TABLE_PLANSTORY)->data($data)->exec();
            }
        }
    }

    /**
     * Compute parent story estimate.
     *
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function computeEstimate(int $storyID): bool
    {
        if(!$storyID) return true;

        $estimates = $this->dao->select('`id`,`estimate`')->from(TABLE_STORY)->where('parent')->eq($storyID)->andWhere('deleted')->eq(0)->fetchPairs('id', 'estimate');
        if(empty($estimates)) return true;

        $estimate = round(array_sum($estimates), 2);
        $this->dao->update(TABLE_STORY)->set('estimate')->eq($estimate)->where('id')->eq($storyID)->exec();
        return !dao::isError();
    }

    /**
     * Batch update stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function batchUpdate(array $stories): bool
    {
        /* Init vars. */
        $oldStories  = $this->getByList(array_keys($stories));
        $unlinkPlans = array();
        $link2Plans  = array();

        foreach($stories as $storyID => $story)
        {
            unset($story->status);
            $oldStory = $oldStories[$storyID];
            if($story->plan != $oldStory->plan)
            {
                if(!empty($oldStory->plan)) $unlinkPlans[$oldStory->plan] = empty($unlinkPlans[$oldStory->plan]) ? $storyID : "{$unlinkPlans[$oldStory->plan]},{$storyID}";
                if(!empty($story->plan))    $link2Plans[$story->plan]     = empty($link2Plans[$story->plan])     ? $storyID : "{$link2Plans[$story->plan]},{$storyID}";
            }
        }

        $this->loadModel('action');
        foreach($stories as $storyID => $story)
        {
            $oldStory = $oldStories[$storyID];
            $this->dao->update(TABLE_STORY)->data($story)
                ->autoCheck()
                ->checkIF($story->closedBy, 'closedReason', 'notempty')
                ->checkIF($story->closedReason == 'done', 'stage', 'notempty')
                ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                ->where('id')->eq((int)$storyID)
                ->exec();

            if(dao::isError()) return false;

            /* Update story sort of plan when story plan has changed. */
            if($oldStory->plan != $story->plan) $this->updateStoryOrderOfPlan($storyID, $story->plan, $oldStory->plan);

            $this->executeHooks($storyID);
            if($oldStory->type == 'story') $this->batchChangeStage(array($storyID), $story->stage);
            if($story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');

            $changes = common::createChanges($oldStory, $story);
            if($changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if($this->config->edition != 'open' && $oldStory->feedback && !isset($feedbacks[$oldStory->feedback]))
            {
                $feedbacks[$oldStory->feedback] = $oldStory->feedback;
                $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
            }
        }

        $this->loadModel('score')->create('ajax', 'batchEdit');
        foreach($unlinkPlans as $planID => $stories) $this->action->create('productplan', $planID, 'unlinkstory', '', $stories);
        foreach($link2Plans as $planID => $stories)  $this->action->create('productplan', $planID, 'linkstory', '', $stories);

        return true;
    }

    /**
     * Review a story.
     *
     * @param  int    $storyID
     * @param  object $story
     * @param  string $comment
     * @access public
     * @return bool
     */
    public function review(int $storyID, object $story, string $comment = ''): bool
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = helper::now();
        $account  = $this->app->user->account;
        if(!str_contains(",{$oldStory->reviewedBy},", ",{$account}")) $story->reviewedBy = $oldStory->reviewedBy . ',' . $account;

        $this->dao->update(TABLE_STORYREVIEW)
            ->set('result')->eq($story->result)
            ->set('reviewDate')->eq($now)
            ->where('story')->in($storyID . ($oldStory->twins ? ",{$oldStory->twins}" : ''))
            ->andWhere('version')->eq($oldStory->version)
            ->andWhere('reviewer')->eq($account)
            ->exec();

        $story = $this->updateStoryByReview($storyID, $oldStory, $story);

        $skipFields      = 'finalResult,result';
        $isSuperReviewer = $this->storyTao->isSuperReviewer();
        if($isSuperReviewer)
        {
            $reviewers = $this->getReviewerPairs($storyID, $oldStory->version);
            if(count($reviewers) > 1) $skipFields .= ',closedReason';
        }

        $this->dao->update(TABLE_STORY)->data($story, $skipFields)->autoCheck()->checkFlow()->where('id')->eq($storyID)->exec();
        if(dao::isError()) return false;

        if($story->result != 'reject') $this->setStage($storyID);

        $changes = common::createChanges($oldStory, $story);
        if($changes)
        {
            $story->id = $storyID;
            $actionID  = $this->recordReviewAction($story, $comment);
            if($actionID) $this->action->logHistory($actionID, $changes);
        }

        if(!empty($oldStory->twins)) $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Reviewed');

        return true;
    }

    /**
     * Batch review stories.
     *
     * @param  array   $storyIdList
     * @param  string  $result
     * @param  string  $reason
     * @access public
     * @return string|null
     */
    public function batchReview(array $storyIdList, string $result, string $reason = ''): string|null
    {
        $now           = helper::now();
        $account       = $this->app->user->account;
        $reviewedTwins = array();
        $this->loadModel('action');

        $storyIdList = array_filter($storyIdList);
        foreach($storyIdList as $index => $storyID)
        {
            /* 处理选中的子需求的ID，截取-后的子需求ID。*/
            /* Process selected child story ID. */
            if(strpos($storyID, '-') !== false) $storyIdList[$index] = substr($storyID, strpos($storyID, '-') + 1);
        }

        $oldStories          = $this->getByList($storyIdList);
        $hasResult           = $this->dao->select('story,version,result')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->andWhere('reviewer')->eq($account)->andWhere('result')->ne('')->orderBy('version')->fetchAll('story');
        $reviewerList        = $this->dao->select('story,reviewer,result,version')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->orderBy('version')->fetchGroup('story', 'reviewer');
        $isSuperReviewer     = $this->storyTao->isSuperReviewer();
        $cannotReviewStories = array();
        $cannotReviewTips    = $this->lang->product->reviewStory;

        foreach($storyIdList as $storyID)
        {
            $storyID  = (int)$storyID;
            $oldStory = zget($oldStories, $storyID, '');
            if(empty($oldStory)) continue;

            if($oldStory->status != 'reviewing') continue;
            if($oldStory->version > 1 and $result == 'reject') continue;
            if(isset($hasResult[$storyID]) and $hasResult[$storyID]->version == $oldStory->version) continue;
            if(!isset($reviewerList[$storyID][$account]) && !$isSuperReviewer)
            {
                $cannotReviewStories[$storyID] = "#{$storyID}";
                continue;
            }

            $reviewerPairs = array();
            foreach($reviewerList[$storyID] as $reviewer => $reviewerInfo)
            {
                if($reviewerInfo->version != $oldStory->version) continue;
                $reviewerPairs[$reviewer] = $reviewerInfo->result;
            }
            $reviewerPairs[$account] = $result;

            $story = new stdClass();
            $story->reviewedDate   = $now;
            $story->lastEditedBy   = $account;
            $story->lastEditedDate = $now;
            $story->status         = $oldStory->status;
            if(!str_contains(",{$oldStory->reviewedBy},", ",{$account},")) $story->reviewedBy = $oldStory->reviewedBy . ',' . $account;

            $twinsIdList = $storyID . ($oldStory->twins ? ",{$oldStory->twins}" : '');
            $this->dao->update(TABLE_STORYREVIEW)->set('result')->eq($result)->set('reviewDate')->eq($now)->where('story')->in($twinsIdList)->andWhere('version')->eq($oldStory->version)->andWhere('reviewer')->eq($account)->exec();

            /* Update the story status by review rules. */
            $reviewedBy = explode(',', trim($story->reviewedBy, ','));
            if($isSuperReviewer) $story = $this->superReview($storyID, $oldStory, $story, $result, $reason);
            if(!$isSuperReviewer and !array_diff(array_keys($reviewerPairs), $reviewedBy))
            {
                $reviewResult = $this->getReviewResult($reviewerPairs);
                $story        = $this->setStatusByReviewResult($story, $oldStory, $reviewResult, $reason);
            }

            $this->dao->update(TABLE_STORY)->data($story, 'finalResult')->autoCheck()->where('id')->eq($storyID)->exec();
            $this->setStage((int)$storyID);

            $story->id = $storyID;
            $this->recordReviewAction($story, $result, $reason);

            /* Sync twins. */
            $changes = common::createChanges($oldStory, $story);
            if(!empty($oldStory->twins))
            {
                $twins = $oldStory->twins;
                foreach(explode(',', $twins) as $twinID)
                {
                    if(in_array($twinID, $storyIdList) or isset($reviewedTwins[$twinID])) $twins = str_replace(",$twinID,", ',', $twins);
                }
                $this->syncTwins($storyID, trim($twins, ','), $changes, 'Reviewed');
                foreach(explode(',', trim($twins, ',')) as $reviewedID) $reviewedTwins[$reviewedID] = $reviewedID;
            }
        }
        if(!$isSuperReviewer && empty($reviewerList) && !empty($cannotReviewStories)) return sprintf($cannotReviewTips, implode(',', $cannotReviewStories));

        if($cannotReviewStories) return sprintf($cannotReviewTips, implode(',', $cannotReviewStories));
        return null;
    }

    /**
     * Recall the story review.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function recallReview(int $storyID): void
    {
        $oldStory    = $this->fetchById($storyID);
        $isChanged   = $oldStory->changedBy ? true : false;
        $twinsIdList = $storyID . ($oldStory->twins ? ",{$oldStory->twins}" : '');

        $story = new stdclass();
        $story->status = $isChanged ? 'changing' : 'draft';
        $this->dao->update(TABLE_STORY)->set('status')->eq($story->status)->where('id')->in($twinsIdList)->exec();

        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->in($twinsIdList)->andWhere('version')->eq($oldStory->version)->exec();

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'recalled');
    }

    /**
     * Recall the story change.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function recallChange(int $storyID): void
    {
        $oldStory = $this->fetchById($storyID);
        if(empty($oldStory)) return;

        /* Update story title and version and status. */
        $twinsIdList = $storyID . ($oldStory->twins ? ",{$oldStory->twins}" : '');
        $titleList   = $this->dao->select('story,title')->from(TABLE_STORYSPEC)->where('story')->in($twinsIdList)->andWHere('version')->eq($oldStory->version - 1)->fetchAll('story');

        foreach(explode(',', $twinsIdList) as $twinID)
        {
            $story = new stdclass();
            $story->title   = $titleList[$twinID]->title;
            $story->version = $oldStory->version - 1;
            $story->status  = ($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $this->config->vision == 'rnd') ? 'launched' : 'active';
            $this->dao->update(TABLE_STORY)->set('title')->eq($story->title)->set('version')->eq($story->version)->set('status')->eq($story->status)->where('id')->eq($storyID)->exec();
        }

        /* Delete versions that is after this version. */
        $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->in($twinsIdList)->andWHere('version')->eq($oldStory->version)->exec();
        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->in($twinsIdList)->andWhere('version')->eq($oldStory->version)->exec();

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'recalledChange');
    }

    /**
     * Submit review.
     *
     * @param  int    $storyID
     * @param  object $story
     * @access public
     * @return array|bool
     */
    public function submitReview(int $storyID, object $story): array|false
    {
        $oldStory     = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $reviewerList = $this->getReviewerPairs($oldStory->id, $oldStory->version);
        $oldStory->reviewer = implode(',', array_keys($reviewerList));

        $twinsIdList = $storyID . ($oldStory->twins ? ",{$oldStory->twins}" : '');
        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->in($twinsIdList)->andWhere('version')->eq($oldStory->version)->exec();

        foreach(explode(',', $twinsIdList) as $twinID)
        {
            if(empty($twinID)) continue;
            $this->storyTao->doCreateReviewer((int)$twinID, $story->reviewer, $oldStory->version);
        }

        $story->reviewer = implode(',', $story->reviewer);
        if($story->reviewer) $story->status = ($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') ? 'launched' : 'reviewing';

        $this->dao->update(TABLE_STORY)->data($story, 'reviewer')->where('id')->in($twinsIdList)->exec();

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'submitReview');
        if(!dao::isError()) return $changes;

        return false;
    }

    /**
     * Subdivide story
     *
     * @param  int    $storyID
     * @param  array  $stories
     * @access public
     * @return void
     */
    public function subdivide(int $storyID, array $SRList): void
    {
        $now      = helper::now();
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        /* 如果该需求是用户需求，则为细分操作，记录用户需求和软件需求的关联关系。 */
        if($oldStory->type == 'requirement')
        {
            foreach($SRList as $SRID) $this->storyTao->doCreateURRelations($SRID, array($storyID));
            return;
        }

        /* 如果该需求是软件需求，则为分解子需求操作。 */
        /* Set parent to child story. */
        $this->dao->update(TABLE_STORY)->set('parent')->eq($storyID)->where('id')->in($SRList)->exec();
        $this->computeEstimate($storyID);

        /* Set childStories. */
        $childStories = implode(',', $SRList);
        $newStory     = new stdClass();
        $newStory->parent         = '-1';
        $newStory->plan           = '';
        $newStory->lastEditedBy   = $this->app->user->account;
        $newStory->lastEditedDate = $now;
        $newStory->childStories   = trim($oldStory->childStories . ',' . $childStories, ',');
        $this->dao->update(TABLE_STORY)->data($newStory)->autoCheck()->where('id')->eq($storyID)->exec();

        $actionID = $this->loadModel('action')->create('story', $storyID, 'createChildrenStory', '', $childStories);
        $this->action->logHistory($actionID, common::createChanges($oldStory, $newStory));
    }

    /**
     * 关闭需求。
     * Close the story.
     *
     * @param  int    $storyID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function close(int $storyID, object $postData): array|false
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $story    = $postData;

        if(!empty($story->duplicateStory))
        {
            $duplicateStoryID = $this->dao->select('id')->from(TABLE_STORY)->where('id')->eq($story->duplicateStory)->fetch();
            if(empty($duplicateStoryID))
            {
                dao::$errors[] = sprintf($this->lang->story->errorDuplicateStory, $story->duplicateStory);
                return false;
            }
        }

        if($story->closedReason == 'duplicate' and empty($story->duplicateStory))
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->story->duplicateStory);
            return false;
        }

        $this->lang->story->comment = $this->lang->comment;
        $this->dao->update(TABLE_STORY)->data($story, 'comment,closeSync')
            ->autoCheck()
            ->batchCheck($this->config->story->close->requiredFields, 'notempty')
            ->checkIF($story->closedReason == 'duplicate', 'duplicateStory', 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)
            ->exec();
        if(dao::isError()) return false;

        /* Update parent story status and stage. */
        if($oldStory->parent > 0)
        {
            $this->updateParentStatus($storyID, $oldStory->parent);
            $this->setStage($oldStory->parent);
        }
        if(!dao::isError())
        {
            $this->setStage($storyID);
            $this->loadModel('score')->create('story', 'close', $storyID);

            if(in_array($this->config->edition, array('max', 'ipd')) && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
        }

        $changes = common::createChanges($oldStory, $story);
        if(!empty($postData->closeSync))
        {
            /* batchUnset twinID from twins.*/
            $replaceSql = "UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins,',$storyID,', ',') WHERE `product` = $oldStory->product";
            $this->dbh->exec($replaceSql);

            /* Update twins to empty by twinID and if twins eq ','.*/
            $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();

            if(!dao::isError()) $this->loadModel('action')->create('story', $storyID, 'relieved');
        }
        if(!empty($oldStory->twins) and empty($postData->closeSync)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Closed');
        return $changes;
    }

    /**
     * 批量关闭需求。
     * Batch close the stories.
     *
     * @param  array $stories
     * @access public
     * @return array|bool
     */
    public function batchClose(array $stories): array|bool
    {
        $this->loadModel('action');
        $oldStories = $this->getByList(array_keys($stories));
        foreach($stories as $storyID => $story)
        {
            if(empty($story->closedReason)) continue;

            $oldStory = $oldStories[$storyID];

            $this->dao->update(TABLE_STORY)->data($story, 'comment')->autoCheck()
                ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                ->where('id')->eq($storyID)
                ->exec();

            if(dao::isError()) return dao::$errors[] = 'story#' . $storyID . dao::getError(true);

            /* Update parent story status. */
            if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);
            $this->setStage($storyID);

            $changes = common::createChanges($oldStory, $story);
            if($changes)
            {
                $preStatus = $oldStory->status;
                $isChanged = $oldStory->changedBy ? true : false;
                if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';

                $actionID = $this->action->create('story', $storyID, 'Closed', $story->comment ?? '', ucfirst($story->closedReason) . (!empty($story->duplicateStory) ? ':' . (int)$story->duplicateStory : '') . "|{$preStatus}");
                $this->action->logHistory($actionID, $changes);

                if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Closed');
            }

            if($this->config->edition != 'open' && $oldStory->feedback && !isset($feedbacks[$oldStory->feedback]))
            {
                $feedbacks[$oldStory->feedback] = $oldStory->feedback;
                $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
            }

            $this->loadModel('score')->create('story', 'close', $storyID);
        }
        return true;
    }

    /**
     * Batch change the module of story.
     *
     * @param  array  $storyIdList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule(array $storyIdList, int $moduleID): array
    {
        $now        = helper::now();
        $allChanges = array();
        $oldStories = $this->getByList($storyIdList);
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if(empty($oldStory)) continue;
            if($moduleID == $oldStory->module) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->module         = $moduleID;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        return $allChanges;
    }

    /**
     * Batch change the plan of story.
     *
     * @param  array  $storyIdList
     * @param  int    $planID
     * @param  int    $oldPlanID
     * @access public
     * @return array
     */
    public function batchChangePlan(array $storyIdList, int $planID, int $oldPlanID = 0): array
    {
        /* Prepare data. */
        $now            = helper::now();
        $oldStories     = $this->getByList($storyIdList);
        $oldStoryStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->fetchGroup('story', 'branch');
        $plan           = $this->loadModel('productplan')->getById($planID);
        if(empty($plan))
        {
            $plan = new stdClass();
            $plan->branch = BRANCH_MAIN;
        }

        $productIdList = array();
        foreach($oldStories as $oldStory) $productIdList[$oldStory->product] = $oldStory->product;
        $products = $this->loadModel('product')->getByIdList($productIdList);

        /* Cycle every story and process it's plan and stage. */
        $allChanges  = array();
        $unlinkPlans = array();
        $link2Plans  = array();
        foreach($storyIdList as $storyID)
        {
            $oldStory = zget($oldStories, $storyID, null);
            if(empty($oldStory)) continue;
            if($oldStory->branch != BRANCH_MAIN and $plan->branch != BRANCH_MAIN and !in_array($oldStory->branch, explode(',', $plan->branch))) continue;

            /* Ignore parent story, closed story and story linked to this plan already. */
            if($oldStory->parent < 0) continue;
            if($oldStory->status == 'closed') continue;
            if(strpos(",{$oldStory->plan},", ",$planID,") !== false) continue;

            /* Init story and set last edited data. */
            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;

            /* Remove old plan from the plan field. */
            if($oldPlanID) $story->plan = trim(str_replace(",$oldPlanID,", ',', ",$oldStory->plan,"), ',');

            /* Update the order of the story in the plan. */
            $this->updateStoryOrderOfPlan((int)$storyID, (string)$planID, $oldStory->plan);

            /* Replace plan field if product is normal or not linked to plan or story linked to a branch. */
            $productType = $products[$oldStory->product]->type;
            if($productType == 'normal') $story->plan = $planID;
            if(empty($oldPlanID)) $story->plan = $planID;
            if($oldStory->branch) $story->plan = $planID;

            /* Change stage. */
            if($planID)
            {
                if($oldStory->stage == 'wait') $story->stage = 'planned';
                if($productType != 'normal' and $oldStory->branch == 0)
                {
                    if(!empty($oldPlanID)) $story->plan = trim("{$story->plan},{$planID}", ',');
                    foreach(explode(',', $plan->branch) as $planBranch)
                    {
                        if(isset($oldStoryStages[$storyID][$planBranch])) continue;

                        $story->stage  = 'planned';
                        $newStoryStage = new stdclass();
                        $newStoryStage->story  = $storyID;
                        $newStoryStage->branch = $planBranch;
                        $newStoryStage->stage  = $story->stage;
                        $this->dao->insert(TABLE_STORYSTAGE)->data($newStoryStage)->autoCheck()->exec();
                    }
                }
            }

            /* Update story and recompute stage. */
            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();

            if(!$planID) $this->setStage($storyID);
            if(!dao::isError())
            {
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
                if($story->plan != $oldStory->plan and !empty($oldStory->plan) and strpos((string)$oldStory->plan, ',') === false) $unlinkPlans[$oldStory->plan] = empty($unlinkPlans[$oldStory->plan]) ? $storyID : "{$unlinkPlans[$oldStory->plan]},$storyID";
                if($story->plan != $oldStory->plan and !empty($story->plan)    and strpos((string)$story->plan, ',') === false)    $link2Plans[$story->plan]     = empty($link2Plans[$story->plan])     ? $storyID : "{$link2Plans[$story->plan]},$storyID";
            }
        }

        if(!dao::isError())
        {
            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $stories) $this->action->create('productplan', $planID, 'unlinkstory', '', $stories);
            foreach($link2Plans  as $planID => $stories) $this->action->create('productplan', $planID, 'linkstory', '', $stories);
        }

        return $allChanges;
    }

    /**
     * Batch change branch.
     *
     * @param  array  $storyIdList
     * @param  int    $branchID
     * @param  string $confirm        yes|null
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function batchChangeBranch(array $storyIdList, int $branchID, string $confirm = '', array $plans = array()): array
    {
        $now         = helper::now();
        $allChanges  = array();
        $oldStories  = $this->getByList($storyIdList);
        $story       = current($oldStories);
        $productID   = $story->product;
        $mainModules = $this->dao->select('id')->from(TABLE_MODULE)->where('root')->eq($productID)->andWhere('branch')->eq(0)->andWhere('type')->eq('story')->fetchPairs('id', 'id');

        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->branch         = $branchID;
            $story->module         = ($oldStory->branch != $branchID and !in_array($oldStory->module, $mainModules)) ? 0 : $oldStory->module;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError())
            {
                if($confirm == 'yes')
                {
                    $planIdList         = array();
                    $conflictPlanIdList = array();

                    /* Determine whether there is a conflict between the branch of the story and the linked plan. */
                    if($oldStory->branch != $branchID and $branchID != BRANCH_MAIN and isset($plans[$storyID]))
                    {
                        foreach($plans[$storyID] as $planID => $plan)
                        {
                            if($plan->branch != $branchID) $conflictPlanIdList[$planID] = $planID;
                            if($plan->branch == $branchID) $planIdList[$planID]         = $planID;
                        }

                        /* If there is a conflict in the linked plan when the branch story to be modified, the linked with the conflicting plan will be removed. */
                        if($conflictPlanIdList) $this->dao->delete()->from(TABLE_PLANSTORY)->where('story')->eq($storyID)->andWhere('plan')->in(implode(',', $conflictPlanIdList))->exec();
                        if($planIdList)
                        {
                            $story->plan = implode(',', $planIdList);
                            $this->dao->update(TABLE_STORY)->set('plan')->eq($story->plan)->where('id')->eq($storyID)->exec();
                        }
                    }
                }
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
            }
        }
        return $allChanges;
    }

    /**
     * Batch change the stage of story.
     *
     * @param array  $storyIdList
     * @param string $stage
     *
     * @access public
     * @return string|null
     */
    public function batchChangeStage(array $storyIdList, string $stage): string|null
    {
        $now           = helper::now();
        $account       = $this->app->user->account;
        $oldStories    = $this->getByList($storyIdList);
        $ignoreStories = '';
        $this->loadModel('action');
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'draft' or $oldStory->status == 'closed')
            {
                $ignoreStories .= "#{$storyID} ";
                continue;
            }

            $story = new stdclass();
            $story->lastEditedBy   = $account;
            $story->lastEditedDate = $now;
            $story->stage          = $stage;
            $story->stagedBy       = $account;

            /* Add for record released date. */
            if($story->stage == 'released') $story->releasedDate = $now;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            $this->dao->update(TABLE_STORYSTAGE)->set('stage')->eq($stage)->set('stagedBy')->eq($account)->where('story')->eq((int)$storyID)->exec();
            if(!dao::isError())
            {
                $changes  = common::createChanges($oldStory, $story);
                $action   = $stage == 'verified' ? 'Verified' : 'Edited';
                $actionID = $this->action->create('story', (int)$storyID, $action);
                $this->action->logHistory($actionID, $changes);
            }
        }

        if($ignoreStories) return sprintf($this->lang->story->ignoreChangeStage, $ignoreStories);
        return null;
    }

    /**
     * Batch to task.
     *
     * @param  int    $executionID
     * @param  array  $tasks
     * @access public
     * @return array|false
     */
    public function batchToTask(int $executionID, array $tasks): array|false
    {
        /* load Module and get the data from the post and get the current time. */
        $this->loadModel('action');
        $this->loadModel('task');

        $taskIdList = array();
        foreach($tasks as $task)
        {
            $this->dao->insert(TABLE_TASK)->data($task)->autoCheck()
                ->batchCheck($this->config->task->create->requiredFields, 'notempty')
                ->exec();

            if(dao::isError()) return false;

            $taskID       = $this->dao->lastInsertID();
            $taskIdList[] = $taskID;

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;
            $taskSpec->estStarted = $task->estStarted;
            $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            if($task->story) $this->setStage($task->story);
            $this->action->create('task', $taskID, 'Opened', '');
        }

        $this->loadModel('kanban')->updateLane($executionID, 'task');
        return $taskIdList;
    }

    /**
     * Assign story.
     *
     * @param  int    $storyID
     * @access public
     * @return array|false
     */
    public function assign(int $storyID): array|false
    {
        $oldStory   = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now        = helper::now();
        $assignedTo = $this->post->assignedTo;
        if($assignedTo == $oldStory->assignedTo) return array();

        $story = fixer::input('post')
            ->add('id', $storyID)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('assignedDate', $now)
            ->stripTags($this->config->story->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->assignto['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->where('id')->eq((int)$storyID)->exec();

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Assigned');
        if(!dao::isError()) return $changes;
        return false;
    }

    /**
     * Batch assign to.
     *
     * @param  array  $storyIdList
     * @param  string $assignedTo
     * @access public
     * @return array
     */
    public function batchAssignTo(array $storyIdList, string $assignedTo = ''): array
    {
        $this->loadModel('action');
        $now           = helper::now();
        $allChanges    = array();
        $oldStories    = $this->getByList($storyIdList);
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];

            if($oldStory->status == 'closed') continue;
            if($assignedTo == $oldStory->assignedTo) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->assignedTo     = $assignedTo;
            $story->assignedDate   = $now;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            $allChanges[$storyID] = common::createChanges($oldStory, $story);

            $actionID = $this->action->create('story', (int)$storyID, 'Assigned', '', $assignedTo);
            $this->action->logHistory($actionID, $allChanges[$storyID]);
        }

        return $allChanges;
    }

    /**
     * 激活需求。
     * Activate a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array|false
     */
    public function activate(int $storyID, object $postData): array|false
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();

        /* Get status after activation. */
        $story = $postData;
        $story->status = $this->getActivateStatus($storyID);

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';

        $this->dao->update(TABLE_STORY)->data($story, 'comment')->autoCheck()->checkFlow()->where('id')->eq($storyID)->exec();

        if($story->status == 'active')
        {
            $twinsIdList = $storyID . (!empty($oldStory->twins) ? ",{$oldStory->twins}" : '');
            $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->in($twinsIdList)->exec();
        }

        $this->setStage($storyID);

        /* Update parent story status. */
        if(!empty($oldStory->parent) && $oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Activated');
        return $changes;
    }

    /**
     * Set stage of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function setStage(int $storyID): bool
    {
        $story  = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(empty($story)) return false;

        /* 获取已经存在的分支阶段. */
        $oldStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchAll('branch');
        $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->exec();

        /* 手动设置了阶段，就不需要字段计算阶段了。 */
        $product   = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fetch();
        $hasBranch = ($product and $product->type != 'normal' and empty($story->branch));
        if(!empty($story->stagedBy) and $story->status != 'closed') return true;

        /* 获取需求关联的分支和项目。 */
        list($linkedBranches, $linkedProjects) = $this->storyTao->getLinkedBranchesAndProjects($storyID);

        /* 设置默认阶段。 */
        $stages = array();
        if($hasBranch) $stages = $this->storyTao->getDefaultStages($story->plan, $linkedProjects ? $linkedBranches : array());

        /* When the status is closed, stage is also changed to closed. */
        if($story->status == 'closed') return $this->storyTao->setStageToClosed($storyID, array_merge($linkedBranches, array_keys($stages)), $linkedProjects);

        /* If no executions, in plan, stage is planned. No plan, wait. */
        if(!$linkedProjects) return $this->storyTao->setStageToPlanned($storyID, $stages, $oldStages);

        /* 根据需求关联的任务状态统计，计算需求的阶段。 */
        $taskStat = $this->storyTao->getLinkedTaskStat($storyID, $linkedProjects);
        $stages   = $this->storyTao->computeStagesByTasks($storyID, $taskStat, $stages, $linkedProjects);

        $this->storyTao->updateStage($storyID, $stages, $oldStages, $linkedProjects);
        return true;
    }

    /**
     * 获取可关联的需求列表。
     * Get the stories to link.
     *
     * @param  int     $storyID
     * @param  string  $type           linkStories|linkRelateSR|linkRelateUR
     * @param  string  $browseType     bySearch
     * @param  int     $queryID
     * @param  string  $storyType      story|requirement
     * @param  object  $pager
     * @param  string  $excludeStories
     * @access public
     * @return array
     */
    public function getStories2Link(int $storyID, string $type = 'linkStories', string $browseType = 'bySearch', int $queryID = 0, string $storyType = 'story', object $pager = null, string $excludeStories = ''): array
    {
        $story         = $this->getById($storyID);
        $tmpStoryType  = $storyType == 'story' ? 'requirement' : 'story';
        $stories2Link  = array();
        if($type == 'linkRelateSR' or $type == 'linkRelateUR')
        {
            $tmpStoryType   = $story->type;
            $linkStoryField = $story->type == 'story' ? 'linkStories' : 'linkRequirements';
            $storyIDList    = $story->id . ',' . $excludeStories . ',' . $story->{$linkStoryField};
        }
        else
        {
            $storyIDList = $this->storyTao->getRelation($storyID, $story->type);
        }

        if($browseType == 'bySearch')
        {
            $stories2Link = $this->getBySearch($story->product, $story->branch, $queryID, 'id_desc', 0, $tmpStoryType, $storyIDList, $pager);
        }
        elseif($type != 'linkRelateSR' and $type != 'linkRelateUR')
        {
            $status = $storyType == 'story' ? 'active' : 'all';
            $stories2Link = $this->getProductStories($story->product, $story->branch, '', $status, $tmpStoryType, 'id_desc', true, $storyIDList, $pager);
        }

        if($type != 'linkRelateSR' and $type != 'linkRelateUR')
        {
            foreach($stories2Link as $id => $story)
            {
                if($storyType == 'story' and $story->status != 'active') unset($stories2Link[$id]);
            }
        }

        return $stories2Link;
    }

    /**
     * Get stories list of a product.
     *
     * @param  string|int       $productID
     * @param  array|string|int $branch
     * @param  array|string     $moduleIdList
     * @param  array|string     $status
     * @param  string           $type    requirement|story
     * @param  string           $orderBy
     * @param  array|string     $excludeStories
     * @param  object|null      $pager
     * @param  bool             $hasParent
     *
     * @access public
     * @return array
     */
    public function getProductStories(string|int $productID = 0, array|string|int $branch = 0, array|string $moduleIdList = '', array|string $status = 'all', string $type = 'story', string $orderBy = 'id_desc', bool $hasParent = true, array|string $excludeStories = '', object|null $pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getStories();

        $productQuery = $this->storyTao->buildProductsCondition($productID, $branch);
        $stories      = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere($productQuery)
            ->beginIF(!$hasParent)->andWhere("parent")->ge(0)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF(!empty($excludeStories))->andWhere('id')->notIN($excludeStories)->fi()
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('type')->eq($type)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $this->storyTao->mergePlanTitleAndChildren($productID, $stories, $type);
    }

    /**
     * 通过产品获取需求ID和需求信息的键值对。
     * Get stories pairs of a product.
     *
     * @param  int|array        $productIdList
     * @param  string|int       $branch
     * @param  array|string|int $moduleIdList
     * @param  string|array     $status       ''|all|changing|active|draft|closed|reviewing
     * @param  string           $order
     * @param  int              $limit
     * @param  string           $type         full|all
     * @param  string           $storyType    requirement|story|demand
     * @param  bool|string      $hasParent
     * @access public
     * @return array
     */
    public function getProductStoryPairs(int|array $productIdList = 0, string|int $branch = 'all', array|string|int $moduleIdList = '', string|array $status = 'all', string $order = 'id_desc', int $limit = 0, string $type = 'full', string $storyType = 'story', bool|string $hasParent = true): array
    {
        $stories = $this->dao->select('t1.id, t1.title, t1.module, t1.pri, t1.estimate, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($productIdList)->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in("0,$branch")->fi()
            ->beginIF(!$hasParent or $hasParent == 'false')->andWhere('t1.parent')->ge(0)->fi()
            ->beginIF($status and $status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF($type != 'full' || $type != 'all')->andWhere('t1.type')->eq($storyType)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($order)
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories, $type, $limit);
    }

    /**
     * Get stories by assignedTo.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAssignedTo($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'assignedTo', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by openedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByOpenedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'openedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by reviewedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByReviewedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'reviewedBy', $account, $type, $orderBy, $pager, 'include');
    }

    /**
     * Get stories which need to review.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByReviewBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'reviewBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by closedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @return array
     */
    public function getByClosedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'closedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by status.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $status
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByStatus($productID, $branch, $modules, $status, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'status', $status, $type, $orderBy, $pager);
    }

    /**
     * Get stories by plan.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  array  $modules
     * @param  int    $plan
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     *
     * @access public
     * @return array
     */
    public function getByPlan($productID, $branch, $modules, $plan, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'plan', $plan, $type, $orderBy, $pager);
    }

    /**
     * Get stories by assignedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAssignedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'assignedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by a field.
     *
     * @param  int          $productID
     * @param  int|string   $branch
     * @param  string|array $modules
     * @param  string       $fieldName
     * @param  string       $fieldValue
     * @param  string       $type         requirement|story
     * @param  string       $orderBy
     * @param  object       $pager
     * @param  string       $operator     equal|include
     * @access public
     * @return array
     */
    public function getByField(int $productID, int|string $branch, string|array $modules, string $fieldName, string $fieldValue, string $type = 'story', string $orderBy = '', object|null $pager = null, string $operator = 'equal'): array
    {
        if(!$this->loadModel('common')->checkField(TABLE_STORY, $fieldName) and $fieldName != 'reviewBy' and $fieldName != 'assignedBy') return array();

        $actionIDList = array();
        if($fieldName == 'assignedBy') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($fieldValue)->fetchPairs('objectID', 'objectID');

        $sql = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1');
        if($fieldName == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version');

        $stories = $sql->where('t1.product')->in($productID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.type')->eq($type)
            ->beginIF($branch != 'all')->andWhere("t1.branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("t1.module")->in($modules)->fi()
            ->beginIF($operator == 'equal' and $fieldName != 'reviewBy' and $fieldName != 'assignedBy')->andWhere('t1.' . $fieldName)->eq($fieldValue)->fi()
            ->beginIF($operator == 'include' and $fieldName != 'reviewBy' and $fieldName != 'assignedBy')->andWhere('t1.' . $fieldName)->like("%$fieldValue%")->fi()
            ->beginIF($fieldName == 'reviewBy')
            ->andWhere('t2.reviewer')->eq($this->app->user->account)
            ->andWhere('t2.result')->eq('')
            ->andWhere('t1.status')->eq('reviewing')
            ->fi()
            ->beginIF($fieldName == 'assignedBy')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $this->storyTao->mergePlanTitleAndChildren($productID, $stories, $type);
    }

    /**
     * Get to be closed stories.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $type requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function get2BeClosed(int $productID, int|string $branch, string|array $modules, string $type = 'story', string $orderBy = '', object|null $pager = null): array
    {
        $stories = $this->dao->select("*,IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere('type')->eq($type)
            ->beginIF($branch and $branch != 'all')->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('stage')->in('developed,released')
            ->andWhere('status')->ne('closed')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $this->storyTao->mergePlanTitleAndChildren($productID, $stories, $type);
    }

    /**
     * Get stories through search.
     *
     * @access public
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  int|array   $executionID
     * @param  string      $type requirement|story
     * @param  string      $excludeStories
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getBySearch(int $productID, int|string $branch = '', int $queryID = 0, string $orderBy = '', int|array $executionID = 0, string $type = 'story', array|string $excludeStories = '', object|null $pager = null): array
    {
        $this->loadModel('product');
        $executionID = empty($executionID) ? 0 : $executionID;
        if(is_array($executionID))
        {
            $products = array();
            foreach($executionID as $id)
            {
                $productList = $this->product->getProducts($id);
                $products   += $productList;
            }
        }
        else
        {
            $products = empty($executionID) ? $this->product->getList(0, 'all', 0, 0, 'all') : $this->product->getProducts($executionID);
        }

        $this->loadModel('search')->setQuery('story', $queryID);

        $allProduct     = "`product` = 'all'";
        $storyQuery     = $this->session->storyQuery;
        $queryProductID = $productID;
        if(strpos($storyQuery, $allProduct) !== false)
        {
            $storyQuery     = str_replace($allProduct, '1=1', $storyQuery);
            $queryProductID = 'all';
        }

        $storyQuery = $storyQuery . ' AND `product` ' . helper::dbIN(array_keys($products));

        if($excludeStories)
        {
            $dbIN = helper::dbIN($excludeStories);
            $dbIN = strpos($dbIN, '=') === 0 ? "`id` !{$dbIN}" : "`id` NOT {$dbIN}";
            $storyQuery = $storyQuery . ' AND ' . $dbIN;
        }
        if($this->app->moduleName == 'productplan') $storyQuery .= " AND `status` NOT IN ('closed') AND `parent` >= 0 ";
        $allBranch = "`branch` = 'all'";
        if(!empty($executionID))
        {
            $normalProducts = array();
            $branchProducts = array();
            foreach($products as $product)
            {
                if($product->type != 'normal') $branchProducts[$product->id] = $product;
                if($product->type == 'normal') $normalProducts[$product->id] = $product;
            }

            $storyQuery .= ' AND (';
            if(!empty($normalProducts)) $storyQuery .= '`product` ' . helper::dbIN(array_keys($normalProducts));
            if(!empty($branchProducts))
            {
                $branches = array(BRANCH_MAIN => BRANCH_MAIN);
                if($branch === '')
                {
                    foreach($branchProducts as $product)
                    {
                        foreach($product->branches as $branchID) $branches[$branchID] = $branchID;
                    }
                }
                else
                {
                    $branches[$branch] = $branch;
                }

                $branches = implode(',', $branches);
                if(!empty($normalProducts)) $storyQuery .= " OR ";
                $storyQuery .= "(`product` " . helper::dbIN(array_keys($branchProducts)) . " AND `branch` " . helper::dbIN($branches) . ")";
            }
            if(empty($normalProducts) and empty($branchProducts)) $storyQuery .= '1 = 1';
            $storyQuery .= ') ';

            if($this->app->moduleName == 'release' or $this->app->moduleName == 'build')
            {
                $storyQuery .= " AND `status` NOT IN ('draft')"; // Fix bug #990.
            }
            else
            {
                $storyQuery .= " AND `status` NOT IN ('draft', 'reviewing', 'changing', 'closed')";
            }

            if($this->app->rawModule == 'build' and $this->app->rawMethod == 'linkstory') $storyQuery .= " AND `parent` != '-1'";
        }
        elseif(strpos($storyQuery, $allBranch) !== false)
        {
            $storyQuery = str_replace($allBranch, '1=1', $storyQuery);
        }
        elseif($branch !== 'all' and $branch !== '' and strpos($storyQuery, '`branch` =') === false and $queryProductID != 'all')
        {
            if($branch and strpos($storyQuery, '`branch` =') === false) $storyQuery .= " AND `branch` " . helper::dbIN($branch);
        }
        $storyQuery = preg_replace("/`plan` +LIKE +'%([0-9]+)%'/i", "CONCAT(',', `plan`, ',') LIKE '%,$1,%'", $storyQuery);

        return $this->getBySQL($queryProductID, $storyQuery, $orderBy, $pager, $type);
    }

    /**
     * Get stories by a sql.
     *
     * @param  int|string $productID    int|all
     * @param  string     $sql
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  string     $type requirement|story
     * @access public
     * @return array
     */
    public function getBySQL(int|string $productID, string $sql, string $orderBy, object|null $pager = null, string $type = 'story'): array
    {
        /* Get plans. */
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq('0')
            ->beginIF($productID != 'all' and $productID != '')->andWhere('product')->eq((int)$productID)->fi()
            ->fetchPairs();

        $review = $this->storyTao->getRevertStoryIdList((int)$productID);
        $sql = str_replace(array('`product`', '`version`', '`branch`'), array('t1.`product`', 't1.`version`', 't1.`branch`'), $sql);
        if(strpos($sql, 'result') !== false)
        {
            if(strpos($sql, 'revert') !== false)
            {
                $sql  = str_replace("AND `result` = 'revert'", '', $sql);
                $sql .= " AND t1.`id` " . helper::dbIN($review);
            }
            else
            {
                $sql = str_replace(array('`result`'), array('t3.`result`'), $sql);
            }
        }

        $tmpStories = $this->dao->select("DISTINCT t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->beginIF(strpos($sql, 'result') !== false)->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version')->fi()
            ->where($sql)
            ->beginIF($productID != 'all' && $productID != '' && $productID != 0)->andWhere('t1.`product`')->eq((int)$productID)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.type')->eq($type)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        if(!$tmpStories) return array();

        /* Process plans. */
        $stories = array();
        foreach($tmpStories as $story)
        {
            $story->planTitle = '';
            $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';
            $stories[$story->id] = $story;
        }

        return $stories;
    }

    /**
     * 获取某产品下的父需求的键值对列表。
     * Get parent story pairs.
     *
     * @param  int        $productID
     * @param  string|int $appendedStories
     * @access public
     * @return array
     */
    public function getParentStoryPairs(int $productID, string|int $appendedStories = ''): array
    {
        $stories = $this->dao->select('id, title')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('parent')->le(0)
            ->andWhere('type')->eq('story')
            ->andWhere('stage')->eq('wait')
            ->andWhere('status')->eq('active')
            ->andWhere('plan')->in('0,')
            ->andWhere('twins')->eq('')
            ->beginIF(!empty($appendedStories))->orWhere('id')->in($appendedStories)->fi()
            ->fetchPairs();
        return array(0 => '') + $stories;
    }

    /**
     * 关闭用户需求，如果所有细分的软件需求已被关闭。
     * Close the parent user requirement if all divided stories has been closed.
     *
     * @param  int    $storyID
     * @param  object $postData
     * @access public
     * @return void
     */
    public function closeParentRequirement(int $storyID, object $postData): void
    {
        $parentID = $this->dao->select('BID')->from(TABLE_RELATION)->where('AID')->eq($storyID)->fetch();
        if(empty($parentID)) return;

        $stories  = $this->dao->select('t2.id, t2.status')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t2.id=t1.AID')
            ->where('t1.BType')->eq('requirement')
            ->andWhere('t2.status')->ne('closed')
            ->andWhere('t2.type')->eq('story')
            ->fetchPairs();
        if(empty($stories)) $this->close($parentID->BID, $postData);
    }

    /**
     * Get stories of a user.
     *
     * @param  string     $account
     * @param  string     $type         the query type
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  string     $storyType    requirement|story
     * @param  string|int $shadow       all | 0 | 1
     * @param  int        $productID
     * @access public
     * @return array
     */
    public function getUserStories(string $account, string $type = 'assignedTo', string $orderBy = 'id_desc', object|null $pager = null, string $storyType = 'story', bool $includeLibStories = true, string|int $shadow = 0, int $productID = 0): array
    {
        $sql = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t2.shadow as shadow")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id');
        if($type == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version');

        $stories = $sql->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq($storyType)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all')
            ->beginIF($type == 'assignedTo')->andWhere('t1.assignedTo')->eq($account)->fi()
            ->beginIF($type == 'reviewBy')->andWhere('t3.reviewer')->eq($account)->andWhere('t3.result')->eq('')->andWhere('t1.status')->in('reviewing,changing')->fi()
            ->beginIF($type == 'openedBy')->andWhere('t1.openedBy')->eq($account)->fi()
            ->beginIF($type == 'reviewedBy')->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,$account,%")->fi()
            ->beginIF($type == 'closedBy')->andWhere('t1.closedBy')->eq($account)->fi()
            ->fi()
            ->beginIF($includeLibStories == false and $this->config->edition == 'max')->andWhere('t1.lib')->eq('0')->fi()
            ->beginIF($shadow !== 'all')->andWhere('t2.shadow')->eq((int)$shadow)->fi()
            ->beginIF($productID)->andWhere('t1.product')->eq((int)$productID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);
        $productIdList = array();
        foreach($stories as $story) $productIdList[$story->product] = $story->product;

        return $this->storyTao->mergePlanTitleAndChildren($productIdList, $stories, $storyType);
    }

    /**
     * 通过用户获取需求ID和需求信息的键值对。array(storyID => storyTitle, ...)
     * Get story pairs by account name of the user.
     *
     * @param  string    $account
     * @param  int       $limit
     * @param  string    $type              requirement|story
     * @param  array     $skipProductIDList
     * @param  int|array $appendStoryID
     * @access public
     * @return array
     */
    public function getUserStoryPairs(string $account, int $limit = 10, string $type = 'story', array|string $skipProductIDList = array(), int|string|array $appendStoryID = 0): array
    {
        return $this->dao->select('id, title')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq($type)
            ->andWhere('assignedTo')->eq($account)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('status')->ne('closed')
            ->andWhere('product')->ne(0)
            ->beginIF(!empty($skipProductIDList))->andWhere('product')->notin($skipProductIDList)->fi()
            ->beginIF(!empty($appendStoryID))->orWhere('id')->in($appendStoryID)->fi()
            ->orderBy('id_desc')
            ->limit($limit)
            ->fetchPairs('id', 'title');
    }

    /**
     * Get the story ID list of the linked to task.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getIdListWithTask(int $executionID): array
    {
        return $this->dao->select('story')->from(TABLE_TASK)
            ->where('execution')->eq($executionID)
            ->andWhere('story')->ne(0)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get team members for a project or execution.
     *
     * @param  int    $storyID
     * @param  string $actionType
     * @access public
     * @return array
     */
    public function getTeamMembers(int $storyID, string $actionType): array
    {
        $teamMembers = array();
        if($actionType == 'changed')
        {
            $executions = $this->dao->select('execution')->from(TABLE_TASK)
                ->where('story')->eq($storyID)
                ->andWhere('status')->ne('cancel')
                ->andWhere('deleted')->eq(0)
                ->fetchPairs('execution', 'execution');
            if($executions) $teamMembers = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->in($executions)->andWhere('type')->eq('execution')->fetchPairs('account');
        }
        else
        {
            $projects = $this->dao->select('t1.project')
                ->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.story')->eq((int)$storyID)
                ->andWhere('t2.status')->eq('doing')
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('project', 'project');
            if($projects) $teamMembers = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->in($projects)->andWhere('type')->eq('project')->fetchPairs('account');
        }
        return $teamMembers;
    }

    /**
     * Get version of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return int
     */
    public function getVersion(int $storyID): int
    {
        return (int)$this->dao->select('version')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch('version');
    }

    /**
     * Get versions of some stories.
     *
     * @param  array|string story id list
     * @access public
     * @return array
     */
    public function getVersions(string|array $storyIdList): array
    {
        return $this->dao->select('id, version')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchPairs('id', 'version');
    }

    /**
     * 获取零用例需求。
     * Get zero case.
     *
     * @param  int     $productID
     * @param  int     $branchID
     * @param  string  $orderBy
     * @param  object  $pager
     * @access public
     * @return array
     */
    public function getZeroCase(int $productID, int $branchID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        $casedStories = $this->dao->select('DISTINCT story')->from(TABLE_CASE)->where('product')->eq($productID)->andWhere('story')->ne(0)->andWhere('deleted')->eq(0)->fetchAll('story');
        $allStories   = $this->getProductStories($productID, $branchID, '', 'all', 'story', $orderBy, false, array_keys($casedStories), $pager);
        return $allStories;
    }

    /**
     * 根据变更了的软件需求查找对应的用户需求。
     * Get the requirements by the changed stories.
     *
     * @param  object $story
     * @access public
     * @return array
     */
    public function getChangedStories(object $story): array
    {
        if($story->type == 'requirement') return array();

        $relations = $this->dao->select('*')->from(TABLE_RELATION)
            ->where('AType')->eq('requirement')
            ->andWhere('BType')->eq('story')
            ->andWhere('relation')->eq('subdivideinto')
            ->andWhere('BID')->eq($story->id)
            ->fetchAll('AID');

        if(empty($relations)) return array();

        $stories = $this->getByList(array_keys($relations));
        foreach($stories as $id => $story)
        {
            $version = $relations[$story->id]->AVersion;
            if($version > $story->version) unset($stories[$id]);
        }

        return $stories;
    }

    /**
     * Batch get story stage.
     *
     * @param  array    $stories
     * @access public
     * @return array
     */
    public function batchGetStoryStage(array $storyIdList): array
    {
        return $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->fetchGroup('story', 'branch');
    }

    /**
     * Check need confirm.
     *
     * @param  array|object    $object
     * @access public
     * @return array|object
     */
    public function checkNeedConfirm(array|object $data): array|object
    {
        $objectList = is_object($data) ? array($data->id => $data) : $data;

        $storyIdList      = array();
        $storyVersionList = array();

        foreach($objectList as $key => $object)
        {
            $object->needconfirm = false;
            if($object->story)
            {
                $storyIdList[$key]      = $object->story;
                $storyVersionList[$key] = $object->storyVersion;
            }
        }

        $stories = $this->dao->select('id,version')->from(TABLE_STORY)->where('id')->in($storyIdList)->andWhere('status')->eq('active')->fetchPairs('id', 'version');
        foreach($storyIdList as $key => $storyID)
        {
            if(isset($stories[$storyID]) and $stories[$storyID] > $storyVersionList[$key]) $objectList[$key]->needconfirm = true;
        }

        return is_object($data) ? reset($objectList) : $objectList;
    }

    /**
     * 格式化需求的概要信息。
     * Format stories pairs.
     *
     * @param  array  $stories
     * @param  string $type    full|short|story|requirement
     * @param  int    $limit   0|integer
     * @access public
     * @return array
     */
    public function formatStories(array $stories, string $type = 'full', int $limit = 0): array
    {
        /* Format these stories. */
        $storyPairs = array();
        foreach($stories as $story)
        {
            $property = '';
            if($type == 'short')
            {
                $property = '[p' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ', ' . $story->estimate . "{$this->config->hourUnit}]";
            }
            elseif($type == 'full')
            {
                $property = '(' . $this->lang->story->pri . ':' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ',' . $this->lang->story->estimate . ':' . $story->estimate . ')';
            }
            $storyPairs[$story->id] = $story->id . ':' . $story->title . ' ' . $property;
        }

        if($limit == 0) return $storyPairs;
        return array_slice($storyPairs, 0, $limit, true);
    }

    /**
     * Extract accounts from some stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function extractAccountsFromList(array $stories): array
    {
        $accounts = array();
        foreach($stories as $story)
        {
            if(!empty($story->openedBy))     $accounts[] = $story->openedBy;
            if(!empty($story->assignedTo))   $accounts[] = $story->assignedTo;
            if(!empty($story->closedBy))     $accounts[] = $story->closedBy;
            if(!empty($story->lastEditedBy)) $accounts[] = $story->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /**
     * Extract accounts from a story.
     *
     * @param  object  $story
     * @access public
     * @return array
     */
    public function extractAccountsFromSingle(object $story): array
    {
        $accounts = array();
        if(!empty($story->openedBy))     $accounts[] = $story->openedBy;
        if(!empty($story->assignedTo))   $accounts[] = $story->assignedTo;
        if(!empty($story->closedBy))     $accounts[] = $story->closedBy;
        if(!empty($story->lastEditedBy)) $accounts[] = $story->lastEditedBy;
        return array_unique($accounts);
    }

    /**
     * 将默认的图表设置和当前图表的设置合并。
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string $chartType
     * @access public
     * @return void
     */
    public function mergeChartOption(string $chartType): void
    {
        $chartOption  = $this->lang->story->report->$chartType;
        $commonOption = $this->lang->story->report->options;

        $chartOption->graph->caption = $this->lang->story->report->charts[$chartType];
        if(!isset($chartOption->type))   $chartOption->type   = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
    }

    /**
     * 获取产品软件需求数量的数据。
     * Get report data of stories per product.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerProduct(): array
    {
        $datas = $this->dao->select('product as name, count(product) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('product')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        $products = $this->loadModel('product')->getPairs();
        foreach($datas as $productID => $data) $data->name = isset($products[$productID]) ? $products[$productID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按模块软件需求数量的统计数据。
     * Get report data of stories per module.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerModule(): array
    {
        $datas = $this->dao->select('module as name, count(module) as value, product, branch')
            ->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('module,product,branch')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $branchIDList = array();
        foreach($datas as $project)
        {
            if(!$project->branch) continue;
            $branchIDList[$project->branch] = $project->branch;
        }

        $branches = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($branchIDList)->andWhere('deleted')->eq('0')->fetchALL('id');
        $modules  = $this->loadModel('tree')->getModulesName(array_keys($datas));

        foreach($datas as $moduleID => $data)
        {
            $branch = '';
            if(isset($branches[$data->branch]->name))
            {
                $branch = '/' . $branches[$data->branch]->name;
            }

            $data->name = $branch . (isset($modules[$moduleID]) ? $modules[$moduleID] : '/');
        }

        return $datas;
    }

    /**
     * 获取按需求来源统计的数据。
     * Get report data of stories per source.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerSource(): array
    {
        $datas = $this->dao->select('source as name, count(source) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('source')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        $this->lang->story->sourceList[''] = $this->lang->report->undefined;
        foreach($datas as $key => $data) $data->name = isset($this->lang->story->sourceList[$key]) ? $this->lang->story->sourceList[$key] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按计划进行统计的数据。
     * Get report data of stories per plan.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerPlan(): array
    {
        $datas = $this->dao->select('plan as name, count(plan) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('plan')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        /* Separate for multi-plan key. */
        foreach($datas as $planID => $data)
        {
            if(strpos((string)$planID, ',') !== false)
            {
                $planIdList = explode(',', $planID);
                foreach($planIdList as $multiPlanID)
                {
                    if(empty($datas[$multiPlanID]))
                    {
                        $datas[$multiPlanID] = new stdclass();
                        $datas[$multiPlanID]->name  = $multiPlanID;
                        $datas[$multiPlanID]->value = 0;
                    }
                    $datas[$multiPlanID]->value += $data->value;
                }
                unset($datas[$planID]);
            }
        }

        /* Fix bug #2697. */
        if(isset($datas['']))
        {
            if(empty($datas[0]))
            {
                $datas[0] = new stdclass();
                $datas[0]->name  = 0;
                $datas[0]->value = 0;
            }
            $datas[0]->value += $datas['']->value;
            unset($datas['']);
        }

        $plans = $this->dao->select('id, title')->from(TABLE_PRODUCTPLAN)->where('id')->in(array_keys($datas))->fetchPairs();
        foreach($datas as $planID => $data) $data->name = isset($plans[$planID]) ? $plans[$planID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按状态进行统计的数据。
     * Get report data of stories per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerStatus(): array
    {
        $datas = $this->dao->select('status as name, count(status) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('status')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) if(isset($this->lang->story->statusList[$status])) $data->name = $this->lang->story->statusList[$status];
        return $datas;
    }

    /**
     * 获取按所处阶段进行统计的数据。
     * Get report data of stories per stage.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerStage(): array
    {
        $datas = $this->dao->select('stage as name, count(stage) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('stage')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $stage => $data) $data->name = $this->lang->story->stageList[$stage] != '' ? $this->lang->story->stageList[$stage] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按优先级进行统计的数据。
     * Get report data of stories per pri.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerPri(): array
    {
        $datas = $this->dao->select('pri as name, count(pri) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('pri')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $pri => $data)
        {
            if(isset($this->lang->story->priList[$pri]) && $this->lang->story->priList[$pri] != '')
                $data->name = $this->lang->story->priList[$pri];
            else
                $data->name = $this->lang->report->undefined;
        }
        return $datas;
    }

    /**
     * 获取按照预计工时进行统计的数据。
     * Get report data of stories per estimate.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerEstimate(): array
    {
        return $this->dao->select('estimate as name, count(estimate) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('estimate')->orderBy('value')
            ->fetchAll();
    }

    /**
     * 获取按由谁创建来进行统计的数据。
     * Get report data of stories per openedBy.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerOpenedBy(): array
    {
        $datas = $this->dao->select('openedBy as name, count(openedBy) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('openedBy')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = isset($this->users[$account]) ? $this->users[$account] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按当前指派给来进行统计的数据。
     * Get report data of stories per assignedTo.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerAssignedTo(): array
    {
        $datas = $this->dao->select('assignedTo as name, count(assignedTo) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('assignedTo')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = (isset($this->users[$account]) and $this->users[$account] != '') ? $this->users[$account] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按关闭原因来进行统计的数据。
     * Get report data of stories per closedReason.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerClosedReason(): array
    {
        $datas = $this->dao->select('closedReason as name, count(closedReason) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('closedReason')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $reason => $data) $data->name = $this->lang->story->reasonList[$reason] != '' ? $this->lang->story->reasonList[$reason] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按变更次数来进行统计的数据。
     * Get report data of stories per change.
     *
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerChange(): array
    {
        return $this->dao->select('(version-1) as name, count(*) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('version')->orderBy('value')
            ->fetchAll();
    }

    /**
     * Get kanban group data.
     *
     * @param  array    $stories
     * @access public
     * @return array
     */
    public function getKanbanGroupData(array $stories): array
    {
        $storyGroup = array();
        foreach($stories as $story) $storyGroup[$story->stage][$story->id] = $story;

        return $storyGroup;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $story
     * @param  string    $actionType
     * @access public
     * @return bool|array
     */
    public function getToAndCcList(object $story, string $actionType): bool|array
    {
        /* Set toList and ccList. */
        $toList = $story->assignedTo;
        $ccList = isset($story->mailto) ? str_replace(' ', '', trim($story->mailto, ',')) : '';

        /* If the action is changed or reviewed, mail to the project or execution team. */
        if(strtolower($actionType) == 'changed' or strtolower($actionType) == 'reviewed')
        {
            $teamMembers = $this->getTeamMembers($story->id, $actionType);
            if($teamMembers)
            {
                $ccList .= ',' . implode(',', $teamMembers);
                $ccList = ltrim($ccList, ',');
            }
        }

        if(strtolower($actionType) == 'changed' or strtolower($actionType) == 'opened')
        {
            $reviewerList = $this->getReviewerPairs($story->id, $story->version);
            unset($reviewerList[$story->assignedTo]);

            $ccList .= ',' . implode(',', array_keys($reviewerList));
        }

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
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        elseif($story->status == 'closed')
        {
            $ccList .= ',' . $story->openedBy;
        }

        return array($toList, $ccList);
    }

    /**
     * Adjust the action clickable.
     *
     * @param  object $story
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $story, string $action): bool
    {
        global $app, $config;
        $action = strtolower($action);

        if($action == 'recall')     return strpos('reviewing,changing', $story->status) !== false;
        if($action == 'close')      return $story->status != 'closed';
        if($action == 'activate')   return $story->status == 'closed';
        if($action == 'assignto')   return $story->status != 'closed';
        if($action == 'batchcreate' and $story->parent > 0) return false;
        if($action == 'batchcreate' and !empty($story->twins)) return false;
        if($action == 'batchcreate' and $story->type == 'requirement' and $story->status != 'closed') return strpos('draft,reviewing,changing', $story->status) === false;
        if($action == 'submitreview' and strpos('draft,changing', $story->status) === false) return false;

        static $shadowProducts = array();
        static $taskGroups     = array();
        static $hasShadow      = true;
        if(isset($story->product) && $hasShadow && empty($shadowProducts[$story->product]))
        {
            $stmt = $app->dbQuery('SELECT id FROM ' . TABLE_PRODUCT . " WHERE shadow = 1")->fetchAll();
            if(empty($stmt)) $hasShadow = false;
            foreach($stmt as $row) $shadowProducts[$row->id] = $row->id;
        }

        if($hasShadow and empty($taskGroups[$story->id])) $taskGroups[$story->id] = $app->dbQuery('SELECT id FROM ' . TABLE_TASK . " WHERE story = $story->id")->fetch();

        if(isset($story->parent) && $story->parent < 0 && strpos($config->story->list->actionsOperatedParentStory, ",$action,") === false) return false;

        if($action == 'batchcreate')
        {
            if($config->vision == 'lite' && ($story->status == 'active' && in_array($story->stage, array('wait', 'projected')))) return true;

            if($story->status != 'active' || !empty($story->plan)) return false;
            if(isset($shadowProducts[$story->product]) && (!empty($taskGroups[$story->id]) || $story->stage != 'projected')) return false;
            if(!isset($shadowProducts[$story->product]) && $story->stage != 'wait') return false;
        }

        $story->reviewer  = isset($story->reviewer)  ? $story->reviewer  : array();
        $story->notReview = isset($story->notReview) ? $story->notReview : array();
        $isSuperReviewer = strpos(',' . trim(zget($config->story, 'superReviewers', ''), ',') . ',', ',' . $app->user->account . ',');

        if($action == 'change') return (($isSuperReviewer !== false or count($story->reviewer) == 0 or count($story->notReview) == 0) and $story->status == 'active');
        if($action == 'review') return (($isSuperReviewer !== false or in_array($app->user->account, $story->notReview)) and $story->status == 'reviewing');

        return true;
    }

    /**
     * Build operate menu.
     *
     * @param  object $story
     * @param  string $type
     * @param  object $execution
     * @param  string $storyType story|requirement
     * @access public
     * @return string
     */
    public function buildOperateMenu(object $story, string $type = 'view', object|null $execution = null, string $storyType = 'story'): array
    {
        $menu       = '';
        $mainMenu   = array();
        $dropMenus  = array();
        $suffixMenu = array();
        $params     = "storyID=$story->id";

        if($type == 'view')
        {
            $mainMenu[] = commonModel::buildActionItem('story', 'change', $params, $story, array('icon' => 'alter', 'text' => $this->lang->story->change, 'data-app' => $this->app->tab));
            if(str_contains('draft,changing', $story->status)) $mainMenu[] = commonModel::buildActionItem('story', 'submitReview', $params . "&storyType=$story->type", $story, array('icon' => 'confirm', 'text' => $this->lang->story->submitReview, 'data-toggle' => 'modal'));

            $title = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
            $mainMenu[] = commonModel::buildActionItem('story', 'recall', $params . "&from=view&confirm=no&storyType={$story->type}", $story, array('icon' => 'undo', 'text' => $title, 'class' => 'ajax-submit', 'data-app' => $this->app->tab));
            $mainMenu[] = commonModel::buildActionItem('story', 'review', $params . "&from={$this->app->tab}&storyType={$story->type}", $story, array('icon' => 'search', 'text' => $this->lang->story->review, 'data-app' => $this->app->tab));

            $executionID = empty($execution) ? 0 : $execution->id;
            if(!helper::isAjaxRequest('modal') && $this->config->vision != 'lite')
            {
                $mainMenu[] = commonModel::buildActionItem('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&$params&executionID=$executionID&plan=0&storyType=story", $story, array('icon' => 'split', 'text' => $this->lang->story->subdivide, 'data-app' => $this->app->tab));
            }

            $mainMenu[] = commonModel::buildActionItem('story', 'assignTo', $params . "&kanbanGroup=default&from=&storyType=$story->type", $story, array('icon' => 'hand-right', 'text' => $this->lang->story->assignTo, 'data-toggle' => 'modal'));
            $mainMenu[] = commonModel::buildActionItem('story', 'close',    $params . "&from=&storyType=$story->type", $story, array('icon' => 'off', 'text' => $this->lang->story->close, 'data-toggle' => 'modal'));
            $mainMenu[] = commonModel::buildActionItem('story', 'activate', $params . "&storyType=$story->type", $story, array('icon' => 'magic', 'text' => $this->lang->story->activate, 'data-toggle' => 'modal'));

            $disabledFeatures = ",{$this->config->disabledFeatures},";
            if(in_array($this->config->edition, array('max', 'ipd')) && $this->app->tab == 'project' && common::hasPriv('story', 'importToLib') && strpos($disabledFeatures, ',assetlibStorylib,') === false && strpos($disabledFeatures, ',assetlib,') === false)
            {
                $mainMenu[] = array('url' => '#importToLib', 'icon' => 'assets', 'text' => $this->lang->story->importToLib, 'data-toggle' => 'modal');
            }

            /* Print testcate actions. */
            if($this->config->vision != 'lite' && $story->parent >= 0 && $story->type != 'requirement' && (common::hasPriv('testcase', 'create', $story) || common::hasPriv('testcase', 'batchCreate', $story)))
            {
                $this->app->loadLang('testcase');
                $mainMenu[] = array('url' => '#caseActions', 'text' => $this->lang->testcase->common, 'data-toggle' => 'dropdown', 'data-placement' => 'top-end', 'caret' => 'up');
                $dropMenus['caseActions'][] = commonModel::buildActionItem('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=0&from=&param=0&$params", $story, array('text' => $this->lang->testcase->create, 'data-toggle' => 'modal', 'data-size' => 'lg'));
                $dropMenus['caseActions'][] = commonModel::buildActionItem('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=0&$params", null, array('text' => $this->lang->testcase->batchCreate, 'data-toggle' => 'modal', 'data-size' => 'lg'));
            }

            if(($this->app->tab == 'execution' || (!empty($execution) && $execution->multiple == '0')) && $story->status == 'active' && $story->type == 'story')
            {
                $mainMenu[] = commonModel::buildActionItem('task', 'create', "execution={$this->session->execution}&{$params}&moduleID=$story->module", $story, array('icon' => 'plus', 'text' => $this->lang->task->create));
            }

            $suffixMenu[] = commonModel::buildActionItem('story', 'edit', $params . "&kanbanGroup=default&storyType=$story->type", $story, array('icon' => 'edit', 'data-app' => $this->app->tab));
            $suffixMenu[] = commonModel::buildActionItem('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&{$params}&executionID=0&bugID=0&planID=0&todoID=0&extra=&storyType=$story->type", $story, array('icon' => 'copy', 'data-app' => $this->app->tab));
            if(common::hasPriv('story', 'delete')) $suffixMenu[] = array('url' => helper::createLink('story', 'delete', $params), 'class' => 'ajax-submit', 'icon' => 'trash');
        }

        return array('mainMenu' => array_values(array_filter($mainMenu)), 'suffixMenu' => array_values(array_filter($suffixMenu)), 'dropMenus' => $dropMenus);
    }

    /**
     * Merge story reviewers.
     *
     * @param  array|object  $stories
     * @param  bool          $isObject
     * @access public
     * @return array|object
     */
    public function mergeReviewer(object|array $stories, $isObject = false): array|object
    {
        $rawQuery = $this->dao->get();
        if($isObject)
        {
            $story   = $stories;
            $stories = (array)$stories;
            $stories[$story->id] = $story;
        }

        /* Set child story id into array. */
        $storyIdList = isset($stories['id']) ? array($stories['id'] => $stories['id']) : array_keys($stories);
        if(isset($stories['id']) and isset($story->children)) $storyIdList = array_merge($storyIdList, array_keys($story->children));
        if(!isset($stories['id']))
        {
            foreach($stories as $story)
            {
                if(isset($story->children)) $storyIdList = array_merge($storyIdList, array_keys($story->children));
            }
        }

        $allReviewers = $this->dao->select('story,reviewer,result')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.version=t2.version and t1.id=t2.story')
            ->where('story')->in($storyIdList)
            ->fetchGroup('story', 'reviewer');

        foreach($allReviewers as $storyID => $reviewerList)
        {
            if(isset($stories[$storyID]))
            {
                $stories[$storyID]->reviewer  = array_keys($reviewerList);
                $stories[$storyID]->notReview = array();
                foreach($reviewerList as $reviewer => $reviewInfo)
                {
                    if($reviewInfo->result == '') $stories[$storyID]->notReview[] = $reviewer;
                }
            }
            else
            {
                foreach($stories as $story)
                {
                    if(!isset($story->children)) continue;
                    if(isset($story->children[$storyID]))
                    {
                        $story->children[$storyID]->reviewer  = array_keys($reviewerList);
                        $story->children[$storyID]->notReview = array();
                        foreach($reviewerList as $reviewer => $reviewInfo)
                        {
                            if($reviewInfo->result == '') $story->children[$storyID]->notReview[] = $reviewer;
                        }
                    }
                }
            }
        }

        $this->dao->sqlobj->sql = $rawQuery;

        if($isObject) return $stories[$story->id];
        return $stories;
    }

    /**
     * Set report condition.
     *
     * @access public
     * @return string
     */
    public function reportCondition(): string
    {
        if(isset($_SESSION['storyQueryCondition']))
        {
            if(!$this->session->storyOnlyCondition)
            {
                preg_match_all('/[`"]' . trim(TABLE_STORY, '`') .'[`"] AS ([\w]+) /', $this->session->storyQueryCondition, $matches);
                $tableAlias = isset($matches[1][0]) ? $matches[1][0] . '.' : '';
                return 'id in (' . preg_replace('/SELECT .* FROM/', "SELECT $tableAlias" . "id FROM", $this->session->storyQueryCondition) . ')';
            }
            return $this->session->storyQueryCondition;
        }
        return '1=1';
    }

    /**
     * Check force review for user.
     *
     * @access public
     * @return bool
     */
    public function checkForceReview(): bool
    {
        $forceReview = false;

        $forceField       = $this->config->story->needReview == 0 ? 'forceReview' : 'forceNotReview';
        $forceReviewRoles = !empty($this->config->story->{$forceField . 'Roles'}) ? $this->config->story->{$forceField . 'Roles'} : '';
        $forceReviewDepts = !empty($this->config->story->{$forceField . 'Depts'}) ? $this->config->story->{$forceField . 'Depts'} : '';

        $forceUsers = '';
        if(!empty($this->config->story->{$forceField})) $forceUsers = $this->config->story->{$forceField};

        if(!empty($forceReviewRoles) or !empty($forceReviewDepts))
        {
            $users = $this->dao->select('account')->from(TABLE_USER)
                ->where('deleted')->eq(0)
                ->andWhere('1!=1', true)
                ->beginIF(!empty($forceReviewRoles))
                ->orWhere('(role', true)->in($forceReviewRoles)
                ->andWhere('role')->ne('')
                ->markRight(1)
                ->fi()
                ->beginIF(!empty($forceReviewDepts))->orWhere('dept')->in($forceReviewDepts)->fi()
                ->markRight(1)
                ->fetchAll('account');

            $forceUsers .= "," . implode(',', array_keys($users));
        }

        $forceReview = $this->config->story->needReview == 0 ? strpos(",{$forceUsers},", ",{$this->app->user->account},") !== false : strpos(",{$forceUsers},", ",{$this->app->user->account},") === false;

        return $forceReview;
    }

    /**
     * 根据产品或项目，获取需求跟踪矩阵。
     * Get tracks by producr or project.
     *
     * @param  int        $productID
     * @param  string|int $branch
     * @param  int        $projectID
     * @param  object     $pager
     * @access public
     * @return array|false
     */
    public function getTracks(int $productID = 0, string|int $branch = 0, int $projectID = 0, object|null $pager = null): array|false
    {
        /* 获取从用户需求开始的跟踪矩阵信息。 */
        $tracks = $this->getRequirements4Track($productID, $branch, $projectID, $pager);
        if(count($tracks) >= $pager->recPerPage) return $tracks;

        /* 如果没有用户需求，或者需求不满一页，用非细分的研发需求补充。 */
        $excludeStories = $this->storyTao->getSubdividedStoriesByProduct($productID);
        if($projectID)
        {
            $stories = $this->getExecutionStories($projectID, $productID, '`order`_desc', 'all', '0', 'story', $excludeStories, $this->config->URAndSR ? null : $pager);
        }
        else
        {
            $stories = $this->getProductStories($productID, $branch, '', 'all', 'story', 'id_desc', true, $excludeStories, $this->config->URAndSR ? null : $pager);
        }
        if(empty($stories)) return $tracks;

        /* 展开子需求。 */
        $expandedStories = array();
        foreach($stories as $id => $story)
        {
            $expandedStories[$id] = $story;

            if(!isset($story->children) or count($story->children) == 0) continue;
            foreach($story->children as $childID => $child) $expandedStories[$childID] = $child;
        }

        /* 获取需求的跟踪矩阵信息。 */
        foreach($expandedStories as $id => $story) $expandedStories[$id] = $this->storyTao->buildStoryTrack($story, $projectID);

        /* 跟踪列表中追加 noRequirement 项。如果设置了用户需求，跟踪总条目加1。 */
        $tracks['noRequirement'] = $expandedStories;
        if($this->config->URAndSR) $pager->recTotal += 1;

        return $tracks;
    }

    /**
     * 获取产品或项目关联的用户需求跟踪矩阵。
     * Get requirements track.
     *
     * @param  int         $productID
     * @param  string|int  $branch
     * @param  int         $projectID
     * @param  object      $pager
     * @access public
     * @return int[]
     */
    public function getRequirements4Track(int $productID, string|int $branch, int $projectID, object $pager): array
    {
        if(empty($this->config->URAndSR)) return array();

        /* 获取关联产品或项目的用户需求。 */
        $rawPageID = $pager->pageID;
        if(empty($projectID))  $requirements = $this->getProductStories($productID, $branch, '0', 'all', 'requirement', 'id_desc', true, '', $pager);
        if(!empty($projectID)) $requirements = $this->storyTao->getProjectRequirements($productID, $projectID, $pager);

        /* 如果页码发生变化，说明查出的用户需求还是上一页的数据。当前页没有用户需求数据。 */
        if($pager->pageID != $rawPageID)
        {
            $pager->pageID = $rawPageID;
            return array();
        }

        /* 获取关联项目的研发需求。*/
        $projectStories = array();
        if($projectID) $projectStories = $this->getExecutionStories($projectID, $productID, '`order`_desc', 'all', '0', 'story');

        /* 获取用户需求细分的研发需求。 */
        $requirementStories = $this->storyTao->batchGetRelations(array_keys($requirements), 'requirement', array('id', 'title', 'parent'));

        /* 根据用户需求，构造跟踪矩阵信息。*/
        foreach($requirements as $requirement)
        {
            $stories      = zget($requirementStories, $requirement->id, array());
            $trackStories = array();
            foreach($stories as $id => $story)
            {
                if(empty($story)) continue;
                if($projectStories and !isset($projectStories[$id])) continue;
                $trackStories[$id] = $this->storyTao->buildStoryTrack($story, $projectID);
            }
            $requirement->track = $trackStories;
        }

        return $requirements;
    }

    /**
     * 获取单个用户需求的跟踪矩阵
     * Get track by id.
     *
     * @param  int  $storyID
     * @access public
     * @return array
     */
    public function getTrackByID(int $storyID): array
    {
        $requirement = $this->getByID($storyID);

        /* 获取该用户需求细分的研发需求，并构造跟踪矩阵信息。 */
        $storyIdList = $this->storyTao->getRelation($requirement->id, 'requirement');
        $stories     = $this->dao->select('id,title,parent')->from(TABLE_STORY)->where('id')->in($storyIdList)->andWhere('deleted')->eq(0)->fetchAll('id');
        $track       = array();
        foreach($stories as $id => $story) $track[$id] = $this->storyTao->buildStoryTrack($story);

        return $track;
    }

    /**
     * 通过关系表查询UR关联的SR或SR关联的UR。
     * Get SR or UR with the relationship between UR and SR.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getStoryRelation(int $storyID, string $storyType = 'story', array $fields = array()): array
    {
        $conditionField = $storyType == 'story' ? 'BID' : 'AID';
        $storyType      = $storyType == 'story' ? 'AID' : 'BID';

        $relations = $this->dao->select($storyType)->from(TABLE_RELATION)
            ->where('AType')->eq('requirement')
            ->andWhere('BType')->eq('story')
            ->andWhere('relation')->eq('subdivideinto')
            ->andWhere($conditionField)->eq($storyID)
            ->fetchPairs();

        if(empty($relations)) return array();

        $fields = empty($fields) ? '*' : implode(',', $fields);
        return $this->dao->select($fields)->from(TABLE_STORY)
            ->where('id')->in($relations)
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();
    }

    /**
     * Link a story.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function linkStory(int $executionID, int $productID, int $storyID): void
    {
        if(empty($executionID) || empty($productID) || empty($storyID)) return;
        $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->limit(1)->fetch('order');

        $projectStory = new stdclass();
        $projectStory->project = $executionID;
        $projectStory->product = $productID;
        $projectStory->story   = $storyID;
        $projectStory->version = 1;
        $projectStory->order   = $lastOrder + 1;
        $this->dao->insert(TABLE_PROJECTSTORY)->data($projectStory)->exec();
    }

    /**
     * 关联软件需求和用户需求。
     * Create a relationship between the story and the requirement.
     *
     * @param  int          $storyID
     * @param  array|object storyList  postedData
     * @access public
     * @return void
     */
    public function linkStories(int $storyID, array|object $storyList = array()): void
    {
        $story   = $this->getByID($storyID);
        $stories = empty($storyList) ? $this->post->stories : $storyList;
        $isStory = ($story->type == 'story');

        foreach($stories as $id)
        {
            $requirement = $this->getByID((int)$id);
            $data = new stdclass();
            $data->AType    = 'requirement';
            $data->BType    = 'story';
            $data->product  = $story->product;
            $data->relation = 'subdivideinto';
            $data->AID      = $isStory ? $id : $storyID;
            $data->BID      = $isStory ? $storyID : $id;
            $data->AVersion = $isStory ? $requirement->version : $story->version;
            $data->BVersion = $isStory ? $story->version : $requirement->version;

            $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = 'story';
            $data->BType    = 'requirement';
            $data->relation = 'subdividedfrom';
            $data->product  = $story->product;
            $data->AID      = $isStory ? $storyID : $id;
            $data->BID      = $isStory ? $id : $storyID;
            $data->AVersion = $isStory ? $story->version : $requirement->version;
            $data->BVersion = $isStory ? $requirement->version : $story->version;

            $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();
        }
    }

    /**
     * Unlink story.
     *
     * @param  int $storyID
     * @param  int $linkedStoryID
     * @access public
     * @return bool
     */
    public function unlinkStory(int $storyID, int $linkedStoryID): bool
    {
        $idList = "$storyID,$linkedStoryID";

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->in('story,requirement')
            ->andWhere('BType')->in('story,requirement')
            ->andWhere('relation')->in('subdivideinto,subdividedfrom')
            ->andWhere('AID')->in($idList)
            ->andWhere('BID')->in($idList)
            ->exec();

        return !dao::isError();
    }

    /**
     * 根据需求ID和轮次获取需求估算。
     * Get estimate info.
     *
     * @param  int    $storyID
     * @param  int    $round
     * @access public
     * @return bool|object
     */
    public function getEstimateInfo(int $storyID, int $round = 0): object|bool
    {
        $estimateInfo = $this->dao->select('*')->from(TABLE_STORYESTIMATE)
            ->where('story')->eq($storyID)
            ->beginIf($round)->andWhere('round')->eq($round)->fi()
            ->orderBy('round_desc')
            ->fetch();

        if(!empty($estimateInfo)) $estimateInfo->estimate = json_decode($estimateInfo->estimate);
        return $estimateInfo;
    }

    /**
     * 获取需求估算的轮次列表。
     * Get the rounds list of the estimate for the story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getEstimateRounds(int $storyID): array
    {
        $lastRoundNo = $this->dao->select('round')->from(TABLE_STORYESTIMATE)
            ->where('story')->eq($storyID)
            ->orderBy('round_desc')
            ->fetch('round');
        if(!$lastRoundNo) return array();

        $rounds = array();
        for($roundNo = 1; $roundNo <= $lastRoundNo; $roundNo++)
        {
            $rounds[$roundNo] = sprintf($this->lang->story->storyRound, $roundNo);
        }

        return $rounds;
    }

    /**
     * Save estimate information.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function saveEstimateInfo(int $storyID): void
    {
        $data = fixer::input('post')->get();

        $lastRound = $this->dao->select('round')->from(TABLE_STORYESTIMATE)
            ->where('story')->eq($storyID)
            ->orderBy('round_desc')
            ->fetch('round');

        $estimates = array();
        foreach($data->account as $key => $account)
        {
            if(!empty($data->estimate[$key]) and !is_numeric($data->estimate[$key])) dao::$errors[] = $this->lang->story->estimateMustBeNumber;
            if(!empty($data->estimate[$key]) and $data->estimate[$key] < 0) dao::$errors[] = $this->lang->story->estimateMustBePlus;
            if(dao::isError()) return;

            $estimates[$account]['account']  = $account;
            $estimates[$account]['estimate'] = strpos($data->estimate[$key], '-') !== false ? (int)$data->estimate[$key] : (float)$data->estimate[$key];
        }


        $storyEstimate = new stdclass();
        $storyEstimate->story      = $storyID;
        $storyEstimate->round      = empty($lastRound) ? 1 : $lastRound + 1;
        $storyEstimate->estimate   = json_encode($estimates);
        $storyEstimate->average    = $data->average;
        $storyEstimate->openedBy   = $this->app->user->account;
        $storyEstimate->openedDate = helper::now();

        $this->dao->insert(TABLE_STORYESTIMATE)->data($storyEstimate)->exec();
    }

    /**
     * Update the story order according to the plan.
     *
     * @param  int    $planID
     * @param  array  $sortIDList
     * @param  string $orderBy
     * @param  int    $pageID
     * @param  int    $recPerPage
     * @access public
     * @return void
     */
    public function sortStoriesOfPlan($planID, $sortIDList, $orderBy = 'id_desc', $pageID = 1, $recPerPage = 100)
    {
        /* Append id for second sort. */
        $orderBy = common::appendOrder($orderBy);

        /* Get all stories by plan. */
        $stories     = $this->getPlanStories($planID, 'all', $orderBy);
        $storyIDList = array_keys($stories);

        /* Calculate how many numbers there are before the sort list and after the sort list. */
        $frontStoryCount   = $recPerPage * ($pageID - 1);
        $behindStoryCount  = $recPerPage * $pageID;
        $frontStoryIDList  = array_slice($storyIDList, 0, $frontStoryCount);
        $behindStoryIDList = array_slice($storyIDList, $behindStoryCount, count($storyIDList) - $behindStoryCount);

        /* Merge to get a new sort list. */
        $newSortIDList = array_merge($frontStoryIDList, $sortIDList, $behindStoryIDList);
        if(strpos($orderBy, 'order_desc') !== false) $newSortIDList = array_reverse($newSortIDList);

        /* Loop update the story order of plan. */
        $order = 1;
        foreach($newSortIDList as $storyID)
        {
            $this->dao->update(TABLE_PLANSTORY)->set('`order`')->eq($order)->where('story')->eq($storyID)->andWhere('plan')->eq($planID)->exec();
            $order++;
        }
    }

    /**
     * 将软件需求语言项替换为用户需求语言项。
     * Replace the lang of the story with the requirement.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function replaceURLang(string $type): void
    {
        if($type != 'requirement') return;

        $storyLang = $this->lang->story;
        $SRCommon  = $this->lang->SRCommon;
        $URCommon  = $this->lang->URCommon;

        $storyLang->create             = str_replace($SRCommon, $URCommon, $storyLang->create);
        $storyLang->changeAction       = str_replace($SRCommon, $URCommon, $storyLang->changeAction);
        $storyLang->changed            = str_replace($SRCommon, $URCommon, $storyLang->changed);
        $storyLang->assignAction       = str_replace($SRCommon, $URCommon, $storyLang->assignAction);
        $storyLang->reviewAction       = str_replace($SRCommon, $URCommon, $storyLang->reviewAction);
        $storyLang->subdivideAction    = str_replace($SRCommon, $URCommon, $storyLang->subdivideAction);
        $storyLang->closeAction        = str_replace($SRCommon, $URCommon, $storyLang->closeAction);
        $storyLang->activateAction     = str_replace($SRCommon, $URCommon, $storyLang->activateAction);
        $storyLang->deleteAction       = str_replace($SRCommon, $URCommon, $storyLang->deleteAction);
        $storyLang->view               = str_replace($SRCommon, $URCommon, $storyLang->view);
        $storyLang->linkStory          = str_replace($SRCommon, $URCommon, $storyLang->linkStory);
        $storyLang->unlinkStory        = str_replace($SRCommon, $URCommon, $storyLang->unlinkStory);
        $storyLang->exportAction       = str_replace($SRCommon, $URCommon, $storyLang->exportAction);
        $storyLang->zeroCase           = str_replace($SRCommon, $URCommon, $storyLang->zeroCase);
        $storyLang->zeroTask           = str_replace($SRCommon, $URCommon, $storyLang->zeroTask);
        $storyLang->copyTitle          = str_replace($SRCommon, $URCommon, $storyLang->copyTitle);
        $storyLang->common             = str_replace($SRCommon, $URCommon, $storyLang->common);
        $storyLang->title              = str_replace($SRCommon, $URCommon, $storyLang->title);
        $storyLang->spec               = str_replace($SRCommon, $URCommon, $storyLang->spec);
        $storyLang->children           = str_replace($SRCommon, $URCommon, $storyLang->children);
        $storyLang->linkStories        = str_replace($SRCommon, $URCommon, $storyLang->linkStories);
        $storyLang->childStories       = str_replace($SRCommon, $URCommon, $storyLang->childStories);
        $storyLang->duplicateStory     = str_replace($SRCommon, $URCommon, $storyLang->duplicateStory);
        $storyLang->newStory           = str_replace($SRCommon, $URCommon, $storyLang->newStory);
        $storyLang->copy               = str_replace($SRCommon, $URCommon, $storyLang->copy);
        $storyLang->total              = str_replace($SRCommon, $URCommon, $storyLang->total);
        $storyLang->released           = str_replace($SRCommon, $URCommon, $storyLang->released);
        $storyLang->legendLifeTime     = str_replace($SRCommon, $URCommon, $storyLang->legendLifeTime);
        $storyLang->legendLinkStories  = str_replace($SRCommon, $URCommon, $storyLang->legendLinkStories);
        $storyLang->legendChildStories = str_replace($SRCommon, $URCommon, $storyLang->legendChildStories);
        $storyLang->legendSpec         = str_replace($SRCommon, $URCommon, $storyLang->legendSpec);

        $storyLang->report->charts['storiesPerProduct'] = str_replace($SRCommon, $URCommon, $storyLang->report->charts['storiesPerProduct']);
        $storyLang->report->charts['storiesPerModule']  = str_replace($SRCommon, $URCommon, $storyLang->report->charts['storiesPerModule']);
        $storyLang->report->charts['storiesPerSource']  = str_replace($SRCommon, $URCommon, $storyLang->report->charts['storiesPerSource']);
    }

    /**
     * 通过需求编号和版本获取评审人和评审结果的键值对。
     * Get story reviewer pairs. array(reviewer => result, ...)
     *
     * @param  int  $storyID
     * @param  int  version
     * @access public
     * @return array
     */
    public function getReviewerPairs(int $storyID, int $version): array
    {
        return $this->dao->select('reviewer,result')->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetchPairs('reviewer', 'result');
    }

    /**
     * Set story status by review rules.
     *
     * @param  array  $reviewerList
     * @access public
     * @return string
     */
    public function getReviewResult(array $reviewerList): string
    {
        $results      = array();
        $passCount    = 0;
        $rejectCount  = 0;
        $revertCount  = 0;
        $clarifyCount = 0;
        $reviewRule   = $this->config->story->reviewRules;
        foreach($reviewerList as $result)
        {
            $passCount    = $result == 'pass'    ? $passCount    + 1 : $passCount;
            $rejectCount  = $result == 'reject'  ? $rejectCount  + 1 : $rejectCount;
            $revertCount  = $result == 'revert'  ? $revertCount  + 1 : $revertCount;
            $clarifyCount = $result == 'clarify' ? $clarifyCount + 1 : $clarifyCount;

            $results[$result] = $result;
        }

        $finalResult = '';
        if($reviewRule == 'allpass' and $passCount == count($reviewerList)) $finalResult = 'pass';
        if($reviewRule == 'halfpass' and $passCount >= floor(count($reviewerList) / 2) + 1) $finalResult = 'pass';

        if(empty($finalResult))
        {
            if($clarifyCount >= floor(count($reviewerList) / 2) + 1) return 'clarify';
            if($revertCount  >= floor(count($reviewerList) / 2) + 1) return 'revert';
            if($rejectCount  >= floor(count($reviewerList) / 2) + 1) return 'reject';

            if(isset($results['clarify'])) return 'clarify';
            if(isset($results['revert']))  return 'revert';
            if(isset($results['reject']))  return 'reject';
        }

        return $finalResult;
    }

    /**
     * Set story status by review result.
     *
     * @param  object $story
     * @param  object $oldStory
     * @param  object $result
     * @param  string $reason
     * @access public
     * @return array
     */
    public function setStatusByReviewResult(object $story, object $oldStory, string $result, string $reason = 'cancel'): object
    {
        if($result == 'pass') $story->status = 'active';

        if($result == 'clarify')
        {
            /* When the review result of the changed story is clarify, the status should be changing. */
            $isChanged = $oldStory->changedBy ? true : false;
            $story->status = $isChanged ? 'changing' : 'draft';
        }

        if($result == 'revert')
        {
            $story->status  = 'active';
            $story->version = $oldStory->version - 1;
            $story->title   = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($story->id)->andWHere('version')->eq($oldStory->version - 1)->fetch('title');

            /* Delete versions that is after this version. */
            $twinsIdList = $story->id . ($oldStory->twins ? ",{$oldStory->twins}" : '');
            $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->in($twinsIdList)->andWHere('version')->in($oldStory->version)->exec();
            $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->in($twinsIdList)->andWhere('version')->in($oldStory->version)->exec();
        }

        if($result == 'reject')
        {
            $now    = helper::now();
            $reason = (!empty($story->closedReason)) ? $story->closedReason : $reason;

            $story->status       = 'closed';
            $story->closedBy     = $this->app->user->account;
            $story->closedDate   = $now;
            $story->assignedTo   = 'closed';
            $story->assignedDate = $now;
            $story->stage        = $reason == 'done' ? 'released' : 'closed';
            $story->closedReason = $reason;
        }

        $story->finalResult = $result;

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';

        return $story;
    }

    /**
     * Record story review actions.
     *
     * @param  object $story
     * @param  string $result
     * @param  string $reason
     * @access public
     * @return int|string
     */
    public function recordReviewAction(object $story, string $comment = ''): int
    {
        $isSuperReviewer = $this->storyTao->isSuperReviewer();
        $result          = zget($story, 'result', '');
        $reason          = zget($story, 'closedReason', '');
        $story->id       = (int)$story->id;

        $this->loadModel('action');
        if($isSuperReviewer and $this->app->rawMethod != 'edit') return $this->action->create('story', $story->id, 'Reviewed', $comment, ucfirst($result) . '|superReviewer');

        $reasonParam = $result == 'reject' ? ',' . $reason : '';
        $actionID    = 0;
        if(!empty($result)) $actionID = $this->action->create('story', $story->id, 'Reviewed', $comment, ucfirst($result) . $reasonParam);

        if(isset($story->finalResult))
        {
            if($story->finalResult == 'reject')  return $this->action->create('story', $story->id, 'ReviewRejected');
            if($story->finalResult == 'pass')    return $this->action->create('story', $story->id, 'ReviewPassed');
            if($story->finalResult == 'clarify') return $this->action->create('story', $story->id, 'ReviewClarified');
            if($story->finalResult == 'revert')  return $this->action->create('story', $story->id, 'ReviewReverted');
        }

        return $actionID;
    }

    /**
     * Update the story fields value by review.
     *
     * @param  int    $storyID
     * @param  object $oldStory
     * @param  object $story
     * @access public
     * @return object
     */
    public function updateStoryByReview(int $storyID, object $oldStory, object $story): object
    {
        $isSuperReviewer = $this->storyTao->isSuperReviewer();
        if($isSuperReviewer) return $this->superReview($storyID, $oldStory, $story);

        $reviewerList = $this->getReviewerPairs($storyID, (int)$oldStory->version);
        $reviewedBy   = explode(',', trim($story->reviewedBy, ','));
        if(!array_diff(array_keys($reviewerList), $reviewedBy))
        {
            $reviewResult = $this->getReviewResult($reviewerList);
            $story        = $this->setStatusByReviewResult($story, $oldStory, $reviewResult);
        }

        return $story;
    }

    /**
     * Do update reviewer.
     *
     * @param  int       $storyID
     * @param  object    $story
     * @access protected
     * @return void
     */
    public function doUpdateReviewer(int $storyID, object $story): void
    {
        $oldStory = $this->fetchByID($storyID);
        if(empty($oldStory)) return;

        $oldReviewer  = $this->getReviewerPairs($storyID, $oldStory->version);
        $twins        = explode(',', trim($oldStory->twins, ','));
        $reviewerList = implode(',', array_filter($story->reviewer));

        /* Update story reviewer. */
        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)
            ->andWhere('version')->eq($oldStory->version)
            ->beginIF($oldStory->status == 'reviewing')->andWhere('reviewer')->notIN($reviewerList)
            ->exec();

        /* Sync twins. */
        if(!empty($twins))
        {
            foreach($twins as $twinID)
            {
                $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($twinID)
                    ->andWhere('version')->eq($oldStory->version)
                    ->beginIF($oldStory->status == 'reviewing')->andWhere('reviewer')->notin($reviewerList)
                    ->exec();
            }
        }

        foreach($story->reviewer as $reviewer)
        {
            if(empty($reviewer)) continue;
            if($oldStory->status == 'reviewing' and isset($oldReviewer[$reviewer])) continue;

            $reviewData = new stdclass();
            $reviewData->story    = $storyID;
            $reviewData->version  = $oldStory->version;
            $reviewData->reviewer = $reviewer;
            $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

            /* Sync twins. */
            if(!empty($twins))
            {
                foreach($twins as $twinID)
                {
                    $reviewData->story = $twinID;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                }
            }
        }

        if($oldStory->status == 'reviewing') $story = $this->updateStoryByReview($storyID, $oldStory, $story);
        if(strpos('draft,changing', $oldStory->status) != false) $story->reviewedBy = '';
    }

    /**
     * To review for super reviewer.
     *
     * @param  int     $storyID
     * @param  object  $oldStory
     * @param  object  $story
     * @param  string  $result
     * @param  string  $reason
     * @access public
     * @return object
     */
    public function superReview(int $storyID, object $oldStory, object $story, string $result = '', string $reason = ''): object
    {
        $result = isset($story->result) ? $story->result : $result;
        if(empty($result)) return $story;

        $reason = isset($story->closedReason) ? $story->closedReason : $reason;
        $story  = $this->setStatusByReviewResult($story, $oldStory, $result, $reason);

        $this->dao->delete()->from(TABLE_STORYREVIEW)
            ->where('story')->in($storyID . ($oldStory->twins ? ",{$oldStory->twins}" : ''))
            ->andWhere('version')->eq($oldStory->version)
            ->andWhere('result')->eq('')
            ->exec();

        return $story;
    }

    /**
     * 获取要导出的需求数据。
     * Get the stories to export.
     *
     * @param  string $orderBy     id_desc
     * @param  string $storyType
     * @param  object $postData
     * @access public
     * @return array
     */
    public function getExportStories(string $orderBy = 'id_desc', string $storyType = 'story', object|null $postData = null): array
    {
        $orderBy = $orderBy !== 'id_desc' ? 'id_desc' : $orderBy; /* The order of the stories for exporting is disabled. */

        $this->loadModel('file');
        $this->loadModel('branch');

        $this->replaceURLang($storyType);
        $storyLang = $this->lang->story;

        /* Create field lists. */
        $fields = !empty($postData->exportFields) ? $postData->exportFields : explode(',', $this->config->story->exportFields);
        if(empty($postData->exportFields)) $postData->exportFields = $this->config->story->exportFields;
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($storyLang->$fieldName) ? $storyLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        /* Get stories. */
        $stories        = array();
        $selectedIDList = $this->cookie->checkedItem ? $this->cookie->checkedItem : '0';
        if($this->session->storyOnlyCondition)
        {
            $queryCondition = $postData->exportType == 'selected' ? ' `id` ' . helper::dbIN($selectedIDList) : str_replace('`story`', '`id`', $this->session->storyQueryCondition);
            $stories        = $this->dao->select('id,title,linkStories,childStories,parent,mailto,reviewedBy')->from(TABLE_STORY)->where($queryCondition)->orderBy($orderBy)->fetchAll('id');
        }
        else
        {
            $orderBy  = " ORDER BY " . helper::wrapSqlAfterOrderBy($orderBy);
            $querySQL = $this->session->storyQueryCondition . $orderBy;
            if($postData->exportType == 'selected') $querySQL = "SELECT * FROM " . TABLE_STORY . "WHERE `id` IN({$selectedIDList})" . $orderBy;

            $stmt = $this->app->dbQuery($querySQL);
            while($row = $stmt->fetch()) $stories[$row->id] = $row;
        }

        if(empty($stories)) return $stories;

        $storyIdList = array_keys($stories);
        $children    = array();
        foreach($stories as $story)
        {
            if($story->parent > 0 and isset($stories[$story->parent]))
            {
                $children[$story->parent][$story->id] = $story;
                unset($stories[$story->id]);
            }
        }

        if(!empty($children))
        {
            $reorderStories = array();
            foreach($stories as $story)
            {
                $reorderStories[$story->id] = $story;
                if(!isset($children[$story->id])) continue;

                foreach($children[$story->id] as $childrenID => $childrenStory) $reorderStories[$childrenID] = $childrenStory;
            }
            $stories = $reorderStories;
        }

        /* Get users, products and relations. */
        $users      = $this->loadModel('user')->getPairs('noletter');
        $storyTasks = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        /* Get related objects title or names. */
        $relatedSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('`story`')->in($storyIdList)->orderBy('version desc')->fetchGroup('story');
        $relatedStories = $this->dao->select('*')->from(TABLE_STORY)->where('`id`')->in($storyIdList)->fetchPairs('id', 'title');

        $fileIdList = array();
        foreach($relatedSpecs as $relatedSpec)
        {
            if(!empty($relatedSpec[0]->files)) $fileIdList[] = $relatedSpec[0]->files;
        }
        $fileIdList   = array_unique($fileIdList);
        $relatedFiles = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->in($storyIdList)->andWhere('extra')->ne('editor')->fetchGroup('objectID');
        $filesInfo    = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('id')->in($fileIdList)->andWhere('extra')->ne('editor')->fetchAll('id');

        foreach($stories as $story)
        {
            $story->spec   = '';
            $story->verify = '';
            if(isset($relatedSpecs[$story->id]))
            {
                $storySpec     = $relatedSpecs[$story->id][0];
                $story->title  = $storySpec->title;
                $story->spec   = $storySpec->spec;
                $story->verify = $storySpec->verify;

                if(!empty($storySpec->files) and empty($relatedFiles[$story->id]) and !empty($filesInfo[$storySpec->files])) $relatedFiles[$story->id][0] = $filesInfo[$storySpec->files];
            }

            if($postData->fileType == 'csv')
            {
                $story->spec = htmlspecialchars_decode($story->spec);
                $story->spec = str_replace("<br />", "\n", $story->spec);
                $story->spec = str_replace('"', '""', $story->spec);
                $story->spec = str_replace('&nbsp;', ' ', $story->spec);

                $story->verify = htmlspecialchars_decode($story->verify);
                $story->verify = str_replace("<br />", "\n", $story->verify);
                $story->verify = str_replace('"', '""', $story->verify);
                $story->verify = str_replace('&nbsp;', ' ', $story->verify);
            }

            /* fill some field with useful value. */
            if(isset($storyTasks[$story->id])) $story->taskCountAB = $storyTasks[$story->id];
            if(isset($storyBugs[$story->id]))  $story->bugCountAB  = $storyBugs[$story->id];
            if(isset($storyCases[$story->id])) $story->caseCountAB = $storyCases[$story->id];

            if($story->linkStories)
            {
                $tmpLinkStories    = array();
                $linkStoriesIdList = explode(',', $story->linkStories);
                foreach($linkStoriesIdList as $linkStoryID) $tmpLinkStories[] = zget($relatedStories, trim($linkStoryID), '');
                $story->linkStories = implode("; \n", array_filter($tmpLinkStories));
            }

            if($story->childStories)
            {
                $tmpChildStories = array();
                $childStoriesIdList = explode(',', $story->childStories);
                foreach($childStoriesIdList as $childStoryID) $tmpChildStories[] = zget($relatedStories, trim($childStoryID));
                $story->childStories = implode("; \n", array_filter($tmpChildStories));
            }

            /* Set related files. */
            $story->files = '';
            if(isset($relatedFiles[$story->id]))
            {
                foreach($relatedFiles[$story->id] as $file)
                {
                    $fileURL = common::getSysURL() . helper::createLink('file', 'download', "fileID=$file->id");
                    $story->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                }
            }

            $mailtoList = array_filter(explode(',', $story->mailto));
            foreach($mailtoList as $i => $mailto) $mailtoList[$i] = zget($users, trim($mailto));
            $story->mailto = implode(',', array_filter($mailtoList));

            $reviewedByList = array_filter(explode(',', $story->reviewedBy));
            foreach($reviewedByList as $i => $reviewedBy) $reviewedByList[$i] = zget($users, trim($reviewedBy));
            $story->reviewedBy = implode(',', array_filter($reviewedByList));

            /* Set child story title. */
            if($story->parent > 0 && strpos($story->title, htmlentities('>', ENT_COMPAT | ENT_HTML401, 'UTF-8')) !== 0) $story->title = '>' . $story->title;
        }

        return $stories;
    }

    /**
     * 获取需求激活后的状态。
     * Get the story status after activation.
     *
     * @param  int    $storyID
     * @param  bool   $hasTwins
     * @access public
     * @return string
     */
    public function getActivateStatus(int $storyID, bool $hasTwins = true): string
    {
        $status     = 'active';
        $action     = 'closed,reviewrejected,closedbysystem';
        $action     = $hasTwins ? $action . ',synctwins' : $action;
        $lastRecord = $this->dao->select('action,extra')->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('objectID')->eq($storyID)
            ->andWhere('action')->in($action)
            ->orderBy('id_desc')
            ->fetch();

        if(empty($lastRecord->action)) return $status;

        /* Set the status of the story to previous status in latest action log. */
        $lastAction = $lastRecord->action;
        if(strpos(',closed,reviewrejected,', ",$lastAction,") !== false)
        {
            $status = strpos($lastRecord->extra, '|') !== false ? substr($lastRecord->extra, strpos($lastRecord->extra, '|') + 1) : 'active';
            if($status == 'closed') $status = 'active'; /* Set the status to active if last status before close it is closed as well. */
        }

        /* Activate parent story. */
        if($lastAction == 'closedbysystem')
        {
            $status = $lastRecord->extra ? $lastRecord->extra : 'active';
            if($status == 'active')
            {
                /* If the parent story is not reviewed before closing, it will be activated to the status in changing. */
                $hasNotReviewed = $this->dao->select('t1.*')->from(TABLE_STORYREVIEW)->alias('t1')
                    ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id and t2.version = t1.version')
                    ->where('t1.story')->eq($storyID)
                    ->andWhere('t1.result')->eq('')
                    ->fetchAll();
                if(!empty($hasNotReviewed)) $status = 'changing';
            }
        }

        /* When activating twin story, you need to check the status of the twin story selected when closing. */
        if($lastAction == 'synctwins')
        {
            $syncStoryID = strpos($lastRecord->extra, '|') !== false ? substr($lastRecord->extra, strpos($lastRecord->extra, '|') + 1) : 0;
            $status      = $this->getActivateStatus((int)$syncStoryID, false);
        }

        return $status;
    }

    /**
     * Get reviewer pairs for story .
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getStoriesReviewer(int $productID = 0): array
    {
        $this->loadModel('user');
        $product   = $this->loadModel('product')->getByID($productID);
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->user->getProductViewListUsers($product);
        return $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);
    }

    /**
     * Get the last reviewer.
     *
     * @param  int $storyID
     * @access public
     * @return string
     */
    public function getLastReviewer(int $storyID): string
    {
        return $this->dao->select('t2.new')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_HISTORY)->alias('t2')->on('t1.id = t2.action')
            ->where('t1.objectType')->eq('story')
            ->andWhere('t1.objectID')->eq($storyID)
            ->andWhere('t2.field')->in('reviewer,reviewers')
            ->andWhere('t2.new')->ne('')
            ->orderBy('t1.id_desc')
            ->fetch('new');
    }

    /**
     * Sync twins.
     *
     * @param  int    $storyID
     * @param  string $twins
     * @param  array  $changes
     * @param  string $operate
     * @access public
     * @return void
     */
    public function syncTwins(int $storyID, string $twins, array $changes, string $operate): void
    {
        if(empty($twins) || empty($changes)) return;

        /* Get the fields and values to be synchronized. */
        $syncFieldList = array();
        foreach($changes as $changeInfo)
        {
            $fieldName  = $changeInfo['field'];
            $fieldValue = $changeInfo['new'];

            if(str_contains(',product,branch,module,plan,stage,stagedBy,spec,verify,files,reviewers,reviewer,', ",{$fieldName},")) continue;
            $syncFieldList[$fieldName] = $fieldValue;
        }
        if(empty($syncFieldList)) return;

        /* Synchronize and record dynamics. */
        $this->loadModel('action');
        foreach(explode(',', $twins) as $twinID)
        {
            $twinID = (int)$twinID;
            if(empty($twinID)) continue;

            $this->dao->update(TABLE_STORY)->data($syncFieldList)->where('id')->eq($twinID)->exec();
            if(dao::isError()) continue;

            $this->setStage($twinID);

            $actionID = $this->action->create('story', $twinID, 'synctwins', '', "$operate|$storyID");
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * Build operate menu.
     *
     * @param  object $story
     * @param  string $type
     * @param  object $execution
     * @param  string $storyType story|requirement
     * @access public
     * @return array
     */
    public function buildActionButtonList(object $story, $type = 'browse', object|null $execution = null, $storyType = 'story'): array
    {
        $menu   = '';
        $params = "storyID=$story->id";

        if($type == 'browse') return $this->storyTao->buildBrowseActionBtnList($story, $params, $storyType, $execution);
        return array();
    }

    /**
     * 格式化列表中需求数据。
     * Format story for list.
     *
     * @param  object $story
     * @param  array  $options
     * @access public
     * @return object
     */
    public function formatStoryForList(object $story, array $options = array()): object
    {
        $story->actions  = $this->buildActionButtonList($story, 'browse', zget($options, 'execution', null));
        $story->estimate = $story->estimate . $this->config->hourUnit;

        $story->taskCount = zget(zget($options, 'storyTasks', array()), $story->id, 0);
        $story->bugCount  = zget(zget($options, 'storyBugs',  array()), $story->id, 0);
        $story->caseCount = zget(zget($options, 'storyCases', array()), $story->id, 0);
        $story->module    = zget(zget($options, 'modules',    array()), $story->module, '');
        $story->branch    = zget(zget($options, 'branches',   array()), $story->branch, '');
        $story->plan      = isset($story->planTitle) ? $story->planTitle : zget(zget($options, 'plans', array()), $story->plan, '');

        $story->source       = zget($this->lang->story->sourceList,   $story->source, '');
        $story->category     = zget($this->lang->story->categoryList, $story->category);
        $story->closedReason = zget($this->lang->story->reasonList,   $story->closedReason);

        /* Set rowKey to uniqueID in dTable. */
        $story->uniqueID = $story->parent > 0 ? $story->parent . '-' . $story->id : $story->id;

        if($story->parent < 0) $story->parent = 0;
        if(empty($options['execution'])) $story->isParent = isset($story->children);

        /* Format user list. */
        foreach(array('mailto', 'reviewer') as $fieldName)
        {
            if(!isset($story->{$fieldName})) continue;

            $fieldValue = is_string($story->{$fieldName}) ? array_filter(explode(',', $story->{$fieldName})) : array_filter($story->{$fieldName});
            foreach($fieldValue as $i => $account) $fieldValue[$i] = zget(zget($options, 'users', array()), $account);
            $story->{$fieldName} = implode(' ', $fieldValue);
        }

        /* Rewrite actions by action menus in options. */
        if(isset($options['actionMenus']))
        {
            $actions = array();
            foreach($options['actionMenus'] as $actionName)
            {
                foreach($story->actions as $action)
                {
                    if($action['name'] == $actionName) $actions[] = $action;
                }
            }
            $story->actions = $actions;
        }

        return $story;
    }

    /**
     * 更新需求的发布日期
     * Update the released date of story.
     *
     * @param  string $stories
     * @param  string $releasedDate
     * @access public
     * @return bool
     */
    public function updateStoryReleasedDate(string $stories, string $releasedDate): bool
    {
        $this->dao->update(TABLE_STORY)
            ->set('releasedDate')->eq($releasedDate)
            ->where('id')->in($stories)
            ->exec();

        return !dao::isError();
    }

    /**
     * 根据项目ID获取需求列表。
     * Get story list by project id.
     *
     * @param  int $projectID
     * @access public
     * @return array
     */
    public function getListByProject(int $projectID): array
    {
        return $this->dao->select('t2.*, t1.version as taskVersion')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision != 'lite')->andWhere('t2.type')->eq('story')->fi()
            ->orderBy('t1.`order`_desc')
            ->fetchAll();
    }

    /**
     * 获取产品所有状态对应的需求总数。
     * Get stories count of each status by product ID.
     *
     * @param  int    $productID
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getStoriesCountByProductID(int $productID, string $storyType = 'requirement'): array
    {
        return $this->dao->select('product, status, count(status) AS count')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq($storyType)
            ->andWhere('product')->eq($productID)
            ->groupBy('product, status')
            ->fetchAll('status');
    }

    /**
     * Get product stroies by status.
     *
     * @param  string $status noclosed || active
     * @access public
     * @return array | string
     */
    public function getStatusList(string $status)
    {
        $storyStatus = '';
        if($status == 'noclosed')
        {
            $storyStatus = $this->lang->story->statusList;
            unset($storyStatus['closed']);
            $storyStatus = array_keys($storyStatus);
        }

        if($status == 'active') $storyStatus = $status;

        return $storyStatus;
    }
}
