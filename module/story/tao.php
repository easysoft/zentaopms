<?php
declare(strict_types=1);
/**
 * The model file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      wangyidong<wangyidong@cnezsoft.com>
 * @package     story
 * @link        https://www.zentao.net
 */
class storyTao extends storyModel
{
    /**
     * 获取需求的基础数据。
     * Fetch base info of a story.
     *
     * @param  int       $storyID
     * @access protected
     * @return object|false
     */
    protected function fetchBaseInfo(int $storyID): object|false
    {
        return $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
    }

    /**
     * 获取项目关联的用户需求。
     * Get project requirements.
     *
     * @param  int $productID
     * @param  int $projectID
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function getProjectRequirements(int $productID, int $projectID, ?object $pager = null): array
    {
        return $this->dao->select('t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on("t1.story = t2.id AND t2.type ='requirement'")
            ->where('t2.deleted')->eq('0')
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t1.product')->eq($productID)
            ->page($pager, 't2.id')
            ->fetchAll('id');
    }

    /**
     * 获取需求主动关联、被动关联的需求。
     * Get linked stories.
     *
     * @param  int     $storyID
     * @param  string  $storyType
     * @access public
     * @return array
     */
    public function getRelation(int $storyID, string $storyType): array
    {
        /* 主动关联。*/
        $linkedToList = $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('AType')->eq($storyType)
            ->andWhere('AID')->eq($storyID)
            ->andWhere('relation')->eq('linkedto')
            ->fetchPairs();

        /* 被动关联。*/
        $linkedFromList = $this->dao->select('AID')->from(TABLE_RELATION)
            ->where('BType')->eq($storyType)
            ->andWhere('BID')->eq($storyID)
            ->andWhere('relation')->eq('linkedto')
            ->fetchPairs();

        return $linkedToList + $linkedFromList;
    }

    /**
     * 批量获取产品所有状态对应的需求总数。
     * Get stories count of each status by product ID.
     *
     * @param  array     $productIDs
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function getStoriesCountByProductIDs(array $productIDs, string $storyType = 'requirement'): array
    {
        return $this->dao->select('product, status, count(status) AS count')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq($storyType)
            ->andWhere('product')->in($productIDs)
            ->groupBy('product, status')
            ->fetchGroup('product', 'status');
    }

    /**
     * 获取所有完成的需求数量。
     * Get the count of closed stories.
     *
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function getFinishClosedTotal(string $storyType = 'story'): array
    {
        return $this->dao->select('product, count(1) AS finish')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('closed')
            ->andWhere('type')->eq($storyType)
            ->andWhere('closedReason')->eq('done')
            ->groupBy('product')
            ->fetchPairs();
    }

    /**
     * 获取所有未完成的需求数量。
     * Get the count of unclosed stories.
     *
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function getUnClosedTotal(string $storyType = 'story'): array
    {
        return $this->dao->select('product, count(1) AS unclosed')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq($storyType)
            ->andWhere('status')->ne('closed')
            ->groupBy('product')
            ->fetchPairs();
    }

    /**
     * 获取产品评审人。
     * Get product reviewers.
     *
     * @param  int       $productID
     * @param  array     $storyReviewers
     * @access protected
     * @return array
     */
    protected function getProductReviewers(int $productID, array $storyReviewers = array()): array
    {
        $this->loadModel('user');
        $product   = $this->loadModel('product')->getByID($productID);
        $reviewers = $product->reviewer;

        if(!$reviewers and $product->acl != 'open') $reviewers = $this->user->getProductViewListUsers($product);
        return $this->user->getPairs('noclosed|nodeleted|noletter', $storyReviewers, 0, $reviewers);
    }

    /**
     * 构建研发需求的跟踪矩阵信息。
     * Build story track.
     *
     * @param  object    $story
     * @param  int       $projectID
     * @access protected
     * @return object
     */
    protected function buildStoryTrack(object $story, int $projectID = 0): object
    {
        if(count(get_object_vars($story)) == 0) return $story;

        /* 获取关联需求的用例、Bug、任务。 */
        $track = new stdclass();
        $track->parent = $story->parent;
        $track->title  = $story->title;
        $track->cases  = $this->loadModel('testcase')->getStoryCases($story->id);
        $track->bugs   = $this->loadModel('bug')->getStoryBugs($story->id);
        $track->tasks  = $this->loadModel('task')->getListByStory($story->id, 0, $projectID);
        if(!in_array($this->config->edition, array('max', 'ipd'))) return $track;

        /* 获取关联需求的设计、关联版本库提交。 */
        $track->designs   = $this->dao->select('id, name')->from(TABLE_DESIGN)->where('story')->eq($story->id)->andWhere('deleted')->eq('0')->fetchAll('id');
        $track->revisions = $this->dao->select('BID, t2.comment')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.BID = t2.id')
            ->where('t1.AType')->eq('design')
            ->andWhere('t1.BType')->eq('commit')
            ->andWhere('t1.AID')->in(array_keys($track->designs))
            ->fetchPairs();

        return $track;
    }

    /**
     * 根据产品 ID 列表和分支参数，构建查询条件。
     * Build products condition.
     *
     * @param  string|int|array $productIdList
     * @param  array|string|int $branch
     * @access protected
     * @return string
     */
    protected function buildProductsCondition(string|int|array $productIdList, array|string|int $branch = 'all'): string
    {
        /* 如果查询所有分支，直接用 idList 条件。 */
        if(empty($productIdList))  $productIdList = '0';
        if(is_int($productIdList)) $productIdList = (string)$productIdList;
        if(empty($productIdList) or $branch === 'all' or $branch === '') return '`product` ' . helper::dbIN($productIdList);

        /* 将产品分类为正常产品和多分支产品。 */
        $branchProducts = array();
        $normalProducts = array();
        $productList    = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchAll('id');
        foreach($productList as $product)
        {
            if($product->type != 'normal') $branchProducts[$product->id] = $product->id;
            if($product->type == 'normal') $normalProducts[$product->id] = $product->id;
        }

        /* 如果没有多分支产品，直接返回正常产品 ID 列表。*/
        if(empty($branchProducts)) return '`product` ' . helper::dbIN($normalProducts);

        /* 构造多分支产品和正常产品的复合条件。 */
        if(is_int($branch)) $branch = (string)$branch;
        $productQuery = "(`product` " . helper::dbIN($branchProducts) . " AND `branch` " . helper::dbIN($branch) . ')';
        if(!empty($normalProducts)) $productQuery .= ' OR `product` ' . helper::dbIN($normalProducts);
        return "({$productQuery}) ";
    }

    /**
     * 追加需求所属的计划标题和子需求。
     * Merge plan title and children.
     *
     * @param  int|array|string $productID
     * @param  array            $stories
     * @param  string           $type      story|requirement
     *
     * @access protected
     * @return array
     */
    protected function mergePlanTitleAndChildren(array|string|int $productID, array $stories, string $type = 'story'): array
    {
        if(empty($stories)) return array();
        $rawQuery = $this->dao->get();

        /* Get plans. */
        if(empty($productID)) $productID = '0';
        if(is_int($productID))$productID = (string)$productID;
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->Where('deleted')->eq(0)->beginIF($productID)->andWhere('product')->in($productID)->fetchPairs('id', 'title');

        $parents = $this->extractParents($stories);
        if($parents) $parents = $this->dao->select('id,title,status,version,type')->from(TABLE_STORY)->where('id')->in($parents)->andWhere('deleted')->eq(0)->fetchAll('id');

        $childItems = $this->getChildItems($stories);

        if($type != 'story')
        {
            $sameTypeChildren = $this->dao->select('distinct t1.parent')->from(TABLE_STORY)->alias('t1')
                 ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.parent = t2.id')
                 ->where('t1.parent')->in(array_keys($stories))
                 ->andWhere('t1.type = t2.type')
                 ->andWhere('t2.deleted')->eq(0)
                 ->fetchPairs();

            $otherTypeChildren = $this->dao->select('distinct t1.parent')->from(TABLE_STORY)->alias('t1')
                 ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.parent = t2.id')
                 ->where('t1.parent')->in(array_keys($stories))
                 ->andWhere('t1.type != t2.type')
                 ->andWhere('t2.deleted')->eq(0)
                 ->fetchPairs();
        }

        $demands = $this->dao->select('id,version')->from(TABLE_DEMAND)->where('deleted')->eq(0)->andWhere('status')->eq('active')->fetchPairs();
        foreach($stories as $story)
        {
            /* Judge parent story has changed. */
            if($story->parent > 0 && isset($parents[$story->parent]))
            {
                if($parents[$story->parent]->version > $story->parentVersion && $story->parentVersion > 0 && $parents[$story->parent]->status == 'active') $story->parentChanged = true;
            }

            /* Judge demand has changed. */
            if($story->demand && $story->parent <= 0 && isset($demands[$story->demand]) && $demands[$story->demand] > $story->demandVersion) $story->demandChanged = true;

            /* Judge parent story if has same type child or other type child. */
            if($story->type != 'story' && $story->isParent == '1')
            {
                if(isset($sameTypeChildren[$story->id]))  $story->hasSameTypeChild  = true;
                if(isset($otherTypeChildren[$story->id])) $story->hasOtherTypeChild = true;
            }

            /* Merge plan title. */
            $story->planTitle = '';
            $storyPlans       = explode(',', trim((string)$story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';

            if(isset($childItems[$story->id]))
            {
                $story->childItem      = $childItems[$story->id]['finished'] . ' / ' . $childItems[$story->id]['total'];
                $story->childItemTitle = $childItems[$story->id]['title'];
            }

            $story->originParent = $story->parent;

            $story->parent = array();
            foreach(explode(',', trim((string)$story->path, ',')) as $parentID)
            {
                if(!$parentID) continue;
                if($parentID == $story->id) continue;
                $story->parent[] = (int)$parentID;
            }
        }

        /* For save session query. */
        $this->dao->sqlobj->sql = $rawQuery;
        return $stories;
    }

    /**
     * 提取需求列表中的父需求 ID 列表。
     * Extract parents from stories.
     *
     * @param  array     $stories
     * @access protected
     * @return int[]
     */
    protected function extractParents(array $stories): array
    {
        $parent = array_map(function($story)
        {
            if($story->parent > '0') return $story->parent;
            return false;
        }, $stories);
        return array_values(array_unique(array_filter($parent)));
    }

    /**
     * 获取需求的子项，统计已完成/总数。
     * Get child items of stories, and count the finished/total.
     *
     * @param  array     $stories
     * @access protected
     * @return array
     */
    protected function getChildItems(array $stories): array
    {
        $childItems = array();

        $childStories = $this->dao->select('id, parent, status')->from(TABLE_STORY)
            ->where('parent')->in(array_keys($stories))
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('parent');

        foreach($childStories as $parentID => $childStory)
        {
            $childItems[$parentID]['total']    = count($childStory);
            $childItems[$parentID]['finished'] = count(array_filter($childStory, function($story){return $story->status == 'closed';}));
            $childItems[$parentID]['title']    = sprintf($this->lang->story->childStoryTitle, $childItems[$parentID]['total'], $childItems[$parentID]['finished']);
        }

        $childTasks = $this->dao->select('id, story, status')->from(TABLE_TASK)
            ->where('story')->in(array_keys($stories))
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('story');

        foreach($childTasks as $parentID => $childTask)
        {
            $childItems[$parentID]['total']    = count($childTask);
            $childItems[$parentID]['finished'] = count(array_filter($childTask, function($task){return in_array($task->status, array('done', 'closed', 'cancel'));}));
            $childItems[$parentID]['title']    = sprintf($this->lang->story->childTaskTitle, $childItems[$parentID]['total'], $childItems[$parentID]['finished']);
        }

        return $childItems;
    }

    /**
     * 通过搜索条件获取关联执行的需求。
     * Get execution stories by search.
     *
     * @param  int         $executionID
     * @param  int         $queryID
     * @param  int         $productID
     * @param  string      $orderBy
     * @param  string      $storyType
     * @param  string      $sqlCondition
     * @param  array       $excludeStories
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function getExecutionStoriesBySearch(int $executionID, int $queryID, int $productID, string $orderBy, string $storyType = 'story', string $sqlCondition = '', array $excludeStories = array(), ?object $pager = null): array
    {
        /* 获取查询条件。 */
        $rawModule = $this->app->rawModule;
        $this->loadModel('search')->setQuery($rawModule == 'projectstory' ? 'projectstory' : 'executionStory', $queryID);
        if(!$this->session->executionStoryQuery) $this->session->set('executionStoryQuery', ' 1 = 1');
        if($rawModule == 'projectstory') $this->session->set('executionStoryQuery', $this->session->projectstoryQuery);

        /* 处理查询条件。 */
        $storyQuery = $this->replaceAllProductQuery($this->session->executionStoryQuery);
        $storyQuery = $this->replaceRevertQuery($storyQuery, $productID);
        $storyQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $storyQuery);
        $storyQuery = preg_replace_callback("/t2.`grade` (=|!=) '(\w+)(\d+)'/", function($matches){return "t2.`grade` {$matches[1]} '" . $matches[3] . "' AND t2.`type` = '" . $matches[2] . "'";}, $storyQuery);
        if(strpos($storyQuery, 'result') !== false) $storyQuery = str_replace('t2.`result`', 't4.`result`', $storyQuery);

        $hasExecution = strpos($storyQuery, 't2.`execution`') !== false;
        if($hasExecution) $storyQuery = str_replace('t2.`execution`', 't1.`project`', $storyQuery);

        return $this->dao->select("distinct t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->beginIF(strpos($storyQuery, 'result') !== false)->leftJoin(TABLE_STORYREVIEW)->alias('t4')->on('t2.id = t4.story and t2.version = t4.version')->fi()
            ->where($storyQuery)
            ->beginIF(!$hasExecution)->andWhere('t1.project')->eq($executionID)->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->beginIF($storyType != 'all')->andWhere('t2.type')->in($storyType)->fi()
            ->beginIF($sqlCondition)->andWhere($sqlCondition)->fi()
            ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
            ->orderBy($orderBy)
            ->page($pager, 't2.id')
            ->fetchAll('id');
    }

    /**
     * 替换所有产品的查询条件。
     * Replace all product query.
     *
     * @param  string    $query
     * @access protected
     * @return string
     */
    protected function replaceAllProductQuery(string $query): string
    {
        $allProduct = "`product` = 'all'";
        if(strpos($query, $allProduct) !== false) $query = str_replace($allProduct, '1 = 1', $query);
        return $query;
    }

    /**
     * 如果有撤销变更的条件，用撤销变更的 ID 列表做替换
     * Replace revert query.
     *
     * @param  string    $storyQuery
     * @param  int       $productID
     * @access protected
     * @return string
     */
    protected function replaceRevertQuery(string $storyQuery, int $productID): string
    {
        if(strpos($storyQuery, 'result') === false) return $storyQuery;
        if(strpos($storyQuery, 'revert') === false) return $storyQuery;

        $reviews     = $this->getRevertStoryIdList($productID);
        $storyQuery  = str_replace("`result` = 'revert'", '1 = 1', $storyQuery);
        $storyQuery .= " AND `id` " . helper::dbIN($reviews);
        return $storyQuery;
    }

    /**
     * 获取撤销变更的 ID 列表。
     * Get Story changed Revert ObjectID.
     *
     * @param  int $productID
     * @access public
     * @return array
     */
    protected function getRevertStoryIdList(int $productID): array
    {
        if(empty($productID)) return array();
        return $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('product')->like("%,$productID,%")
            ->andWhere('action')->eq('reviewed')
            ->andWhere('objectType')->eq('story')
            ->andWhere('extra')->eq('Revert')
            ->groupBy('objectID')
            ->orderBy('objectID_desc')
            ->fetchPairs('objectID', 'objectID');
    }

    /**
     * 根据请求类型获取查询的模块，如果有模块就获取该模块下的需求，否则获取所有模块下的需求。
     * Get modules for query execution stories. If there is a module, get stories under that module, otherwise get the stories under all modules.
     *
     * @param  string    $type   bymodule|allstory|unclosed
     * @param  string    $param
     * @access protected
     * @return array
     */
    protected function getModules4ExecutionStories(string $type, string $param): array
    {
        $moduleID = (int)($type == 'bymodule'  && $param !== '' ? $param : $this->cookie->storyModuleParam);

        /* 如果模块为空，或者标签不是所有需求，未关闭的需求，某个模块下的需求时，获取所有模块下的需求。*/
        /* If the module is empty, or type is not allstory, unclosed, or bymodule, get the stories under all modules. */
        if(empty($moduleID) || strpos('allstory,unclosed,bymodule', $type) === false) return [];

        /* 从缓存中获取模块路径然后在 LIKE 查询中使用左匹配以利用索引提高性能。Find the path of the module from cache and use left match in like query to improve performance. */
        $path = $this->mao->select('path')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch('path');
        if(empty($path)) return array($moduleID);

        return $this->dao->select('id')->from(TABLE_MODULE)->where('deleted')->eq('0')->andWhere('path')->like("$path%")->fetchPairs();
    }

    /**
     * 获取执行下关联的需求。
     * Fetch execution stories.
     *
     * @param  dao         $storyDAO
     * @param  int         $productID
     * @param  string      $branch
     * @param  string      $type
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access protected
     * @return int[]
     */
    protected function fetchExecutionStories(dao $storyDAO, int $productID, string $type, string $branch, string $orderBy, ?object $pager = null): array
    {
        if(strpos($orderBy, 'version_') !== false) $orderBy = str_replace('version_', 't2.version_', $orderBy);

        $browseType     = $this->session->executionStoryBrowseType;
        $unclosedStatus = $this->getUnclosedStatusKeys();
        return $storyDAO->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($type == 'bybranch' && $branch !== '')->andWhere('t2.branch')->in("0,$branch")->fi()
            ->beginIF(!empty($browseType) && strpos('draft|reviewing|changing|closed', $browseType) !== false)->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'unclosed')->andWhere('t2.status')->in($unclosedStatus)->fi()
            ->orderBy($orderBy)
            ->page($pager, 't2.id')
            ->fetchAll('id');
    }

