<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class reportZenTest extends baseTest
{
    protected $moduleName = 'report';
    protected $className  = 'zen';

    /**
     * Test assignAnnualBaseData method.
     *
     * @param  string $account
     * @param  string $dept
     * @param  string $year
     * @access public
     * @return mixed
     */
    public function assignAnnualBaseDataTest(string $account, string $dept, string $year)
    {
        $result = $this->invokeArgs('assignAnnualBaseData', [$account, $dept, $year]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test assignAnnualReport method.
     *
     * @param  string $year
     * @param  string $dept
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function assignAnnualReportTest(string $year, string $dept, string $account)
    {
        // 获取或创建report zen实例
        $instance = $this->getInstance($this->moduleName, $this->className);

        // 调用方法
        $this->invokeArgs('assignAnnualReport', [$year, $dept, $account]);
        if(dao::isError()) return dao::getError();

        // 收集view属性用于验证,view应该在instance对象中
        $result = array();
        $result['dept'] = isset($instance->view->dept) ? (string)$instance->view->dept : '';
        $result['year'] = isset($instance->view->year) ? (string)$instance->view->year : '';
        $result['hasYears'] = isset($instance->view->years) && !empty($instance->view->years) ? 'yes' : 'no';
        $result['hasMonths'] = isset($instance->view->months) && !empty($instance->view->months) ? 'yes' : 'no';
        $result['hasContributionGroups'] = isset($instance->view->contributionGroups) && !empty($instance->view->contributionGroups) ? 'yes' : 'no';
        $result['hasRadarData'] = isset($instance->view->radarData) && is_array($instance->view->radarData) ? 'yes' : 'no';
        $result['hasMaxCount'] = isset($instance->view->maxCount) && is_numeric($instance->view->maxCount) ? 'yes' : 'no';
        $result['hasContributionCount'] = isset($instance->view->contributionCount) && is_numeric($instance->view->contributionCount) ? 'yes' : 'no';
        $result['hasData'] = isset($instance->view->data) && is_array($instance->view->data) ? 'yes' : 'no';

        return $result;
    }

    /**
     * Test getReminder method.
     *
     * @access public
     * @return mixed
     */
    public function getReminderTest()
    {
        $result = $this->invokeArgs('getReminder', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
