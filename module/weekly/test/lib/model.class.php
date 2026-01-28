<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class weeklyModelTest extends baseTest
{
    protected $moduleName = 'weekly';
    protected $className  = 'model';


    /**
     * GetPageNav
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return string
     */

    public function getPageNavTest($projectID, $date)
    {
        // 创建模拟项目对象，避免数据库查询
        $project = new stdClass();
        $project->id = $projectID;
        $project->name = '项目' . $projectID;
        $project->status = 'doing'; // 默认状态
        $project->realBegan = '2022-04-01';
        $project->realEnd = '2022-12-31';
        $project->suspendedDate = '2022-06-01';

        // 根据项目ID设置不同的状态用于测试
        if($projectID == '17') $project->status = 'wait';
        if($projectID == '18') $project->status = 'suspended';
        if($projectID == '20') $project->status = 'closed';

        $pageNav = $this->instance->getPageNav($project, $date);

        if(dao::isError()) return dao::getError();

        // 解析HTML中的项目名称
        // HTML格式: <a href='###' class='btn'>周报-项目名称</a>
        preg_match("/class='btn'>([^<]+)<\/a>/", $pageNav, $matches);
        if(isset($matches[1])) {
            return trim($matches[1]);
        }

        return '';
    }

    /**
     * GetWeekPairs
     *
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return array
     */

    public function getWeekPairsTest($begin, $end)
    {
        switch($begin)
        {
            case '1':
               $begin = '2023-04-24';
               break;
            case '2':
               $begin = date('Y-m-d', strtotime('2023-04-24' . "- 10 days"));
               break;
            case '3':
               $begin = date('Y-m-d', strtotime('2023-04-24' . "+ 10 days"));
               break;
            case '':
               $begin = '';
               break;
        }

        switch($end)
        {
            case '1':
               $end = '2023-04-24';
               break;
            case '2':
               $end = date('Y-m-d', strtotime('2023-04-24' . "- 10 days"));
               break;
            case '3':
               $end = date('Y-m-d', strtotime('2023-04-24' . "+ 10 days"));
               break;
            case '':
               $end = '';
               break;
        }

        $objects = $this->instance->getWeekPairs($begin, $end);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * GetFromDB
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return object
     */

    public function getFromDBTest($project, $date)
    {
        $objects = $this->instance->getFromDB($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

     /**
     * Save data.
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */

    public function saveTest($project, $date)
    {
        $objects = $this->instance->save($project, $date);

        if(dao::isError()) return dao::getError();

        $weekly = $this->instance->getFromDB($project, $date);
        return $weekly;
    }

    /**
     * GetWeekSN
     *
     * @param  string $begin
     * @param  string $date
     * @access public
     * @return int
     */

    public function getWeekSNTest($begin, $date)
    {
        $objects = $this->instance->getWeekSN($begin, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get monday for a date.
     *
     * @param  string $date
     * @access public
     * @return date
     */

    public function getThisMondayTest($date)
    {
        $objects = $this->instance->getThisMonday($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * GetThisSunday
     *
     * @param  string $date
     * @access public
     * @return date
     */

    public function getThisSundayTest($date)
    {
        $objects = $this->instance->getThisSunday($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * GetLastDay
     *
     * @param  string $date
     * @access public
     * @return string
     */

    public function getLastDayTest($date)
    {
        $objects = $this->instance->getLastDay($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getStaff
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return array
     */
    public function getStaffTest($project, $date = '')
    {
        $objects = $this->instance->getStaff($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getFinished
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getFinishedTest($project, $date = '')
    {
        $objects = $this->instance->getFinished($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getPostponed
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getPostponedTest($project, $date = '')
    {
        $objects = $this->instance->getPostponed($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getTasksOfNextWeek
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getTasksOfNextWeekTest($project, $date = '')
    {
        $objects = $this->instance->getTasksOfNextWeek($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getWorkloadByType
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return object
     */
    public function getWorkloadByTypeTest($project, $date = '')
    {
        $objects = $this->instance->getWorkloadByType($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getPlanedTaskByWeek
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return array
     */
    public function getPlanedTaskByWeekTest($project, $date = '')
    {
        $objects = $this->instance->getPlanedTaskByWeek($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getPVEV
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return int
     */
    public function getPVEVTest($projectID, $date = '')
    {
        $objects = $this->instance->getPVEV($projectID, $date);

        if(dao::isError()) return dao::getError();

        return $objects['PV'] . ',' . $objects['EV'];
    }

    /**
     * Test get AC data.
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return int
     */
    public function getACTest($project, $date = '')
    {
        $objects = $this->instance->getAC($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get SV data.
     *
     * @param  int    $ev
     * @param  int    $pv
     * @access public
     * @return int
     */
    public function getSVTest($ev, $pv)
    {
        $objects = $this->instance->getSV($ev, $pv);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getCV
     *
     * @param  int    $ev
     * @param  int    $ac
     * @access public
     * @return int
     */
    public function getCVTest($ev, $ac)
    {
        $objects = $this->instance->getCV($ev, $ac);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getTips
     *
     * @param  string $type
     * @param  int    $data
     * @access public
     * @return string
     */
    public function getTipsTest($type = 'progress', $data = 0)
    {
        $objects = $this->instance->getTips($type, $data);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 添加内置报告模板范围。
     * Add built-in report template scope.
     *
     * @access public
     * @return bool|object
     */
    public function addBuiltinScopeTest(): bool|object
    {
        $scopeID = $this->instance->addBuiltinScope();
        if(!$scopeID) return false;

        if(dao::isError()) return dao::getError();

        $scope = $this->instance->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq($scopeID)->fetch();
        return $scope ? $scope : false;
    }

    /**
     * 添加内置分类。
     * Add built-in category.
     *
     * @access public
     * @return object
     */
    public function addBuiltinCategoryTest(): object
    {
        $scopeID    = $this->instance->addBuiltinScope();
        $categroyID = $this->instance->addBuiltinCategory($scopeID);

        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($categroyID)->fetch();
    }

    /**
     * 添加内置报告模板。
     * Add builtin report template.
     *
     * @access public
     * @return array|object
     */
    public function addBuiltinTemplateTest(): array|object
    {
        $scopeID    = $this->instance->addBuiltinScope();
        $categroyID = $this->instance->addBuiltinCategory($scopeID);
        $this->instance->addBuiltinTemplate($scopeID, $categroyID, array());

        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('*')->from(TABLE_DOC)->fetch();
    }

    /**
     * 获取内置项目周报模板内容。
     * Get builtin project weekly report template content.
     *
     * @access public
     * @return array
     */
    public function getBuildinRawContentTest(): array
    {
        $content = $this->instance->getBuildinRawContent(array());
        return json_decode($content, true);
    }

    /**
     * 添加内置项目周报模板。
     * Add builtin project weekly report template.
     *
     * @access public
     * @return bool|object
     */
    public function addBuiltinWeeklyTemplateTest():bool|object
    {
        try
        {
            $result = $this->instance->addBuiltinWeeklyTemplate();
            if(!$result) return false;

            $doc = $this->instance->dao->select('*')->from(TABLE_DOC)->where('templateType')->eq('reportTemplate')->orderBy('id desc')->fetch();
            return $doc ? $doc : true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Test getLeft
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return float
     */
    public function getLeftTest($projectID, $date = '')
    {
        $objects = $this->instance->getLeft($projectID, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
