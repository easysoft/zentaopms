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
    protected function getStoriesTODO( array $productIDs): array
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
    protected function getRequirementsTODO( array $productIDs): array
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
    protected function getPlansTODO( array $productIDs): array
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
    protected function getReleasesTODO( array $productIDs): array
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
    protected function getBugsTODO( array $productIDs): array
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
    protected function getUnResolvedTODO( array $productIDs): array
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
    protected function getFixedBugsTODO( array $productIDs): array
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
    protected function getClosedBugsTODO( array $productIDs): array
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

}