    /**
     * 获取项目下关联的需求。
     * Fetch project stories.
     *
     * @param  dao         $storyDAO
     * @param  int         $productID
     * @param  string      $type
     * @param  string      $branch
     * @param  array       $executionIdList
     * @param  string      $orderBy
     * @param  object|null $pager
     * @param  object|null $project
     * @access protected
     * @return int[]
     */
    protected function fetchProjectStories(dao $storyDAO, int $productID, string $type, string $branch, array $executionStoryIdList, string $orderBy, ?object $pager = null, ?object $project = null): array
    {
        if(strpos($orderBy, 'version_') !== false) $orderBy = str_replace('version_', 't2.version_', $orderBy);
        if(strpos($orderBy, 'id_')      !== false) $orderBy = str_replace('id_', 't2.id_', $orderBy);

        $unclosedStatus = $this->getUnclosedStatusKeys();
        $assignProduct  = false;
        if(!empty($productID) && !empty($project))
        {
            if(empty($project->charter)) $assignProduct = true;
            if(!empty($project->charter) && $project->hasProduct) $assignProduct = true;
        }

        return $storyDAO->beginIF($assignProduct)->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($type == 'bybranch' and $branch !== '')->andWhere('t2.branch')->in("0,$branch")->fi()
            ->beginIF(strpos('draft|reviewing|changing|closed', $type) !== false)->andWhere('t2.status')->eq($type)->fi()
            ->beginIF($type == 'unclosed')->andWhere('t2.status')->in($unclosedStatus)->fi()
            ->beginIF($type == 'linkedexecution')->andWhere('t2.id')->in($executionStoryIdList)->fi()
            ->beginIF($type == 'unlinkedexecution')->andWhere('t2.id')->notIn($executionStoryIdList)->fi()
            ->orderBy($orderBy)
            ->page($pager, 't2.id')
            ->fetchAll('id');
    }

    /**
     * 修正多分支产品需求的阶段，取最靠前的阶段。
     * Fix branch story stage.
     *
     * @param  array     $stories
     * @access protected
     * @return array
     */
    protected function fixBranchStoryStage(array $stories): array
    {
        if(empty($stories)) return array();
        $rawQuery = $this->dao->get();

        /* 获取阶段序列和关联的多分支产品需求。 */
        $branches = $this->dao->select('t1.story, t2.branch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.project = t2.project')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.story')->in(array_keys($stories))
            ->andWhere('t1.branch')->eq(BRANCH_MAIN)
            ->andWhere('t3.type')->ne('normal')
            ->fetchGroup('branch', 'story');

        /* Replace branch stage with the story stage. */
        foreach($branches as $branchID => $branchList)
        {
            $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in(array_keys($branchList))->andWhere('branch')->eq((int)$branchID)->fetchPairs('story', 'stage');
            foreach($stages as $storyID => $stage)
            {
                if($stories[$storyID]->branch != $branchID) continue;
                $stories[$storyID]->stage = $stage;
            }
        }

        $this->dao->sqlobj->sql = $rawQuery;
        return $stories;
    }

    /**
     * 获取需求非关闭状态的键值。
     * Get unclosed status keys.
     *
     * @access protected
     * @return array
     */
    protected function getUnclosedStatusKeys(): array
    {
        $moduleName = $this->app->rawModule;
        if(!in_array($moduleName, array('story', 'epic', 'requirement'))) $moduleName = 'story';

        $unclosedStatus = $this->lang->{$moduleName}->statusList;
        unset($unclosedStatus['closed']);
        return array_keys($unclosedStatus);
    }

    /**
     * 获取需求 ID 列表，这些需求是关联在项目下的执行。
     * Get id list of executions by product.
     *
     * @param  string    $type
     * @param  int|array $projectID
     * @access protected
     * @return array
     */
    protected function getIdListOfExecutionsByProjectID(string $type, int|array $projectID): array
    {
        if($type != 'linkedexecution' && $type != 'unlinkedexecution') return array();

        $executions = $this->loadModel('execution')->getPairs($projectID);
        if(empty($executions)) return array();

        return $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->in(array_keys($executions))->fetchPairs();
    }

