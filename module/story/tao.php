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
     * 获取产品或项目关联的用户需求跟踪矩阵。
     * Get requirements track.
     *
     * @param  int         $productID
     * @param  string|int  $branch
     * @param  int         $projectID
     * @param  object|null $pager
     * @access protected
     * @return int[]
     */
    protected function getRequirements4Track(int $productID, string|int $branch, int $projectID, object|null $pager = null): array
    {
        if(empty($this->config->URAndSR)) return array();

        /* 获取关联产品或项目的用户需求。 */
        $rawPageID = $pager->pageID;
        if(empty($projectID))  $requirements = $this->getProductStories($productID, $branch, 0, 'all', 'requirement', 'id_desc', true, '', $pager);
        if(!empty($projectID)) $requirements = $this->getProjectRequirements($productID, $project, $pager);

        /* 如果页码发生变化，说明查出的用户需求还是上一页的数据。当前页没有用户需求数据。 */
        if($pager->pageID != $rawPageID)
        {
            $pager->pageID = $rawPageID;
            return array();
        }

        /* 获取关联项目的研发需求。*/
        $projectStories = array();
        if($projectID) $projectStories = $this->getExecutionStories($projectID, $productID, $branch, '`order`_desc', 'all', 0, 'story');

        /* 获取用户需求细分的研发需求。 */
        $requirementStories = $this->batchGetRelations(array_keys($requirements), 'requirement', array('id', 'title', 'parent'));

        /* 根据用户需求，构造跟踪矩阵信息。*/
        foreach($requirements as $requirement)
        {
            $stories      = zget($requirementStories, $requirement->id, array());
            $trackStories = array();
            foreach($stories as $id => $story)
            {
                if($projectStories and !isset($projectStories[$id])) continue;
                $trackStories[$id] = $this->buildStoryTrack($story, $projectID);
            }

            $requirement->track = $trackStories;
        }

        return $requirements;
    }

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
     * 获取用户需求细分的研发需求，或者研发需求关联的用户需求。
     * Get associated requirements.
     *
     * @param  int     $storyID
     * @param  string  $storyType
     * @param  array   $fields
     * @access public
     * @return array
     */
    public function getRelation($storyID, $storyType, $fields = array()): array
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
}
