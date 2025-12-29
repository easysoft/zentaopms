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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getStoryByID($storyID);

        $story = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(!$story) return false;

        if($version == 0) $version = $story->version;

        $this->loadModel('file');
        $spec = $this->dao->select('title,spec,verify,files,docs,docVersions')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title       = !empty($spec->title)       ? $spec->title  : '';
        $story->spec        = !empty($spec->spec)        ? $spec->spec   : '';
        $story->verify      = !empty($spec->verify)      ? $spec->verify : '';
        $story->files       = !empty($spec->files)       ? $this->file->getByIdList($spec->files) : array();
        $story->docs        = !empty($spec->docs)        ? $spec->docs : '';
        $story->docVersions = !empty($spec->docVersions) ? json_decode($spec->docVersions, true) : array();
        $story->stages      = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchPairs('branch', 'stage');

        /* Clear the extra field to display file. */
        foreach($story->files as $file) $file->extra = '';

        $story = $this->file->replaceImgURL($story, 'spec,verify');
        if($setImgSize) $story->spec   = $this->file->setImgSize($story->spec);
        if($setImgSize) $story->verify = $this->file->setImgSize($story->verify);

        $storyIdList = $storyID . ($story->twins ? "," . trim($story->twins, ',') : '');
        $story->executions = $this->dao->select('t2.project, t2.id, t2.name, t2.status, t2.type, t2.multiple')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.type')->in('sprint,stage,kanban')
            ->andWhere('t1.story')->in($storyIdList)
            ->orderBy('t1.`order` DESC')
            ->fetchAll('id');

        $story->tasks = $this->dao->select('id,name,assignedTo,execution,project,status,consumed,`left`,type')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('story')->in($storyIdList)->orderBy('id DESC')->fetchGroup('execution');
        if($this->config->vision == 'lite' && $story->tasks) $story->executions += $this->dao->select('project,id,name,status,type,multiple')->from(TABLE_EXECUTION)->where('id')->in(array_keys($story->tasks))->orderBy('`order` DESC')->fetchAll('id');

        if($story->parent > 0)
        {
            $parent = $this->dao->findById($story->parent)->from(TABLE_STORY)->fetch();
            $story->parentName    = $parent->title;
            $story->parentType    = $parent->type;
            $story->parentChanged = $story->parentVersion > 0 && $parent->version > $story->parentVersion && $parent->status == 'active';
        }

        if($story->toBug)     $story->toBugTitle = $this->dao->findById($story->toBug)->from(TABLE_BUG)->fetch('title');
        if($story->fromStory) $story->sourceName = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($story->fromStory)->fetch('title');

        $story->children = array();
        if($story->isParent == '1')
        {
            $childIdList     = $this->getAllChildId($storyID, false);
            $story->children = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($childIdList)->andWhere('deleted')->eq(0)->orderBy('id_desc')->fetchAll('id', false);
            if(!empty($story->children)) $story->children = $this->storyTao->mergePlanTitleAndChildren($story->product, $story->children);
        }

        if($story->plan)
        {
            $plans = $this->dao->select('id,title,branch')->from(TABLE_PRODUCTPLAN)->where('id')->in($story->plan)->fetchAll('id');
            foreach($plans as $planID => $plan)
            {
                $story->planTitle[$planID] = $plan->title;
                if($plan->branch and !isset($story->stages[$plan->branch]) and empty($story->branch)) $story->stages[$plan->branch] = 'planned';
            }
        }

        $extraStories = $story->duplicateStory ? array($story->duplicateStory) : array();
        if(!empty($extraStories)) $story->extraStories = $this->dao->select('id,title,type')->from(TABLE_STORY)->where('id')->in($extraStories)->fetchAll('id');

        $story->hasOtherTypeChild = $this->dao->select('id')->from(TABLE_STORY)->where('parent')->eq($story->id)->andWhere('type')->ne($story->type)->andWhere('deleted')->eq('0')->fetch('id');
        $story->hasSameTypeChild  = $this->dao->select('id')->from(TABLE_STORY)->where('parent')->eq($story->id)->andWhere('type')->eq($story->type)->andWhere('deleted')->eq('0')->fetch('id');

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
     * @param  bool   $hasParent true|false
     * @access public
     * @return array
     */
    public function getPairs(int $productID = 0, int $planID = 0, string $field = 'title', bool $hasParent = false): array
    {
        return $this->dao->select("id, {$field}")->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($planID)
            ->andWhere("CONCAT(',', `plan`, ',')")->like("%,{$planID},%")
            ->beginIF(!$hasParent)->andWhere("isParent")->eq('0')->fi()
            ->fi()
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

        if(common::isTutorialMode())
        {
            $stories = $this->loadModel('tutorial')->getStories();
            foreach($stories as $story)
            {
                if(!in_array($story->id, $storyIdList)) unset($stories[$story->id]);
            }
            return $stories;
        }

        return $this->dao->select('t1.*, t1.mailto, t2.spec, t2.verify, t3.name as productTitle, t3.deleted as productDeleted')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.version=t2.version')
            ->andWhere('t1.id')->in($storyIdList)
            ->beginIF($mode != 'all')->andWhere('t1.deleted')->eq('0')->fi()
            ->beginIF($this->config->vision == 'or')->andWhere("FIND_IN_SET('or', t1.vision)")->fi()
            ->fetchAll('id', false);
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
        $storyPairs = $this->dao->select('id, title')->from(TABLE_STORY)->where('id')->in($storyIdList)->beginIF($this->config->vision == 'or')->andWhere("FIND_IN_SET('or', t1.vision)")->fi()->fetchPairs();

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

        if($story->type == 'story' && $story->isParent == '0')
        {
            $story = $this->storyTao->getAffectedProjects($story, $users);
            $story = $this->storyTao->getAffectedBugs($story, $users);
            $story = $this->storyTao->getAffectedCases($story, $users);
            $story = $this->storyTao->getAffectedTwins($story, $users);
        }
        else
        {
            $story = $this->storyTao->getAffectedChildren($story, $users);
        }

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
     * @param  int|array    $executionID
     * @param  int          $productID
     * @param  string       $orderBy
     * @param  string       $browseType
     * @param  int|string   $param
     * @param  string       $storyType
     * @param  array|string $excludeStories
     * @param  object|null  $pager
     * @access public
     * @return array
     */
    public function getExecutionStories(int|array $executionID = 0, int $productID = 0, string $orderBy = 't1.`order`_desc', string $browseType = 'byModule', string $param = '0', string $storyType = 'story', array|string $excludeStories = '', ?object $pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStories();

        if(empty($executionID)) return array();

        if(is_array($executionID))
        {
            $module = 'execution';
        }
        else
        {
            $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch();
            $module    = $execution->type == 'project' ? 'project' : 'execution';
        }

        $sqlCondition = '';
        $showGrades   = isset($this->config->{$module}->showGrades) ? $this->config->{$module}->showGrades : null;
        if($showGrades && $this->config->vision != 'lite')
        {
            $items = explode(',', $showGrades);
            $conditions = array();
            foreach($items as $item)
            {
                preg_match('/^([a-zA-Z]+)(\d+)$/', $item, $matches);
                $type  = $matches[1];
                $grade = $matches[2];
                $conditions[$type][] = $grade;
            }

            $sqlCondition = '(';
            foreach($conditions as $type => $grades) $sqlCondition .= "(t2.type = '$type' AND t2.grade " . helper::dbIN($grades) . ") OR ";
            $sqlCondition  = rtrim($sqlCondition, 'OR ');
            $sqlCondition .= ')';
        }

        /* 格式化参数。 */
        $orderBy    = str_replace('branch_', 't2.branch_', $orderBy);
        $browseType = strtolower($browseType);
        if(is_string($excludeStories)) $excludeStories = array_filter(explode(',', $excludeStories));

        /* 获取需求。 */
        if($browseType == 'bysearch') $stories = $this->storyTao->getExecutionStoriesBySearch($executionID, (int)$param, $productID, $orderBy, $storyType, $sqlCondition, $excludeStories, $pager);
        if($browseType != 'bysearch')
        {
            /* 根据请求类型和参数，获取查询要用到的条件。 */
            $modules      = $this->storyTao->getModules4ExecutionStories($browseType, $param);
            $storyIdList  = is_array($executionID) ? array() : $this->storyTao->getIdListOfExecutionsByProjectID($browseType, $executionID);
            $productParam = ($browseType == 'byproduct' and $param)        ? $param : $productID;
            $branchParam  = ($browseType == 'bybranch'  and $param !== '') ? $param : (string)$this->cookie->storyBranchParam;

            /* 设置查询需求的公共 DAO 变量。 */
            $browseType = (!empty($browseType) and strpos('bymodule|byproduct', $browseType) !== false and $this->session->storyBrowseType) ? $this->session->storyBrowseType : $browseType;
            $storyDAO = $this->dao->select("DISTINCT t1.*, t2.*, t2.`path`, t2.`plan`, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
                ->where('t1.project')->in($executionID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t3.deleted')->eq(0)
                ->beginIF(strpos('withoutparent', $browseType) !== false)->andWhere('t2.isParent')->eq('0')->fi()
                ->beginIF($storyType != 'all')->andWhere('t2.type')->in($storyType)->fi()
                ->beginIF($sqlCondition)->andWhere($sqlCondition)->fi()
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->beginIF($this->session->storyBrowseType and strpos('changing|', $this->session->storyBrowseType) !== false)->andWhere('t2.status')->in($this->storyTao->getUnclosedStatusKeys())->fi()
                ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi();

            /* 根据传入的 ID 是项目还是执行分别查询需求。 */
            if($module == 'project') $stories = $this->storyTao->fetchProjectStories($storyDAO, $productID, $browseType, $branchParam, $storyIdList, $orderBy, $pager, $execution);
            if($module != 'project') $stories = $this->storyTao->fetchExecutionStories($storyDAO, (int)$productParam, $browseType, $branchParam, $orderBy, $pager);
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
    public function batchGetExecutionStories(string $executionIdList = '', int $productID = 0, string $orderBy = 't1.`order`_desc', string $type = 'byModule', string $param = '0', string $storyType = 'story', array|string $excludeStories = '', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getStories();

        if(empty($executionIdList)) return array();

        /* 格式化参数。 */
        $type = strtolower($type);
        if(is_string($excludeStories))$excludeStories = explode(',', $excludeStories);
        $executions   = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($executionIdList)->fetchAll('id');
        $hasExecution = false;
        foreach($executions as $execution)
        {
            if($execution->type != 'project') $hasExecution = true;
        }

        $modules        = $this->storyTao->getModules4ExecutionStories($type, $param);
        $unclosedStatus = $this->lang->story->statusList;
        unset($unclosedStatus['closed']);

        $branchParam  = ($type == 'bybranch' and $param !== '') ? $param : (string)$this->cookie->storyBranchParam;

        if(strpos($orderBy, 'version_') !== false) $orderBy = str_replace('id_', 't2.version_', $orderBy);
        if(strpos($orderBy, 'id_')      !== false) $orderBy = str_replace('id_', 't2.id_',      $orderBy);

        $stories = $this->dao->select("distinct t1.*, t2.`path`, t2.`plan`, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->in($executionIdList)
            ->andWhere('t2.type')->eq($storyType)
            ->andWhere('t2.parent')->ge(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
            ->beginIF($this->session->storyBrowseType and strpos('changing|', $this->session->storyBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($type == 'bybranch' and $branchParam !== '')->andWhere('t2.branch')->in("0,$branchParam")->fi()
            ->beginIF($hasExecution)
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
     * @param  string           $storyType    story|epic|requirement
     * @param  bool             $hasParent
     * @access public
     * @return array
     */
    public function getExecutionStoryPairs(int $executionID = 0, int $productID = 0, string|int $branch = 'all', array|string|int $moduleIdList = '', string $type = 'full', string $status = 'all', string $storyType = '', bool $hasParent = true): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStoryPairs();

        $stories = $this->dao->select('t2.id, t2.title, t2.module, t2.pri, t2.estimate, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq($executionID)
            ->beginIF($storyType)->andWhere('t2.type')->in($storyType)->fi()
            ->beginIF(!$hasParent)->andWhere('t2.isParent')->eq('0')->fi()
            ->andWhere('t2.deleted')->eq('0')
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
    public function getPlanStories(int $planID, string $status = 'all', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return array();

        if(strpos($orderBy, 'module') !== false)
        {
            $orderBy = (strpos($orderBy, 'module_asc') !== false) ? 't3.path asc' : 't3.path desc';
            $stories = $this->dao->select('distinct t1.story, t1.`plan`, t1.order, t2.*, t3.`path`')
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
            $stories = $this->dao->select("distinct t1.story, t1.`plan`, t1.order, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder")
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
        $this->common->saveQueryCondition($this->dao->get(), 'epic', false);
        $this->common->saveQueryCondition($this->dao->get(), 'requirement', false);

        /* Add parent list for display. */
        foreach($stories as $story)
        {
            $story->parent = array();
            foreach(explode(',', trim($story->path, ',')) as $parentID)
            {
                if(!$parentID) continue;
                if($parentID == $story->id) continue;
                $story->parent[] = (int)$parentID;
            }
        }

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
     * 批量获取关联传入项目ID的需求，并按照项目分组。
     * Fetch stories by project id list.
     *
     * @param  array $projectIdList
     * @param  string $storyType    story|requirement|epic
     * @access public
     * @return array
     */
    public function fetchStoriesByProjectIdList(array $projectIdList = array(), string $storyType = ''): array
    {
        return $this->dao->select("t2.*,t1.project")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->in($projectIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF(strpos(',story,requirement,epic,', ",{$storyType},") !== false)->andWhere('t2.type')->in($storyType)->fi()
            ->fetchGroup('project', 'id');
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

        if(isset($story->estimate)) $story->estimate = round((float)$story->estimate, 2);
        $storyID = $this->storyTao->doCreateStory($story);
        if(!$storyID) return false;

        /* Upload files. */
        $this->loadModel('action');
        $this->loadModel('file')->updateObjectID($this->post->uid, $storyID, $story->type);
        $files = $this->file->saveUpload($story->type, $storyID, 1);

        /* Add story spec verify. */
        $this->storyTao->doCreateSpec($storyID, $story, $files ?: '');

        $extraList   = $this->storyTao->parseExtra($extra);
        $storyFrom   = isset($extraList['fromType']) ? $extraList['fromType'] : '';
        $storyFromID = isset($extraList['fromID']) ? $extraList['fromID'] : '';
        $extra       = $bugID == 0 ? $storyFromID : $bugID;

        if($executionID) $this->storyTao->linkToExecutionForCreate($executionID, $storyID, $story, (string)$extra);
        if($bugID)       $this->storyTao->closeBugWhenToStory($bugID, $storyID);
        if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($storyID, $story->reviewer);
        if(!empty($story->parent))
        {
            $this->subdivide($story->parent, array($storyID));
            $this->updateParentStatus($storyID, $story->parent, false);
        }
        else
        {
            $this->dao->update(TABLE_STORY)->set('root')->eq($storyID)->set('path')->eq(",{$storyID},")->where('id')->eq($storyID)->exec();
        }
        if(!empty($story->plan))
        {
            $this->updateStoryOrderOfPlan($storyID, (string)$story->plan); // Set story order in this plan.
            foreach(explode(',', $story->plan) as $planID)
            {
                if(!$planID) continue;
                $this->action->create('productplan', (int)$planID, 'linkstory', '', $storyID);
            }
        }

        $this->setStage($storyID);
        $this->loadModel('score')->create('story', 'create',$storyID);

        /* Create actions. Record submit review action. */
        $bugAction = empty($storyFrom) ? 'Opened' : 'From' . ucfirst($storyFrom);
        $action    = $bugID == 0 ? $bugAction : 'Frombug';
        $this->action->create('story', $storyID, $action, '', $extra);
        if($story->status == 'reviewing') $this->action->create('story', $storyID, 'submitReview');
        if(!empty($story->assignedTo)) $this->action->create('story', $storyID, 'Assigned', '', $story->assignedTo);

        if($todoID > 0)
        {
            $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
            $this->action->create('todo', $todoID, 'finished', '', "STORY:$storyID");

            if($this->config->edition != 'open')
            {
                $todo = $this->dao->select('type, objectID')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
                if($todo->type == 'feedback' && $todo->objectID) $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, 'done', '', $todoID);
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
            $storyData->plan   = isset($storyData->plans[$key]) ? $storyData->plans[$key] : 0;

            $storyID = $this->create($storyData, $objectID, $bugID, $extra, $todoID);
            $storyIdList[$storyID] = $storyID;
            if(empty($mainStoryID)) $mainStoryID = $storyID;
        }

        $this->storyTao->updateTwins($storyIdList, $mainStoryID);
        return $mainStoryID;
    }

    /**
     * 解除孪生需求。
     * Relieve twins stories.
     *
     * @param  int $productID
     * @param  int $storyID
     * @access public
     * @return bool
     */
    public function relieveTwins(int $productID, int $storyID): bool
    {
        /* batchUnset twinID from twins.*/
        $this->dao->update(TABLE_STORY)->set("twins = REPLACE(twins, ',$storyID,', ',')")->where('product')->eq($productID)->exec();
        /* Update twins to empty by twinID and if twins eq ','.*/
        $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();

        return !dao::isError();
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
     * @access public
     * @return array
     */
    public function batchCreate(array $stories): array
    {
        $this->loadModel('action');
        $storyIdList = array();
        $link2Plans  = array();
        foreach($stories as $i => $story)
        {
            if(isset($story->estimate)) $story->estimate = round((float)$story->estimate, 2);
            $storyID = $this->storyTao->doCreateStory($story);
            if(!$storyID) return array();

            $this->storyTao->doCreateSpec($storyID, $story);
            if(!empty($story->parent))
            {
                $this->subdivide($story->parent, array($storyID));
                $this->updateParentStatus($storyID, $story->parent, false);
            }
            else
            {
                $this->dao->update(TABLE_STORY)->set('root')->eq($storyID)->set('path')->eq(",{$storyID},")->where('id')->eq($storyID)->exec();
            }

            /* Update product plan stories order. */
            if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($storyID, $story->reviewer);
            if($story->plan)
            {
                $this->updateStoryOrderOfPlan($storyID, (string)$story->plan);
                foreach(explode(',', (string)$story->plan) as $planID)
                {
                    if(!$planID) continue;
                    $link2Plans[$planID] = empty($link2Plans[$planID]) ? $storyID : "{$link2Plans[$planID]},$storyID";
                }
            }

            /* 拆分时，如果父需求已经在已计划、研发立项阶段，则不需要通过拆分的子需求推算父需求的阶段。*/
            if(!empty($story->parent))
            {
                $parentStage = $this->dao->select('stage')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch('stage');
                if(!in_array($parentStage, array('planned', 'projected'))) $this->setStage($storyID);
            }

            $this->executeHooks($storyID);

            $this->action->create('story', $storyID, 'Opened', '');
            if(!empty($story->assignedTo)) $this->action->create('story', $storyID, 'Assigned', '', $story->assignedTo);
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
        $this->dao->update(TABLE_STORY)->data($story, 'spec,verify,deleteFiles,renameFiles,files,relievedTwins,reviewer,reviewerHasChanged')
            ->autoCheck()
            ->batchCheck($this->config->{$oldStory->type}->change->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('file')->updateObjectID($this->post->uid, $storyID, $oldStory->type);

        $specChanged = $oldStory->version != $story->version;
        if($specChanged)
        {
            $this->loadModel('file')->processFileDiffsForObject($oldStory->type, $oldStory, $story, (string)$story->version);

            $this->storyTao->doCreateSpec($storyID, $story, $story->files);
            if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer($storyID, $story->reviewer, $story->version);

            /* Sync twins. */
            if(!isset($story->relievedTwins) and !empty($oldStory->twins))
            {
                foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                {
                    $this->storyTao->doCreateSpec((int)$twinID, $story, $story->files);
                    if(!empty($story->reviewer)) $this->storyTao->doCreateReviewer((int)$twinID, $story->reviewer, $story->version);
                }
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
            $this->relieveTwins($oldStory->product, $storyID);
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
     * @param  int      $storyID
     * @access public
     * @return bool|int
     */
    public function update(int $storyID, object $story, string|bool $comment = ''): bool|int
    {
        $oldStory = $this->getByID($storyID);

        /* Relieve twins when change product. */
        if(!empty($oldStory->twins) and $story->product != $oldStory->product)
        {
            $this->relieveTwins($oldStory->product, $storyID);
            $oldStory->twins = '';
        }

        if($oldStory->stage != 'verified' && $story->stage == 'verified') $story->verifiedDate = helper::now();
        if(isset($story->estimate)) $story->estimate = round((float)$story->estimate, 2);

        $moduleName = $this->app->rawModule;
        $this->dao->update(TABLE_STORY)->data($story, 'reviewer,spec,verify,deleteFiles,renameFiles,files,finalResult,oldDocs,docVersions')
            ->autoCheck()
            ->batchCheck($this->config->{$moduleName}->edit->requiredFields, 'notempty')
            ->checkIF(!empty($story->closedBy), 'closedReason', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->notifyEmail, 'notifyEmail', 'email')
            ->checkFlow()
            ->where('id')->eq((int)$storyID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');
        $this->loadModel('file')->updateObjectID($this->post->uid, $storyID, $oldStory->type);
        $this->file->processFileDiffsForObject($oldStory->type, $oldStory, $story, (string)$oldStory->version);
        $this->storyTao->doUpdateSpec($storyID, $story, $oldStory);
        $this->storyTao->doUpdateLinkStories($storyID, $story, $oldStory);

        if($story->product != $oldStory->product || $story->branch != $oldStory->branch)
        {
            $this->dao->update(TABLE_PROJECTSTORY)->set('product')->eq($story->product)->where('story')->eq($storyID)->exec();
            $childStories = $this->getAllChildId($storyID, false);
            $story->id    = $storyID;
            foreach($childStories as $childStoryID)
            {
                $this->updateStoryProduct($childStoryID, $story, $story->product);
            }
        }
        if($story->grade != $oldStory->grade) $this->syncGrade($oldStory, $story);
        $parentChanged = $story->parent != $oldStory->parent;
        if($parentChanged) $this->doChangeParent($storyID, $story, $oldStory);
        if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent, !$parentChanged);
        if($story->parent > 0) $this->updateParentStatus($storyID, $story->parent, !$parentChanged);
        $this->storyTao->computeParentStage($story);

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
            list($linkedBranches, $linkedProjects) = $this->storyTao->getLinkedBranchesAndProjects($storyID);
            foreach($linkedProjects as $linkedProject)
            {
                $this->dao->update(TABLE_STORYSTAGE)->set('stage')->eq($story->stage)->where('story')->eq((int)$storyID)->andWhere('branch')->in($linkedProject->branches)->exec();
            }

            $executionIdList = $this->dao->select('t1.project')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.story')->eq($storyID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.type')->in('sprint,stage,kanban')
                ->fetchPairs('project', 'project');

            $this->loadModel('kanban');
            foreach($executionIdList as $executionID) $this->kanban->updateLane($executionID, $oldStory->type, $storyID);
        }

        unset($oldStory->parent, $story->parent);
        if($this->config->edition != 'open' && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status, $storyID);

        if(!empty($story->reviewer))
        {
            if(strpos(',draft,changing,', ",{$oldStory->status},") !== false && $story->status == 'reviewing') $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->in($oldStory->version)->exec();

            $oldReviewer = $this->getReviewerPairs($storyID, $oldStory->version);
            $oldStory->reviewers = implode(',', array_keys($oldReviewer));
            $story->reviewers    = implode(',', $story->reviewer);
            if($story->reviewers != $oldStory->reviewers)
            {
                $oldStatus = $story->status;
                $this->doUpdateReviewer($storyID, $story);
                if($story->status != $oldStatus) $this->dao->update(TABLE_STORY)->set('status')->eq($story->status)->where('id')->eq($storyID)->exec();
                if($story->status == 'active')   $story->finalResult = $story->status;
            }
        }

        $story   = $this->loadModel('file')->replaceImgURL($story, 'spec,verify');
        $changes = common::createChanges($oldStory, $story);
        if(!empty($comment) or !empty($changes))
        {
            $action   = !empty($changes) ? 'Edited' : 'Commented';
            $actionID = $this->action->create('story', $storyID, $action, $comment);
            $this->action->logHistory($actionID, $changes);
            if(isset($story->finalResult))
            {
                if($story->finalResult == 'clarify')
                {
                    $action = 'reviewclarified';
                }
                elseif($story->finalResult == 'active')
                {
                    $action = 'reviewpassed';
                }
                else
                {
                    $action = 'review' . $story->finalResult . 'ed';
                }

                $this->action->create('story', $storyID, $action, '', "{$story->finalResult}|$oldStatus");
            }
        }

        if(isset($story->closedReason) and $story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');
        if(!empty($oldStory->twins)) $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Edited');

        return !empty($actionID) ? $actionID : false;
    }

    /**
     * Update story product.
     *
     * @param  int    $storyID
     * @param  object $parentID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function updateStoryProduct(int $storyID, object $parent, int $productID): void
    {
        if($parent->id != $storyID)
        {
            $childPath = $this->dao->select('path')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch('path');
            $childPath = strstr($childPath, ",{$parent->id},");
            $this->dao->update(TABLE_STORY)
                 ->set('product')->eq($parent->product)
                 ->set('branch')->eq($parent->branch)
                 ->set('module')->eq(0)
                 ->set('root')->eq($parent->id)
                 ->set('path')->eq($childPath)
                 ->where('id')->eq($storyID)
                 ->exec();
        }
        else
        {
            $this->dao->update(TABLE_STORY)->set('path')->eq(",{$storyID},")->where('id')->eq($storyID)->exec();
        }

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
            $this->dao->update(TABLE_STORY)->set('isParent')->eq('0')->where('id')->eq($parentID)->exec();
            return true;
        }
        $this->dao->update(TABLE_STORY)->set('isParent')->eq('1')->where('id')->eq($parentID)->exec();

        $oldParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->andWhere('deleted')->eq(0)->fetch();
        if(empty($oldParentStory))
        {
            $this->dao->update(TABLE_STORY)->set('parent')->eq('0')->where('id')->eq($storyID)->exec();
            return true;
        }
        $this->computeEstimate($parentID);

        $status = $oldParentStory->status;
        if(count($childrenStatus) == 1 and current($childrenStatus) == 'closed')   $status = current($childrenStatus); // Close parent story.
        if($oldParentStory->status == 'closed' && $childStory->status == 'active') $status = $this->getActivateStatus($parentID); // Activate parent story.

        $action    = '';
        $preStatus = '';
        if($status and $oldParentStory->status != $status)
        {
            $story = $this->storyTao->doUpdateParentStatus($parentID, $status);
            if(dao::isError()) return false;
            if(!$createAction) return $story;

            if(strpos('active,draft,changing', $status) !== false) $action = 'Activated';
            if($status == 'closed')
            {
                /* Record the status before closed. */
                $action    = 'closedbysystem';
                $preStatus = $oldParentStory->status;
                $isChanged = $oldParentStory->changedBy ? true : false;
                if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';
            }
        }
        else
        {
            $action = 'Edited';
            if(dao::isError()) return false;
        }

        $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->fetch();
        if($this->config->edition != 'open' && $oldParentStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldParentStory->feedback, $status, $oldParentStory->status, $oldParentStory->id);

        $changes = common::createChanges($oldParentStory, $newParentStory);
        if($action and $changes)
        {
            $actionID = $this->loadModel('action')->create('story', $parentID, $action, '', $preStatus, '', false);
            $this->action->logHistory($actionID, $changes);
        }

        if($newParentStory->parent) return $this->updateParentStatus($parentID, $newParentStory->parent, true);
        return true;
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
            $maxOrders = $this->dao->select('`plan`, max(`order`) as `order`')->from(TABLE_PLANSTORY)->where('plan')->in($plansTobeInsert)->groupBy('plan')->fetchPairs();
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
            $oldStory = $oldStories[$storyID];
            if($story->plan != $oldStory->plan)
            {
                if(!empty($oldStory->plan))
                {
                    foreach(explode(',', (string)$oldStory->plan) as $planID)
                    {
                        if(!$planID) continue;
                        $unlinkPlans[$planID] = empty($unlinkPlans[$planID]) ? $storyID : "{$unlinkPlans[$planID]},$storyID";
                    }
                }
                if(!empty($story->plan))
                {
                    foreach(explode(',', (string)$story->plan) as $planID)
                    {
                        if(!$planID) continue;
                        $link2Plans[$planID] = empty($link2Plans[$planID]) ? $storyID : "{$link2Plans[$planID]},$storyID";
                    }
                }
            }
        }

        $this->loadModel('action');
        foreach($stories as $storyID => $story)
        {
            $oldStory = $oldStories[$storyID];

            if($oldStory->stage != 'verified' && $story->stage == 'verified') $story->verifiedDate = helper::now();
            if(isset($story->estimate)) $story->estimate = round((float)$story->estimate, 2);

            $this->dao->update(TABLE_STORY)->data($story)
                ->autoCheck()
                ->checkIF($story->closedBy, 'closedReason', 'notempty')
                ->checkIF($story->closedReason == 'done', 'stage', 'notempty')
                ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                ->where('id')->eq((int)$storyID)
                ->exec();

            if(dao::isError()) return false;

            /* Update story sort of plan when story plan has changed. */
            if($oldStory->plan != $story->plan) $this->updateStoryOrderOfPlan($storyID, (string)$story->plan, $oldStory->plan);
            if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);

            $this->executeHooks($storyID);
            if($oldStory->type == 'story' && $story->stage != $oldStory->stage) $this->batchChangeStage(array($storyID), $story->stage);
            if($story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');
            if($story->roadmap != $oldStory->roadmap) $this->storyTao->computeParentStage($oldStory);

            $changes = common::createChanges($oldStory, $story);
            if($changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if($this->config->edition != 'open' && $oldStory->feedback && !isset($feedbacks[$oldStory->feedback]))
            {
                $feedbacks[$oldStory->feedback] = $oldStory->feedback;
                $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status, $storyID);
            }
        }

        $this->loadModel('score')->create('ajax', 'batchEdit');
        foreach($unlinkPlans as $planID => $stories) $this->action->create('productplan', (int)$planID, 'unlinkstory', '', $stories);
        foreach($link2Plans as $planID => $stories)  $this->action->create('productplan', (int)$planID, 'linkstory', '', $stories);

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
        if(isset($story->estimate)) $story->estimate = round((float)$story->estimate, 2);

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
        if($story->result == 'reject' && $this->config->edition != 'open' && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status, $storyID);

        if($oldStory->parent) $this->computeEstimate($oldStory->parent);

        $changes = common::createChanges($oldStory, $story);
        if($changes)
        {
            $story->id = $storyID;
            $actionID  = $this->recordReviewAction($oldStory, $story, $comment);
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
        $this->app->loadLang('product');

        $oldStories          = $this->getByList($storyIdList);
        $hasResult           = $this->dao->select('story,version,result')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->andWhere('reviewer')->eq($account)->andWhere('result')->ne('')->orderBy('version')->fetchAll('story');
        $reviewerList        = $this->dao->select('story,reviewer,result,version')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->orderBy('version')->fetchGroup('story', 'reviewer');
        $isSuperReviewer     = $this->storyTao->isSuperReviewer();
        $cannotReviewStories = array();
        $cannotRejectStories = array();
        $cannotReviewTips    = $this->lang->product->reviewStory;

        foreach($storyIdList as $storyID)
        {
            $storyID  = (int)$storyID;
            $oldStory = zget($oldStories, $storyID, '');
            if(empty($oldStory)) continue;

            if($oldStory->status != 'reviewing') continue;
            if($oldStory->version > 1 and $result == 'reject')
            {
                $cannotRejectStories[$storyID] = "#{$storyID}";
                continue;
            }
            if(isset($hasResult[$storyID]) and $hasResult[$storyID]->version == $oldStory->version) continue;

            /* 当评审人列表中没有当前用户或者当前用户不是当前版本需求的评审人时，将需求ID添加到不能评审的提示语中。*/
            if((!isset($reviewerList[$storyID][$account]) || (isset($reviewerList[$storyID][$account]) && $reviewerList[$storyID][$account]->version != $oldStory->version)) && !$isSuperReviewer)
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
            $story->closedReason   = $result == 'reject' ? $reason : '';
            $story->reviewedBy     = str_contains(",{$oldStory->reviewedBy},", ",{$account},") ? $oldStory->reviewedBy : ($oldStory->reviewedBy . ',' . $account);

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

            $story->id     = $storyID;
            $story->result = $result;
            $this->recordReviewAction($oldStories[$storyID], $story);

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

        $message = '';
        if($cannotReviewStories) $message .= sprintf($cannotReviewTips, implode(',', $cannotReviewStories));
        if(!empty($cannotRejectStories)) $message .= sprintf($this->lang->story->cannotRejectTips, implode(',', $cannotRejectStories));
        if(!empty($message)) return $message;

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
            $story->status  = 'active';
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
        if($story->reviewer) $story->status = 'reviewing';

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
        $now       = helper::now();
        $oldStory  = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $storyType = $this->dao->select('id,type')->from(TABLE_STORY)->where('id')->in($SRList)->fetchPairs('id');

        /* Set parent to child story. */
        foreach($SRList as $childStoryID)
        {
            $path = rtrim($oldStory->path, ',') . ",$childStoryID,";
            $this->dao->update(TABLE_STORY)
                 ->set('parent')->eq($storyID)
                 ->set('parentVersion')->eq($oldStory->version)
                 ->set('root')->eq($oldStory->root)
                 ->set('path')->eq($path)
                 ->where('id')->eq($childStoryID)
                 ->exec();

            if($this->config->edition != 'open')
            {
                $relation = new stdClass();
                $relation->product  = 0;
                $relation->AID      = $storyID;
                $relation->AType    = $oldStory->type;
                $relation->relation = 'subdivideinto';
                $relation->BID      = $childStoryID;
                $relation->BType    = $storyType[$childStoryID];
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }
        $this->computeEstimate($storyID);

        /* Set childStories. */
        $newStory     = new stdClass();
        $newStory->isParent       = '1';
        $newStory->lastEditedBy   = $this->app->user->account;
        $newStory->lastEditedDate = $now;
        $this->dao->update(TABLE_STORY)->data($newStory)->autoCheck()->where('id')->eq($storyID)->exec();

        $childStories = implode(',', $SRList);
        $actionID = $this->loadModel('action')->create('story', $storyID, 'createChildrenStory', '', $childStories);
        $this->action->logHistory($actionID, common::createChanges($oldStory, $newStory));
    }

    /**
     * 关闭需求。
     * Close the story.
     *
     * @param  int         $storyID
     * @param  object      $postData
     * @access public
     * @return array|false
     */
    public function close(int $storyID, object $postData): array|false
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $story    = $postData;

        $this->loadModel($oldStory->type);

        if(!empty($story->duplicateStory))
        {
            $duplicateStoryID = $this->dao->select('id')->from(TABLE_STORY)->where('id')->eq($story->duplicateStory)->fetch();
            if(empty($duplicateStoryID))
            {
                dao::$errors['duplicateStory'] = sprintf($this->lang->story->errorDuplicateStory, $story->duplicateStory);
                return false;
            }
        }

        if($story->closedReason == 'duplicate' and empty($story->duplicateStory))
        {
            dao::$errors['duplicateStory'] = sprintf($this->lang->error->notempty, $this->lang->story->duplicateStory);
            return false;
        }

        if(strpos($this->config->{$oldStory->type}->close->requiredFields, 'comment') !== false and !$this->post->comment) dao::$errors['comment'][] = sprintf($this->lang->error->notempty, $this->lang->comment);

        $this->lang->story->comment = $this->lang->comment;
        $this->dao->update(TABLE_STORY)->data($story, 'comment,closeSync')
            ->autoCheck()
            ->batchCheck($this->config->{$oldStory->type}->close->requiredFields, 'notempty')
            ->checkIF($story->closedReason == 'duplicate', 'duplicateStory', 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldStory, $story);
        if($changes)
        {
            $preStatus = $oldStory->status;
            $isChanged = !empty($oldStory->changedBy) ? true : false;
            if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';

            $actionID = $this->loadModel('action')->create('story', $storyID, 'Closed', $this->post->comment, ucfirst(zget($_POST, 'closedReason', '')) . (!empty($_POST['duplicateStory']) ? ':' . (int)$this->post->duplicateStory : '') . "|$preStatus");
            $this->action->logHistory($actionID, $changes);
        }

        $this->dao->update(TABLE_STORY)->set('assignedTo')->eq('closed')->set('assignedDate')->eq(helper::now())->where('id')->eq((int)$storyID)->exec();

        if($oldStory->isParent == '1') $this->closeAllChildren($storyID, $story->closedReason);
        $this->setStage($storyID);
        $this->loadModel('score')->create('story', 'close', $storyID);

        if($this->config->edition != 'open' && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status, $storyID);

        if(!empty($postData->closeSync))
        {
            $this->relieveTwins($oldStory->product, $storyID);

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
     * @return bool
     */
    public function batchClose(array $stories): bool
    {
        $this->loadModel('action');
        $oldStories = $this->getByList(array_keys($stories));
        foreach($stories as $storyID => $story)
        {
            if(empty($story->closedReason)) continue;

            $oldStory = $oldStories[$storyID];
            if(isset($stories[$oldStory->parent])) continue;

            $this->dao->update(TABLE_STORY)->data($story, 'comment')->autoCheck()
                ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                ->where('id')->eq($storyID)
                ->exec();

            if(dao::isError())
            {
                dao::$errors[] = 'story#' . $storyID . dao::getError(true);
                return false;
            }

            if($oldStory->isParent == '1') $this->closeAllChildren($storyID, $story->closedReason);
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
                $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status, $storyID);
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

            /* Ignore story linked to this plan already. */
            if(strpos(",{$oldStory->plan},", ",$planID,") !== false) continue;

            /* Init story and set last edited data. */
            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->demand         = $oldStory->demand;

            /* Remove old plan from the plan field. */
            if($oldPlanID) $story->plan = trim(str_replace(",$oldPlanID,", ',', ",$oldStory->plan,"), ',');

            /* Update the order of the story in the plan. */
            $oldStoryPlan = $oldStory->type == 'story' ? $oldStory->plan : '';
            $this->updateStoryOrderOfPlan((int)$storyID, (string)$planID, $oldStoryPlan);

            /* 用需、业需追加计划，软需替换计划。 */
            $productType = $products[$oldStory->product]->type;
            if($oldStory->type != 'story')
            {
                $story->plan = trim("{$oldStory->plan},{$planID}", ',');
            }
            elseif($productType == 'normal' || empty($oldStory->plan) || $oldStory->branch)
            {
                $story->plan = $planID;
            }

            /* Change stage. */
            if($planID)
            {
                if($oldStory->stage == 'wait') $story->stage = 'planned';
                if($productType != 'normal' and $oldStory->branch == 0)
                {
                    if(!empty($oldPlanID) && $oldStory->type == 'story') $story->plan = trim("{$story->plan},{$planID}", ',');
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

            if(!$planID) $this->setStage((int)$storyID);
            if(isset($story->stage) && $story->stage != $oldStory->stage)
            {
                $story->parent = $oldStory->parent;
                $this->storyTao->computeParentStage($story);
            }
            if(!dao::isError())
            {
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
                if($story->plan != $oldStory->plan and !empty($oldStory->plan) and $oldStory->type == 'story')
                {
                    foreach(explode(',', (string)$oldStory->plan) as $oldPlanID)
                    {
                        if(!$oldPlanID) continue;
                        $unlinkPlans[$oldPlanID] = empty($unlinkPlans[$oldPlanID]) ? $storyID : "{$unlinkPlans[$oldPlanID]},$storyID";
                    }
                }
                if($story->plan != $oldStory->plan and !empty($story->plan))
                {
                    foreach(explode(',', (string)$story->plan) as $newPlanID)
                    {
                        if(!$newPlanID) continue;
                        $link2Plans[$newPlanID] = empty($link2Plans[$newPlanID]) ? $storyID : "{$link2Plans[$newPlanID]},$storyID";
                    }
                }
            }
        }

        if(!dao::isError())
        {
            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $stories) $this->action->create('productplan', (int)$planID, 'unlinkstory', '', $stories);
            foreach($link2Plans  as $planID => $stories) $this->action->create('productplan', (int)$planID, 'linkstory', '', $stories);
        }

        return $allChanges;
    }

    /**
     * 批量修改需求的父需求。
     * Batch change the parent of story.
     *
     * @param  string $storyIdList
     * @param  int    $parentID
     * @param  string $storyType
     * @access public
     * @return bool|string
     */
    public function batchChangeParent(string $storyIdList, int $parentID, string $storyType = 'story')
    {
        if(empty($storyIdList)) return;

        $stories    = $this->getByList($storyIdList);
        $parent     = $this->fetchById($parentID);
        $gradePairs = $this->getGradePairs($storyType);
        $gradePairs = array_keys($gradePairs);

        $gradeErrorStories  = array();
        $parentErrorStories = array();

        $this->loadModel('action');
        foreach($stories as $storyID => $story)
        {
            if(!empty($story->twins)) continue;
            if($story->parent == $parentID) continue;
            if($story->id == $parentID)
            {
                $parentErrorStories[] = '#' . $storyID;
                continue;
            }
            if($parent && $story->root == $parent->root && $story->type == $parent->type && $story->grade < $parent->grade)
            {
                $parentErrorStories[] = '#' . $storyID;
                continue;
            }

            $oldStory = clone $story;
            if($parent && $parent->type == $story->type)
            {
                $parentGradeIndex = array_search($parent->grade, $gradePairs);
                $story->grade     = $gradePairs[$parentGradeIndex + 1];
            }
            else
            {
                $story->grade = current($gradePairs);
            }

            if($story->grade > $oldStory->grade)
            {
                if(!$this->checkGrade($story, $oldStory, 'batch'))
                {
                    $gradeErrorStories[] = '#' . $storyID;
                    continue;
                }
            }

            if($story->grade != $oldStory->grade) $this->syncGrade($oldStory, $story);

            $this->dao->update(TABLE_STORY)
                 ->set('grade')->eq($story->grade)
                 ->set('parent')->eq($parentID)
                 ->autoCheck()
                 ->where('id')->eq($story->id)
                 ->exec();

            $this->action->create('story', $story->id, 'syncGrade', '', $story->grade);

            $story->parent = $parentID;
            $this->doChangeParent($story->id, $story, $oldStory);

            if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent, false);
            if($story->parent > 0) $this->updateParentStatus($storyID, $story->parent, false);

            $this->setStage($story->id);
        }

        $notice = '';
        if($gradeErrorStories)  $notice = sprintf($this->lang->story->batchGradeOverflow, implode(',', $gradeErrorStories));
        if($parentErrorStories) $notice = sprintf($this->lang->story->batchParentError, implode(',', $parentErrorStories));

        return $notice;
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
            if(dao::isError()) return array();

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
        return $allChanges;
    }

    /**
     * Batch change the grade of story.
     *
     * @param array        $storyIdList
     * @param int          $grade
     * @param string       $storyType
     *
     * @access public
     * @return string|null
     */
    public function batchChangeGrade(array $storyIdList, int $grade, string $storyType = 'story'): string|null
    {
        $now           = helper::now();
        $account       = $this->app->user->account;
        $oldStories    = $this->getByList($storyIdList);
        $this->loadModel('action');

        $rootGroup    = array();
        $parentIdList = array();
        foreach($oldStories as $oldStory)
        {
            $rootGroup[$oldStory->root] = isset($rootGroup[$oldStory->root]) ? $rootGroup[$oldStory->root] + 1 : 1;
            if($oldStory->parent > 0) $parentIdList[] = $oldStory->parent;
        }

        $parents = $this->dao->select('id, grade, type')->from(TABLE_STORY)->where('id')->in($parentIdList)->fetchAll('id');

        $sameRootList      = '';
        $gradeGtParentList = '';
        $gradeOverflowList = '';

        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($grade == $oldStory->grade) continue;
            if($oldStory->type != $storyType) continue;

            if($rootGroup[$oldStory->root] > 1)
            {
                $sameRootList .= "#{$storyID} ";
                continue;
            }

            if($oldStory->parent > 0 && $grade < $parents[$oldStory->parent]->grade && $oldStory->type == $parents[$oldStory->parent]->type)
            {
                $gradeGtParentList .= "#{$storyID} ";
                continue;
            }

            $story = new stdclass();
            $story->lastEditedBy   = $account;
            $story->lastEditedDate = $now;
            $story->grade          = $grade;

            if($story->grade > $oldStory->grade)
            {
                if(!$this->checkGrade($story, $oldStory))
                {
                    $gradeOverflowList .= "#{$storyID} ";
                    continue;
                }
            }

            if($story->grade != $oldStory->grade) $this->syncGrade($oldStory, $story);
            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError())
            {
                $changes  = common::createChanges($oldStory, $story);
                $actionID = $this->action->create('story', (int)$storyID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        if($gradeOverflowList) return sprintf($this->lang->story->batchGradeOverflow, $gradeOverflowList);
        if($sameRootList)      return sprintf($this->lang->story->batchGradeSameRoot, $sameRootList);
        if($gradeGtParentList) return sprintf($this->lang->story->batchGradeGtParent, $gradeGtParentList);
        return null;
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
            if($oldStory->type != 'story' || $oldStory->isParent == '1') continue;

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

            $oldStory->stage = $stage;
            $this->storyTao->computeParentStage($oldStory);
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
            if(isset($task->estimate)) $task->estimate = round((float)$task->estimate, 2);
            if(isset($task->left)) $task->left = round((float)$task->left, 2);
            $this->dao->insert(TABLE_TASK)->data($task)->autoCheck()
                ->batchCheck($this->config->task->create->requiredFields, 'notempty')
                ->exec();

            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();

            $this->dao->update(TABLE_TASK)->set('path')->eq(",$taskID,")->where('id')->eq($taskID)->exec();

            $taskIdList[] = $taskID;

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;
            $taskSpec->estStarted = $task->estStarted;
            $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            if($task->story)
            {
                $this->setStage($task->story);
                if($this->config->edition != 'open')
                {
                    $relation = new stdClass();
                    $relation->relation = 'generated';
                    $relation->AID      = $task->story;
                    $relation->AType    = 'story';
                    $relation->BID      = $taskID;
                    $relation->BType    = 'task';
                    $relation->product  = 0;
                    $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                }
            }
            $this->action->create('task', $taskID, 'Opened', '');
        }

        $this->loadModel('kanban')->updateLane($executionID, 'task');
        return $taskIdList;
    }

    /**
     * 指派需求。
     * Assign story.
     *
     * @param  int         $storyID
     * @param  object      $story
     * @access public
     * @return array|bool
     */
    public function assign(int $storyID, object $story): array|bool
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if($story->assignedTo == $oldStory->assignedTo) return array();

        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->where('id')->eq((int)$storyID)->exec();

        $changes = common::createChanges($oldStory, $story);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('story', $storyID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);
        }

        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Assigned');

        return !dao::isError();
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
     * @return bool
     */
    public function activate(int $storyID, object $postData): bool
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();

        /* Get status after activation. */
        $story = $postData;
        $story->status = $this->getActivateStatus($storyID);

        $this->dao->update(TABLE_STORY)->data($story, 'comment')->autoCheck()->checkFlow()->where('id')->eq($storyID)->exec();

        /* Update parent story status. */
        if(!empty($oldStory->parent) && $oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);

        $this->setStage($storyID);

        $changes = common::createChanges($oldStory, $story);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('story', $storyID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Activated');
        if($this->config->edition != 'open' && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status, $storyID);

        if($this->config->edition == 'ipd' and $oldStory->demand)
        {
            $this->loadModel('demand')->changeDemandStatus($oldStory->demand, '0', true);
            $this->loadModel('action')->create('demand', $oldStory->demand, 'restored', '', $storyID);

            $relation = new stdClass();
            $relation->AID      = $oldStory->demand;
            $relation->AType    = 'demand';
            $relation->relation = 'subdivideinto';
            $relation->BID      = $storyID;
            $relation->BType    = $oldStory->type;
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        return !dao::isError();
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
        $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(empty($story)) return false;

        /* 获取已经存在的分支阶段. */
        $oldStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchAll('branch');
        $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->exec();

        /* 手动设置了阶段，就不需要自动计算阶段了。 */
        $product   = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fetch();
        $hasBranch = ($product and $product->type != 'normal' and empty($story->branch));
        if (!empty($story->stagedBy) and $story->status != 'closed') return true;

        /* 获取需求关联的分支和项目。 */
        list($linkedBranches, $linkedProjects) = $this->storyTao->getLinkedBranchesAndProjects($storyID);

        /* 设置默认阶段。 */
        $stages = array();
        if($hasBranch) $stages = $this->storyTao->getDefaultStages($story->plan, $linkedProjects ? $linkedBranches : array());

        /* When the status is closed, stage is also changed to closed. */
        if($story->status == 'closed') return $this->storyTao->setStageToClosed($storyID, array_merge($linkedBranches, array_keys($stages)), $linkedProjects);

        /* If no executions, in plan, stage is planned. No plan, wait. */
        if(!$linkedProjects) $this->storyTao->setStageToPlanned($storyID, $stages, $oldStages);

        /* 根据需求关联的任务状态统计，计算需求的阶段。 */
        $taskStat = $this->storyTao->getLinkedTaskStat($storyID, $linkedProjects);
        $stages   = $this->storyTao->computeStagesByTasks($storyID, $taskStat, $stages, $linkedProjects);
        $stages   = $this->storyTao->computeStagesByRelease($storyID, $stages);

        $this->storyTao->updateStage($storyID, $stages, $oldStages, $linkedProjects);
        return true;
    }

    /**
     * 获取可关联的需求列表。
     * Get the stories to link.
     *
     * @param  int     $storyID
     * @param  string  $browseType bySearch
     * @param  int     $queryID
     * @param  object  $pager
     * @access public
     * @return array
     */
    public function getStories2Link(int $storyID, string $browseType = 'bySearch', int $queryID = 0, ?object $pager = null): array
    {
        $story    = $this->getById($storyID);
        $excludes = $this->storyTao->getRelation($storyID, $story->type);

        /* 自身不能关联自身。 */
        $excludes[$storyID] = $storyID;

        $stories2Link = array();
        if($browseType == 'bySearch')
        {
            $stories2Link = $this->getBySearch($story->product, $story->branch, $queryID, 'id_desc', 0, 'all', $excludes, '', $pager);
        }

        return $stories2Link;
    }

    /**
     * Get stories list of a product.
     *
     * @param  string|int|array $productID
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
    public function getProductStories(string|int|array $productID = 0, array|string|int $branch = 0, array|string $moduleIdList = '', array|string $status = 'all', string $type = 'story', string $orderBy = 'id_desc', bool $hasParent = true, array|string $excludeStories = '', ?object $pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getStories();
        $showGrades = isset($this->config->{$type}->showGrades) ? $this->config->{$type}->showGrades : null;
        if($showGrades)
        {
            $pattern = "/{$type}(\d+)/";
            preg_match_all($pattern, $showGrades, $matches);
            $showGrades = isset($matches[1]) ? $matches[1] : null;
        }

        $productQuery = $this->storyTao->buildProductsCondition($productID, $branch);
        $stories      = $this->dao->select("*,`plan`,`path`,IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere($productQuery)
            ->beginIF(!$hasParent)->andWhere("isParent")->eq('0')->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF(!empty($excludeStories))->andWhere('id')->notIN($excludeStories)->fi()
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
            ->beginIF($showGrades)->andWhere('grade')->in($showGrades)->fi()
            ->beginIF(empty($this->config->enableER))->andWhere('type')->ne('epic')->fi()
            ->beginIF(empty($this->config->URAndSR) && $this->config->edition != 'ipd')->andWhere('type')->ne('requirement')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getStoryPairs();

        if($hasParent === 'false') $hasParent = false;

        $stories = $this->dao->select('t1.id, t1.title, t1.module, t1.pri, t1.estimate, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($productIdList)->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in("0,$branch")->fi()
            ->beginIF(!$hasParent)->andWhere('t1.isParent')->eq('0')->fi()
            ->beginIF($status and $status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF($type != 'full' && $type != 'all')->andWhere('t1.type')->eq($storyType)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($order)
            ->fetchAll();

        if(!$stories) return array();
        return $this->formatStories($stories, $type, $limit);
    }

    /**
     * 关闭父需求的所有子需求。
     * Close all children of a story.
     *
     * @param  int    $storyID
     * @param  string closedReason
     * @access public
     * @return void
     */
    public function closeAllChildren(int $storyID, string $closedReason)
    {
        $now         = helper::now();
        $childIdList = $this->getAllChildId($storyID, false);
        $childList   = $this->getByList($childIdList);
        $this->dao->update(TABLE_STORY)
             ->set('status')->eq('closed')
             ->set('stage')->eq('closed')
             ->set('assignedTo')->eq('closed')
             ->set('assignedDate')->eq($now)
             ->set('closedReason')->eq($closedReason)
             ->set('closedBy')->eq($this->app->user->account)
             ->set('closedDate')->eq($now)
             ->set('lastEditedBy')->eq($this->app->user->account)
             ->set('lastEditedDate')->eq($now)
             ->where('id')->in($childIdList)
             ->exec();

        $this->loadModel('action');
        foreach($childList as $child)
        {
            $preStatus = $child->status;
            $isChanged = !empty($child->changedBy) ? true : false;
            if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';
            $this->loadModel('action')->create('story', $child->id, 'closedbyparent', '', ucfirst($closedReason) . "|$preStatus");
        }
    }

    /**
     * 获取需求的所有子需求ID。
     * Get all child stories of a story.
     *
     * @param  int    $storyID
     * @param  bool   $includeSelf
     * @param  bool   $sameType true|false
     * @access public
     * @return array
     */
    public function getAllChildId(int $storyID, bool $includeSelf = true, bool $sameType = false): array
    {
        if($storyID == 0) return array();

        $story = $this->fetchByID($storyID);
        if(empty($story)) return array();

        $children = $this->dao->select('id')->from(TABLE_STORY)
            ->where('path')->like($story->path . '%')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$includeSelf)->andWhere('id')->ne($storyID)->fi()
            ->beginIF($sameType)->andWhere('type')->eq($story->type)->fi()
            ->fetchPairs();

        return array_keys($children);
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
     * @param  int|array    $productID
     * @param  int|string   $branch
     * @param  string|array $modules
     * @param  string       $fieldName
     * @param  string       $fieldValue
     * @param  string       $type       requirement|story
     * @param  string       $orderBy
     * @param  object       $pager
     * @param  string       $operator   equal|include
     * @access public
     * @return array
     */
    public function getByField(int|array $productID, int|string $branch, string|array $modules, string $fieldName, string $fieldValue, string $type = 'story', string $orderBy = '', ?object $pager = null, string $operator = 'equal'): array
    {
        if(!$this->loadModel('common')->checkField(TABLE_STORY, $fieldName) and $fieldName != 'reviewBy' and $fieldName != 'assignedBy') return array();

        $actionIDList = array();
        if($fieldName == 'assignedBy') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($fieldValue)->fetchPairs('objectID', 'objectID');

        $sql = $this->dao->select("t1.*, t1.`path`, t1.`plan`, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1');
        if($fieldName == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version');

        $showGrades = isset($this->config->{$type}->showGrades) ? $this->config->{$type}->showGrades : null;
        if($showGrades)
        {
            $pattern = "/{$type}(\d+)/";
            preg_match_all($pattern, $showGrades, $matches);
            $showGrades = isset($matches[1]) ? $matches[1] : null;
        }

        if($fieldValue == 'launched' && $fieldName == 'status')
        {
            $fieldValue = 'projected';
            $fieldName  = 'stage';
        }

        $stories = $sql->where('t1.product')->in($productID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.type')->eq($type)
            ->beginIF($showGrades)->andWhere('t1.grade')->in($showGrades)->fi()
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
     * @param  int|array $productID
     * @param  int       $branch
     * @param  string    $modules
     * @param  string    $type requirement|story
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function get2BeClosed(int|array $productID, int|string $branch, string|array $modules, string $type = 'story', string $orderBy = '', ?object $pager = null): array
    {
        $showGrades = isset($this->config->{$type}->showGrades) ? $this->config->{$type}->showGrades : null;
        if($showGrades)
        {
            $pattern = "/{$type}(\d+)/";
            preg_match_all($pattern, $showGrades, $matches);
            $showGrades = isset($matches[1]) ? $matches[1] : null;
        }

        $stories = $this->dao->select("*, `path`, `plan`, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere('type')->eq($type)
            ->beginIF($showGrades)->andWhere('grade')->in($showGrades)->fi()
            ->beginIF($branch and $branch != 'all')->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('stage')->in('developed,released,delivered')
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
     * @param  int|string  $productID
     * @param  int|string  $branch
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  int|array   $executionID
     * @param  string      $type requirement|story
     * @param  string      $excludeStories
     * @param  string      $excludeStatus
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getBySearch(int|string $productID, int|string $branch = '', int $queryID = 0, string $orderBy = '', int|array $executionID = 0, string $type = 'story', array|string $excludeStories = '', string $excludeStatus = '', ?object $pager = null): array
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
        elseif($this->app->rawModule == 'mr' && $this->session->repoID)
        {
            $repo     = $this->loadModel('repo')->fetchByID((int)$this->session->repoID);
            $products = $repo ? array_flip(explode(',', $repo->product)) : array();
        }
        else
        {
            $products = empty($executionID) ? $this->product->getList(0, 'all', 0, 0, 'all') : $this->product->getProducts($executionID);
        }

        $module = in_array($type, array('requirement', 'epic')) ? $type : 'story';
        $this->loadModel('search')->setQuery($module, $queryID);

        $allProduct     = "`product` = 'all'";
        $queryVar       = "{$module}Query";
        $storyQuery     = $this->session->{$queryVar};
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
        if($excludeStatus) $storyQuery = $storyQuery . ' AND `status` NOT ' . helper::dbIN($excludeStatus);
        if($this->app->moduleName == 'productplan') $storyQuery .= " AND `status` NOT IN ('closed') AND `parent` >= 0 ";
        if(in_array($this->app->rawModule, array('build', 'release', 'projectrelease')) && $this->app->rawMethod == 'linkstory') $storyQuery .= " AND `parent` != '-1'";
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
                $storyQuery .= " AND `status` != 'draft'"; // Fix bug #990.
            }
            else
            {
                $storyQuery .= " AND `status` NOT IN ('draft', 'reviewing', 'changing', 'closed')";
            }
        }
        elseif(strpos($storyQuery, $allBranch) !== false)
        {
            $storyQuery = str_replace($allBranch, '1=1', $storyQuery);
        }
        elseif($branch !== 'all' and $branch !== '' and strpos($storyQuery, '`branch` =') === false and $queryProductID != 'all')
        {
            if($branch and strpos($storyQuery, '`branch` =') === false) $storyQuery .= " AND `branch` " . helper::dbIN((string)$branch);
        }
        $storyQuery = preg_replace("/`plan` +LIKE +'%([0-9]+)%'/i", "CONCAT(',', `plan`, ',') LIKE '%,$1,%'", $storyQuery);
        $storyQuery = preg_replace_callback("/AND `grade` (=|!=) '(\w+)(\d+)'/", function($matches){return "AND `grade` {$matches[1]} '" . $matches[3] . "' AND `type` = '" . $matches[2] . "'";}, $storyQuery);

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
    public function getBySQL(int|string $productID, string $sql, string $orderBy, ?object $pager = null, string $type = 'story'): array
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

        $showGrades = isset($this->config->{$type}->showGrades) ? $this->config->{$type}->showGrades : null;
        if($showGrades)
        {
            $pattern = "/{$type}(\d+)/";
            preg_match_all($pattern, $showGrades, $matches);
            $showGrades = isset($matches[1]) ? $matches[1] : null;
        }

        $tmpStories = $this->dao->select("DISTINCT t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->beginIF(strpos($sql, 'result') !== false)->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version')->fi()
            ->where($sql)
            ->beginIF($productID != 'all' && $productID != '' && $productID != 0)->andWhere('t1.`product`')->eq((int)$productID)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->beginIF($type != 'all')->andWhere('t1.type')->in($type)->fi()
            ->beginIF($showGrades)->andWhere('t1.grade')->in($showGrades)->fi()
            ->beginIF(empty($this->config->enableER))->andWhere('t1.type')->ne('epic')->fi()
            ->beginIF(empty($this->config->URAndSR) && $this->config->edition != 'ipd')->andWhere('t1.type')->ne('requirement')->fi()
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

            $story->parent = array();
            foreach(explode(',', trim((string)$story->path, ',')) as $parentID)
            {
                if(!$parentID) continue;
                if($parentID == $story->id) continue;
                $story->parent[] = (int)$parentID;
            }

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
     * @param  string     $storyType
     * @param  int        $storyID
     * @access public
     * @return array
     */
    public function getParentStoryPairs(int $productID, string|int $appendedStories = '', string $storyType = 'story', int $storyID = 0): array
    {
        if(common::isTutorialMode()) return array();

        if($storyType == 'story')
        {
            $maxGradeGroup = $this->getMaxGradeGroup();
            $lastGrade     = $this->dao->select('grade')->from(TABLE_STORYGRADE)->where('type')->eq($storyType)->andWhere('status')->eq('enable')->orderBy('grade_desc')->limit(1)->fetch('grade');
            $SRGradePairs  = $this->getGradePairs('story', 'all');
            $URGradePairs  = $this->getGradePairs('requirement', 'all');
            $requirements  = $this->dao->select('id, parent, grade, title, status')->from(TABLE_STORY)
                ->where('deleted')->eq('0')
                ->andWhere('product')->eq($productID)
                ->andWhere('type')->eq('requirement')
                ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
                ->beginIF($this->config->requirement->gradeRule == 'stepwise')->andWhere('grade')->eq($maxGradeGroup['requirement'])->fi()
                ->fetchAll('id');

            $parents = array();
            foreach($requirements as $requirement) $parents[$requirement->parent] = $requirement->parent;

            foreach($requirements as $id => $requirement)
            {
                if(isset($parents[$requirement->id])) unset($requirements[$id]);
                if(!isset($URGradePairs[$requirement->grade])) unset($requirements[$id]);
                if(in_array($requirement->status, array('reviewing', 'closed'))) unset($requirements[$id]);
            }

            $childIdList = $this->getAllChildId($storyID);
            $stories = $this->dao->select('id, grade, title')->from(TABLE_STORY)
                ->where('deleted')->eq('0')
                ->andWhere('product')->eq($productID)
                ->andWhere('type')->eq('story')
                ->andWhere('status')->notin('reviewing,closed')
                ->andWhere('twins')->eq('')
                ->andWhere('grade')->ne($lastGrade)
                ->andWhere('grade')->in(array_keys($SRGradePairs))
                ->beginIF($childIdList)->andWhere('id')->notIN($childIdList)->fi()
                ->beginIF(!empty($appendedStories))->orWhere('id')->in($appendedStories)->fi()
                ->fetchAll('id');

            $storyIdList = array_keys($stories);
            $casePairs   = $this->dao->select('story')->from(TABLE_CASE)->where('story')->in($storyIdList)->andWhere('story')->notin($appendedStories)->andWhere('story')->ne('0')->andWhere('deleted')->eq('0')->fetchPairs();
            $taskPairs   = $this->dao->select('story')->from(TABLE_TASK)->where('story')->in($storyIdList)->andWhere('story')->notin($appendedStories)->andWhere('story')->ne('0')->andWhere('deleted')->eq('0')->fetchPairs();
            foreach($stories as $story)
            {
                if(isset($casePairs[$story->id]) || isset($taskPairs[$story->id])) unset($stories[$story->id]);
            }

            $parents = $requirements + $stories;
            return $this->addGradeLabel($parents);
        }
        elseif($storyType == 'requirement')
        {
            return $this->getRequirementParents($productID, $appendedStories, $storyType, $storyID);
        }
        elseif($storyType == 'epic')
        {
            return $this->getEpicParents($productID, $appendedStories, $storyType, $storyID);
        }
    }

    /**
     * 获取用户需求的父需求键值对。
     * Get requirement parents.
     *
     * @param  int        $productID
     * @param  string|int $appendedStories
     * @param  string     $storyType
     * @param  int        $storyID
     * @access public
     * @return array
     */
    public function getRequirementParents(int $productID, string|int $appendedStories = '', string $storyType = 'requirement', int $storyID = 0): array
    {
        $maxGradeGroup = $this->getMaxGradeGroup();
        $lastGrade     = $this->dao->select('grade')->from(TABLE_STORYGRADE)->where('type')->eq($storyType)->andWhere('status')->eq('enable')->orderBy('grade_desc')->limit(1)->fetch('grade');
        $ERGradePairs  = $this->getGradePairs('epic', 'all');
        $URGradePairs  = $this->getGradePairs('requirement', 'all');

        $epics = $this->dao->select('id, parent, grade, title, status')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('type')->eq('epic')
            ->andWhere("CONCAT(',', vision, ',')")->like("%,{$this->config->vision},%")
            ->beginIF($this->config->epic->gradeRule == 'stepwise')->andWhere('grade')->eq($maxGradeGroup['epic'])->fi()
            ->fetchAll('id');

        $parents = array();
        foreach($epics as $epic) $parents[$epic->parent] = $epic->parent;

        foreach($epics as $id => $epic)
        {
            if(isset($parents[$epic->id])) unset($epics[$id]);
            if(!isset($ERGradePairs[$epic->grade])) unset($epics[$id]);
            if(in_array($epic->status, array('reviewing', 'closed'))) unset($epics[$id]);
        }

        $childIdList     = $this->getAllChildId($storyID);
        $allStoryParents = $this->dao->select('parent')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('type')->eq('story')
            ->andWhere('parent')->gt('0')
            ->fetchPairs();

        $requirements = $this->dao->select('id, parent, grade, title')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('type')->eq('requirement')
            ->andWhere("CONCAT(',', vision, ',')")->like("%,{$this->config->vision},%")
            ->andWhere('status')->notin('reviewing,closed')
            ->andWhere('grade')->in(array_keys($URGradePairs))
            ->andWhere('grade')->ne($lastGrade)
            ->andWhere('id')->notIN($allStoryParents)
            ->beginIF($childIdList)->andWhere('id')->notIN($childIdList)->fi()
            ->beginIF(!empty($appendedStories))->orWhere('id')->in($appendedStories)->fi()
            ->fetchAll('id');

        $parents = $epics + $requirements;
        if(!$this->config->enableER) $parents = $requirements;

        return $this->addGradeLabel($parents);
    }

    /**
     * 获取业务需求的父需求键值对。
     * Get epic parents.
     *
     * @param  int        $productID
     * @param  string|int $appendedStories
     * @param  string     $storyType
     * @param  int        $storyID
     * @access public
     * @return array
     */
    public function getEpicParents(int $productID, string|int $appendedStories = '', string $storyType = 'epic', int $storyID = 0): array
    {
        $lastGrade    = $this->dao->select('grade')->from(TABLE_STORYGRADE)->where('type')->eq($storyType)->andWhere('status')->eq('enable')->orderBy('grade_desc')->limit(1)->fetch('grade');
        $ERGradePairs = $this->getGradePairs('epic', 'all');
        $childIdList  = $this->getAllChildId($storyID);

        $allRequirementParents = $this->dao->select('parent')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('type')->eq('requirement')
            ->andWhere("CONCAT(',', vision, ',')")->like("%,{$this->config->vision},%")
            ->andWhere('parent')->gt('0')
            ->fetchPairs();

        $epics = $this->dao->select('id, parent, grade, title')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('type')->eq('epic')
            ->andWhere("CONCAT(',', vision, ',')")->like("%,{$this->config->vision},%")
            ->andWhere('status')->notin('reviewing,closed')
            ->andWhere('grade')->in(array_keys($ERGradePairs))
            ->andWhere('grade')->ne($lastGrade)
            ->andWhere('id')->notIN($allRequirementParents)
            ->beginIF($childIdList)->andWhere('id')->notIN($childIdList)->fi()
            ->beginIF(!empty($appendedStories))->orWhere('id')->in($appendedStories)->fi()
            ->fetchAll('id');

        return $this->addGradeLabel($epics);
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
    public function getUserStories(string $account, string $type = 'assignedTo', string $orderBy = 'id_desc', ?object $pager = null, string $storyType = 'story', bool $includeLibStories = true, string|int $shadow = 0, int $productID = 0): array
    {
        $sql = $this->dao->select("t1.*, t1.`path`, t1.`plan`, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t2.shadow as shadow")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id');
        if($type == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version');

        $stories = $sql->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq($storyType)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all')
            ->beginIF($type == 'assignedTo')->andWhere('t1.assignedTo')->eq($account)->fi()
            ->beginIF($type == 'reviewBy')->andWhere('t3.reviewer')->eq($account)->andWhere('t3.result')->eq('')->andWhere('t1.status')->in('reviewing')->fi()
            ->beginIF($type == 'openedBy')->andWhere('t1.openedBy')->eq($account)->fi()
            ->beginIF($type == 'reviewedBy')->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,$account,%")->fi()
            ->beginIF($type == 'closedBy')->andWhere('t1.closedBy')->eq($account)->fi()
            ->fi()
            ->beginIF(!$includeLibStories and $this->config->edition == 'max')->andWhere('t1.lib')->eq('0')->fi()
            ->beginIF($shadow !== 'all')->andWhere('t2.shadow')->eq((int)$shadow)->fi()
            ->beginIF($productID)->andWhere('t1.product')->eq((int)$productID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

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
        if(common::isTutorialMode())
        {
            $versions = array();
            $stories  = $this->loadModel('tutorial')->getStories();
            foreach($stories as $story) $versions[$story->id] = $story->version;
            return $versions;
        }
        return $this->dao->select('id, version')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchPairs('id', 'version');
    }

    /**
     * 获取零用例需求。
     * Get zero case.
     *
     * @param  int     $productID
     * @param  int     $projectID
     * @param  int     $executionID
     * @param  int     $branchID
     * @param  string  $orderBy
     * @param  object  $pager
     * @access public
     * @return array
     */
    public function getZeroCase(int $productID, int $projectID = 0, int $executionID = 0, int $branchID = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $casedStories = $this->dao->select('DISTINCT t1.story')
            ->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id = t2.case')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.story')->ne(0)
            ->beginIF($projectID && !$executionID)->andWhere('t2.project IS NOT NULL')->andWhere('t2.project')->eq($projectID)->fi()
            ->beginIF($executionID)->andWhere('t2.project IS NOT NULL')->andWhere('t2.project')->eq($executionID)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('story');

        if($projectID) $allStories = $this->getExecutionStories($projectID, $productID, $orderBy, 'withoutparent', '', 'story', array_keys($casedStories), $pager);
        if($executionID) $allStories = $this->getExecutionStories($executionID, $productID, $orderBy, 'withoutparent', '', 'story', array_keys($casedStories), $pager);
        if(!$projectID && !$executionID) $allStories = $this->getProductStories($productID, $branchID, '', 'all', 'story', $orderBy, false, array_keys($casedStories), $pager);
        if(!empty($allStories)) $allStories = $this->loadModel('story')->mergeReviewer($allStories);
        return $allStories;
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
            $prefix = $story->id . ':';
            if($type == 'ignoreID') $prefix = '';

            $property = '';
            if($type == 'short')
            {
                $property = '[p' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ', ' . $story->estimate . "{$this->config->hourUnit}]";
            }
            elseif($type == 'full')
            {
                $property = '(' . $this->lang->story->pri . ':' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ',' . $this->lang->story->estimate . ':' . $story->estimate . ')';
            }
            $storyPairs[$story->id] = $prefix . $story->title . ' ' . $property;
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
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerProduct(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('product as name, count(product) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
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
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerModule(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('module as name, count(module) as value, product, branch')
            ->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
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
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerSource(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('source as name, count(source) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('source')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $this->lang->{$storyType}->sourceList[''] = $this->lang->report->undefined;
        foreach($datas as $key => $data) $data->name = isset($this->lang->{$storyType}->sourceList[$key]) ? $this->lang->{$storyType}->sourceList[$key] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按计划进行统计的数据。
     * Get report data of stories per plan.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerPlan(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('plan as name, count(plan) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
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
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerStatus(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('status as name, count(status) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('status')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $status => $data) if(isset($this->lang->{$storyType}->statusList[$status])) $data->name = $this->lang->{$storyType}->statusList[$status];
        return $datas;
    }

    /**
     * 获取按所处阶段进行统计的数据。
     * Get report data of stories per stage.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerStage(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('stage as name, count(stage) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('stage')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $stage => $data) $data->name = $this->lang->{$storyType}->stageList[$stage] != '' ? $this->lang->{$storyType}->stageList[$stage] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按优先级进行统计的数据。
     * Get report data of stories per pri.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerPri(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('pri as name, count(pri) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('pri')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $pri => $data)
        {
            if(isset($this->lang->{$storyType}->priList[$pri]) && $this->lang->{$storyType}->priList[$pri] != '')
                $data->name = $this->lang->{$storyType}->priList[$pri];
            else
                $data->name = $this->lang->report->undefined;
        }
        return $datas;
    }

    /**
     * 获取按照预计工时进行统计的数据。
     * Get report data of stories per estimate.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerEstimate(string $storyType = 'story'): array
    {
        return $this->dao->select('estimate as name, count(estimate) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('estimate')->orderBy('value')
            ->fetchAll();
    }

    /**
     * 获取按由谁创建来进行统计的数据。
     * Get report data of stories per openedBy.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerOpenedBy(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('openedBy as name, count(openedBy) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
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
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerAssignedTo(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('assignedTo as name, count(assignedTo) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
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
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerClosedReason(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('closedReason as name, count(closedReason) as value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('closedReason')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $reason => $data) $data->name = $this->lang->{$storyType}->reasonList[$reason] != '' ? $this->lang->{$storyType}->reasonList[$reason] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * 获取按变更次数来进行统计的数据。
     * Get report data of stories per change.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerChange(string $storyType = 'story'): array
    {
        return $this->dao->select('(version-1) as name, COUNT(1) AS value')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('version')->orderBy('value')
            ->fetchAll();
    }

    /**
     * 获取按需求层级来进行统计的数据。
     * Get report data of stories per grade.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerGrade(string $storyType = 'story'): array
    {
        $datas = $this->dao->select('type, grade as name, COUNT(1) AS count')->from(TABLE_STORY)
            ->where($this->reportCondition($storyType))
            ->groupBy('type,name')
            ->fetchGroup('type', 'name');

        $gradeGroup = $this->getGradeGroup();
        $summary    = array();
        foreach($datas as $type => $gradeCount)
        {
            $gradePairs = zget($gradeGroup, $type, array());
            foreach($gradeCount as $gradeValue => $count)
            {
                $grade = zget($gradePairs, $gradeValue);

                $data = new stdclass();
                $data->name  = isset($grade->name) ? $grade->name : $this->lang->report->undefined;
                $data->value = $count->count;
                $summary[] = $data;
            }
        }
        return $summary;
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
        $this->app->loadConfig('execution');
        $cols = $this->config->execution->storyKanbanCols;

        $kanbanData = array();
        $storyGroup = array();
        foreach($stories as $story)
        {
            unset($story->deleted);
            $index = array_search($story->stage, $cols) + 1;

            $story->statusLabel = zget($this->lang->story->statusList, $story->status, '');
            $storyGroup[$index][] = $story;
        }

        foreach($cols as $index => $stage)
        {
            $index ++;
            $count = isset($storyGroup[$index]) ? count($storyGroup[$index]) : 0;

            $col = new stdclass();
            $col->name  = $index;
            $col->title = $this->lang->story->stageList[$stage] . ' (' . $count . ')';
            $col->key   = $stage;
            $kanbanData['cols'][] = $col;
        }

        $lane = new stdclass();
        $lane->name  = 1;
        $lane->title = '';

        $kanbanData['lanes'][]  = $lane;
        $kanbanData['items'][1] = $storyGroup;

        return $kanbanData;
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
        if((strtolower($actionType) == 'changed' or strtolower($actionType) == 'reviewed') && $story->type == 'story')
        {
            $teamMembers = $this->getTeamMembers($story->id, $actionType);
            if($teamMembers)
            {
                $ccList .= ',' . implode(',', $teamMembers);
                $ccList = ltrim($ccList, ',');
            }
        }

        if(in_array(strtolower($actionType), array('changed', 'opened', 'submitreview')))
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
     * @param  array  $storyTasks
     * @param  array  $storyCases
     * @access public
     * @return bool
     */
    public static function isClickable(object $story, string $action, array $storyTasks = [], array $storyCases = []): bool
    {
        global $app, $config;
        $action = strtolower($action);

        if($action == 'subdivide') $action = 'batchcreate';

        if($action == 'recall')             return $story->status == 'reviewing' || $story->status == 'changing';
        if($action == 'close')              return $story->status != 'closed';
        if($action == 'activate')           return $story->status == 'closed';
        if($action == 'assignto')           return $story->status != 'closed';
        if($action == 'processstorychange') return !empty($story->parentChanged);
        if($action == 'submitreview' && strpos('draft,changing', $story->status) === false)          return false;
        if($action == 'createtestcase' || $action == 'batchcreatetestcase') return $config->vision != 'lite' && $story->isParent == '0' && $story->type == 'story';

        /* Check isClickable when feedback convert to story. */
        if($action == 'create' && $app->rawModule == 'feedback') return ($config->global->flow == 'full' && strpos('closed|clarify|noreview', $story->status) === false);

        if($action == 'createtask')
        {
            if($app->tab == 'project' && !empty($_SESSION['project']))
            {
                global $dao;
                $project = $dao->findByID($_SESSION['project'])->from(TABLE_PROJECT)->fetch();
            }
            return ($app->tab == 'execution' || (!empty($project) && $project->multiple == '0')) && $story->status == 'active' && $story->type == 'story' && $story->isParent == '0';
        }

        $disabledFeatures = ",{$config->disabledFeatures},";
        if($action == 'importtolib')
        {
            if($config->vision == 'lite')                        return false;
            if(!in_array($config->edition, array('max', 'ipd'))) return false;
            if($app->tab != 'project')                           return false;
            if($story->type == 'requirement')                    return false;
            if(!common::hasPriv('story', 'importToLib'))         return false;
            if(strpos($disabledFeatures, ',assetlibStorylib,') !== false || strpos($disabledFeatures, ',assetlib,') !== false) return false;
            return true;
        }

        static $shadowProducts = array();
        static $taskGroups     = array();
        static $caseGroups     = array();
        if(empty($shadowProducts)) $shadowProducts = $app->dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->andWhere('shadow')->eq(1)->fetchPairs();
        if(empty($taskGroups)) $taskGroups = $storyTasks ?: $app->dao->select('story, id')->from(TABLE_TASK)->where('deleted')->eq('0')->andWhere('story')->ne(0)->fetchPairs();
        if(empty($caseGroups)) $caseGroups = $storyCases ?: $app->dao->select('story, id')->from(TABLE_CASE)->where('deleted')->eq('0')->andWhere('story')->ne(0)->fetchPairs();

        if(isset($story->parent) && $story->parent < 0 && strpos($config->story->list->actionsOperatedParentStory, ",$action,") === false) return false;

        if($action == 'batchcreate')
        {
            if($config->vision == 'or')
            {
                $myself = new self();
                static $getMaxGradeGroup;
                if(empty($getMaxGradeGroup)) $getMaxGradeGroup = $myself->getMaxGradeGroup();
                if(!empty($story->grade) && $story->type == 'requirement' && $story->grade >= $getMaxGradeGroup[$story->type]) return false;
            }

            if(!empty($story->twins))   return false;
            if(strpos('reviewing,closed', $story->status) !== false) return false;
            if($config->vision == 'lite' && ($story->status == 'active' && in_array($story->stage, array('wait', 'projected')))) return true;

            if(!empty($caseGroups[$story->id])) return false;
            if(!empty($taskGroups[$story->id])) return false;
            if(!isset($shadowProducts[$story->product]) && !in_array($story->stage, array('wait', 'planned', 'projected')) && $story->type == 'story' && $story->isParent == '0') return false;
        }

        $story->reviewer  = isset($story->reviewer)  ? $story->reviewer  : array();
        $story->notReview = isset($story->notReview) ? $story->notReview : array();
        $storyType        = $app->methodName == 'audit' ? zget($story, 'storyType', $story->type) : $story->type;
        $isSuperReviewer  = isset($config->{$storyType}) ? strpos(',' . trim(zget($config->{$storyType}, 'superReviewers', ''), ',') . ',', ',' . $app->user->account . ',') : false;

        if($action == 'change')       return (($isSuperReviewer !== false or count($story->reviewer) == 0 or count($story->notReview) == 0) and $story->status == 'active');
        if($action == 'review')       return (($isSuperReviewer !== false or in_array($app->user->account, $story->notReview)) and $story->status == 'reviewing');
        if($action == 'createbranch') return $story->type == 'story';

        return true;
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
        if(common::isTutorialMode()) return $stories;

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
     * 向业务需求和用户需求追加子需求。
     * Append children to story.
     *
     * @param  int          $productID
     * @param  array        $stories
     * @param  string       $storyType
     * @access public
     * @return array|object
     */
    public function appendChildren(int $productID, array $stories, string $storyType): array
    {
        if(common::isTutorialMode()) return $stories;

        /* 如果是OR界面的用户需求，则不需要追加子需求（研发需求）。 */
        /* If it is the OR interface for user requirements, no need to add sub requirements.*/
        if($storyType == 'requirement' && $this->config->vision == 'or') return $stories;

        $storyGrades       = array();
        $requirementGrades = array();

        $showGrades = isset($this->config->{$storyType}->showGrades);
        if($showGrades)
        {
            foreach(explode(',', $this->config->{$storyType}->showGrades) as $grade)
            {
                if(!$grade) continue;
                if(strpos($grade, 'requirement') !== false) $requirementGrades[] = str_replace('requirement', '', $grade);
                if(strpos($grade, 'story') !== false && $this->config->vision != 'or') $storyGrades[] = str_replace('story', '', $grade);
            }
        }

        $rootIdList = array();
        foreach($stories as $story) $rootIdList[$story->root] = $story->root;
        if(empty($rootIdList)) return $stories;

        $children = $this->dao->select('*,path,plan')->from(TABLE_STORY)
            ->where('root')->in($rootIdList)
            ->andWhere('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->beginIF($storyType == 'requirement')
            ->andWhere('type')->eq('story')
            ->beginIF($showGrades && isset($storyGrades))->andWhere('grade')->in($storyGrades)->fi()
            ->fi()
            ->beginIF($storyType == 'epic')
            ->andWhere('((type')->eq('requirement')
            ->beginIF($showGrades && isset($requirementGrades))->andWhere('grade')->in($requirementGrades)->fi()
            ->markRight(1)
            ->orWhere('(type')->eq('story')
            ->beginIF($showGrades && isset($storyGrades))->andWhere('grade')->in($storyGrades)->fi()
            ->markRight(1)
            ->markRight(1)
            ->fi()
            ->fetchAll('id');

        /* 获取关联对象。*/
        if($this->config->edition != 'open')
        {
            $requirements = array();
            $childStories = array();
            foreach($children as $child)
            {
                if($child->type == 'requirement') $requirements[$child->id] = $child->id;
                if($child->type == 'story') $childStories[$child->id] = $child->id;
            }
            $this->loadModel('custom');
            $requirementRelatedObjectList = $this->custom->getRelatedObjectList($requirements, 'requirement', 'byRelation', true);
            $storyRelatedObjectList       = $this->custom->getRelatedObjectList($childStories, 'story', 'byRelation', true);
            foreach($children as $child)
            {
                if($child->type == 'requirement') $child->relatedObject = zget($requirementRelatedObjectList, $child->id, 0);
                if($child->type == 'story')       $child->relatedObject = zget($storyRelatedObjectList, $child->id, 0);
            }
        }

        if($children)
        {
            $children = $this->storyTao->mergePlanTitleAndChildren($productID, $children, $storyType);
            $children = $this->mergeReviewer($children);
            return array_merge($stories, $children);
        }

        return $stories;
    }

    /**
     * Set report condition.
     *
     * @param  string $storyType
     * @access public
     * @return string
     */
    public function reportCondition(string $storyType): string
    {
        $queryCondition = $storyType . 'QueryCondition';
        $onlyCondition  = $storyType . 'OnlyCondition';
        if(isset($_SESSION[$queryCondition]))
        {
            if(!$this->session->{$onlyCondition})
            {
                preg_match_all('/[`"]' . trim(TABLE_STORY, '`') .'[`"] AS ([\w]+) /', $this->session->{$queryCondition}, $matches);
                $tableAlias = isset($matches[1][0]) ? $matches[1][0] . '.' : '';
                return 'id in (' . preg_replace('/SELECT .* FROM/', "SELECT $tableAlias" . "id FROM", $this->session->{$queryCondition}) . ')';
            }
            return $this->session->{$queryCondition};
        }
        return '1=1';
    }

    /**
     * Check force review for user.
     *
     * @param  string $storyType
     * @access public
     * @return bool
     */
    public function checkForceReview(string $storyType = 'story'): bool
    {
        $forceField       = $this->config->{$storyType}->needReview == 0 ? 'forceReview' : 'forceNotReview';
        $forceReviewRoles = !empty($this->config->{$storyType}->{$forceField . 'Roles'}) ? $this->config->{$storyType}->{$forceField . 'Roles'} : '';
        $forceReviewDepts = !empty($this->config->{$storyType}->{$forceField . 'Depts'}) ? $this->config->{$storyType}->{$forceField . 'Depts'} : '';

        $forceUsers = '';
        if(!empty($this->config->{$storyType}->{$forceField})) $forceUsers = $this->config->{$storyType}->{$forceField};

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

        return $this->config->{$storyType}->needReview == 0 ? strpos(",{$forceUsers},", ",{$this->app->user->account},") !== false : strpos(",{$forceUsers},", ",{$this->app->user->account},") === false;
    }

    /**
     * 根据传入的需求获取跟踪矩阵，返回看板格式数据。
     * Get track by stories, return kanban format.
     *
     * @param  array   $stories
     * @param  string  $storyType    epic|requirement|story
     * @access public
     * @return array
     */
    public function getTracksByStories(array $stories, string $storyType): array
    {
        if(empty($stories)) return array();

        $rootIdList = array_unique(array_column($stories, 'root'));
        $allStories = $this->dao->select('id,parent,color,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('root')->in($rootIdList)->andWhere('deleted')->eq(0)->orderBy('id')->fetchAll('id');
        $stories    = $this->storyTao->mergeChildrenForTrack($allStories, $stories, $storyType);
        $leafNodes  = $this->storyTao->getLeafNodes($stories, $storyType);

        $lanes  = $this->storyTao->buildTrackLanes($leafNodes, $storyType);
        $cols   = $this->storyTao->buildTrackCols($storyType);
        $items  = $this->storyTao->buildTrackItems($stories, $leafNodes, $storyType);

        return array('lanes' => $lanes, 'cols' => $cols, 'items' => $items, 'leafNodes' => $leafNodes);
    }

    /**
     * 获取矩阵需要合并的单元格。
     * Get need merge cells for track.
     *
     * @param  array   $tracks
     * @param  array   $showCols
     * @access public
     * @return array
     */
    public function getMergeTrackCells(array &$tracks, array $showCols): array
    {
        if(empty($tracks)) return array();

        $storyCols = array();
        foreach($showCols as $colType)
        {
            if(!in_array($colType, array('demand', 'epic', 'requirement', 'story'))) continue;
            foreach($tracks['cols'] as $col)
            {
                $colName = $col['name'];
                if($col['parent'] == -1) continue;
                if(str_contains($colName, $colType)) $storyCols[] = $col;
            }
        }

        /* 比较前一行的单元格，是否需要合并。
           如果是相同的需求，则合并单元格。
           如果没有需求，则根据前面的单元格的合并情况判断是否合并。
           如果前面的单元格没有合并，则不合并。
           但是第一列仅根据有无需求和需求是否相同，判断是否合并。
        */
        $mergeCells   = array();
        $preRowIdList = array();
        $preRowColID  = null;
        foreach($tracks['lanes'] as $lane)
        {
            $laneName  = $lane['name'];
            $preMerged = false;

            foreach($storyCols as $i => $col)
            {
                $colName = $col['name'];
                $story   = isset($tracks['items'][$laneName][$colName]) ? reset($tracks['items'][$laneName][$colName]) : 0;
                $storyID = $story ? $story->id : 0;

                if(isset($preRowIdList[$i])) $preRowColID = $preRowIdList[$i];
                $preRowIdList[$i] = $storyID;
                if($preRowColID === null || $preRowColID != $storyID)
                {
                    $preMerged = false;
                    continue;
                }
                if($i > 0 && !$preMerged && empty($storyID)) continue;

                if(!empty($tracks['items'][$laneName][$colName])) $tracks['items'][$laneName][$colName] = array();

                $mergeCells[$laneName][$colName] = true;
                $preMerged = true;
            }
        }

        return $mergeCells;
    }

    /**
     * 获取需求关联的需求列表。
     * Get story relation.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @access public
     * @return array
     */
    public function getStoryRelation(int $storyID, string $storyType = 'story'): array
    {
        $relations = $this->storyTao->getRelation($storyID, $storyType);

        if(empty($relations)) return array();

        return $this->dao->select('*')->from(TABLE_STORY)
            ->where('id')->in($relations)
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchGroup('type', 'id');
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
     * 需求关联需求。
     * Story link story.
     *
     * @param  int          $storyID
     * @param  array|object storyList  postedData
     * @access public
     * @return void
     */
    public function linkStories(int $storyID, array|object $storyList = array()): void
    {
        $story       = $this->getByID($storyID);
        $linkStories = $this->getByList($storyList);

        $this->loadModel('action');
        foreach($linkStories as $id => $linkStory)
        {
            $data = new stdclass();
            $data->AType    = $story->type;
            $data->BType    = $linkStory->type;
            $data->product  = $story->product;
            $data->relation = 'linkedto';
            $data->AID      = $storyID;
            $data->BID      = $id;
            $data->AVersion = $story->version;
            $data->BVersion = $linkStory->version;

            $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = $linkStory->type;
            $data->BType    = $story->type;
            $data->product  = $linkStory->product;
            $data->relation = 'linkedfrom';
            $data->AID      = $id;
            $data->BID      = $storyID;
            $data->AVersion = $linkStory->version;
            $data->BVersion = $story->version;

            $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $this->action->create('story', $id, 'linkrelatedstory', '', $storyID);
        }

        $this->action->create('story', $storyID, 'linkrelatedstory', '', implode(',', array_keys($linkStories)));
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
            ->where('AType')->in('story,requirement,epic')
            ->andWhere('BType')->in('story,requirement,epic')
            ->andWhere('relation')->in('linkedto,linkedfrom')
            ->andWhere('AID')->in($idList)
            ->andWhere('BID')->in($idList)
            ->exec();

        $this->loadModel('action')->create('story', $storyID, 'unlinkrelatedstory', '', $linkedStoryID);
        $this->loadModel('action')->create('story', $linkedStoryID, 'unlinkrelatedstory', '', $storyID);

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
            if(!empty($data->estimate[$key]) and !is_numeric($data->estimate[$key]))
            {
                dao::$errors['estimate'] = $this->lang->story->estimateMustBeNumber;
            }
            elseif(!empty($data->estimate[$key]) and $data->estimate[$key] < 0)
            {
                dao::$errors['estimate'] = $this->lang->story->estimateMustBePlus;
            }
            if(dao::isError()) return;

            $estimates[$account]['account']  = $account;
            $estimates[$account]['estimate'] = strpos($data->estimate[$key], '-') !== false ? (int)$data->estimate[$key] : (float)$data->estimate[$key];
        }


        $storyEstimate = new stdclass();
        $storyEstimate->story      = $storyID;
        $storyEstimate->round      = empty($lastRound) ? 1 : $lastRound + 1;
        $storyEstimate->estimate   = json_encode($estimates);
        $storyEstimate->average    = (float)$data->average;
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
        $storyLang = $this->lang->story;
        $SRCommon  = $this->lang->SRCommon;
        if($this->app->tab != 'product' && $this->app->rawMethod == 'report')
        {
            $replacement = $this->lang->common->story;
            $storyLang->report->charts['storiesPerProduct'] = str_replace($SRCommon, $replacement, $storyLang->report->charts['storiesPerProduct']);
            $storyLang->report->charts['storiesPerModule']  = str_replace($SRCommon, $replacement, $storyLang->report->charts['storiesPerModule']);
            $storyLang->report->charts['storiesPerSource']  = str_replace($SRCommon, $replacement, $storyLang->report->charts['storiesPerSource']);
        }
        else
        {
            if($type == 'story') return;
            $replacement = $type == 'requirement' ? $this->lang->URCommon : $this->lang->epic->common;

            $storyLang->create             = str_replace($SRCommon, $replacement, $storyLang->create);
            $storyLang->changeAction       = str_replace($SRCommon, $replacement, $storyLang->changeAction);
            $storyLang->changed            = str_replace($SRCommon, $replacement, $storyLang->changed);
            $storyLang->assignAction       = str_replace($SRCommon, $replacement, $storyLang->assignAction);
            $storyLang->reviewAction       = str_replace($SRCommon, $replacement, $storyLang->reviewAction);
            $storyLang->subdivideAction    = str_replace($SRCommon, $replacement, $storyLang->subdivideAction);
            $storyLang->closeAction        = str_replace($SRCommon, $replacement, $storyLang->closeAction);
            $storyLang->activateAction     = str_replace($SRCommon, $replacement, $storyLang->activateAction);
            $storyLang->deleteAction       = str_replace($SRCommon, $replacement, $storyLang->deleteAction);
            $storyLang->view               = str_replace($SRCommon, $replacement, $storyLang->view);
            $storyLang->linkStory          = str_replace($SRCommon, $replacement, $storyLang->linkStory);
            $storyLang->exportAction       = str_replace($SRCommon, $replacement, $storyLang->exportAction);
            $storyLang->zeroCase           = str_replace($SRCommon, $replacement, $storyLang->zeroCase);
            $storyLang->zeroTask           = str_replace($SRCommon, $replacement, $storyLang->zeroTask);
            $storyLang->copyTitle          = str_replace($SRCommon, $replacement, $storyLang->copyTitle);
            $storyLang->title              = str_replace($SRCommon, $replacement, $storyLang->title);
            $storyLang->spec               = str_replace($SRCommon, $replacement, $storyLang->spec);
            $storyLang->children           = str_replace($SRCommon, $replacement, $storyLang->children);
            $storyLang->linkStories        = str_replace($SRCommon, $replacement, $storyLang->linkStories);
            $storyLang->childStories       = str_replace($SRCommon, $replacement, $storyLang->childStories);
            $storyLang->duplicateStory     = str_replace($SRCommon, $replacement, $storyLang->duplicateStory);
            $storyLang->newStory           = str_replace($SRCommon, $replacement, $storyLang->newStory);
            $storyLang->copy               = str_replace($SRCommon, $replacement, $storyLang->copy);
            $storyLang->total              = str_replace($SRCommon, $replacement, $storyLang->total);
            $storyLang->released           = str_replace($SRCommon, $replacement, $storyLang->released);
            $storyLang->legendLifeTime     = str_replace($SRCommon, $replacement, $storyLang->legendLifeTime);
            $storyLang->legendLinkStories  = str_replace($SRCommon, $replacement, $storyLang->legendLinkStories);
            $storyLang->legendChildStories = str_replace($SRCommon, $replacement, $storyLang->legendChildStories);
            $storyLang->legendSpec         = str_replace($SRCommon, $replacement, $storyLang->legendSpec);
            $storyLang->unlinkStory        = str_replace($SRCommon, $replacement, $storyLang->unlinkStory);
            $storyLang->legendLifeTime     = str_replace($SRCommon, $replacement, $storyLang->legendLifeTime);
            $storyLang->legendLinkStories  = str_replace($SRCommon, $replacement, $storyLang->legendLinkStories);
            $storyLang->legendChildStories = str_replace($SRCommon, $replacement, $storyLang->legendChildStories);
            $storyLang->legendSpec         = str_replace($SRCommon, $replacement, $storyLang->legendSpec);

            $storyLang->notice->closed           = str_replace($SRCommon, $replacement, $storyLang->notice->closed);
            $storyLang->notice->reviewerNotEmpty = str_replace($SRCommon, $replacement, $storyLang->notice->reviewerNotEmpty);
            $storyLang->closedStory              = str_replace($SRCommon, $replacement, $storyLang->closedStory);

            $storyLang->report->charts['storiesPerProduct'] = str_replace($SRCommon, $replacement, $storyLang->report->charts['storiesPerProduct']);
            $storyLang->report->charts['storiesPerModule']  = str_replace($SRCommon, $replacement, $storyLang->report->charts['storiesPerModule']);
            $storyLang->report->charts['storiesPerSource']  = str_replace($SRCommon, $replacement, $storyLang->report->charts['storiesPerSource']);
        }
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
        $moduleName   = $this->app->rawModule;
        $reviewRule   = !empty($this->config->{$moduleName}->reviewRules) ? $this->config->{$moduleName}->reviewRules : 'allpass';
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
            $story->status     = $isChanged ? 'changing' : 'draft';
            $story->reviewedBy = '';
        }

        if($result == 'revert')
        {
            $story->status  = 'active';
            $story->version = $oldStory->version - 1;
            $story->title   = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($oldStory->id)->andWHere('version')->eq($oldStory->version - 1)->fetch('title');

            /* Delete versions that is after this version. */
            $twinsIdList = $oldStory->id . ($oldStory->twins ? ",{$oldStory->twins}" : '');
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
            $story->stage        = $reason == 'done' && $oldStory->type == 'story' ? 'released' : 'closed';
            $story->closedReason = $reason;
        }

        $story->finalResult = $result;

        return $story;
    }

    /**
     * Record story review actions.
     *
     * @param  object $oldStory
     * @param  object $story
     * @param  string $comment
     * @access public
     * @return int|string
     */
    public function recordReviewAction(object $oldStory, object $story, string $comment = ''): int
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
            $preStatus = $oldStory->status;
            $isChanged = !empty($story->changedBy) ? true : false;
            if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';

            if($story->finalResult == 'reject')  return $this->action->create('story', $story->id, 'ReviewRejected', '', "reject|$preStatus");
            if($story->finalResult == 'pass')    return $this->action->create('story', $story->id, 'ReviewPassed', '', "pass|$preStatus");
            if($story->finalResult == 'clarify') return $this->action->create('story', $story->id, 'ReviewClarified', '', "clarify|$preStatus");
            if($story->finalResult == 'revert')  return $this->action->create('story', $story->id, 'ReviewReverted', '', "revert|$preStatus");
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
        if(!empty(array_filter($twins)))
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
            if(!empty(array_filter($twins)))
            {
                foreach($twins as $twinID)
                {
                    $reviewData->story = $twinID;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                }
            }
        }

        if($oldStory->status == 'reviewing') $story = $this->updateStoryByReview($storyID, $oldStory, $story);
        if(strpos('draft,changing', $oldStory->status) !== false) $story->reviewedBy = '';
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
    public function getExportStories(string $orderBy = 'id_desc', string $storyType = 'story', ?object $postData = null): array
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
        $queryCondition = $storyType . 'QueryCondition';
        $onlyCondition  = $storyType . 'OnlyCondition';
        if($this->session->$onlyCondition)
        {
            $queryCondition = $postData->exportType == 'selected' ? ' `id` ' . helper::dbIN($selectedIDList) : str_replace('`story`', '`id`', $this->session->$queryCondition);
            $stories        = $this->dao->select('id,title,type,grade,linkStories,parent,mailto,reviewedBy')->from(TABLE_STORY)->where($queryCondition)->orderBy($orderBy)->fetchAll('id');
        }
        else
        {
            $orderBy  = " ORDER BY " . helper::wrapSqlAfterOrderBy($orderBy);
            $querySQL = $this->session->$queryCondition . $orderBy;
            if($postData->exportType == 'selected') $querySQL = "SELECT * FROM " . TABLE_STORY . "WHERE `id` IN({$selectedIDList})" . $orderBy;

            $stmt = $this->app->dbQuery($querySQL);
            while($row = $stmt->fetch()) $stories[$row->id] = $row;
        }

        if(empty($stories)) return $stories;

        $storyIdList  = array_keys($stories);
        $children     = array();
        $parentIdList = array();
        foreach($stories as $story)
        {
            if($story->parent > 0)
            {
                $parentIdList[] = $story->parent;
                if(isset($stories[$story->parent]))
                {
                    $children[$story->parent][$story->id] = $story;
                }
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
        $parents    = $this->dao->select('id, title')->from(TABLE_STORY)->where('id')->in($parentIdList)->fetchPairs('id', 'title');

        /* Get related objects title or names. */
        $relatedSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('`story`')->in($storyIdList)->orderBy('version desc')->fetchGroup('story');
        $relatedStories = $this->dao->select('*')->from(TABLE_STORY)->where('`id`')->in($storyIdList)->andWhere('deleted')->eq('0')->fetchPairs('id', 'title');

        $fileIdList = array();
        foreach($relatedSpecs as $relatedSpec)
        {
            if(!empty($relatedSpec[0]->files)) $fileIdList[] = $relatedSpec[0]->files;
        }
        $fileIdList   = array_unique($fileIdList);
        $relatedFiles = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq($storyType)->andWhere('objectID')->in($storyIdList)->andWhere('extra')->ne('editor')->fetchGroup('objectID');
        $filesInfo    = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('id')->in($fileIdList)->andWhere('extra')->ne('editor')->fetchAll('id');

        $gradeGroup = $this->getGradeGroup();
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

            $gradePairs      = zget($gradeGroup, $story->type, array());
            $grade           = zget($gradePairs, $story->grade, '');
            $story->grade    = $grade->name;
            $story->parentId = $story->parent;
            $story->parent   = zget($parents, $story->parent, '');
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
        $action     = 'closed,reviewrejected,closedbysystem,closedbyparent';
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
        if(strpos(',closed,reviewrejected,closedbyparent,', ",$lastAction,") !== false)
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

        /* Change status of launched and projected to active. */
        if(in_array($status, array('launched', 'projected'))) $status = 'active';

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
            if(strtolower($operate) == 'closed') $this->dao->update(TABLE_STORY)->set('assignedTo')->eq('closed')->where('id')->eq($twinID)->exec();
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
     * @param  array  $maxGradeGroup
     * @access public
     * @return array
     */
    public function buildActionButtonList(object $story, $type = 'browse', ?object $execution = null, $storyType = 'story', array $maxGradeGroup = array()): array
    {
        $params = "storyID=$story->id";

        if($type == 'browse') return $this->storyTao->buildBrowseActionBtnList($story, $params, $storyType, $execution, $maxGradeGroup);
        return array();
    }

    /**
     * 格式化列表中需求数据。
     * Format story for list.
     *
     * @param  object $story
     * @param  array  $options
     * @param  string $storyType sting|requirement
     * @param  array  $maxGradeGroup
     * @access public
     * @return object
     */
    public function formatStoryForList(object $story, array $options = array(), string $storyType = 'story', array $maxGradeGroup = array()): object
    {
        $story->actions  = $this->buildActionButtonList($story, 'browse', zget($options, 'execution', null), $storyType, $maxGradeGroup);
        $story->estimate = helper::formatHours($story->estimate) . $this->config->hourUnit;

        $story->taskCount = zget(zget($options, 'storyTasks', array()), $story->id, 0);
        $story->bugCount  = zget(zget($options, 'storyBugs',  array()), $story->id, 0);
        $story->caseCount = zget(zget($options, 'storyCases', array()), $story->id, 0);
        $story->module    = zget(zget($options, 'modules',    array()), $story->module, '');
        $story->branch    = zget(zget($options, 'branches',   array()), $story->branch, '');
        $story->plan      = isset($story->planTitle) ? $story->planTitle : zget(zget($options, 'plans', array()), $story->plan, '');
        $story->roadmap   = zget(zget($options, 'roadmaps', array()), $story->roadmap, 0);

        $story->sourceNote   = $story->source == 'researchreport' ? zget(zget($options, 'reports', array()), $story->sourceNote, '') : $story->sourceNote;
        $story->source       = zget($this->lang->{$story->type}->sourceList,   $story->source);
        $story->category     = zget($this->lang->{$story->type}->categoryList, $story->category);
        $story->closedReason = zget($this->lang->{$story->type}->reasonList,   $story->closedReason);
        $story->stage        = zget($this->lang->{$story->type}->stageList,    $story->stage);

        if($story->parent < 0) $story->parent = 0;
        if(empty($options['execution'])) $story->isParent = isset($story->childItem);

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

        /* Parent story has changed. */
        if(!empty($story->parentChanged))
        {
            $story->rawStatus = 'changing';
            $story->status    = $this->lang->story->parent . $this->lang->story->change;
        }
        else
        {
            $story->rawStatus = $story->status;
            $story->status    = zget($this->lang->{$story->type}->statusList, $story->status);
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

    /**
     * 需求下拉列表增加层级标签。
     * Add grade label to story options.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function addGradeLabel(array $stories): array
    {
        $gradeGroup = $this->getGradeGroup();

        $storyList = $this->getByList(array_keys($stories));

        $options = array();
        foreach($storyList as $story)
        {
            $gradePairs = zget($gradeGroup, $story->type, array());
            $storyTitle = is_string($stories[$story->id]) ? $stories[$story->id] : $story->title;
            if(isset($gradePairs[$story->grade]))
            {
                $options[] = array('text' => array('html' => "<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>{$gradePairs[$story->grade]->name}</span> {$storyTitle}"), 'hint' => $storyTitle, 'value' => $story->id, 'keys' => $storyTitle);
            }
            else
            {
                $options[] = array('text' => $stories[$story->id], 'value' => $story->id);
            }
        }

        return $options;
    }

    /**
     * Get story grade list.
     *
     * @param  string $type story|requirement|epic
     * @access public
     * @return array
     */
    public function getGradeList(string $type = 'story'): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getStoryGrade();

        return $this->dao->select('*')->from(TABLE_STORYGRADE)
            ->where('1=1')
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->orderBy('grade_asc')
            ->fetchAll();
    }

    /**
     * Get story grade group.
     *
     * @access public
     * @return array
     */
    public function getGradeGroup(): array
    {
        return $this->dao->select('type, grade, name')->from(TABLE_STORYGRADE)->orderBy('type,grade')->fetchGroup('type', 'grade');
    }

    /**
     * Get story grade pairs.
     *
     * @param  string $type story|requirement|epic
     * @param  string $status enable|disable
     * @param  array  $appendList
     * @access public
     * @return array
     */
    public function getGradePairs(string $type = 'story', string $status = 'enable', array $appendList = array()): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getGradePairs($type);

        return $this->dao->select("grade, name")->from(TABLE_STORYGRADE)
            ->where('type')->eq($type)
            ->beginIF($status == 'enable')->andWhere('status')->eq('enable')->fi()
            ->beginIF($appendList)
            ->orWhere('(grade')->in($appendList)
            ->andWhere('type')->eq($type)
            ->markRight(1)
            ->fi()
            ->orderBy('grade_asc')
            ->fetchPairs();
    }

    /**
     * 获取需求列表层级设置菜单。
     * Get grade menu in story list.
     *
     * @param  string $storyType
     * @param  object $project
     * @access public
     * @return array
     */
    public function getGradeMenu(string $storyType, ?object $project = null): array
    {
        $storyTypes      = isset($project->storyType) ? explode(',', $project->storyType) : array();
        $showEpic        = $this->config->enableER && ($storyType == 'epic' || in_array('epic', $storyTypes) || $storyType == 'all');
        $showRequirement = $showEpic || $storyType == 'requirement' || in_array('requirement', $storyTypes) || $storyType == 'all';

        $menu = array();
        if($showEpic)
        {
            $items = array();
            $gradePairs = $this->getGradePairs('epic', 'all');
            foreach($gradePairs as $grade => $name) $items[] = array('text' => $name, 'value' => "epic{$grade}");
            $menu[] = array('text' => $this->lang->preview . $this->lang->ERCommon, 'value' => 'epic', 'items' => $items);
        }

        if($showRequirement)
        {
            $items = array();
            $gradePairs = $this->getGradePairs('requirement', 'all');
            foreach($gradePairs as $grade => $name) $items[] = array('text' => $name, 'value' => "requirement{$grade}");
            $menu[] = array('text' => $this->lang->preview . $this->lang->URCommon, 'value' => 'requirement', 'items' => $items);
        }

        if($this->config->vision != 'or')
        {
            $items = array();
            $gradePairs = $this->getGradePairs('story', 'all');
            foreach($gradePairs as $grade => $name) $items[] = array('text' => $name, 'value' => "story{$grade}");
            $menu[] = array('text' => $this->lang->preview . $this->lang->SRCommon, 'value' => 'story', 'items' => $items);
        }

        return $menu;
    }

    /**
     * 获取需求层级下拉列表。
     * Get grade options.
     *
     * @param  object|bool $story
     * @param  string      $storyType
     * @param  array       $appendList
     * @access public
     * @return array
     */
    public function getGradeOptions(object|bool $story, string $storyType, array $appendList = array()): array
    {
        if(!$story || common::isTutorialMode()) return $this->getGradePairs($storyType, 'enable', $appendList);

        $gradeOptions = array();
        if($storyType != $story->type)
        {
            $gradeOptions = $this->getGradePairs($storyType, 'enable', $appendList);
        }
        else
        {
            $gradeOptions = $this->dao->select('grade, name')->from(TABLE_STORYGRADE)
                ->where('type')->eq($storyType)
                ->andWhere('grade')->gt($story->grade)
                ->andWhere('status')->eq('enable')
                ->beginIF($appendList)
                ->orWhere('(grade')->in($appendList)
                ->andWhere('type')->eq($storyType)
                ->markRight(1)
                ->fi()
                ->orderBy('grade_asc')
                ->fetchPairs();
        }

        return $gradeOptions;
    }

    /**
     * 按照类型分组，获取需求最大层级。
     * Get max grade group by story type.
     *
     * @param  string $status all|enable
     * @access public
     * @return array
     */
    public function getMaxGradeGroup($status = 'enable')
    {
        return $this->dao->select('type, max(grade) as maxGrade')->from(TABLE_STORYGRADE)
            ->where('1 = 1')
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->groupBy('type')
            ->fetchPairs();
    }

    /**
     * 获取需求列表默认展示的层级。
     * Get the default show grades.
     *
     * @param  array $gradeMenu
     * @access public
     * @return string
     */
    public function getDefaultShowGrades(array $gradeMenu): string
    {
        $showGrades = '';
        foreach($gradeMenu as $menu)
        {
            foreach($menu['items'] as $item)
            {
                $showGrades .= $item['value'] . ',';
            }
        }

        return $showGrades;
    }

    /**
     * 检查需求层级是否合法。
     * Check if the grade is valid.
     *
     * @param  object $story
     * @param  object $oldStory
     * @param  strign $mode     single|batch
     * @access public
     * @return bool
     */
    public function checkGrade(object $story, object $oldStory, string $mode = 'single')
    {
        $children = $this->getAllChildId($oldStory->id);
        $maxGrade = $this->dao->select('max(grade) as maxGrade')->from(TABLE_STORY)
             ->where('id')->in($children)
             ->andWhere('deleted')->eq('0')
             ->fetch('maxGrade');

        $systemMaxGrade = $this->dao->select('max(grade) as maxGrade')->from(TABLE_STORYGRADE)
            ->where('type')->eq($oldStory->type)
            ->andWhere('status')->eq('enable')
            ->fetch('maxGrade');

        $newMaxGrade = (int)$maxGrade + (int)$story->grade - (int)$oldStory->grade;
        if($newMaxGrade > $systemMaxGrade)
        {
            if($mode == 'batch') return false;
            dao::$errors['grade'] = sprintf($this->lang->story->gradeOverflow, $systemMaxGrade, $newMaxGrade);
        }

        return !dao::isError();
    }

    /**
     * 父需求层级变更时，同步子需求层级。
     * Sync child story grade when parent story grade changed.
     *
     * @param  object $oldStory
     * @param  object $story
     * @access public
     * @return array
     */
    public function syncGrade(object $oldStory, object $story)
    {
        if($oldStory->isParent != '1') return;

        $childIdList = $this->getAllChildId($oldStory->id, false);
        if($childIdList)
        {
            $this->loadModel('action');
            $children = $this->getByList($childIdList);
            foreach($children as $child)
            {
                if($child->type != $oldStory->type) continue;
                $grade = (int)$child->grade + (int)$story->grade - (int)$oldStory->grade;
                $this->dao->update(TABLE_STORY)->set('grade')->eq($grade)->where('id')->eq($child->id)->exec();
                $this->action->create('story', $child->id, 'syncGrade', '', $grade);
            }
        }
    }

    /**
     * 更新需求关联的代码提交记录。
     * Update the commit logs linked with the stories.
     *
     * @param  int       $storyID
     * @param  int       $repoID
     * @param  array     $revisions
     * @access protected
     * @return bool
     */
    public function updateLinkedCommits(int $storyID, int $repoID, array $revisions): bool
    {
        if(!$storyID || !$repoID || empty($revisions)) return true;
        $story = $this->dao->select('product')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(!$story) return true;
        foreach($revisions as $revision)
        {
            $data = new stdclass();
            $data->product  = $story->product;
            $data->AType    = 'story';
            $data->AID      = $storyID;
            $data->BType    = 'commit';
            $data->BID      = $revision;
            $data->relation = 'completedin';
            $data->extra    = $repoID;
            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = 'commit';
            $data->AID      = $revision;
            $data->BType    = 'story';
            $data->BID      = $storyID;
            $data->relation = 'completedfrom';
            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取需求关联的提交数据。
     * Get the commit data for the associated stories
     * @param  int       $storyID
     * @param  int       $repoID
     * @param  array     $revisions
     * @access protected
     * @return bool
     */
    public function getLinkedCommits(int $repoID, array $revisions): array
    {
        return $this->dao->select('t1.revision,t3.id AS id,t3.title AS title')
            ->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_RELATION)->alias('t2')->on("t2.relation='completedin' AND t2.BType='commit' AND t2.BID=t1.id")
            ->leftJoin(TABLE_STORY)->alias('t3')->on("t2.AType='story' AND t2.AID=t3.id")
            ->where('t1.revision')->in($revisions)
            ->andWhere('t1.repo')->eq($repoID)
            ->andWhere('t3.id')->notNULL()
            ->fetchGroup('revision', 'id');
    }
}