    /**
     * 通过产品ID列表批量获取产品关联的需求列表。
     * Get story list by product ID list.
     *
     * @param  int[]     $productIdList
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function getStoriesByProductIdList(array $productIdList, string $storyType = '', bool $hasParent = true): array
    {
        return $this->dao->select('id, product, parent')
            ->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->beginIF($storyType)->andWhere('type')->eq($storyType)->fi()
            ->beginIF(!$hasParent)->andWhere("isParent")->eq('0')->fi()
            ->andWhere('product')->in($productIdList)
            ->fetchAll();
    }

    /**
     * 将需求数据插入到需求表。
     * Do create story data.
     *
     * @param  object    $story
     * @access protected
     * @return int|false
     */
    protected function doCreateStory(object $story): int|false
    {
        $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify,reviewer,region,lane,branches,plans,modules,uploadImage')
            ->autoCheck()
            ->checkIF(!empty($story->notifyEmail), 'notifyEmail', 'email')
            ->batchCheck($this->config->{$story->type}->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 创建需求描述和验收标准。
     * Do create story spec.
     *
     * @param  int       $storyID
     * @param  object    $story    must has title,spec,verify,version items.
     * @param  array     $files    e.g. array(fileID => fileName)
     * @access protected
     * @return void
     */
    protected function doCreateSpec(int $storyID, object $story, array|string $files = array()): void
    {
        if(empty($storyID)) return;

        $spec          = new stdclass();
        $spec->story   = $storyID;
        $spec->version = zget($story, 'version', 1);
        $spec->title   = $story->title;
        $spec->spec    = $story->spec;
        $spec->verify  = $story->verify;
        $spec->files   = is_string($files) ? $files : implode(',', array_keys($files));

        if(isset($story->uploadImage)) $spec = $this->doSaveUploadImage($storyID, $story->uploadImage, $spec);

        $this->dao->insert(TABLE_STORYSPEC)->data($spec)->exec();
    }

    /**
     * 保存上传图片作为需求内容。
     * Do save upload image.
     *
     * @param  int       $storyID
     * @param  string    $fileName
     * @param  object    $spec
     * @access protected
     * @return object
     */
    protected function doSaveUploadImage(int $storyID, string $fileName, object $spec): object
    {
        $storyImageFiles = $this->session->storyImagesFile;
        if(empty($storyImageFiles)) return $spec;
        if(empty($storyImageFiles[$fileName])) return $spec;

        $file     = $storyImageFiles[$fileName];
        $realPath = $file['realpath'];
        unset($file['realpath']);
        if(!file_exists($realPath)) return $spec;

        $this->loadModel('file');
        if(!is_dir($this->file->savePath)) mkdir($this->file->savePath, 0777, true);
        if($realPath && rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
        {
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = helper::now();
            $file['objectType'] = 'story';
            $file['objectID']   = $storyID;

            $isImage = in_array($file['extension'], $this->config->file->imageExtensions);
            if($isImage) $file['extra'] = 'editor';

            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileID = $this->dao->lastInsertID();

            if($isImage)  $spec->spec  .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
            if(!$isImage) $spec->files .= ',' . $fileID;
        }

        return $spec;
    }

    /**
     * 创建需求的时候，关联创建评审人列表。
     * Do create reviewer when create story.
     *
     * @param  int       $storyID
     * @param  array     $reviewers
     * @param  int       $storyVersion
     * @access protected
     * @return void
     */
    protected function doCreateReviewer(int $storyID, array $reviewers, int $storyVersion = 1): void
    {
        if(empty($storyID) or empty($reviewers)) return;

        foreach($reviewers as $reviewer)
        {
            if(empty($reviewer)) continue;

            $reviewData = new stdclass();
            $reviewData->story    = $storyID;
            $reviewData->version  = $storyVersion;
            $reviewData->reviewer = $reviewer;
            $reviewData->result   = '';
            $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
        }
    }

    /**
     * 更新需求描述。
     * Do update story spec.
     *
     * @param  int       $storyID
     * @param  object    $story
     * @param  object    $oldStory
     * @access protected
     * @return void
     */
    protected function doUpdateSpec(int $storyID, object $story, object $oldStory): void
    {
        if(empty($oldStory)) return;
        if($story->spec == $oldStory->spec and $story->verify == $oldStory->verify and $story->title == $oldStory->title and empty($story->deleteFiles) and empty($story->addedFiles)) return;

        $data = new stdclass();
        $data->title  = $story->title;
        $data->spec   = $story->spec;
        $data->verify = $story->verify;
        $data->files  = $story->files;
        $this->dao->update(TABLE_STORYSPEC)->data($data)->where('story')->eq((int)$storyID)->andWhere('version')->eq($oldStory->version)->exec();

        /* Sync twins. */
        if(!empty($oldStory->twins))
        {
            foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
            {
                $this->dao->update(TABLE_STORYSPEC)->data($data)->where('story')->eq((int)$twinID)->andWhere('version')->eq($oldStory->version)->exec();
            }
        }
    }

    /**
     * Do string when change parent.
     *
     * @param  int       $storyID
     * @param  object    $story
     * @param  object    $oldStory
     * @access protected
     * @return void
     */
    protected function doChangeParent(int $storyID, object $story, object $oldStory)
    {
        $this->loadModel('action');
        if($oldStory->parent > 0)
        {
            $oldParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($oldStory->parent)->fetch();
            $oldChildren    = $this->dao->select('id')->from(TABLE_STORY)->where('parent')->eq($oldStory->parent)->andWhere('deleted')->eq(0)->fetchPairs('id', 'id');
            if(empty($oldChildren))
            {
                $this->dao->update(TABLE_STORY)
                     ->set('isParent')->eq('0')
                     ->beginIF($oldParentStory->type == 'story')->set('stage')->eq('wait')->fi()
                     ->where('id')->eq($oldStory->parent)
                     ->exec();
            }
            $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($oldStory->parent)->fetch();

            $this->action->create('story', $storyID, 'unlinkParentStory', '', $oldStory->parent, '', false);
            $actionID = $this->action->create('story', $oldStory->parent, 'unLinkChildrenStory', '', $storyID, '', false);
            $changes  = common::createChanges($oldParentStory, $newParentStory);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            $this->updateLane($oldParentStory->id, $oldParentStory->type);

            if($this->config->edition != 'open')
            {
                $this->dao->delete()->from(TABLE_RELATION)
                    ->where('relation')->eq('subdivideinto')
                    ->andWhere('AID')->eq($oldStory->parent)
                    ->andWhere('AType')->eq($oldParentStory->type)
                    ->andWhere('BID')->eq($oldStory->id)
                    ->andWhere('BType')->eq($oldStory->type)
                    ->exec();
            }
        }

        if($story->parent > 0)
        {
            $parentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch();
            $newRoot     = $parentStory->root;
            $story->path = rtrim($parentStory->path, ',') . ',' . $storyID . ',';

            $this->dao->update(TABLE_STORY)->set('parentVersion')->eq($parentStory->version)->where('id')->eq($storyID)->exec();
            $this->dao->update(TABLE_STORY)
                ->set('isParent')->eq('1')
                ->set('lastEditedBy')->eq($this->app->user->account)
                ->set('lastEditedDate')->eq(helper::now())
                ->where('id')->eq($story->parent)
                ->exec();
            $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch();

            $this->action->create('story', $storyID, 'linkParentStory', '', $story->parent, '', false);
            $actionID = $this->action->create('story', $story->parent, 'linkChildStory', '', $storyID, '', false);
            $changes  = common::createChanges($parentStory, $newParentStory);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            $this->updateLane($parentStory->id, $parentStory->type);

            if($this->config->edition != 'open')
            {
                $relation = new stdClass();
                $relation->AID      = $story->parent;
                $relation->AType    = $parentStory->type;
                $relation->relation = 'subdivideinto';
                $relation->BID      = $oldStory->id;
                $relation->BType    = $oldStory->type;
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }
        else
        {
            $newRoot     = $storyID;
            $story->path = ",{$storyID},";
        }

        $childIdList = $this->getAllChildId($storyID, false);
        $children    = $this->getByList($childIdList);
        foreach($children as $child)
        {
            $newChildPath = str_replace($oldStory->path, $story->path, $child->path);
            $this->dao->update(TABLE_STORY)->set('path')->eq($newChildPath)->where('id')->eq($child->id)->exec();
        }

        if($childIdList) $this->dao->update(TABLE_STORY)->set('root')->eq($newRoot)->where('id')->in($childIdList)->exec();
        $this->dao->update(TABLE_STORY)->set('root')->eq($newRoot)->set('path')->eq($story->path)->where('id')->eq($storyID)->exec();
    }

    /**
     * Do update link stories.
     *
     * @param  int       $storyID
     * @param  object    $story
     * @param  object    $oldStory
     * @access protected
     * @return void
     */
    protected function doUpdateLinkStories(int $storyID, object $story, object $oldStory)
    {
        if($oldStory->type == 'epic') return;
        $linkStoryField = $oldStory->type == 'story' ? 'linkStories' : 'linkRequirements';
        $linkStories    = explode(',', $story->{$linkStoryField});
        $oldLinkStories = explode(',', $oldStory->{$linkStoryField});
        $addStories     = array_diff($linkStories, $oldLinkStories);
        $removeStories  = array_diff($oldLinkStories, $linkStories);
        $changeStories  = array_merge($addStories, $removeStories);
        $changeStories  = $this->dao->select("id,$linkStoryField")->from(TABLE_STORY)->where('id')->in(array_filter($changeStories))->fetchPairs();
        foreach($changeStories as $changeStoryID => $changeStory)
        {
            if(in_array($changeStoryID, $addStories))
            {
                $stories = empty($changeStory) ? $storyID : $changeStory . ',' . $storyID;
                $this->dao->update(TABLE_STORY)->set($linkStoryField)->eq((string)$stories)->where('id')->eq((int)$changeStoryID)->exec();
            }

            if(in_array($changeStoryID, $removeStories))
            {
                $linkedStories = str_replace(",$storyID,", ',', ",$changeStory,");
                $linkedStories = trim($linkedStories, ',');
                $this->dao->update(TABLE_STORY)->set($linkStoryField)->eq((string)$linkedStories)->where('id')->eq((int)$changeStoryID)->exec();
            }
        }
    }

    /**
     * 在创建需求的时候，将需求关联到项目或执行。
     * Link to execution for create story.
     *
     * @param  int       $executionID
     * @param  int       $storyID
     * @param  object    $story
     * @param  string    $extra
     * @access protected
     * @return void
     */
    protected function linkToExecutionForCreate(int $executionID, int $storyID, object $story, string $extra = ''): void
    {
        if(empty($executionID) || empty($storyID)) return;

        $this->linkStory($executionID, $story->product, $storyID);
        if(in_array($this->config->systemMode, array('ALM', 'PLM')) && $this->session->project && $executionID != $this->session->project) $this->linkStory((int)$this->session->project, $story->product, $storyID);

        $this->loadModel('action');
        $extra  = $this->parseExtra($extra);
        $object = $this->dao->findById($executionID)->from(TABLE_PROJECT)->fetch();
        if($object->type == 'project')
        {
            $this->action->create('story', $storyID, 'linked2project', '', $object->id);
            return;
        }
        if($object->type == 'kanban')
        {
            $laneID = zget($story, 'lane', 0);
            if(empty($laneID)) $laneID = zget($extra, 'laneID', 0);

            $columnID = $this->loadModel('kanban')->getColumnIDByLaneID((int)$laneID, 'backlog');
            if(empty($columnID)) $columnID = zget($extra, 'columnID', 0);

            if(!empty($laneID) && !empty($columnID)) $this->kanban->addKanbanCell($executionID, (int)$laneID, (int)$columnID, 'story', (string)$storyID);
            if(empty($laneID)  || empty($columnID))  $this->kanban->updateLane($executionID, 'story');
        }

        $actionType = $object->type == 'kanban' ? 'linked2kanban' : 'linked2execution';
        $this->action->create('story', $storyID, 'linked2project', '', (string)$object->project);
        if($object->multiple) $this->action->create('story', $storyID, $actionType, '', (string)$executionID);
    }

    /**
     * 当Bug转需求后，关闭Bug。
     * Close bug when to story.
     *
     * @param  int       $bugID
     * @param  int       $storyID
     * @access protected
     * @return void
     */
    protected function closeBugWhenToStory(int $bugID, int $storyID): void
    {
        if(empty($bugID) or empty($storyID)) return;

        $oldBug = $this->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
        if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, 'closed', $oldBug->status, $bugID);

        $now = helper::now();
        $bug = new stdclass();
        $bug->toStory      = $storyID;
        $bug->status       = 'closed';
        $bug->resolution   = 'tostory';
        $bug->resolvedBy   = $this->app->user->account;
        $bug->resolvedDate = $now;
        $bug->closedBy     = $this->app->user->account;
        $bug->closedDate   = $now;
        $bug->assignedTo   = 'closed';
        $bug->assignedDate = $now;
        $bug->confirmed    = 1;
        $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();

        $this->loadModel('action')->create('bug', $bugID, 'ToStory', '', $storyID);
        $actionID = $this->action->create('bug', $bugID, 'Closed');
        $changes  = common::createChanges($oldBug, $bug, 'bug');
        $this->action->logHistory($actionID, $changes);

        if($this->config->edition != 'open')
        {
            $relation = new stdClass();
            $relation->AID      = $bugID;
            $relation->AType    = 'bug';
            $relation->relation = 'transferredto';
            $relation->BID      = $storyID;
            $relation->BType    = 'story';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }

        /* add files to story from bug. */
        $files = $this->dao->select('*')->from(TABLE_FILE)->where('objectType')->eq('bug')->andWhere('objectID')->eq($bugID)->fetchAll();
        if(empty($files)) return;
        foreach($files as $file)
        {
            $file->objectType = 'story';
            $file->objectID   = $storyID;
            unset($file->id);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
        }
    }

    /**
     * 当待办转需求后，将待办改为完成。
     * Finish todo when to story.
     *
     * @param  int       $todoID
     * @param  int       $storyID
     * @access protected
     * @return void
     */
    protected function finishTodoWhenToStory(int $todoID, int $storyID): void
    {
        if(empty($todoID) or empty($storyID)) return;

        $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'finished', '', "STORY:$storyID");
        if($this->config->edition == 'open')return;

        $todo = $this->dao->select('type, objectID')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
        if($todo->type == 'feedback' && $todo->objectID) $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, 'done', '', $todoID);
    }

    /**
     * 更新孪生需求字段。
     * Update twins.
     *
     * @param  array     $storyIdList
     * @param  int       $mainStoryID
     * @access protected
     * @return void
     */
    protected function updateTwins(array $storyIdList, int $mainStoryID): void
    {
        if(count($storyIdList) <= 1) return;

        foreach($storyIdList as $storyID)
        {
            $twinsIdList = $storyIdList;
            unset($twinsIdList[$storyID]);
            $this->dao->update(TABLE_STORY)->set('twins')->eq(',' . implode(',', $twinsIdList) . ',')->where('id')->eq($storyID)->exec();
        }

        $storyFiles = $this->dao->select('files')->from(TABLE_STORYSPEC)->where('story')->eq($mainStoryID)->fetch('files');
        $this->dao->update(TABLE_STORYSPEC)->set('files')->eq($storyFiles)->where('story')->in($storyIdList)->exec();
    }

    /**
     * 解析extra参数。
     * Parse extra param.
     *
     * @param  string    $extra
     * @access protected
     * @return array
     */
    protected function parseExtra(string $extra): array
    {
        if(empty($extra)) return array();

        /* Whether there is a object to transfer story, for example feedback. */
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);
        return $output;
    }

    /**
     * Check whether a story can be subdivided.
     *
     * @param  object    $story
     * @param  bool      $isShadowProduct
     * @access protected
     * @return bool
     */
    protected function checkCanSubdivide($story, $isShadowProduct): bool
    {
        if($this->config->vision == 'lite') return true;
        if($story->type != 'story') return true;

        if(in_array($story->status, array('reviewing', 'closed'))) return false;
        if($story->isParent == '1') return true;
        if(!$isShadowProduct && !in_array($story->stage, array('wait', 'planned', 'projected'))) return false;
        if($isShadowProduct && $story->stage != 'projected') return false;

        return true;
    }

    /**
     * Check whether a story can be split.
     *
     * @param  object    $story
     * @access protected
     * @return bool
     */
    protected function checkCanSplit($story): bool
    {
        $sameTypeChild = $this->dao->select('id')->from(TABLE_STORY)
             ->where('parent')->eq($story->id)
             ->andWhere('type')->eq($story->type)
             ->andWhere('deleted')->eq(0)
             ->fetch('id');

        if($sameTypeChild) return false;

        return true;
    }

    /**
     * 获取需求关联的分支和项目。
     * Get linked branches and projects.
     *
     * @param  int       $storyID
     * @access protected
     * @return array
     */
    protected function getLinkedBranchesAndProjects(int $storyID): array
    {
        $projects = $this->dao->select('t2.id,t2.model,t2.type,t3.branch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.project = t3.project AND t1.product=t3.product')
            ->where('t1.story')->eq($storyID)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();

        $linkedBranches = array();
        $linkedProjects = array();
        foreach($projects as $project)
        {
            $linkedBranches[$project->branch] = $project->branch;
            if(!isset($linkedProjects[$project->id]))
            {
                $project->kanban = ($project->model == 'kanban' || $project->type == 'kanban');
                $project->branches[$project->branch] = $project->branch;

                $linkedProjects[$project->id] = $project;
            }
            else
            {
                $linkedProjects[$project->id]->branches[$project->branch] = $project->branch;
            }
        }

        return array($linkedBranches, $linkedProjects);
    }

