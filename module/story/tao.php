<?php
declare(strict_types=1);
/**
 * The model file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      wangyidong<wangyidong@cnezsoft.com>
 * @package     story
 * @link        http://www.zentao.net
 */
class storyTao extends storyModel
{
    /**
     * 获取项目研发需求关联的用户需求。
     * Get project requirements.
     *
     * @param  int $productID
     * @param  int $projectID
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function getProjectRequirements(int $productID, int $projectID, object|null $pager = null): array
    {
        return $this->dao->select('t3.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_RELATION)->alias('t2')->on("t1.story=t2.AID && t2.AType='story'")
            ->leftJoin(TABLE_STORY)->alias('t3')->on("t2.BID=t3.id && t2.BType='requirement' && t3.deleted='0'")
            ->where('t1.project')->eq($projectID)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t3.id')->ne('')
            ->page($pager, 't3.id')
            ->fetchAll('id');
    }

    /**
     * 获取产品下细分的研发需求。
     * Get subdivided stories by product
     *
     * @param  int       $productID
     * @access protected
     * @return array
     */
    protected function getSubdividedStoriesByProduct(int $productID): array
    {
        if(empty($this->config->URAndSR)) return array();
        return $this->dao->select('t1.BID')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on("t1.AID=t2.id")
            ->where('t2.deleted')->eq('0')
            ->andWhere('t1.AType')->eq('requirement')
            ->andWhere('t1.BType')->eq('story')
            ->andWhere('t1.relation')->eq('subdivideinto')
            ->andWhere('t1.product')->eq($productID)
            ->fetchPairs('BID', 'BID');
    }

    /**
     * 获取用户需求细分的研发需求，或者研发需求关联的用户需求。
     * Get associated requirements.
     *
     * @param  int     $storyID
     * @param  string  $storyType
     * @param  array   $fields
     * @access public
     * @return array
     */
    public function getRelation(int $storyID, string $storyType, array $fields = array()): array
    {
        /* 初始化查询条件变量。*/
        $BType       = $storyType == 'story' ? 'requirement' : 'story';
        $relation    = $storyType == 'story' ? 'subdividedfrom' : 'subdivideinto';
        $queryFields = empty($fields) ? 'id,title' : implode(',', $fields);

        /* 获取对应的关联数据。*/
        $relations = $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('AType')->eq($storyType)
            ->andWhere('BType')->eq($BType)
            ->andWhere('relation')->eq($relation)
            ->andWhere('AID')->eq($storyID)
            ->fetchPairs();

        if(empty($relations)) return array();

        /* 根据关联数据查询详细信息。 */
        $query = $this->dao->select($queryFields)->from(TABLE_STORY)->where('id')->in($relations)->andWhere('deleted')->eq(0);
        if(!empty($fields)) return $query ->fetchAll('id');
        return $query->fetchPairs();
    }

