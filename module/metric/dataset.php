<?php
class dataset
{
    /**
     * Database connection.
     *
     * @var object
     * @access public
     */
    public $dao;

    /**
     * __construct.
     *
     * @param  DAO    $dao
     * @access public
     * @return void
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取所有的项目集。
     * Get all program list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getPrograms($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_PROJECT)->alias('t1')
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq('0')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取所有的顶级项目集。
     * Get top program list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getTopPrograms($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_PROJECT)->alias('t1')
            ->where('type')->eq('program')
            ->andWhere('grade')->eq('1')
            ->andWhere('deleted')->eq('0')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * Get all projects.
     * 获取所有项目。
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllProjects($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_PROJECT)->alias('t1')
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('project')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取执行数据。
     * Get executions.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getExecutions($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t2.type')->eq('project')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取发布数据。
     * Get release list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getReleases($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on("CONCAT(',', t2.id, ',') LIKE CONCAT('%', t1.project, '%')")
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)", true)
            ->orWhere("t2.vision IS NULL")->markRight(1)
            ->andWhere("NOT FIND_IN_SET('lite', t2.vision)", true)
            ->orWhere("t2.vision IS NULL")->markRight(1)
            ->query();
    }

    /**
     * 按产品获取发布数据。
     * Get release list according to product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getProductReleases($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->query();
    }

    /**
     * 获取产品计划数据。
     * Get plan list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getPlans($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->query();
    }

    /**
     * 获取bug数据。
     * Get bug list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getBugs($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->query();
    }

    /**
     * 获取项目bug数据。
     * Get project bug list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getProjectBugs($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t3.vision)")
            ->query();
    }

    /**
     * 获取反馈数据。
     * Get feedback list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getFeedbacks($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->query();
    }

    /**
     * 获取需求数据。
     * Get story list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getStories($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取执行的研发需求数据。
     * Get story list, with execution and type is story.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDevStoriesWithExecution($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t4.project=t5.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t4.deleted')->eq(0) // 已删除的执行
            ->andWhere('t4.type')->in('sprint,stage,kanban')
            ->andWhere('t5.deleted')->eq(0) // 已删除的项目
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取项目的研发需求数据。
     * Get story list, with project and type is story.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDevStoriesWithProject($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t4.type')->eq('project')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取研发需求数据，过滤影子产品。
     * Get story list, filter shadow product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDevStories($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取所有研发需求数据，不过滤影子产品。
     * Get all story list, don't filter shadow product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllDevStories($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取已交付的需求数据。
     * Get delivered story list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDeliveredStories($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->andWhere('t1.stage', true)->eq('released')
            ->orWhere('t1.closedReason')->eq('done')
            ->markRight(1)
            ->groupBy('t1.product')
            ->query();
    }

    /**
     * 获取用例数据。
     * Get case list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getCases($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t2.vision)")
            ->query();
    }

    /**
     * 获取产品数据。
     * Get product list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getProducts($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_PRODUCT)->alias('t1')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取项目数据。
     * Get all tasks.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getTasks($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on('t1.id=t4.task')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取产品线数据。
     * Get product lines.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getLines($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('line')
            ->andWhere('t2.type')->eq('program')
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t2.vision)")
            ->query();
    }

    /**
     * 获取用户数据。
     * Get users.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getUsers($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_USER)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->query();
    }

    /**
     * 获取文档数据。
     * Get docs.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDocs($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_DOC)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    /**
     * 获取风险数据。
     * Get risks.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getRisks($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_RISK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project')
            ->query();
    }

    /**
     * 获取问题数据。
     * Get issues.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getIssues($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_ISSUE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project')
            ->query();
    }

    /**
     * 获取制品库数量。
     * Get artifact repo count.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getArtifactRepos($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_ARTIFACTREPO)
            ->where('deleted')->eq('0')
            ->query();
    }

    /**
     * 获取代码库数量。
     * Get repos count.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getRepos($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->query();
    }

    /**
     * 获取上线计划数据。
     * Get deployment data.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDeployment($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_DEPLOY)->alias('t1')
            ->leftJoin(TABLE_DEPLOYPRODUCT)->alias('t2')->on('t1.id=t2.deploy')
            ->where('t1.deleted')->eq('0')
            ->query();
    }

    /**
     * 获取流水线数据。
     * Get pipeline.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getPipeline($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_JOB)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->query();
    }

    /**
     * 统计代码库问题信息。
     * Get repo issues.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getRepoIssues($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo = t2.id')
            ->where('t1.repo')->gt(0)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->query();
    }

    /**
     * 统计执行节点信息。
     * Get za nodes.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getZaNodes($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_ZAHOST)->alias('t1')
            ->leftJoin(TABLE_IMAGE)->alias('t2')->on('t2.id = t1.image')
            ->where('t1.deleted')->eq(0)
            ->andWhere("t1.type = 'node'")
            ->query();
    }

    public function getMRs($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_MR)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.hostID = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->query();
    }
}