    /**
     * 将阶段设置为 planned。
     * Set stage to planned.
     *
     * @param  int       $storyID
     * @param  array     $stages
     * @param  array     $oldStages
     * @access protected
     * @return bool
     */
    protected function setStageToPlanned(int $storyID, array $stages = array(), array $oldStages = array()): bool
    {
        $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(empty($story)) return false;

        /* 当需求有子需求时，需要通过子需求推算当前需求的阶段。*/
        if(!empty($story->isParent))
        {
            $children = $this->dao->select('*')->from(TABLE_STORY)
                ->where('parent')->eq($storyID)
                ->fetch();

            $this->computeParentStage($children);
            return true;
        }

        if(empty($story->plan))
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('wait')->where('id')->eq($storyID)->exec();
            return true;
        }

        $this->dao->update(TABLE_STORY)->set('stage')->eq('planned')->where('id')->eq($storyID)->exec();
        foreach($stages as $branchID => $stage)
        {
            $branchID = (int)$branchID;
            $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branchID)->set('stage')->eq('planned')->exec();
            if(isset($oldStages[$branchID]) && !empty($oldStages[$branchID]->stagedBy)) $this->dao->replace(TABLE_STORYSTAGE)->data($oldStages[$branchID])->exec();
        }
        return true;
    }

    /**
     * 将阶段设置为 closed。
     * Set stage to closed.
     *
     * @param  int       $storyID
     * @param  array     $linkedBranches
     * @param  array     $linkedProjects
     * @access protected
     * @return bool
     */
    protected function setStageToClosed(int $storyID, array $linkedBranches = array(), array $linkedProjects = array()): bool
    {
        $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(empty($story)) return false;

        $this->dao->update(TABLE_STORY)->set('stage')->eq('closed')->where('id')->eq($storyID)->exec();
        foreach($linkedBranches as $branchID)
        {
            if(!empty($branchID)) $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq((int)$branchID)->set('stage')->eq('closed')->exec();
        }

        if($story->stage != 'closed') $this->updateLinkedLane($storyID, $linkedProjects);
        $this->computeParentStage($story);
        return true;
    }

    /**
     * 更新需求阶段。
     * Update stage.
     *
     * @param  int       $storyID
     * @param  array     $stages
     * @param  array     $oldStages
     * @param  array     $linkedProjects
     * @access protected
     * @return bool
     */
    protected function updateStage(int $storyID, array $stages, array $oldStages = array(), array $linkedProjects = array()): bool
    {
        $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(empty($stages) && $oldStages) $stages = array_column($oldStages, 'stage', 'branch');
        if(empty($story)) return false;

        $stage   = empty($stages) ? $story->stage : current($stages);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fetch();
        if($product and $product->type != 'normal' and empty($story->branch))
        {
            $stageList   = implode(',', array_keys($this->lang->story->stageList));
            $minStagePos = strlen($stageList);
            $minStage    = '';
            foreach($stages as $branchID => $stage)
            {
                $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq((int)$branchID)->set('stage')->eq($stage)->exec();
                if(isset($oldStages[$branchID]) && !empty($oldStages[$branchID]->stagedBy))
                {
                    $this->dao->replace(TABLE_STORYSTAGE)->data($oldStages[$branchID])->exec();
                    $stage = $oldStages[$branchID]->stage;
                }

                $position = strpos($stageList, $stage);
                if($position !== false && $position < $minStagePos)
                {
                    $minStage    = $stage;
                    $minStagePos = $position;
                }
            }
            if($minStage) $stage = $minStage;
        }

        /* 如果是IPD项目，则stage有可能是已设路标或Charter立项。 */
        if($this->config->edition == 'ipd' && $story->type != 'story' && $story->roadmap && $stage == 'wait')
        {
            $stage   = 'inroadmap';
            $roadmap = $this->loadModel('roadmap')->getByID($story->roadmap);
            if($roadmap->status == 'launched') $stage = 'incharter';
        }

        $this->dao->update(TABLE_STORY)->set('stage')->eq($stage)->where('id')->eq($storyID)->exec();

        if($story->stage != $stage) $this->updateLinkedLane($storyID, $linkedProjects);
        $this->computeParentStage($story);

        return true;
    }

    /**
     * 通过子需求更新父需求的阶段。
     * Update parent stage by children.
     *
     * @param  object $story
     * @access public
     * @return void
     */
    public function computeParentStage(object $story)
    {
        $demandList = array();
        if(empty($story->parent))
        {
            if($this->config->edition == 'ipd' && !empty($story->demand))
            {
                $demandList[$story->demand] = $story->demand;
                $this->loadModel('demand')->updateDemandStage($demandList);
            }
            return;
        }

        $parent   = $this->dao->findById($story->parent)->from(TABLE_STORY)->fetch();
        $children = $this->dao->select('id, stage, closedReason')->from(TABLE_STORY)
            ->where('parent')->eq($story->parent)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $computedStage = $this->computeStage($children);
        $parentStage   = empty($computedStage) ? $parent->stage : $computedStage;

        if($parentStage == 'wait' && !empty($parent->roadmap))
        {
            $roadmapStatus = $this->dao->select('status')->from(TABLE_ROADMAP)->where('id')->eq($parent->roadmap)->fetch('status');
            $parentStage = $roadmapStatus == 'launched' ? 'incharter' : 'inroadmap';
        }

        if($parentStage != $parent->stage && $parent->status != 'closed')
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq($parentStage)->where('id')->eq($parent->id)->exec();

            if($this->config->edition == 'ipd')
            {
                if(!empty($parent->demand)) $demandList[$parent->demand] = $parent->demand;
                if(!empty($story->demand))  $demandList[$story->demand]  = $story->demand;
                if(!empty($demandList)) $this->loadModel('demand')->updateDemandStage($demandList);
            }

            if($parent->parent > 0) $this->computeParentStage($parent);
        }
    }

    /**
     * 通过子需求推算父需求的阶段。
     * 子需求阶段范围：未开始、已计划、研发立项、设计中、设计完毕、研发中、研发完毕、测试中、测试完毕、已验收、验收失败、已发布、已关闭。
     * 父需求阶段范围：未开始、已计划、研发立项、研发中、交付中、已交付、已关闭。
     *
     * 父需求阶段由子需求阶段决定，规则如下：
     * 1.未开始:
     *   所有子需求都未开始。
     * 2.已计划:
     *   父需求主动关联计划，或有子需求是已计划、其余子需求未开始。
     * 3.研发立项:
     *   父需求主动关联项目，或有子需求是研发立项、其余子需求阶段小于研发立项。
     * 4. 研发中:
     *   排除已关闭（关闭原因不是已完成）后，至少有一个子级需求为设计中、设计完毕、研发中、研发完毕、测试中、测试完毕、已验收、验收失败， 其他子需求阶段仅为未开始、规划中、已立项、已计划。
     * 5. 交付中:
     *   部分子级需求为已发布; 其余子级需求在已发布之前的阶段。
     * 6. 已交付:
     *   所有子需求都已发布; 或部分已发布，其余已关闭（关闭原因是已完成）。
     * 7. 已关闭:
     *   所有子需求都已关闭。
     *
     * Update parent stage by children.
     * Parent stage is decided by children stage, the rules are as follows:
     * 1. Wait:
     *   All child stories are in wait.
     * 2. Planned:
     *   Parent story is linked to plan, or at least one child story is in planned, and the others are in wait.
     * 3. Projected:
     *   Parent story is linked to project, or at least one child story is in projected, and the others are in wait or planned.
     * 4. Developing:
     *   At least one child story is in designing, designed, developing, developed, testing, tested, verified, rejected, and the others are in wait, defining, planning, planned.
     * 5. Delivering:
     *  Some child stories are released, and the others are in the stage before released.
     * 6. Delivered:
     *  All child stories are released, or some are released and the others are closed with reason done.
     * 7. Closed:
     * All child stories are closed.
     *
     * @param  array  $children
     * @access public
     * @return string
     */
    public function computeStage(array $children): string
    {
        $allWait      = true;
        $hasInRoadmap = false;
        $hasInCharter = false;
        $allIpdStage  = true;
        foreach($children as $child)
        {
            if($child->stage == 'closed' && $child->closedReason != 'done') continue;
            if($child->stage != 'wait')   $allWait   = false;
            if($child->stage == 'inroadmap') $hasInRoadmap = true;
            if($child->stage == 'incharter') $hasInCharter = true;
            if(strpos(',wait,inroadmap,incharter,', ",{$child->stage},") === false) $allIpdStage = false;
        }

        $parentStage = '';
        if($allWait)
        {
            $parentStage = 'wait';
        }
        elseif(!$allWait && $allIpdStage)
        {
            if($hasInRoadmap) $parentStage = 'inroadmap';
            if($hasInCharter) $parentStage = 'incharter';
        }
        else
        {
            $hasPlanned       = false;
            $allBeforePlanned = true;
            foreach($children as $child)
            {
                /* Planned. */
                if($child->stage == 'closed' && $child->closedReason != 'done') continue;
                if($child->stage == 'planned')
                {
                    $hasPlanned = true;
                }
                elseif(!in_array($child->stage, array('wait', 'planned')))
                {
                    $allBeforePlanned = false;
                }
            }

            if($hasPlanned && $allBeforePlanned)
            {
                $parentStage = 'planned';
            }
            else
            {
                /* Projected. */
                $hasProjected       = false;
                $allBeforeProjected = true;
                foreach($children as $child)
                {
                    if($child->stage == 'closed' && $child->closedReason != 'done') continue;
                    if($child->stage == 'projected') $hasProjected = true;
                    if(!in_array($child->stage, array('wait', 'planned', 'projected'))) $allBeforeProjected = false;
                }

                if($hasProjected && $allBeforeProjected)
                {
                    $parentStage = 'projected';
                }
                else
                {
                    /* Developing. */
                    $hasDeveloping       = false;
                    $allBeforeDeveloping = true;
                    foreach($children as $child)
                    {
                        if($child->stage == 'closed' && $child->closedReason != 'done') continue;
                        if(in_array($child->stage, array('designing', 'designed', 'developing', 'developed', 'testing', 'tested', 'verified', 'rejected')))
                        {
                            $hasDeveloping = true;
                        }
                        if(in_array($child->stage, array('released', 'delivering', 'delivered', 'closed')))
                        {
                            $allBeforeDeveloping = false;
                        }
                    }

                    if($hasDeveloping && $allBeforeDeveloping)
                    {
                        $parentStage = 'developing';
                    }
                    else
                    {
                        /* Delivering. */
                        $hasDelivering = false;
                        $hasDone       = false;
                        $allDelivered  = true;
                        foreach($children as $child)
                        {
                            if($child->stage == 'closed')
                            {
                                if($child->closedReason != 'done')
                                {
                                    continue;
                                }
                                else
                                {
                                    $hasDone = true;
                                }
                            }
                            if(in_array($child->stage, array('released', 'delivering', 'delivered'))) $hasDelivering = true;
                            if(!in_array($child->stage, array('released', 'closed', 'delivered'))) $allDelivered = false;
                        }

                        if($allDelivered)
                        {
                            $parentStage = 'delivered';
                        }
                        elseif($hasDelivering || $hasDone)
                        {
                            $parentStage = 'delivering';
                        }
                    }
                }
            }
        }

        return $parentStage;
    }

    /**
     * 根据计划和关联的项目的分支，获取默认的阶段值。
     * Get default stages by plans and linked branches.
     *
     * @param  string    $planIdList     e.g. 1,2
     * @param  array     $linkedBranches e.g. array(0, 1, branchID)
     * @access protected
     * @return array
     */
    protected function getDefaultStages(string $planIdList, array $linkedBranches): array
    {
        if(empty($planIdList) && empty($linkedBranches)) return array();

        $stages = array();
        if($planIdList)
        {
            $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($planIdList)->fetchPairs('branch', 'branch');
            foreach($plans as $branchID) $stages[(int)$branchID] = 'planned';
        }
        if(empty($linkedBranches)) return $stages;

        foreach($linkedBranches as $branchID) $stages[(int)$branchID] = 'projected';
        return $stages;
    }

    /**
     * 根据关联执行，获取关联该需求的任务状态统计数。
     * Get linked task status statistics for this story by linked projects.
     *
     * @param  int       $storyID
     * @param  array     $linkedProjects e.g. linkedProjects = array(projectID => stdclass('branch' => array(branchID)))
     * @access protected
     * @return array
     */
    protected function getLinkedTaskStat(int $storyID, array $linkedProjects): array
    {
        $tasks = $this->dao->select('type,execution,status')->from(TABLE_TASK)->where('execution')->in(array_keys($linkedProjects))
            ->andWhere('type')->in('devel,test,design')
            ->andWhere('story')->eq($storyID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('cancel')
            ->andWhere('closedReason')->ne('cancel')
            ->fetchGroup('type');
        if(empty($tasks)) return array();

        /* Cycle all tasks, get counts of every type and every status. */
        $branchStatusList     = $branchDevelCount = $branchTestCount = $branchDesignCount = array();
        $statusList['design'] = array('wait' => 0, 'doing' => 0, 'done' => 0, 'pause' => 0);
        $statusList['devel']  = array('wait' => 0, 'doing' => 0, 'done' => 0, 'pause' => 0);
        $statusList['test']   = array('wait' => 0, 'doing' => 0, 'done' => 0, 'pause' => 0);
        foreach($tasks as $type => $typeTasks)
        {
            if(!isset($statusList[$type])) continue;
            foreach($typeTasks as $task)
            {
                $status = $task->status ? $task->status : 'wait';
                if(!isset($statusList[$type][$status])) $status = 'done'; //如果任务状态不在默认统计状态列表中，则按照完成状态处理。

                $branches = $linkedProjects[$task->execution]->branches;
                foreach($branches as $branch)
                {
                    $branch = (int)$branch;
                    if(!isset($branchStatusList[$branch])) $branchStatusList[$branch] = $statusList;

                    $branchStatusList[$branch][$type][$status] ++;
                    if($type == 'devel')  $branchDevelCount[$branch]  = !isset($branchDevelCount[$branch])  ? 1 : ($branchDevelCount[$branch] + 1);
                    if($type == 'test')   $branchTestCount[$branch]   = !isset($branchTestCount[$branch])   ? 1 : ($branchTestCount[$branch] + 1);
                    if($type == 'design') $branchDesignCount[$branch] = !isset($branchDesignCount[$branch]) ? 1 : ($branchDesignCount[$branch] + 1);
                }
            }
        }
        return array($branchStatusList, $branchDevelCount, $branchTestCount, $branchDesignCount);
    }

    /**
     * 更新关联的看板泳道卡片。
     * Update linked lane.
     *
     * @param  int       $storyID
     * @param  string    $storyType
     * @access protected
     * @return int
     */
    protected function updateLane(int $storyID, string $storyType)
    {
        $executionIdList = $this->dao->select('t1.project')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq($storyID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.type')->in('sprint,stage,kanban')
            ->fetchPairs('project', 'project');

        $this->loadModel('kanban');
        foreach($executionIdList as $executionID) $this->kanban->updateLane($executionID, $storyType, $storyID);
    }

    /**
     * 更新关联的看板泳道。
     * Update linked lane.
     *
     * @param  int       $storyID
     * @param  array     $linkedProjects
     * @access protected
     * @return int
     */
    protected function updateLinkedLane(int $storyID, array $linkedProjects = array()): int
    {
        if(empty($storyID) || empty($linkedProjects)) return 0;

        $this->loadModel('kanban');
        $linkedKanbans = array_keys(array_filter(array_map(function($project){return $project->kanban;}, $linkedProjects)));
        foreach($linkedKanbans as $projectID) $this->kanban->updateLane($projectID, 'story', $storyID);

        return count($linkedKanbans);
    }

    /**
     * 根据任务状态统计，计算需求阶段。
     * Compute stages by tasks status statistics.
     *
     * @param  int       $storyID
     * @param  array     $taskStat
     * @param  array     $stages
     * @param  array     $linkedProjects
     * @access protected
     * @return array
     */
    protected function computeStagesByTasks(int $storyID, array $taskStat = array(), array $stages = array(), array $linkedProjects = array()): array
    {
        /* 设置关联的项目的分支阶段为已立项。 */
        if(empty($taskStat))
        {
            foreach($linkedProjects as $linkedProject)
            {
                foreach($linkedProject->branches as $branchID) $stages[(int)$branchID] = 'projected';
            }
            return $stages;
        }

        /* 根据任务状态统计信息，计算该需求所处的阶段。 */
        list($branchStatusList, $branchDevelCount, $branchTestCount, $branchDesignCount) = $taskStat;
        foreach($branchStatusList as $branch => $statusList)
        {
            $branch     = (int)$branch;
            $stage      = 'projected';
            $desginCount = isset($branchDesignCount[$branch])  ? $branchDesignCount[$branch]  : 0;
            $testCount   = isset($branchTestCount[$branch])  ? $branchTestCount[$branch]  : 0;
            $develCount  = isset($branchDevelCount[$branch]) ? $branchDevelCount[$branch] : 0;

            $doingDesignTask  = $statusList['design']['wait'] < $desginCount && $statusList['design']['done'] < $desginCount && $desginCount > 0;
            $doneDesignTask   = $statusList['design']['done'] == $desginCount && $desginCount > 0;
            $notStartDevTask  = $statusList['devel']['wait'] == $develCount;
            $doingDevelTask   = $statusList['devel']['wait'] < $develCount && $statusList['devel']['done'] < $develCount && $develCount > 0;
            $doneDevelTask    = $statusList['devel']['done'] == $develCount && $develCount > 0;
            $notStartTestTask = $statusList['test']['wait'] == $testCount;
            $doingTestTask    = $statusList['test']['wait'] < $testCount && $statusList['test']['done'] < $testCount && $testCount > 0;
            $doneTestTask     = $statusList['test']['done'] == $testCount && $testCount > 0;
            $hasDoingTestTask = $statusList['test']['doing'] > 0 || $statusList['test']['pause'] > 0;
            $notDoingTestTask = $statusList['test']['doing'] == 0;
            $hasDevelTask     = $develCount > 0;

            if($doingDesignTask && $notStartDevTask)                    $stage = 'designing';  //设计任务没有全部完成，开发任务还没有开始，阶段为设计中。
            if($doneDesignTask  && $notStartDevTask)                    $stage = 'designed';   //设计任务全部完成，开发任务还没有开始，阶段为设计完成。
            if($doingDevelTask  && $notStartTestTask)                   $stage = 'developing'; //开发任务没有全部完成，测试任务没有开始，阶段为开发中。
            if($doingDevelTask  && $notDoingTestTask)                   $stage = 'developing'; //开发任务没有全部完成，没有测试中的测试任务，阶段为开发中。
            if(($notStartDevTask || $doingDevelTask)  && $doneTestTask) $stage = 'testing';    //开发任务没有全部完成，测试任务已经完成，阶段为测试中。
            if($doneDevelTask   && $notStartTestTask)                   $stage = 'developed';  //开发任务已经完成，测试任务还没有开始，阶段为开发完成。
            if($doneDevelTask   && $doingTestTask)                      $stage = 'testing';    //开发任务已经完成，测试任务已经开始，阶段为测试中。
            if($hasDoingTestTask)                                       $stage = 'testing';    //有测试任务正在测试，阶段为测试中。
            if(($doneDevelTask || !$hasDevelTask) && $doneTestTask)     $stage = 'tested';     //开发任务已经完成或者没有开发任务，测试任务已经完成，阶段为测试完成。

            $stages[(int)$branch] = $stage;
        }

        return $stages;
    }

    /**
     * 根据需求是否已经发布，计算需求阶段。
     * Compute stages by release.
     *
     * @param  int       $storyID
     * @param  array     $stages
     * @access protected
     * @return array
     */
    protected function computeStagesByRelease(int $storyID, array $stages): array
    {
        /* 检查该需求是否已经发布，如果已经发布，阶段则为已发布。 */
        $releases = $this->dao->select('branch')->from(TABLE_RELEASE)
            ->where("CONCAT(',', stories, ',')")->like("%,$storyID,%")
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('normal')
            ->fetchAll();

        foreach($releases as $release)
        {
            $branches = array_unique(explode(',', trim($release->branch, ',')));
            foreach($branches as $branch) $stages[(int)$branch] = 'released';
        }

        return $stages;
    }

    /**
     * 获取该需求影响的项目和任务。
     * Get affected projects and tasks for this story.
     *
     * @param  object    $story
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function getAffectedProjects(object $story, array $users): object
    {
        $this->app->loadLang('task');
        $this->config->story->affect = new stdclass();
        $this->config->story->affect->projects = new stdclass();
        $this->config->story->affect->projects->fields['id']         = array('name' => 'id',         'title' => $this->lang->task->id);
        $this->config->story->affect->projects->fields['name']       = array('name' => 'name',       'title' => $this->lang->task->name, 'link' => $this->config->vision != 'or' ? helper::createLink('task', 'view', 'id={id}') : '', 'type' => 'title', 'fixed' => false, 'sortType' => false, 'flex' => false);
        $this->config->story->affect->projects->fields['assignedTo'] = array('name' => 'assignedTo', 'title' => $this->lang->task->assignedTo);
        $this->config->story->affect->projects->fields['consumed']   = array('name' => 'consumed',   'title' => $this->lang->task->consumed);
        $this->config->story->affect->projects->fields['left']       = array('name' => 'left',       'title' => $this->lang->task->left);

        if(empty($story->executions)) return $story;
        $storyExecutions = $story->executions;
        foreach($storyExecutions as $executionID => $execution) if($execution->status == 'done') unset($story->executions[$executionID]);
        $story->teams = $this->dao->select('account, root')->from(TABLE_TEAM)->where('root')->in(array_keys($story->executions))->andWhere('type')->eq('execution')->fetchGroup('root');

        foreach($story->tasks as $executionTasks)
        {
            foreach($executionTasks as $task)
            {
                $task->status     = $this->processStatus('task', $task);
                $task->assignedTo = zget($users, $task->assignedTo);
                if(isset($storyExecutions[$task->execution]))
                {
                    $taskExecution = $storyExecutions[$task->execution];
                    if(!$taskExecution->multiple) $this->config->story->affect->projects->fields['name']['link'] .= '#app=project';
                }
            }
        }
        return $story;
    }

    /**
     * 获取该需求影响的Bug。
     * Get affected bugs for this story.
     *
     * @param  object    $story
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function getAffectedBugs(object $story, array $users): object
    {
        $this->app->loadLang('bug');
        $this->app->loadLang('execution');
        if(!isset($this->config->story->affect)) $this->config->story->affect = new stdclass();
        $this->config->story->affect->bugs = new stdclass();
        $this->config->story->affect->bugs->fields[] = array('name' => 'id',           'title' => $this->lang->idAB);
        $this->config->story->affect->bugs->fields[] = array('name' => 'title',        'title' => $this->lang->bug->title, 'link' => $this->config->vision != 'or' ? helper::createLink('bug', 'view', 'id={id}') : '', 'data-toggle' => 'modal', 'data-size' => 'lg', 'fixed' => false, 'sortType' => false, 'flex' => false);
        $this->config->story->affect->bugs->fields[] = array('name' => 'status',       'title' => $this->lang->statusAB);
        $this->config->story->affect->bugs->fields[] = array('name' => 'openedBy',     'title' => $this->lang->bug->openedBy);
        $this->config->story->affect->bugs->fields[] = array('name' => 'resolvedBy',   'title' => $this->lang->bug->resolvedBy);
        $this->config->story->affect->bugs->fields[] = array('name' => 'resolution',   'title' => $this->lang->bug->resolution);
        $this->config->story->affect->bugs->fields[] = array('name' => 'lastEditedBy', 'title' => $this->lang->bug->lastEditedBy);

        /* Get affected bugs. */
        $storyIdList = $story->id . ($story->twins ? ',' . trim($story->twins, ',') : '');
        $story->bugs = $this->dao->select('*')->from(TABLE_BUG)->where('status')->ne('closed')
            ->andWhere('story')->in($storyIdList)
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchAll();

        foreach($story->bugs as $bug)
        {
            $bug->status       = $this->processStatus('bug', $bug);
            $bug->openedBy     = zget($users, $bug->openedBy);
            $bug->resolvedBy   = zget($users, $bug->resolvedBy);
            $bug->lastEditedBy = zget($users, $bug->lastEditedBy);
            $bug->resolution   = zget($this->lang->bug->resolutionList, $bug->resolution);
        }

        return $story;
    }

    /**
     * 获取该需求影响的用例。
     * Get affected cases for this story.
     *
     * @param  object    $story
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function getAffectedCases(object $story, array $users): object
    {
        $this->app->loadLang('testcase');
        if(!isset($this->config->story->affect)) $this->config->story->affect = new stdclass();
        $this->config->story->affect->cases = new stdclass();
        $this->config->story->affect->cases->fields[] = array('name' => 'id',           'title' => $this->lang->idAB);
        $this->config->story->affect->cases->fields[] = array('name' => 'title',        'title' => $this->lang->testcase->title, 'link' => $this->config->vision != 'or' ? helper::createLink('testcase', 'view', 'id={id}') : '', 'data-toggle' => 'modal', 'data-size' => 'lg', 'fixed' => false, 'sortType' => false, 'flex' => false);
        $this->config->story->affect->cases->fields[] = array('name' => 'status',       'title' => $this->lang->statusAB);
        $this->config->story->affect->cases->fields[] = array('name' => 'openedBy',     'title' => $this->lang->testcase->openedBy);
        $this->config->story->affect->cases->fields[] = array('name' => 'lastEditedBy', 'title' => $this->lang->testcase->lastEditedBy);

        /* Get affected cases. */
        $storyIdList  = $story->id . ($story->twins ? ',' . trim($story->twins, ',') : '');
        $story->cases = $this->dao->select('*')->from(TABLE_CASE)->where('deleted')->eq(0)
            ->andWhere('story')->in($storyIdList)
            ->fetchAll();
        foreach($story->cases as $case)
        {
            $case->status       = $this->processStatus('testcase', $case);
            $case->openedBy     = zget($users, $case->openedBy);
            $case->lastEditedBy = zget($users, $case->lastEditedBy);
        }

        return $story;
    }

    /**
     * 获取该需求影响的子需求。
     * Get affected children for this story.
     *
     * @param  object    $story
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function getAffectedChildren(object $story, array $users): object
    {
        $storyType = $story->type;
        foreach($story->children as $child)
        {
            $child->status   = $this->processStatus('story', $child);
            $child->openedBy = zget($users, $child->openedBy);
            $storyType       = $child->type;
        }

        if(!isset($this->config->story->affect)) $this->config->story->affect = new stdclass();
        $this->config->story->affect->children = new stdclass();
        $this->config->story->affect->children->fields[] = array('name' => 'id',       'title' => $this->lang->idAB, 'type' => 'id', 'sortType' => false);
        $this->config->story->affect->children->fields[] = array('name' => 'title',    'title' => $this->lang->story->name, 'link' => helper::createLink($storyType, 'view', 'id={id}'), 'type' => 'title', 'sortType' => false, 'data-toggle' => 'modal', 'data-size' => 'lg');
        $this->config->story->affect->children->fields[] = array('name' => 'pri',      'title' => $this->lang->priAB, 'type' => 'pri', 'sortType' => false);
        $this->config->story->affect->children->fields[] = array('name' => 'status',   'title' => $this->lang->story->status, 'type' => 'status', 'sortType' => false);
        $this->config->story->affect->children->fields[] = array('name' => 'openedBy', 'title' => $this->lang->story->openedBy, 'type' => 'user', 'sortType' => false);

        return $story;
    }

    /**
     * 获取该需求影响的孪生需求。
     * Get affected twins for this story.
     *
     * @param  object    $story
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function getAffectedTwins(object $story, array $users): object
    {
        if(empty($story->twins)) return $story;

        if(!isset($this->config->story->affect)) $this->config->story->affect = new stdclass();
        $this->config->story->affect->twins = new stdclass();
        $this->config->story->affect->twins->fields[] = array('name' => 'id',           'title' => $this->lang->idAB);
        $this->config->story->affect->twins->fields[] = array('name' => 'branch',       'title' => $this->lang->story->branch);
        $this->config->story->affect->twins->fields[] = array('name' => 'title',        'title' => $this->lang->story->title, 'link' => $this->config->vision != 'or' ? helper::createLink('story', 'view', 'id={id}') : '', 'data-toggle' => 'modal', 'data-size' => 'lg', 'fixed' => false, 'sortType' => false, 'flex' => false);
        $this->config->story->affect->twins->fields[] = array('name' => 'status',       'title' => $this->lang->statusAB);
        $this->config->story->affect->twins->fields[] = array('name' => 'stage',        'title' => $this->lang->story->stageAB);
        $this->config->story->affect->twins->fields[] = array('name' => 'openedBy',     'title' => $this->lang->story->openedBy);
        $this->config->story->affect->twins->fields[] = array('name' => 'lastEditedBy', 'title' => $this->lang->story->lastEditedBy);

        $story->twins = $this->getByList($story->twins);
        $branches     = $this->loadModel('branch')->getPairs($story->product);
        foreach($story->twins as $twin)
        {
            $twin->branch       = zget($branches, $twin->branch, '');
            $twin->status       = $this->processStatus('story', $twin);
            $twin->openedBy     = zget($users, $twin->openedBy);
            $twin->lastEditedBy = zget($users, $twin->lastEditedBy);
            $twin->stage        = zget($this->lang->story->stageList, $twin->stage);
        }

        return $story;
    }

    /**
     * 检查操作按钮的触发条件。
     * Check action conditions.
     *
     * @param  string    $method
     * @param  object    $story
     * @access protected
     * @return bool
     */
    protected function checkConditions(string $method, object $story)
    {
        if($this->config->edition == 'open') return true;

        static $flowActions = [];
        if(empty($flowActions)) $flowActions = $this->loadModel('workflowaction')->getList($story->type);
        $this->loadModel('flow');

        $isClickable = true;
        foreach($flowActions as $flowAction)
        {
            if($flowAction->action == $method && $flowAction->extensionType != 'none' && $flowAction->status == 'enable' && !empty($flowAction->conditions))
            {
                $isClickable = $this->flow->checkConditions($flowAction->conditions, $story);
            }
        }

        return $isClickable;
    }

    /**
     * 构建需求列表中的操作按钮。
     * Build action buttons on the browse page.
     *
     * @param  object    $story
     * @param  string    $params
     * @param  string    $storyType story|requirement
     * @param  object    $execution
     * @param  array     $maxGradeGroup
     * @access protected
     * @return array
     */
    protected function buildBrowseActionBtnList(object $story, string $params = '', string $storyType = 'story', ?object $execution = null, array $maxGradeGroup = array()): array
    {
        global $lang;
        $actions = array();

        if(!empty($execution) && !common::canModify($execution->type == 'project' ? 'project' : 'execution', $execution)) return $actions;

        $tutorialMode = commonModel::isTutorialMode();
        if($this->config->edition == 'ipd' && $storyType == 'story' && !empty($story->confirmeActionType))
        {
            $method    = $story->confirmeActionType == 'confirmedretract' ? 'confirmDemandRetract' : 'confirmDemandUnlink';
            $url       = helper::createLink('story', $method, "objectID=$story->id&object=story&extra={$story->confirmeObjectID}");
            $actions[] = array('name' => $method, 'icon' => 'search', 'hint' => $this->lang->story->$method, 'url' => $url, 'data-toggle' => 'modal');
            return $actions;
        }

        static $taskGroups = array();
        static $caseGroups = array();
        if(empty($taskGroups)) $taskGroups = $this->dao->select('story, id')->from(TABLE_TASK)->where('deleted')->eq('0')->andWhere('story')->ne(0)->fetchPairs();
        if(empty($caseGroups)) $caseGroups = $this->dao->select('story, id')->from(TABLE_CASE)->where('deleted')->eq('0')->andWhere('story')->ne(0)->fetchPairs();

        $actSubmitreview = array();
        $actReview       = array();
        $actRecall       = array();
        $storyReviewer   = isset($story->reviewer) ? $story->reviewer : array();
        $executionID     = empty($execution) ? 0 : $execution->id;
        if(!isset($story->from)) $story->from = '';

        $closeLink               = helper::createLink($story->type, 'close', $params . "&from=$story->from");
        $activateLink            = helper::createLink($story->type, 'activate', $params . "&from=$story->from");
        $processStoryChangeLink  = helper::createLink($story->type, 'processStoryChange', $params);
        $changeLink              = helper::createLink($story->type, 'change', $params . "&from=$story->from");
        $submitReviewLink        = helper::createLink($story->type, 'submitReview', "storyID=$story->id");
        $reviewLink              = helper::createLink($story->type, 'review', $params . "&from=$story->from") . ($this->app->tab == 'project' ? '#app=project' : '');
        $recallLink              = helper::createLink($story->type, 'recall', $params . "&from=list&confirm=no");
        $batchCreateStoryLink    = helper::createLink($story->type, 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0");
        $editLink                = helper::createLink($story->type, 'edit', $params . "&kanbanGroup=default") . ($this->app->tab == 'project' ? '#app=project' : '');
        $createCaseLink          = helper::createLink('testcase', 'create', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$params");

        /* If the story cannot be changed, render the close button. */
        $canClose    = common::hasPriv($story->type, 'close')    && $this->isClickable($story, 'close', $taskGroups, $caseGroups) && $this->checkConditions('close', $story);
        $canActivate = common::hasPriv($story->type, 'activate') && $this->isClickable($story, 'activate', $taskGroups, $caseGroups) && $this->checkConditions('activate', $story);
        if(!common::canBeChanged($story->type, $story)) return array(array('name' => 'close', 'hint' => $lang->close, 'data-toggle' => 'modal', 'url' => $canClose ? $closeLink : null, 'disabled' => !$canClose));
        $canProcessChange = common::hasPriv($story->type, 'processStoryChange');
        if(!empty($story->parentChanged) || !empty($story->demandChanged)) return array(array('name' => 'processStoryChange', 'url' => $canProcessChange ? $processStoryChangeLink : null, 'disabled' => !$canProcessChange, 'innerClass' => 'ajax-submit'));

        /* Change button. */
        $canChange = common::hasPriv($story->type, 'change') && $this->isClickable($story, 'change', $taskGroups, $caseGroups) && $this->checkConditions('change', $story);
        $title     = $canChange ? $lang->story->change : $this->lang->story->changeTip;
        if(common::hasPriv($story->type, 'change')) $actions[] = array('name' => 'change', 'url' => $canChange ? $changeLink : null, 'hint' => empty($story->frozen) ? $title : sprintf($this->lang->story->frozenTip, $this->lang->story->change), 'disabled' => !$canChange, 'class' => 'story-change-btn');

        /* Submitreview, review, recall buttons. */
        if(strpos('draft,changing', $story->status) !== false)
        {
            $canSubmitReview = common::hasPriv($story->type, 'submitReview');
            $actSubmitreview = array('name' => 'submitreview', 'data-toggle' => 'modal', 'url' => $canSubmitReview ? $submitReviewLink : null, 'disabled' => !$canSubmitReview, 'hint' => $canSubmitReview ? $this->lang->story->submitReview : $this->lang->story->reviewTip['noPriv']);
        }
        else
        {
            $canReview = common::hasPriv($story->type, 'review') && $this->isClickable($story, 'review', $taskGroups, $caseGroups) && $this->checkConditions('review', $story);
            $title     = $this->lang->story->review;
            if(!$canReview && $story->status != 'closed')
            {
                if($storyReviewer && !in_array($this->app->user->account, $storyReviewer)) $title = $this->lang->story->reviewTip['notReviewer'];
                if($story->status == 'active')
                {
                    if($storyReviewer && in_array($this->app->user->account, $storyReviewer))
                    {
                        $title = $this->lang->story->reviewTip['reviewed'];
                    }
                    else
                    {
                        $title = $this->lang->story->reviewTip['active'];
                    }
                }
            }

            $actReview = array('name' => 'review', 'url' => $canReview ? $reviewLink : null, 'hint' => $title, 'disabled' => !$canReview, 'class' => 'story-review-btn');
        }

        $canRecall = common::hasPriv($story->type, 'recall') && $this->isClickable($story, $story->status == 'changing' ? 'recallchange' : 'recall', $taskGroups, $caseGroups);
        $title     = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
        if(!$canRecall) $title = $this->lang->story->recallTip['actived'];
        $actRecall = array('name' => $story->status == 'changing' ? 'recalledchange' : 'recall', 'url' => $canRecall ? $recallLink : null, 'hint' => $title, 'disabled' => !$canRecall);

        /* Change the render order. */
        if(!empty($actSubmitreview))
        {
            $actions[] = $actSubmitreview;
            $actions[] = array('name' => 'dropdown', 'type' => 'dropdown', 'items' => array($actRecall + array('innerClass' => 'ajax-submit')));
        }
        elseif($actReview['disabled'] && !$actRecall['disabled'])
        {
            $actions[] = $actRecall + array('className' => 'ajax-submit');
            $actions[] = array('name' => 'dropdown', 'type' => 'dropdown', 'items' => array($actReview));
        }
        else
        {
            $actions[] = $actReview;
            $actions[] = array('name' => 'dropdown', 'type' => 'dropdown', 'items' => array($actRecall + array('innerClass' => 'ajax-submit')));
        }

        if($this->config->vision != 'lite' && $story->status != 'closed' && common::hasPriv($story->type, 'close'))    $actions[] = array('name' => 'close',    'url' => $canClose ? $closeLink : null,       'data-toggle' => 'modal',  'disabled' => !$canClose);
        if($this->config->vision != 'lite' && $story->status == 'closed' && common::hasPriv($story->type, 'activate')) $actions[] = array('name' => 'activate', 'url' => $canActivate ? $activateLink : null, 'data-toggle' => 'modal');

        /* Render divider line. */
        if(!empty($actions)) $actions[] = array('name' => 'divider', 'type'=>'divider');

        /* Edit button. */
        $canEdit = common::hasPriv($story->type, 'edit') && $this->isClickable($story, 'edit', $taskGroups, $caseGroups) && $this->checkConditions('edit', $story);
        if(common::hasPriv($story->type, 'edit')) $actions[] = array('name' => 'edit', 'url' => $this->isClickable($story, 'edit', $taskGroups, $caseGroups) ? $editLink : null, 'disabled' => !$canEdit, 'hint' => empty($story->frozen) ? $this->lang->story->edit : sprintf($this->lang->story->frozenTip, $this->lang->story->edit));

        /* Create test case button. */
        if($story->type == 'story' && $this->config->vision != 'lite' && common::hasPriv('testcase', 'create')) $actions[] = array('name' => 'testcase', 'url' => $story->isParent == '0' ? $createCaseLink : null, 'disabled' => $story->isParent == '1', 'data-toggle' => 'modal', 'data-size' => 'lg');

        /* Batch create button. */
        $shadow = $this->dao->findByID($story->product)->from(TABLE_PRODUCT)->fetch('shadow');

        $canBatchCreateStory = (common::hasPriv($story->type, 'batchcreate') && $this->isClickable($story, 'batchcreate', $taskGroups, $caseGroups) && $story->grade < $maxGradeGroup[$story->type] && empty($story->hasOtherTypeChild)) || common::isTutorialMode();
        if(!($this->app->rawModule == 'projectstory' && $this->app->rawMethod == 'story') || $this->config->vision == 'lite' || $shadow)
        {
            if($shadow and empty($taskGroups[$story->id])) $taskGroups[$story->id] = $this->dao->select('id')->from(TABLE_TASK)->where('story')->eq($story->id)->fetch('id');
            if(empty($caseGroups[$story->id])) $caseGroups[$story->id] = $this->dao->select('id')->from(TABLE_CASE)->where('story')->eq($story->id)->fetch('id');

            /*
             * 需求的拆分按钮分为两种情况：
             *   1.拆分成相同类型的子需求。
             *   2.拆分成其他类型的子需求。
             * 这两种情况是互斥的，拆分了一种类型的需求后，另一种类型的需求就不能再拆分了。
             * 默认拆分相同类型的需求，如果不能拆分相同类型的需求，则拆分其他类型的需求。
             */
            if($canBatchCreateStory)
            {
                $actions[] = array('name' => 'batchCreate', 'url' => $batchCreateStoryLink, 'hint' => empty($story->frozen) ? $this->lang->story->split : sprintf($this->lang->story->frozenTip, $this->lang->story->split), 'icon' => 'split', 'class' => 'batchCreateStoryBtn');
            }
            elseif($story->type == 'epic' && common::hasPriv('requirement', 'batchCreate') && $this->isClickable($story, 'batchcreate', $taskGroups, $caseGroups) && empty($story->hasSameTypeChild) && !($this->config->epic->gradeRule == 'stepwise' && $story->grade < $maxGradeGroup['epic']))
            {
                $actions[] = array('name' => 'batchCreate', 'url' => helper::createLink('requirement', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0"), 'hint' => empty($story->frozen) ? $this->lang->story->split : sprintf($this->lang->story->frozenTip, $this->lang->story->split), 'icon' => 'split', 'class' => 'batchCreateStoryBtn');
            }
            elseif($story->type == 'requirement' && common::hasPriv('story', 'batchCreate') && $this->isClickable($story, 'batchcreate', $taskGroups, $caseGroups) && empty($story->hasSameTypeChild) && !($this->config->requirement->gradeRule == 'stepwise' && $story->grade < $maxGradeGroup['requirement']))
            {
                $actions[] = array('name' => 'batchCreate', 'url' => helper::createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0"), 'hint' => empty($story->frozen) ? $this->lang->story->split : sprintf($this->lang->story->frozenTip, $this->lang->story->split), 'icon' => 'split', 'class' => 'batchCreateStoryBtn');
            }
            elseif(!$canBatchCreateStory && common::hasPriv($story->type, 'batchcreate'))
            {
                $title = $this->lang->story->split;
                if($story->status == 'active' && $story->stage != 'wait') $title = sprintf($this->lang->story->subDivideTip['notWait'], zget($this->lang->{$story->type}->stageList, $story->stage));
                if(!empty($story->twins)) $title = $this->lang->story->subDivideTip['twinsSplit'];
                if(!empty($taskGroups[$story->id])) $title = sprintf($this->lang->story->subDivideTip['notWait'], $this->lang->story->hasDividedTask);
                if(!empty($caseGroups[$story->id])) $title = sprintf($this->lang->story->subDivideTip['notWait'], $this->lang->story->hasDividedCase);
                if(isset($maxGradeGroup[$story->type]) && $story->grade >= $maxGradeGroup[$story->type]) $title = $this->lang->story->errorMaxGradeSubdivide;
                if($story->status != 'active' && $story->status != 'changing') $title = $this->lang->story->subDivideTip['notActive'];
                if(!empty($story->frozen)) $title = sprintf($this->lang->story->frozenTip, $this->lang->story->split);
                $actions[] = array('name' => 'batchCreate', 'hint' => $title, 'disabled' => true, 'icon' => 'split');
            }
        }

        if(!empty($execution))
        {
            if($execution->type != 'project')
            {
                $createTaskLink      = $tutorialMode ? helper::createLink('tutorial', 'wizard', "module=task&method=create&params=" . helper::safe64Encode("executionID={$execution->id}")) : helper::createLink('task', 'create', "executionID={$execution->id}&storyID={$story->id}");
                $batchCreateTaskLink = helper::createLink('task', 'batchCreate', "executionID={$execution->id}&storyID={$story->id}");
                $storyEstimateLink   = helper::createLink('execution', 'storyEstimate', "executionID={$execution->id}&storyID={$story->id}");

                $canCreateTask      = common::hasPriv('task', 'create') && $story->status == 'active' && $story->isParent == '0' && $story->type == 'story';
                $canBatchCreateTask = common::hasPriv('task', 'batchCreate') && $story->status == 'active' && $story->isParent == '0' && $story->type == 'story';
                $canStoryEstimate   = common::hasPriv('execution', 'storyEstimate') && $story->type == 'story';

                $actions[] = array('name' => 'createTask',      'url' => $canCreateTask      ? $createTaskLink      : null, 'disabled' => !$canCreateTask, 'className' => 'create-task-btn');
                $actions[] = array('name' => 'batchCreateTask', 'url' => $canBatchCreateTask ? $batchCreateTaskLink : null, 'disabled' => !$canBatchCreateTask, 'className' => 'batchcreate-task-btn');
                $actions[] = array('name' => 'storyEstimate',   'url' => $canStoryEstimate   ? $storyEstimateLink   : null, 'disabled' => !$canStoryEstimate);
            }

            if($this->config->vision != 'lite' && $execution->hasProduct)
            {
                $unlinkModule    = 'execution';
                $canUnlinkStory  = common::hasPriv($unlinkModule, 'unlinkStory');
                $unlinkStoryLink = helper::createLink($unlinkModule, 'unlinkStory', "projectID={$execution->id}&$params&confirm=yes");
                $unlinkStoryTip  = $this->lang->execution->confirmUnlinkStory;
                $unlinkHint      = $this->lang->story->unlink;
                $disabled        = !$canUnlinkStory;

                if($execution->type == 'project')
                {
                    $unlinkStoryTip = $this->lang->execution->confirmUnlinkExecutionStory;

                    static $executionStories = array();
                    if(!isset($executionStories[$execution->id]))
                    {
                        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($execution->id)->andWhere('type')->ne('project')->fetchAll('id');
                        $executionStories[$execution->id] = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('project')->in(array_keys($executions))->fetchPairs('story', 'story');
                    }
                    if(isset($executionStories[$execution->id][$story->id]))
                    {
                        $disabled   = true;
                        $unlinkHint = $this->lang->execution->notAllowedUnlinkStory;
                    }

                    $canBatchCreateStory = common::hasPriv($story->type, 'batchcreate') && $this->isClickable($story, 'batchcreate', $taskGroups, $caseGroups) && $story->grade < $maxGradeGroup[$story->type] && empty($story->hasOtherTypeChild);
                    if($canBatchCreateStory)
                    {
                        $actions[] = array('name' => 'batchCreate', 'url' => $batchCreateStoryLink, 'hint' => empty($story->frozen) ? $this->lang->story->split : sprintf($this->lang->story->frozenTip, $this->lang->story->split), 'icon' => 'split');
                    }
                    elseif($story->type == 'epic' && common::hasPriv('requirement', 'batchCreate') && $this->isClickable($story, 'batchcreate', $taskGroups, $caseGroups) && empty($story->hasSameTypeChild) && !($this->config->epic->gradeRule == 'stepwise' && $story->grade < $maxGradeGroup['epic']))
                    {
                        $actions[] = array('name' => 'batchCreate', 'url' => helper::createLink('requirement', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0"), 'hint' => empty($story->frozen) ? $this->lang->story->split : sprintf($this->lang->story->frozenTip, $this->lang->story->split), 'icon' => 'split');
                    }
                    elseif($story->type == 'requirement' && common::hasPriv('story', 'batchCreate') && $this->isClickable($story, 'batchcreate', $taskGroups, $caseGroups) && empty($story->hasSameTypeChild) && !($this->config->requirement->gradeRule == 'stepwise' && $story->grade < $maxGradeGroup['requirement']))
                    {
                        $actions[] = array('name' => 'batchCreate', 'url' => helper::createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0"), 'hint' => empty($story->frozen) ? $this->lang->story->split : sprintf($this->lang->story->frozenTip, $this->lang->story->split), 'icon' => 'split');
                    }
                    elseif(!$canBatchCreateStory && $story->status != 'closed' && common::hasPriv($story->type, 'batchcreate'))
                    {
                        $title = $this->lang->story->split;
                        if($story->status == 'active' && $story->stage != 'wait') $title = sprintf($this->lang->story->subDivideTip['notWait'], zget($this->lang->story->stageList, $story->stage));
                        if(!empty($story->twins)) $title = $this->lang->story->subDivideTip['twinsSplit'];
                        if(!empty($taskGroups[$story->id])) $title = sprintf($this->lang->story->subDivideTip['notWait'], $this->lang->story->hasDividedTask);
                        if(!empty($caseGroups[$story->id])) $title = sprintf($this->lang->story->subDivideTip['notWait'], $this->lang->story->hasDividedCase);
                        if(isset($maxGradeGroup[$story->type]) && $story->grade >= $maxGradeGroup[$story->type]) $title = $this->lang->story->errorMaxGradeSubdivide;
                        if($story->status != 'active') $title = $this->lang->story->subDivideTip['notActive'];
                        if(!empty($story->frozen)) $title = sprintf($this->lang->story->frozenTip, $this->lang->story->split);
                        $actions[] = array('name' => 'batchCreate', 'hint' => $title, 'disabled' => true, 'icon' => 'split');
                    }
                }

                if($story->type == 'requirement') $unlinkStoryTip = str_replace($this->lang->SRCommon, $this->lang->URCommon, $unlinkStoryTip);
                $unlinkStoryTip = json_encode(array('message' => array('html' => "<i class='icon icon-exclamation-sign text-warning text-lg mr-2'></i>{$unlinkStoryTip}")));
                $actions[] = array('name' => 'unlink', 'className' => 'ajax-submit', 'data-confirm' => $unlinkStoryTip, 'url' => $canUnlinkStory ? $unlinkStoryLink : null, 'disabled' => $disabled || !empty($story->frozen), 'hint' => !empty($story->frozen) ? sprintf($this->lang->story->frozenTip, $this->lang->story->unlink) : $unlinkHint);
            }
        }

        return $actions;
    }

    /**
     * 检查当前账号是否是超级评审人。
     * Check account is super reviewer or not.
     *
     * @access protected
     * @return bool
     */
    protected function isSuperReviewer(): bool
    {
        $moduleName = $this->app->rawModule;
        return str_contains(',' . zget($this->config->{$moduleName}, 'superReviewers', '') . ',', ",{$this->app->user->account},");
    }

    /**
     * 更新父需求的状态。
     * Update parent story status.
     *
     * @param  int       $parentID
     * @param  string    $status
     * @access protected
     * @return object|false
     */
    protected function doUpdateParentStatus(int $parentID, string $status): object|false
    {
        if(empty($parentID)) return false;

        $oldParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->andWhere('deleted')->eq(0)->fetch();

        $now   = helper::now();
        $story = new stdclass();
        $story->status = $status;
        if(strpos('launched,active,changing,draft', $status) !== false)
        {
            $story->assignedTo   = $oldParentStory->openedBy;
            $story->assignedDate = $now;
            $story->closedBy     = '';
            $story->closedReason = '';
            $story->closedDate   = null;
            $story->reviewedBy   = '';
            $story->reviewedDate = null;
        }

        if($status == 'closed')
        {
            $closedReason = $this->dao->select('closedReason')->from(TABLE_STORY)
                ->where('parent')->eq($parentID)
                ->andWhere('deleted')->eq(0)
                ->andWhere('closedReason')->eq('done')
                ->fetch('closedReason');

            $story->assignedTo   = 'closed';
            $story->assignedDate = $now;
            $story->closedBy     = $this->app->user->account;
            $story->closedDate   = $now;
            $story->closedReason = $closedReason ? 'done' : 'willnotdo';
        }

        $story->lastEditedBy   = $this->app->user->account;
        $story->lastEditedDate = $now;
        $this->dao->update(TABLE_STORY)->data($story)->where('id')->eq($parentID)->exec();

        return $story;
    }

    /**
     * 获取传入所有需求的叶子结点，并按照根节点顺序返回
     * Get leaf node by all stories, and sort by base nodes.
     *
     * @param  array    $stories
     * @param  string   $storyType  epic|requirement|story
     * @access public
     * @return array
     */
    public function getLeafNodes(array $stories, string $storyType = 'epic'): array
    {
        $stmt = $this->dao->select('id,project,commit,name as title,status,story,type AS designType')->from(TABLE_DESIGN)->where('story')->in(array_keys($stories))->andWhere('deleted')->eq(0)->orderBy('project')->query();
        $designGroup = array();
        while($design = $stmt->fetch()) $designGroup[$design->story][$design->id] = $design;

        $leafNodes = array();
        foreach($stories as $story)
        {
            if($storyType == 'requirement' && $story->type == 'epic') continue;
            if($storyType == 'story' && ($story->type == 'epic' || $story->type == 'requirement')) continue;

            if(empty($story->isParent)) $leafNodes[$story->id] = $story;
            if(!isset($leafNodes[$story->id]) && isset($designGroup[$story->id])) $leafNodes[$story->id] = $story;
        }

        return $leafNodes;
    }

    /**
     * 根据叶子结点数据，构建看板泳道数据。
     * Build lanes data by leaf node.
     *
     * @param  array    $leafNodes
     * @param  string   $storyType  epic|requirement|story
     * @access public
     * @return array
     */
    public function buildTrackLanes(array $leafNodes, string $storyType): array
    {
        $lanes = array();
        foreach($leafNodes as $story)
        {
            if(($storyType == 'requirement' || $storyType == 'story') && $story->type == 'epic') continue;
            if($storyType == 'story' && $story->type == 'requirement') continue;
            $lanes[] = array('name' => "lane_{$story->id}", 'title' => '');
        }
        return $lanes;
    }

    /**
     * 根据需求类型，构建看板列数据。
     * Build cols data by storyType.
     *
     * @param  string  $storyType   epic|requirement|story
     * @access public
     * @return array
     */
    public function buildTrackCols(string $storyType): array
    {
        $storyGrade = $this->getGradeGroup();

        $cols = array();
        if($storyType == 'epic')  $cols['epic']        = $this->buildTrackCol('epic',        $this->lang->ERCommon, empty($storyGrade['epic'])        ? 0 : -1);
        if($storyType != 'story') $cols['requirement'] = $this->buildTrackCol('requirement', $this->lang->URCommon, empty($storyGrade['requirement']) ? 0 : -1);

        $cols['story']     = $this->buildTrackCol('story',     $this->lang->SRCommon, empty($storyGrade['story']) ? 0 : -1);
        $cols['project']   = $this->buildTrackCol('project',   $this->lang->story->project);
        $cols['execution'] = $this->buildTrackCol('execution', $this->lang->story->execution);
        $cols['design']    = $this->buildTrackCol('design',    $this->lang->story->design);
        $cols['commit']    = $this->buildTrackCol('commit',    $this->lang->story->repoCommit);
        $cols['task']      = $this->buildTrackCol('task',      $this->lang->story->tasks);
        $cols['bug']       = $this->buildTrackCol('bug',       $this->lang->story->bugs);
        $cols['case']      = $this->buildTrackCol('case',      $this->lang->story->cases);

        foreach($storyGrade as $type => $grades)
        {
            if(!isset($cols[$type])) continue;
            foreach($grades as $grade) $cols["{$type}_{$grade->grade}"] = $this->buildTrackCol("{$type}_{$grade->grade}", $grade->name, $type);
        }

        return array_values($cols);
    }

    /**
     * 根据单个看板列数据。
     * Build single col data.
     *
     * @param  string     $name
     * @param  string     $title
     * @param  int|string $parent
     * @access public
     * @return array
     */
    public function buildTrackCol(string $name, string $title, int|string $parent = 0): array
    {
        $col = array('name' => $name, 'title' => $title, 'parent' => $parent);
        if($parent != '0' && $parent != -1) $col['parentName'] = $parent;
        return $col;
    }

    /**
     * 构建看板项数据。
     * Build items data by storyType.
     *
     * @param  array  $allStories
     * @param  array  $leafNodes
     * @param  string $storyType    epic|requirement|story
     * @access public
     * @return array
     */
    public function buildTrackItems(array $allStories, array $leafNodes, string $storyType): array
    {
        $storyIdList  = array_keys($leafNodes);
        $projectGroup = $this->getProjectsForTrack($storyIdList);
        $designGroup  = $this->getDesignsForTrack($storyIdList);

        $projects   = zget($projectGroup, 'project', array());
        $executions = zget($projectGroup, 'execution', array());
        $designs    = zget($designGroup, 'design', array());
        $commits    = zget($designGroup, 'commit', array());
        $tasks      = $this->getTasksForTrack($storyIdList);
        $cases      = $this->dao->select('id,project,pri,status,color,title,story,lastRunner,lastRunResult')->from(TABLE_CASE)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->orderBy('project')->fetchGroup('story', 'id');
        $bugs       = $this->dao->select('id,project,pri,status,color,title,story,assignedTo,severity')->from(TABLE_BUG)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->orderBy('project')->fetchGroup('story', 'id');
        $storyGrade = $this->getGradeGroup();

        $items = array();
        foreach($leafNodes as $node)
        {
            $laneName = "lane_{$node->id}";
            foreach(explode(',', trim($node->path, ',')) as $storyID)
            {
                if(!isset($allStories[$storyID])) continue;
                $story = clone $allStories[$storyID];

                if($storyType == 'requirement' && $story->type == 'epic') continue;
                if($storyType == 'story' && ($story->type == 'requirement' || $story->type == 'epic')) continue;
                if(!isset($storyGrade[$story->type][$story->grade])) continue;

                $colName = "{$story->type}_{$story->grade}";
                $story->storyType = $story->type;
                unset($story->type);

                $items[$laneName][$colName][] = $story;
            }
            $items[$laneName]['project']   = array_values(zget($projects,   $node->id, array()));
            $items[$laneName]['execution'] = array_values(zget($executions, $node->id, array()));
            $items[$laneName]['design']    = array_values(zget($designs,    $node->id, array()));
            $items[$laneName]['commit']    = array_values(zget($commits,    $node->id, array()));
            $items[$laneName]['task']      = array_values(zget($tasks,      $node->id, array()));
            $items[$laneName]['bug']       = array_values(zget($bugs,       $node->id, array()));
            $items[$laneName]['case']      = array_values(zget($cases,      $node->id, array()));
        }

        return $items;
    }

    /**
     * 根据需求ID列表获取关联的项目和执行
     * Get linked projects and executions by story id list.
     *
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getProjectsForTrack(array $storyIdList): array
    {
        $projectStoryList = array();
        $stmt = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('story')->in($storyIdList)->query();
        while($projectStory = $stmt->fetch()) $projectStoryList[$projectStory->project][$projectStory->story] = $projectStory->story;

        $stmt       = $this->dao->select('id,type AS projectType,model,parent,path,grade,name as title,hasProduct,begin,end,status,project,progress,multiple')->from(TABLE_PROJECT)->where('id')->in(array_keys($projectStoryList))->andWhere('deleted')->eq(0)->orderBy('id')->query();
        $today      = helper::today();
        $storyGroup = array();
        while($project = $stmt->fetch())
        {
            if($project->projectType != 'project' && empty($project->multiple)) continue;

            $delay = 0;
            if($project->status != 'done' && $project->status != 'closed' && $project->status != 'suspended') $delay = helper::diffDate($today, $project->end);

            $project->delay = $delay > 0;
            $projectType = $project->projectType == 'project' ? 'project' : 'execution';
            foreach($projectStoryList[$project->id] as $storyID) $storyGroup[$projectType][$storyID][$project->id] = $project;
        }

        return $storyGroup;
    }

    /**
     * 根据需求ID列表获取关联的设计和提交
     * Get linked designs and commits by story id list.
     *
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getDesignsForTrack(array $storyIdList): array
    {
        $storyGroup   = array('design' => array(), 'commit' => array());
        $stmt         = $this->dao->select('id,project,commit,name as title,status,story,type AS designType')->from(TABLE_DESIGN)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->orderBy('project')->query();
        $commitIdList = '';
        while($design = $stmt->fetch())
        {
            $storyGroup['design'][$design->story][$design->id] = $design;
            $commitIdList .= $design->commit ? "{$design->commit}," : '';
        }

        if($commitIdList) $commits = $this->dao->select('id,repo,revision,committer,comment as title')->from(TABLE_REPOHISTORY)->where('id')->in(array_unique(explode(',', $commitIdList)))->fetchAll('id');
        foreach($storyGroup['design'] as $storyID => $designs)
        {
            foreach($designs as $design)
            {
                if(empty($design->commit)) continue;
                foreach(explode(',', $design->commit) as $commitID)
                {
                    $commit = zget($commits, $commitID, '');
                    if(empty($commit)) continue;

                    $commit->project = $design->project;
                    $storyGroup['commit'][$storyID][$commitID] = $commit;
                }
            }
        }

        return $storyGroup;
    }

    /**
     * 根据需求ID列表获取关联的任务
     * Get linked tasks by story id list.
     *
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getTasksForTrack(array $storyIdList): array
    {
        $stmt       = $this->dao->select('id,project,execution,mode,pri,status,color,name as title,assignedTo,story,estimate,consumed,`left`,parent,isParent,path')->from(TABLE_TASK)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->orderBy('project,execution,isParent_desc,path')->query();
        $tasks      = array();
        $multiTasks = array();
        while($task = $stmt->fetch())
        {
            $task->progress = 0;
            if($task->consumed + $task->left) $task->progress = round(($task->consumed / ($task->consumed + $task->left)) * 100, 2);
            if($task->mode == 'multi') $multiTasks[$task->id] = $task->id;

            $tasks[$task->id] = $task;
        }

        $tasks     = $this->loadModel('task')->mergeChildIntoParent($tasks);
        $taskTeams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($multiTasks)->fetchGroup('task', 'account');
        $taskGroup = array();
        $account   = $this->app->user->account;
        $preTask   = null;
        foreach($tasks as $task)
        {
            $taskGroup[$task->story][$task->id] = $task;

            if(isset($taskTeams[$task->id])) $task->assignedTo = isset($taskTeams[$task->id][$account]) ? $account : $this->lang->task->team;
            if($preTask && $preTask->isParent && ($task->parent == 0 || ($task->parent > 0 && $preTask->id != $task->parent))) $preTask->isParent = 0;
            if($task->parent > 0 && !isset($taskGroup[$task->story][$task->parent]) && isset($tasks[$task->parent])) $tasks[$task->parent]->isParent = 0;

            $preTask = $task;
        }
        if($preTask && $preTask->isParent) $preTask->isParent = 0;

        return $taskGroup;
    }

    /**
     * 根据给出需求，查询并合并子需求，返回新的需求列表。
     * Get with children stories by given stories.
     *
     * @param  array  $allStories
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function mergeChildrenForTrack(array $allStories, array $stories, string $storyType): array
    {
        $newStories  = array();
        $parentGroup = array();
        foreach($allStories as $storyID => $story) $parentGroup[$story->parent][$storyID] = $story;

        $appendChildren = function($parentID) use($parentGroup, &$newStories, &$appendChildren)
        {
            if(!isset($parentGroup[$parentID])) return;
            foreach($parentGroup[$parentID] as $storyID => $story)
            {
                $story->parent = explode(',', trim(str_replace(",{$storyID},", ',', $story->path), ','));
                $newStories[$storyID] = $story;
                $appendChildren($storyID);
            }
        };

        foreach($stories as $storyID => $story)
        {
            if(isset($newStories[$storyID])) continue;
            if($storyType == 'requirement' && $story->type == 'epic') continue;
            if($storyType == 'story' && ($story->type == 'epic' || $story->type == 'requirement')) continue;

            $story = zget($allStories, $storyID, $story);
            $story->parent = explode(',', trim(str_replace(",{$storyID},", ',', $story->path), ','));
            $newStories[$storyID] = $story;
            $appendChildren($storyID);
        }
        return $newStories;
    }

    /**
     * 根据父子关系重新对需求排序。
     * Build track data by stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function reorderStories(array $stories): array
    {
        $tree = $this->buildStoryTree($stories, 0, $stories);

        $result = array();
        $this->buildReorderResult($tree, $result);

        /* 重排后数量如果和重排之前不一致，说明该列表只有子任务、没有父任务，使用重排之前的顺序。*/
        return count($stories) == count($result) ? $result : array_keys($stories);
    }

    /**
     * 递归将一维需求数组构造成父子关系的嵌套数组。
     * Build story tree by parent and children.
     *
     * @param  array  $stories
     * @param  int    $parentId
     * @param  array  $originStories
     * @return array
     */
    public function buildStoryTree(array &$stories, int $parentId = 0, array $originStories = array())
    {
        $tree = array();
        foreach($stories as $id => $parent)
        {
            if($parent == $parentId || (!isset($originStories[$parent]) && $parentId == 0))
            {
                $tree[$id] = $this->buildStoryTree($stories, $id, $originStories);
            }
        }

        return $tree;
    }

     /**
     * 递归将父子关系的嵌套数组构造成一维需求数组。
     * Build story result by parent and children.
     *
     * @param  array  $parent
     * @param  array  $result
     * @access public
     */
    public function buildReorderResult(array $parent, array &$result)
    {
        foreach($parent as $key => $children)
        {
            $result[$key] = $key;
            if(!empty($children)) $this->buildReorderResult($children, $result);
        }
    }
}