    /**
     * 批量获取用户需求细分的研发需求，或者研发需求关联的用户需求。
     * Batch get relations.
     *
     * @param  array     $storyIdList
     * @param  string    $storyType
     * @param  array     $fields
     * @access protected
     * @return array
     */
    protected function batchGetRelations(array $storyIdList, string $storyType, array $fields = array()): array
    {
        if(empty($storyIdList)) return array();

        /* 初始化查询条件变量。*/
        $BType       = $storyType == 'story' ? 'requirement' : 'story';
        $relation    = $storyType == 'story' ? 'subdividedfrom' : 'subdivideinto';
        $queryFields = empty($fields) ? 'id,title' : implode(',', $fields);

        /* 获取对应的关联数据。*/
        $relations = $this->dao->select('AID,BID')->from(TABLE_RELATION)
            ->where('AType')->eq($storyType)
            ->andWhere('BType')->eq($BType)
            ->andWhere('relation')->eq($relation)
            ->andWhere('AID')->in($storyIdList)
            ->fetchAll();

        if(empty($relations)) return array();

        /* 获取BID列表。*/
        $storyIdList = array();
        foreach($relations as $relation) $storyIdList[$relation->BID] = $relation->BID;

        /* 根据关联数据查询详细信息。 */
        $query   = $this->dao->select($queryFields)->from(TABLE_STORY)->where('id')->in($storyIdList)->andWhere('deleted')->eq(0);
        $stories = empty($fields) ? $query->fetchPairs() : $query ->fetchAll('id');

        /* 将查询的信息合并到关联分组中。 */
        $relationGroup = array();
        foreach($relations as $relation) $relationGroup[$relation->AID][$relation->BID] = zget($stories, $relation->BID, null);

        return $relationGroup;
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
     * 获取产品所有状态对应的需求总数。
     * Get stories count of each status by product ID.
     *
     * @param  int       $productID
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function getStoriesCountByProductID(int $productID, string $storyType = 'requirement'): array
    {
        return $this->dao->select('product, status, count(status) AS count')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq($storyType)
            ->andWhere('product')->eq($productID)
            ->groupBy('product, status')
            ->fetchAll('status');
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
        if($this->config->edition != 'max') return $track;

        /* 获取关联需求的设计、关联版本库提交。 */
        $track->designs   = $this->dao->select('id, name')->from(TABLE_DESIGN)->where('story')->eq($story->id)->andWhere('deleted')->eq('0')->fetchAll('id');
        $track->revisions = $this->dao->select('BID, t2.comment')->from(TABLE_RELATION)->alias('t1')
            ->leftjoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.BID = t2.id')
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
     * @param  string|int       $productIdList
     * @param  array|string|int $branch
     * @access protected
     * @return string
     */
    protected function buildProductsCondition(string|int $productIdList, array|string|int $branch = 'all'): string
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
        $productQuery = "(`product` " . helper::dbIN($branchProducts) . " AND `branch` " . helper::dbIN($branch) . ')';
        if(!empty($normalProducts)) $productQuery .= ' OR `product` ' . helper::dbIN($normalProducts);
        return "({$productQuery}) ";
    }

    /**
     * 将需求列表中的子需求合并到列表中的父需求下。
     * Merge children to parent.
     *
     * @param  array     $stories
     * @param  string    $type
     * @access protected
     * @return int[]
     */
    protected function mergeChildren(array $stories, string $type = 'story'): array
    {
        /* For requirement children. */
        $relationGroups = array();
        if($type == 'requirement') $relationGroups = $this->batchGetRelations(array_keys($stories), $type, array('*'));

        foreach($stories as $storyID => $story)
        {
            /* Merge to parent and unset in list. */
            if($story->parent > 0 and isset($stories[$story->parent]))
            {
                $stories[$story->parent]->children[$story->id] = $story;
                unset($stories[$storyID]);
            }

            /* Merge subdivided stories for requirement. */
            if(!empty($relationGroups[$story->id]))
            {
                $story->children    = $relationGroups[$story->id];
                $story->linkStories = implode(',', array_column($story->children, 'title'));
            }
        }
        return $stories;
    }

    /**
     * 追加需求所属的计划标题和子需求。
     * Merge plan title and children.
     *
     * @param  int|array|string $productID
     * @param  array            $stories
     * @param  string           $type          story|requirement
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
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->Where('deleted')->eq(0)->andWhere('product')->in($productID)->fetchPairs('id', 'title');

        /* Get parent stories and children. */
        $stories = $this->mergeChildren($stories, $type);
        $parents = $this->extractParents($stories);
        if($parents)
        {
            $children = $this->dao->select('id,parent,title')->from(TABLE_STORY)->where('parent')->in($parents)->andWhere('deleted')->eq(0)->fetchGroup('parent', 'id');
            $parents  = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($parents)->andWhere('deleted')->eq(0)->fetchAll('id');
        }

        foreach($stories as $storyID => $story)
        {
            /* Export story linkstories. */
            if(isset($children[$story->id])) $story->linkStories = implode(',', array_column($children[$story->id], 'title'));

            /* Merge parent story title. */
            if($story->parent > 0 and isset($parents[$story->parent])) $story->parentName = $parents[$story->parent]->title;

            /* Merge plan title. */
            $story->planTitle = '';
            $storyPlans       = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';
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
            if($story->parent == '-1') return $story->id;
            if($story->parent > '0')   return $story->parent;
            return false;
        }, $stories);
        return array_values(array_unique(array_filter($parent)));
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
     * @param  array       $excludeStories
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function getExecutionStoriesBySearch(int $executionID, int $queryID, int $productID, string $orderBy, string $storyType = 'story', array $excludeStories = array(), object|null $pager = null): array
    {
        /* 获取查询条件。 */
        $rawModule = $this->app->rawModule;
        $this->loadModel('search')->setQuery($rawModule == 'projectstory' ? 'story' : 'executionStory', $queryID);
        if($this->session->executionStoryQuery == false) $this->session->set('executionStoryQuery', ' 1 = 1');
        if($rawModule == 'projectstory') $this->session->set('executionStoryQuery', $this->session->storyQuery);

        /* 处理查询条件。 */
        $storyQuery = $this->replaceAllProductQuery($this->session->executionStoryQuery);
        $storyQuery = $this->replaceRevertQuery($storyQuery, $productID);
        $storyQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $storyQuery);
        if(strpos($storyQuery, 'result') !== false) $storyQuery = str_replace('t2.`result`', 't4.`result`', $storyQuery);

