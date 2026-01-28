<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class userTaoTest extends baseTest
{
    protected $moduleName = 'user';
    protected $className  = 'tao';

    /**
     * 测试根据用户和状态获取项目列表。
     * Test fetch projects by user and status.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function fetchProjectsTest(string $account, string $status = 'all', string $orderBy = 'id', ?object $pager = null): array
    {
        return $this->instance->fetchProjects($account, $status, $orderBy, $pager);
    }

    /**
     * 测试获取某个用户参与的项目和项目包含的执行数键值对。
     * Test fetch project execution count.
     *
     * @param  array  $projectidList
     * @access public
     * @return array
     */
    public function fetchProjectExecutionCountTest(array $projectIdList): array
    {
        return $this->instance->fetchProjectExecutionCount($projectIdList);
    }

    /**
     * 测试获取某个用户参与的项目和项目包含的需求规模数和需求数。
     * Test fetch project story estimate and count.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function fetchProjectStoryCountAndEstimateTest(array $projectIdList): array
    {
        return $this->instance->fetchProjectStoryCountAndEstimate($projectIdList);
    }

    /**
     * 测试根据用户和状态获取执行列表。
     * Test fetch executions by user and status.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function fetchExecutionsTest(string $account, string $status = 'all', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->instance->fetchExecutions($account, $status, $orderBy, $pager);
    }

    /**
     * 测试获取某个用户参与的执行和执行中指派给他的任务数的键值对。
     * Get the executions that the user joined and the task count of the execution.
     *
     * @param  string $account
     * @param  array  $executionIdList
     * @access public
     * @return
     */
    public function fetchExecutionTaskCountTest(string $account, array $executionIdList): array
    {
        return $this->instance->fetchExecutionTaskCount($account, $executionIdList);
    }
}
