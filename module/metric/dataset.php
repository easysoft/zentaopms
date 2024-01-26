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
    public function __construct($dao, $config, $vision = 'rnd')
    {
        $this->dao = $dao;
        $this->config = $config;
        $this->vision = $vision;
    }

    public function defaultWhere($query, $table)
    {
        return $query->andWhere("{$table}.vision LIKE '%{$this->vision}%'", true)
            ->orWhere("{$table}.vision IS NULL")->markRight(1);
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
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PROJECT)->alias('t1')
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PROJECT)->alias('t1')
            ->where('type')->eq('program')
            ->andWhere('grade')->eq('1')
            ->andWhere('deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_PROJECT)->alias('t1')
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('project');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t2.type')->eq('project');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $dbType = $this->config->metricDB->type;
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->beginIF($dbType == 'mysql')->leftJoin(TABLE_PROJECT)->alias('t2')->on("CONCAT(',', t2.id, ',') LIKE CONCAT('%', t1.project, '%')")->fi()
            ->beginIF($dbType == 'sqlite')->leftJoin(TABLE_PROJECT)->alias('t2')->on("(',' || t2.id || ',') LIKE ('%' || t1.project || '%')")->fi()
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
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
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /*
    public function getAllReleases($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->query();
    }
    */

    /**
     * 获取产品计划数据。
     * Get plan list, without shadow product's data.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getPlans($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /**
     * 获取产品计划数据，包括项目型项目的计划。
     * Get plan list include shadow product data.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllPlans($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /**
     * 获取bug数据。
     * Get product bug list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getBugs($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /**
     * 获取所有bug数据。
     * Get all bug list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllBugs($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't3')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /**
     * 获取所有反馈数据。
     * Get all feedback list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllFeedbacks($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)
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
            ->andWhere('t5.deleted')->eq(0); // 已删除的项目

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t4.type')->eq('project');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $caseQuery = $this->dao->select('story, count(DISTINCT id) as case_count')
            ->from(TABLE_CASE)
            ->groupBy('story')
            ->get();

        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin("($caseQuery)")->alias('t3')->on('t1.id=t3.story')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt =  $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.stage', true)->eq('released')
            ->orWhere('t1.closedReason')->eq('done')
            ->markRight(1);

        return $this->defaultWhere($stmt, 't1')->groupBy('t1.product')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /**
     * 获取所有用例数据。
     * Get all case list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllCases($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2')->query();
    }

    /**
     * 获取关联用例的研发需求数据。
     * Get story list with case.
     *
     * @param  string  $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getCasesWithStory($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_CASE)->alias('t0')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t1')->on('t1.story=t0.story')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't2')->query();
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
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PRODUCT)->alias('t1')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on('t1.id=t4.task')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('line')
            ->andWhere('t2.type')->eq('program');

        return $this->defaultWhere($stmt, 't2')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_DOC)->alias('t1')
            ->where('t1.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_RISK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project');

        return $this->defaultWhere($stmt, 't2')->query();
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_ISSUE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project');

        return $this->defaultWhere($stmt, 't2')->query();
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

    /**
     * 统计合并请求信息。
     * Get merge requests.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getMRs($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_MR)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.hostID = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->query();
    }

    /**
     * 统计团队成员信息。
     * Get team members.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getTeamMembers($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t2.id=t1.root')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id=t2.project')
            ->where('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't3')->query();
    }

    /**
     * 统计日志信息。
     * Get effort.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getEfforts($fieldList)
    {
        $defaultHours = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('defaultWorkhours')
            ->fetch('value');
        if(empty($defaultHours)) $defaultHours = 7;

        return $this->dao->select("$fieldList, $defaultHours as defaultHours")
            ->from(TABLE_EFFORT)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->query();
    }

    /**
     * 统计项目日志信息。
     * Get project effort.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getProjectEfforts($fieldList)
    {
        $defaultHours = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('defaultWorkhours')
            ->fetch('value');
        if(empty($defaultHours)) $defaultHours = 7;

        $stmt = $this->dao->select("$fieldList, $defaultHours as defaultHours")
            ->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t3.deleted')->eq('0')
            ->andWhere('t3.type')->eq('project');

        return $this->defaultWhere($stmt, 't3')->query();
    }

    /**
     * 统计瀑布项目任务信息。
     * Get waterfall tasks.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getWaterfallTasks($fieldList)
    {
        $task = $this->dao->select('t1.project, SUM(t1.estimate) as estimate, SUM(t1.consumed) as consumed, SUM(t1.`left`) as `left`')
            ->from(TABLE_TASK)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->ne('-1')
            ->andWhere('t1.status', true)->in('done,closed')
            ->orWhere('t1.closedReason')->eq('done')
            ->markRight(1);

        $task = $this->defaultWhere($task, 't1')->groupBy('t1.project')->get();

        $effort = $this->dao->select('t3.id as project, SUM(t1.consumed) as consumed')
            ->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('1=1');

        $effort = $this->defaultWhere($effort, 't3')->groupBy('t3.id')->get();

        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin("($task)")->alias('t2')->on('t1.id=t2.project')
            ->leftJoin("($effort)")->alias('t3')->on('t1.id=t3.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t1.model')->in('waterfall,waterfallplus');

        return $this->defaultWhere($stmt, 't1')->query();
    }

    /**
     * 统计测试用例结果信息。
     * Get test results.
     *
     * @param  string  $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getTestresults($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.`case`=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product=t3.id')
            ->where('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't3')->query();
    }

    /**
     * 统计研发需求评审信息。
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDevStoriesWithReview($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORYREVIEW)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product=t3.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.type')->eq('story');

        return $this->defaultWhere($stmt, 't3')->query();
    }

    public function getProjectTasks($fieldList)
    {
        $defaultHours = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('defaultWorkhours')
            ->fetch('value');
        if(empty($defaultHours)) $defaultHours = 7;

        $task = $this->dao->select('SUM(t1.consumed) as consumed, t1.project')
            ->from(TABLE_TASK)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->ne('-1');

        $task = $this->defaultWhere($task, 't1')->groupBy('t1.project')->get();

        $stmt =  $this->dao->select("$fieldList, $defaultHours as defaultHours")
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin("($task)")->alias('t2')->on('t1.id = t2.project')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1')->query();
    }

    public function getTestRuns($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.`case` = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->where('t3.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->query();
    }
}
