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
     * 获取执行数据。
     * Get all executions.
     *
     * @param  string $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getAllExecutions($fieldList)
    {
        return $this->dao->select($fieldList)->from(TABLE_PROJECT)
            ->where('deleted')->eq(0)
            ->andWhere('type')->in('sprint,stage,kanban')
            ->query();
    }

    /**
     * 获取发布数据。
     * Get release list.
     *
     * @param  int    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getReleases($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.hasProduct')->eq(1)
            ->query();
    }

    /**
     * 获取产品计划数据。
     * Get plan list.
     *
     * @param  array    $fieldList
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
            ->query();
    }

    /**
     * 获取bug数据。
     * Get bug list.
     *
     * @param  array    $fieldList
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
            ->query();
    }

    /**
     * 获取反馈数据。
     * Get feedback list.
     *
     * @param  array    $fieldList
     * @access public
     * @return PDOStatement
     */
    public function getFeedbacks($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->query();
    }

    /**
     * 获取需求数据。
     * Get story list.
     *
     * @param  array    $fieldList
     * @access public
     * @return void
     */
    public function getStories($fieldList)
    {
        return $this->dao->select($fieldList)
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->query();
    }
}
