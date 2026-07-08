<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class bugTaoTest extends baseTest
{
    protected $moduleName = 'bug';
    protected $className  = 'tao';

    /**
     * 测试添加bug->delay字段，内容为延期的时长（天），不延期则为0
     * Test if the bug is delayed, add the bug->delay field to show the delay time (day).
     *
     * @param  string       $deadline
     * @param  string       $resolvedDate
     * @param  string       $status
     * @access public
     * @return object|array
     */
    public function appendDelayedDaysTest(string $deadline, string $resolvedDate , string $status): object|array
    {
        $bug = new stdclass();
        $bug->status       = $status;
        $bug->deadline     = $deadline     ? date('Y-m-d', strtotime("{$deadline} day"))     : '0000-00-00';
        $bug->resolvedDate = $resolvedDate ? date('Y-m-d', strtotime("{$resolvedDate} day")) : '0000-00-00';

        $object = $this->invokeArgs('appendDelayedDays', array($bug));
        if(dao::isError()) return dao::getError();

        if(!isset($object->delay)) $object->delay = 0;
        return $object;
    }

    /**
     * 测试为 bugs 批量添加延期天数。
     * Test call checkDelayBug in foreach to check if the bug is delay.
     *
     * @param  int          $productID
     * @access public
     * @return string|array
     */
    public function batchAppendDelayedDaysTest($productID): array|string
    {
        $this->instance->app->tab = 'qa';
        $bugs = $this->invokeArgs('getListByBrowseType', array('all', array($productID), 0, array(), 'all', array(), 0, 'id_asc', null));
        $bugs = $this->invokeArgs('batchAppendDelayedDays', array($bugs));
        if(dao::isError()) return dao::getError();

        $delay = '';
        foreach($bugs as $bug)
        {
            $delay .= ',' . (!isset($bug->delay) ? 0 : $bug->delay);
        }
        $delay = trim($delay, ',');

        return $delay;
    }

    /**
     * 测试获取待确认的 bug 列表。
     *
     * @param  array       $productIdList
     * @param  int         $projectID
     * @param  array       $executionIdList
     * @param  int|string  $branch
     * @param  array       $moduleIdList
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getNeedConfirmListTest(array $productIdList, int $projectID, array $executionIdList, int|string $branch, array $moduleIdList, string $orderBy, ?object $pager = null): array
    {
        $this->instance->app->tab = 'project';
        if(empty($executionIdList)) $executionIdList = array(0);

        $bugs = $this->invokeArgs('getNeedConfirmList', array($productIdList, $projectID, $executionIdList, $branch, $moduleIdList, $orderBy, $pager));
        if(dao::isError()) return dao::getError();

        return $bugs;
    }
}
