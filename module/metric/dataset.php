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
     * Config.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * User vision.
     *
     * @var string
     * @access public
     */
    public $vision;

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

    /**
     * append where condition of vision.
     *
     * @param  PDOStatement $query
     * @param  string       $table
     * @access public
     * @return PDOStatement
     */
    public function defaultWhere($query, $table)
    {
        $visions = explode(',', $this->vision);
        $wheres = array();
        foreach($visions as $vision) $wheres[] = "{$table}.vision LIKE '%{$vision}%'";
        $where = implode(' OR ', $wheres);

        return $query->andWhere($where, true)
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

        return $this->defaultWhere($stmt, 't1');
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

        return $this->defaultWhere($stmt, 't1');
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

        return $this->defaultWhere($stmt, 't1');
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
            ->andWhere('t1.multiple')->eq('1')
            ->andWhere('t2.type')->eq('project');

        return $this->defaultWhere($stmt, 't1');
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
            ->beginIF($dbType == 'mysql' || $dbType == 'duckdb')->leftJoin(TABLE_PROJECT)->alias('t2')->on("CONCAT(',', t2.id, ',') LIKE CONCAT('%,', t1.project, ',%')")->fi()
            ->beginIF($dbType == 'sqlite')->leftJoin(TABLE_PROJECT)->alias('t2')->on("(',' || t2.id || ',') LIKE ('%' || t1.project || '%')")->fi()
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't2');
    }

    /**
     * 获取所有发布数据。
     * Get all release data.
     *
     * @param  string $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllReleases($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_RELEASE)->alias('t1')->where('deleted')->eq(0);
    }

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

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't2');
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
        $longlife = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('bug')
            ->andWhere('key')->eq('longlife')
            ->fetch('value');

        if(!$longlife) $longlife = 7;

        $stmt = $this->dao->select("$fieldList, $longlife as longlife")
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0);

        return $this->defaultWhere($stmt, 't2');
    }

    /**
     * 获取执行bug数据。
     * Get execution product bug list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getExecutionBugs($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.execution=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.project=t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t3.type')->in('sprint,stage,kanban')
            ->andWhere('t4.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't3');
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

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取所有需求数据，不区分类型。
     * Get all story list.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllStories($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't1');
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
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t4.type')->in('sprint,stage,kanban')
            ->andWhere('t5.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取执行的研发需求数据，以执行为主表。
     * Get execution story list, story type is story.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getExecutionDevStories($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.project')
            ->leftJoin(TABLE_STORY)->alias('t4')->on('t3.story=t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t1.project=t5.id')
            ->leftJoin(TABLE_ACTION)->alias('t6')->on('t3.story=t6.objectID')
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t6.objectType')->eq('story')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t4.type')->eq('story')
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t5.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取执行的所有需求数据。
     * Get story list, with execution story.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllStoriesWithExecution($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t4.project=t5.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0) // 已删除的执行
            ->andWhere('t4.type')->in('sprint,stage,kanban')
            ->andWhere('t5.deleted')->eq(0); // 已删除的项目

        return $this->defaultWhere($stmt, 't1');
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t4.deleted')->eq('0')
            ->andWhere('t1.isParent')->eq('0')
            ->andWhere('t4.type')->eq('project');

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取项目的业务需求数据。
     * Get epic list with project.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getEpicWithProject($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t1.type')->eq('epic')
            ->andWhere('t3.type')->eq('project');

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取项目的所有需求数据。
     * Get story list, with project story.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllStoriesWithProject($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t4.type')->eq('project');

        return $this->defaultWhere($stmt, 't1');
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
        if(strpos($fieldList, '`t3`.') === false)
        {
            $stmt = $this->dao->select($fieldList)
                ->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t1.type')->eq('story')
                ->andWhere('t1.isParent')->eq('0')
                ->andWhere('t2.shadow')->eq('0');

            return $this->defaultWhere($stmt, 't1');
        }

        $dbType = $this->config->metricDB->type;
        if($dbType == 'duckdb')
        {
            $table = "(SELECT `story`, COUNT(`id`) AS `case_count` FROM " . TABLE_CASE . " GROUP BY `story`)";
        }
        else
        {
            $table = 'tmp_case_getDevStories';
            $this->dao->exec("DROP TABLE IF EXISTS `{$table}`");
            $this->dao->exec("CREATE TABLE `{$table}` AS SELECT `story`, COUNT(`id`) AS `case_count` FROM " . TABLE_CASE . 'GROUP BY `story`');
            $this->dao->exec("CREATE INDEX `story` ON `{$table}` (`story`)");
        }

        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin("`{$table}`")->alias('t3')->on('t1.id=t3.story')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.isParent')->eq('0')
            ->andWhere('t2.shadow')->eq('0');

        return $this->defaultWhere($stmt, 't1');
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.isParent')->eq('0');

        return $this->defaultWhere($stmt, 't1');
    }

    public function getAllDevStoriesWithLinkBug($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_RELATION)->alias('t3')->on("t1.id = t3.AID and t3.AType = 'story' and t3.BType = 'bug'")
            ->leftJoin(TABLE_BUG)->alias('t4')->on("t3.BType = 'bug' and t3.BID = t4.id")
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.isParent')->eq('0');

        $stmt = $this->defaultWhere($stmt, 't1');
        return $stmt->groupBy('t1.id');
    }

    /**
     * 获取所有研发需求数据，包含父需求，不过滤影子产品。
     * Get all story list with parent, don't filter shadow product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllDevStoriesWithParent($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq('story');

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取所有业务需求数据，不过滤影子产品。
     * Get all epics, don't filter shadow product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllEpics($fieldList)
    {
        $stmt = $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('epic');

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取项目的用户需求数据。
     * Get requirement list, with project and type is requirement.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getRequirementsWithProject($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id=t3.story')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq('requirement')
            ->andWhere('t4.deleted')->eq('0')
            ->andWhere('t4.type')->eq('project');

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取所有用户需求数据，不过滤影子产品。
     * Get all requirements, don't filter shadow product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllRequirements($fieldList)
    {
        $stmt =  $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('requirement');

        return $this->defaultWhere($stmt, 't1');
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

        return $this->defaultWhere($stmt, 't1')->groupBy('t1.product');
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

        return $this->defaultWhere($stmt, 't2');
    }

    /**
     * 获取执行下的用例步骤数据。
     * Get case steps data with execution.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getExecutionCasesSteps($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.case = t2.case')
            ->leftJoin(TABLE_CASE)->alias('t3')->on('t2.case = t3.id and t1.version = t3.version')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t5.id = t2.project')
            ->leftJoin(TABLE_PROJECT)->alias('t6')->on('t6.id = t5.project')
            ->where('t3.deleted')->eq('0')
            ->andWhere('t5.deleted')->eq('0')
            ->andWhere('t6.deleted')->eq('0')
            ->andWhere('t5.type')->in('sprint,kanban,stage');

        return $this->defaultWhere($stmt, 't5');
    }

    /**
     * 获取执行下的用例数据。
     * Get case list under execution.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getExecutionCases($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t4.id = t1.project')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t5.id = t4.project')
            ->where('t4.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t5.deleted')->eq('0')
            ->andWhere('t4.type')->in('sprint,kanban,stage');

        return $this->defaultWhere($stmt, 't4');
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

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取任务数据，包括团队成员。
     * Get all tasks data with team.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getTasksWithTeam($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on("t1.id=t4.task and t1.mode='multi'")
            ->where('t2.type')->in('sprint,kanban,stage')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1');
    }

    /**
     * 获取任务数据。
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
            ->where('t2.type')->in('sprint,kanban,stage')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1');
    }

    public function getTasksWithBuildInfo($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.fromBug = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.execution=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project=t4.id')
            ->leftJoin(TABLE_BUILD)->alias('t5')->on('t5.execution=t3.id')
            ->where('t3.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t4.deleted')->eq('0')
            ->andWhere('t1.fromBug')->ne(0)
            ->andWhere('t1.isParent')->eq('0');

        return $this->defaultWhere($stmt, 't1');
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
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq('line');
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
            ->where('t1.deleted')->eq('0');
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

        return $this->defaultWhere($stmt, 't1');
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

        return $this->defaultWhere($stmt, 't2');
    }

    /**
     * 获取评审意见数据。
     * Get reviewissues.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getReviewissues($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_REVIEWISSUE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project');

        return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't2');
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
            ->where('deleted')->eq('0');
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
            ->where('deleted')->eq('0');
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
        return $this->dao->select($fieldList)->from(TABLE_DEPLOY)
            ->where('deleted')->eq('0');
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
            ->where('t1.deleted')->eq('0');
    }

    /**
     * 获取流水线执行数据。
     * Get compile.
     *
     * @param  string    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getCompile($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0');
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
            ->andWhere('t2.deleted')->eq('0');
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
            ->andWhere("t1.type = 'node'");
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
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repoID = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.isFlow')->eq('0');
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
            ->leftJoin(TABLE_USER)->alias('t4')->on('t1.account=t4.account')
            ->where('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0);

        return $this->defaultWhere($stmt, 't3');
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
            ->where('t1.deleted')->eq('0');
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
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project');

        return $this->defaultWhere($stmt, 't2');
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
        $stmt = $this->dao->select($fieldList)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project = t3.id')
            ->where('t1.isParent')->eq(0)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.status')->ne('cancel')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.type')->eq('project')
            ->andWhere('t3.model')->in('waterfall,waterfallplus');

        return $this->defaultWhere($stmt, 't3');
    }

    public function getWaterfallEfforts($fieldList)
    {
       $stmt = $this->dao->select($fieldList)->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.model')->eq('waterfall')
            ->andWhere('t2.type')->eq('project');

       return $this->defaultWhere($stmt, 't2');
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

        return $this->defaultWhere($stmt, 't3');
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

        return $this->defaultWhere($stmt, 't3');
    }

    public function getProjectTasks($fieldList)
    {
        $defaultHours = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('defaultWorkhours')
            ->fetch('value');
        if(empty($defaultHours)) $defaultHours = 7;

        if(strpos($fieldList, '`t2`.') === false)
        {
            $stmt = $this->dao->select("$fieldList, $defaultHours AS defaultHours")
                ->from(TABLE_PROJECT)->alias('t1')
                ->where('t1.type')->eq('project')
                ->andWhere('t1.deleted')->eq('0');

            return $this->defaultWhere($stmt, 't1');
        }

        $task = $this->dao->select('SUM(t1.consumed) AS consumed, t1.project')
            ->from(TABLE_TASK)->alias('t1')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.isParent')->eq('0');

        $query = $this->defaultWhere($task, 't1')->groupBy('t1.project')->get();

        $dbType = $this->config->metricDB->type;
        if($dbType == 'duckdb')
        {
            $table = "($query)";
        }
        else
        {
            $table = 'tmp_task_getProjectTasks';
            $this->dao->exec("DROP TABLE IF EXISTS `{$table}`");
            $this->dao->exec("CREATE TABLE `{$table}` AS {$query}");
            $this->dao->exec("CREATE INDEX `project` ON `{$table}` (`project`)");
        }

        $stmt = $this->dao->select("$fieldList, $defaultHours AS defaultHours")
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin($table)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't1');
    }

    public function getTestRuns($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.`case` = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->where('t3.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0);
    }

    /**
     * 获取工单数据。
     * Get Tickets.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllTickets($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_TICKET)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0');

        return $this->defaultWhere($stmt, 't2');
    }

    /**
     * 获取工单数据，过滤影子产品下的工单。
     * Get Tickets without shadow product.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getTickets($fieldList)
    {
        $stmt = $this->dao->select($fieldList)->from(TABLE_TICKET)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.shadow')->eq('0');

        return $this->defaultWhere($stmt, 't2');
    }

    /**
     * 获取需求池中的需求数据。
     * Get demands.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getDemands($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_DEMAND)
            ->where('deleted')->eq('0')
            ->andWhere("vision LIKE '%or%'");
    }

    /**
     * 获取质量保证计划和不符合项的编号和指派人。
     * Get id and assigned of auditplan and nc.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getQAs($fieldList)
    {
        $fieldList = 't1.id, t1.assignedTo, t2.vision';
        $auditplanTable = $this->config->objectTables['auditplan'];
        $ncTable        = $this->config->objectTables['nc'];
        $userTable      = $this->config->objectTables['user'];
        $projectTable   = $this->config->objectTables['project'];

        $auditplan = $this->dao->select($fieldList)
            ->from(TABLE_AUDITPLAN)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.status')->eq('wait')
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')->get();

        $nc = $this->dao->select($fieldList)
            ->from(TABLE_NC)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.status')->eq('active')
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')->get();

        $sql  = "$auditplan UNION ALL $nc";
        $stmt = $this->dao->select('*')->from("($sql) tt")->where('1=1');
        $stmt = $this->defaultWhere($stmt, 'tt');

        return $stmt;
    }

    /**
     * 获取代码库提交数量。
     * Get repo commits.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getRepoCommits($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_REPO)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t2.repo=t1.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t3')->on('t3.id=t1.serviceHost')
            ->where('t1.deleted')->eq(0);
    }

    /**
     * 获取应用信息。
     * Get applications.
     *
     * @param  string       $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getApplications($fieldList)
    {
        $count = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->fetch('count');

        return $this->dao->select("COUNT(1) AS count, {$count} as instanceCount")->from(TABLE_PIPELINE)
            ->where('deleted')->eq(0);

    }

    /**
     * 获取主机信息。
     * Get hosts.
     *
     * @param  string $fieldList
     * @access public
     * @return void
     */
    public function getHosts($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_HOST)->where('deleted')->eq(0);
    }

    /**
     * 获取DevOps环境信息。
     * Get devops env.
     *
     * @param  string $fieldList
     * @access public
     * @return void
     */
    public function getDevOpsEnv($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_ENV)->where('deleted')->eq(0);
    }
}
