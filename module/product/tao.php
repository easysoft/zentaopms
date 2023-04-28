<?php
declare(strict_types=1);
/**
 * The model file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     product
 * @link        http://www.zentao.net
 */

class productTao extends productModel
{
    /**
     * 从数据表获取符合条件的id=>name的键值对。
     * Fetch pairs like id=>name.
     *
     * @param string       $mode     all|noclosed
     * @param int          $programID
     * @param string|array $append
     * @param string|int   $shadow    all|0|1
     * @access protected
     * @return int[]
     */
    protected function fetchPairs(string $mode = '', int $programID = 0, string|array $append = '', string|int $shadow = 0): array
    {
        $append = $this->formatAppendParam($append);
        return $this->dao->select("t1.*, IF(INSTR(' closed', t1.status) < 2, 0, 1) AS isClosed")->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.vision')->eq($this->config->vision)
            ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
            ->andWhere('((1=1')
            ->beginIF(strpos($mode, 'all') === false)->andWhere('t1.deleted')->eq(0)->fi()
            ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin and $this->config->vision == 'rnd')->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->markRight(1)
            ->beginIF($append)->orWhere('(t1.id')->in($append)->markRight(1)->fi()
            ->markRight(1)
            ->orderBy("isClosed,t2.order_asc,t1.line_desc,t1.order_asc")
            ->fetchPairs('id', 'name');
    }

    /**
     * 获取产品ID数组中带有项目集信息的产品分页列表。
     * Get products with program data that in the ID list.
     *
     * @param  array     $productIDs
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getPagerProductsWithProgramIn(array $productIDs, object|null $pager) :array
    {
        $products = $this->dao->select('t1.*')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.id')->in($productIDs)
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->page($pager)
            ->fetchAll('id');

        return $products;
    }

    /**
     * 获取产品ID数组中的产品排序分页列表。
     * Get products in the ID list.
     *
     * @param  array     $productIDs
     * @param  object    $pager
     * @param  string    $orderBy
     * @access protected
     * @return array
     */
    protected function getPagerProductsIn(array $productIDs, object|null $pager, string $orderBy)
    {
        /* TODO list all fields? */
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('id')->in($productIDs)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $products;
    }

    /**
     * 获取产品列表。
     * Get products list.
     *
     * @param  int        $programID
     * @param  string     $status
     * @param  int        $limit
     * @param  int        $line
     * @param  string|int $shadow    all | 0 | 1
     * @access public
     * @return array
     */
    protected function getList(int $programID = 0, string $status = 'all', int $limit = 0, int $line = 0, string|int $shadow = 0)
    {
        $products = $this->dao->select('DISTINCT t1.*,t2.order')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t3.product = t1.id')
            ->leftJoin(TABLE_TEAM)->alias('t4')->on("t4.root = t3.project and t4.type='project'")
            ->where('t1.deleted')->eq(0)
            ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
            ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
            ->beginIF($line > 0)->andWhere('t1.line')->eq($line)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($status == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!in_array($status, array('all', 'noclosed', 'involved', 'review'), true))->andWhere('t1.status')->in($status)->fi()
            ->beginIF($status == 'involved')
            ->andWhere('t1.PO', true)->eq($this->app->user->account)
            ->orWhere('t1.QD')->eq($this->app->user->account)
            ->orWhere('t1.RD')->eq($this->app->user->account)
            ->orWhere('t1.createdBy')->eq($this->app->user->account)
            ->orWhere('t4.account')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->beginIF($status == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll('id');

        return $products;
    }

    /* TODO move to story module. */
    protected function getStoriesTODO(array $productIDs): array
    {
        $stories = $this->dao->select('product, status, count(status) AS count')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('story')
            ->andWhere('product')->in($productIDs)
            ->groupBy('product, status')
            ->fetchGroup('product', 'status');

        return $stories;
    }

    /* TODO move to story module. */
    protected function getRequirementsTODO(array $productIDs): array
    {
        $requirements = $this->dao->select('product, status, count(status) AS count')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('requirement')
            ->andWhere('product')->in($productIDs)
            ->groupBy('product, status')
            ->fetchGroup('product', 'status');

        return $requirements;
    }

    /* TODO move to story module. */
    protected function getFinishClosedStoryTODO(): array
    {
        $finishClosedStory = $this->dao->select('product, count(1) as finish')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('closed')
            ->andWhere('type')->eq('story')
            ->andWhere('closedReason')->eq('done')
            ->groupBy('product')
            ->fetchPairs();

        return $finishClosedStory;
    }

    /* TODO move to story module. */
    protected function getUnClosedStoryTODO(): array
    {
        $unclosedStory = $this->dao->select('product, count(1) as unclosed')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('story')
            ->andWhere('status')->ne('closed')
            ->groupBy('product')
            ->fetchPairs();

        return $unclosedStory;
    }

    /* TODO move to productplan module. */
    protected function getPlansTODO(array $productIDs): array
    {
        $plans = $this->dao->select('product, count(*) AS count')
            ->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIDs)
            ->andWhere('end')->gt(helper::now())
            ->groupBy('product')
            ->fetchPairs();

        return $plans;
    }

    /* TODO move to release module. */
    protected function getReleasesTODO(array $productIDs): array
    {
        $releases = $this->dao->select('product, count(*) AS count')
            ->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIDs)
            ->groupBy('product')
            ->fetchPairs();

        return $releases;
    }

    /* TODO move to bug module. */
    protected function getBugsTODO(array $productIDs): array
    {
        $bugs = $this->dao->select('product,count(*) AS conut')
            ->from(TABLE_BUG)
            ->where('product')->in($productIDs)
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchPairs();

        return $bugs;
    }

    /* TODO move to bug module. */
    protected function getUnResolvedTODO(array $productIDs): array
    {
        $unResolved = $this->dao->select('product,count(*) AS count')
            ->from(TABLE_BUG)
            ->where('status')->eq('active')
            ->orWhere('resolution')->eq('postponed')
            ->andWhere('product')->in($productIDs)
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchPairs();

        return $unResolved;
    }

    /* TODO move to bug module. */
    protected function getFixedBugsTODO(array $productIDs): array
    {
        $fixedBugs = $this->dao->select('product,count(*) AS count')
            ->from(TABLE_BUG)
            ->where('status')->eq('closed')
            ->andWhere('product')->in($productIDs)
            ->andWhere('deleted')->eq(0)
            ->andWhere('resolution')->eq('fixed')
            ->groupBy('product')
            ->fetchPairs();

        return $fixedBugs;
    }

    /* TODO move to bug module. */
    protected function getClosedBugsTODO(array $productIDs): array
    {
        $closedBugs = $this->dao->select('product,count(*) AS count')
            ->from(TABLE_BUG)
            ->where('status')->eq('closed')
            ->andWhere('product')->in($productIDs)
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchPairs();

        return $closedBugs;
    }
    /* TODO move to bug module. */
    protected function getThisWeekBugsTODO(array $productIDs): array
    {
        $this->app->loadClass('date', true);
        $weekDate     = date::getThisWeek();
        $thisWeekBugs = $this->dao->select('product,count(*) AS count')
            ->from(TABLE_BUG)
            ->where('openedDate')->between($weekDate['begin'], $weekDate['end'])
            ->andWhere('product')->in($productIDs)
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchPairs();

        return $thisWeekBugs;
    }

    /* TODO move to bug module. */
    protected function getAssignToNullTODO(array $productIDs): array
    {
        $assignToNull = $this->dao->select('product,count(*) AS count')
            ->from(TABLE_BUG)
            ->where('assignedTo')->eq('')
            ->andWhere('product')->in($productIDs)
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchPairs();

        return $assignToNull;
    }

    /* TODO move to program module. */
    protected function getProgramsInTODO(array $programIDs): array
    {
        $programs = $this->dao->select('id,name,PM')->from(TABLE_PROGRAM)
            ->where('id')->in($programIDs)
            ->fetchAll('id');

        return $programs;
    }

    /**
     * 获取用于统计的产品列表。
     * Get products list for statistic.
     *
     * @param  int[]     $roductIDs
     * @param  int       $programID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getStatsProducts(array $productIDs, int $programID, string $orderBy, object $pager): array
    {
        if($orderBy == static::OB_PROGRAM) $products = $this->getPagerProductsWithProgramIn($productIDs, $pager);
        else $products = $this->getPagerProductsIn($productIDs, $pager, $orderBy);

        /* Fetch product lines. */
        $linePairs = $this->getLinePairs();
        foreach($products as $product) $product->lineName = zget($linePairs, $product->line, '');

        if(!empty($programID)) return $products;

        $programKeys = array(0 => 0);
        foreach($products as $product) $programKeys[] = $product->program;
        $programs = $this->getProgramsInTODO(array_unique($programKeys));

        foreach($products as $product)
        {
            $product->programName = isset($programs[$product->program]) ? $programs[$product->program]->name : '';
            $product->programPM   = isset($programs[$product->program]) ? $programs[$product->program]->PM : '';
        }

        return $products;
    }

    /**
     * 格式化append参数，保证输出用逗号间隔的id列表。
     * Format append param.
     *
     * @param string|array append
     * @access protected
     * @return string
     */
    protected function formatAppendParam(string|array $append = ''): string
    {
        if(empty($append)) return '';

        if(is_string($append)) $append = explode(',', $append);

        $append = array_map(function($item){return (int)$item;}, $append);
        $append = array_unique(array_filter($append));
        sort($append);

        return implode(',', $append);
    }

    /**
     * 获取用于数据统计的研发需求和用户需求列表。
     * Get dev stories and user requirements for statistics.
     *
     * @param  array     $productIDs
     * @param  string    $storyType
     * @access protected
     * @return [array, array]
     */
    protected function getStatsStoriesAndRequirements(array $productIDs, string $storyType): array
    {
        $stories      = $this->getStoriesTODO($productIDs);
        $requirements = $this->getRequirementsTODO($productIDs);

        /* Padding the stories to sure all products have records. */
        $emptyStory = array_keys($this->lang->story->statusList);
        foreach($productIDs as $productID)
        {
            if(!isset($stories[$productID]))      $stories[$productID]      = $emptyStory;
            if(!isset($requirements[$productID])) $requirements[$productID] = $emptyStory;
        }

        /* Collect count for each status of stories. */
        foreach($stories as $key => $story)
        {
            foreach(array_keys($this->lang->story->statusList) as $status) $story[$status] = isset($story[$status]) ? $story[$status]->count : 0;
            $stories[$key] = $story;
        }

        /* Collect count for each status of requirements. */
        foreach($requirements as $key => $requirement)
        {
            foreach(array_keys($this->lang->story->statusList) as $status) $requirement[$status] = isset($requirement[$status]) ? $requirement[$status]->count : 0;
            $requirements[$key] = $requirement;
        }

        /* Story type is 'requirement'. */
        if($storyType == static::STORY_TYPE_REQ) $stories = $requirements;

        return [$stories, $requirements];
    }

    /**
     * 创建产品线。
     * Create product line.
     *
     * @param int    programID
     * @param string lineName
     * @access public
     * @return int|false
     */
    public function createLine(int $programID, string $lineName): int|false
    {
        if($programID <= 0) return false;
        if(empty($lineName)) return false;

        $line = new stdClass();
        $line->type   = 'line';
        $line->parent = 0;
        $line->grade  = 1;
        $line->name   = htmlSpecialString($lineName);
        $line->root   = $programID;

        $existedLineID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('root')->eq($line->root)->andWhere('name')->eq($line->name)->fetch('id');
        if($existedLineID) return $existedLineID;

        $this->dao->insert(TABLE_MODULE)->data($line)->exec();
        if(dao::isError()) return false;

        $lineID = $this->dao->lastInsertID();
        $path   = ",$lineID,";
        $this->dao->update(TABLE_MODULE)->set('path')->eq($path)->set('`order`')->eq($lineID)->where('id')->eq($lineID)->exec();

        return $lineID;
    }

    /**
     * 关联创建产品主库
     * Create main lib for product
     *
     * @param int productID
     * @access public
     * @return int|false
     */
    public function createMainLib(int $productID): int|false
    {
        if($productID <= 0) return false;

        $existedLibID = $this->dao->select('id')->from(TABLE_DOCLIB)->where('product')->eq($productID)
            ->andWhere('type')->eq('product')
            ->andWhere('main')->eq('1')
            ->fetch('id');
        if($existedLibID) return $existedLibID;

        $this->app->loadLang('doc');
        $lib = new stdclass();
        $lib->product   = $productID;
        $lib->name      = $this->lang->doclib->main['product'];
        $lib->type      = 'product';
        $lib->main      = '1';
        $lib->acl       = 'default';
        $lib->addedBy   = $this->app->user->account;
        $lib->addedDate = helper::now();
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

        if(dao::isError())return false;
        return $this->dao->lastInsertID();
    }
}