        return $this->dao->select("distinct t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->beginIF(strpos($storyQuery, 'result') !== false)->leftJoin(TABLE_STORYREVIEW)->alias('t4')->on('t2.id = t4.story and t2.version = t4.version')->fi()
            ->where($storyQuery)
            ->andWhere('t1.project')->eq($executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.type')->eq($storyType)
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
     * 根据请求类型获取查询的模块。
     * Get modules for query execution stories.
     *
     * @param  string    $type   bymodule|allstory|unclosed
     * @param  string    $param
     * @access protected
     * @return array
     */
    protected function getModules4ExecutionStories(string $type, string $param): array
    {
        $moduleParam = ($type == 'bymodule'  and $param !== '') ? $param : $this->cookie->storyModuleParam;

        if(empty($moduleParam) and strpos('allstory,unclosed,bymodule', $type) === false) return array();
        return $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like("%,$moduleParam,%")->andWhere('type')->eq('story')->andWhere('deleted')->eq(0)->fetchPairs();
    }

    /**
     * 获取执行下关联的需求。
     * Fetch execution stories.
     *
     * @param  dao         $storyDAO
     * @param  int         $productID
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access protected
     * @return int[]
     */
    protected function fetchExecutionStories(dao $storyDAO, int $productID, string $orderBy, object|null $pager = null): array
    {
        $browseType     = $this->session->executionStoryBrowseType;
        $unclosedStatus = $this->getUnclosedStatusKeys();
        return $storyDAO->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($browseType and strpos('changing|', $browseType) !== false)->andWhere('t2.status')->in($unclosedStatus)->fi()
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
     * @access protected
     * @return int[]
     */
    protected function fetchProjectStories(dao $storyDAO, int $productID, string $type, string $branch, array $executionStoryIdList, string $orderBy, object|null $pager = null): array
    {
        $unclosedStatus = $this->getUnclosedStatusKeys();
        return $storyDAO->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
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
        $stageOrderList  = implode(',', array_keys($this->lang->story->stageList));
        $branchStoryList = $this->dao->select('t1.*,t2.branch as productBranch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.project = t2.project')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.story')->in(array_keys($stories))
            ->andWhere('t1.branch')->eq(BRANCH_MAIN)
            ->andWhere('t3.type')->ne('normal')
            ->fetchAll();

        /* 对需求做分组。 */
        $branches = array();
        foreach($branchStoryList as $story) $branches[$story->productBranch][$story->story] = $story->story;

        /* Take the earlier stage. */
        foreach($branches as $branchID => $storyIdList)
        {
            $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->andWhere('branch')->eq($branchID)->fetchPairs('story', 'stage');
            foreach($stages as $storyID => $stage)
            {
                if(strpos($stageOrderList, $stories[$storyID]->stage) > strpos($stageOrderList, $stage)) $stories[$storyID]->stage = $stage;
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
        $unclosedStatus = $this->lang->story->statusList;
        unset($unclosedStatus['closed']);
        return array_keys($unclosedStatus);
    }

    /**
     * 获取需求 ID 列表，这些需求是关联在项目下的执行。
     * Get id list of executions by product.
     *
     * @param  string    $type
     * @param  int       $projectID
     * @access protected
     * @return array
     */
    protected function getIdListOfExecutionsByProjectID(string $type, int $projectID): array
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
    protected function getStoriesByProductIdList(array $productIdList, string $storyType = ''): array
    {
        return $this->dao->select('id, product, parent')
            ->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->beginIF($storyType)->andWhere('type')->eq($storyType)->fi()
            ->andWhere('product')->in($productIdList)
            ->fetchAll();
    }
}
