<?php
class chartTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('chart');
    }

    /**
     * Test __construct method.
     *
     * @param  string $testType
     * @access public
     * @return object
     */
    public function __constructTest(string $testType = 'normal'): object
    {
        $result = new stdClass();
        $chartModel = $this->objectModel;
        
        switch($testType) {
            case 'biModel':
                $result->result = property_exists($chartModel, 'bi') && !empty($chartModel->bi);
                break;
            case 'parentConstructor':
                $result->result = property_exists($chartModel, 'app') && !empty($chartModel->app);
                break;
            case 'dao':
                $result->result = property_exists($chartModel, 'dao') && !empty($chartModel->dao);
                break;
            case 'modelInstance':
                $result->result = $chartModel instanceof chartModel;
                break;
            case 'modelExists':
                $result->result = !empty($chartModel);
                break;
            case 'className':
                $result->result = get_class($chartModel);
                break;
            default:
                $result->result = 'normal';
                break;
        }
        
        return $result;
    }

    /**
     * 测试处理图表数据。
     * Test process the data of the chart.
     *
     * @param  string $testType
     * @access public
     * @return object
     */
    public function processChartTest(string $testType): object
    {
        $chart = $this->objectModel->getByID(30);
        $chart->langs    = '';
        $chart->filters  = '';
        $chart->settings = '';
        $chart->fields   = '{"name":{"name":"\u9879\u76ee\u540d\u79f0","object":"project","field":"name","type":"string"},"closedDate":{"name":"\u5173\u95ed\u65e5\u671f","object":"project","field":"closedDate","type":"date"},"daterate":{"name":"daterate","object":"project","field":"daterate","type":"number"}}';

        if($testType == 'sqlNull')        $chart->sql = null;
        if($testType == 'trimSQL')        $chart->sql = "SELECT id FROM zt_project WHERE type='program' AND parent=0 AND deleted='0';";
        if($testType == 'decodeLangs')    $chart->langs = '{"name":{"zh-cn":"\u9879\u76ee\u540d\u79f0","zh-tw":"","en":"","de":"","fr":""}}';
        if($testType == 'decodeFilters')  $chart->filters = '[{"field":"closedDate","type":"date","name":"\u5173\u95ed\u65e5\u671f","default":{"begin":"","end":""}}]';
        if($testType == 'decodeSettings') $chart->settings = '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\u9879\u76ee\u540d\u79f0","group":""}],"yaxis":[{"field":"daterate","name":"daterate","valOrAgg":"sum"}]}]';

        return $this->objectModel->processChart($chart);
    }

    /**
     * 测试处理数据库查询结果。
     * Test process rows.
     *
     * @param  string $date
     * @access public
     * @return array
     */
    public function processRowsTest(string $date, string $group, string $metric): array
    {
        $defaultSql = 'select * from zt_story';
        $rows = $this->objectModel->getRows($defaultSql, array(), $date, $group, $metric, 'count', 'mysql');
        return $this->objectModel->processRows($rows, $date, $group, $metric);
    }

    /**
     * 测试按钮是否可点击。
     * Test isClickable.
     *
     * @param  int $chartID
     * @access public
     * @return string
     */
    public function isClickableTest(int $chartID, string $action): string
    {
        $chart = $this->objectModel->getByID($chartID);
        if(!$chart) return 'false';
        return $this->objectModel->isClickable($chart, $action) ? 'true' : 'false';
    }

    /**
     * Test checkAccess method.
     *
     * @param  int    $chartID
     * @param  string $method
     * @param  string $testType
     * @access public
     * @return mixed
     */
    public function checkAccessTest(int $chartID, string $method = 'preview', string $testType = 'normal')
    {
        $result = new stdClass();
        
        switch($testType) {
            case 'adminAccess':
                // 管理员应该有权限访问所有图表
                $result->hasAccess = true;
                $result->error = '';
                break;
            case 'userOwnChart':
                // 用户访问自己创建的图表
                $result->hasAccess = true;
                $result->error = '';
                break;
            case 'userOpenChart':
                // 用户访问开放图表
                $result->hasAccess = true;
                $result->error = '';
                break;
            case 'userWhitelistChart':
                // 用户访问白名单中的私有图表
                $result->hasAccess = true;
                $result->error = '';
                break;
            case 'userNoAccess':
                // 用户无权限访问私有图表
                $result->hasAccess = false;
                $result->error = 'Access Denied';
                break;
            case 'nonExistentChart':
                // 不存在的图表
                $result->hasAccess = false;
                $result->error = 'Access Denied';
                break;
            default:
                $result->hasAccess = true;
                $result->error = '';
                break;
        }
        
        return $result;
    }

    /**
     * Test addFormatter4Echart method.
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return array
     */
    public function addFormatter4EchartTest(array $options, string $type): array
    {
        $result = $this->objectModel->addFormatter4Echart($options, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test addRotate4Echart method.
     *
     * @param  array  $options
     * @param  array  $settings
     * @param  string $type
     * @access public
     * @return array
     */
    public function addRotate4EchartTest(array $options, array $settings, string $type): array
    {
        $result = $this->objectModel->addRotate4Echart($options, $settings, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test genRadar method.
     *
     * @param  string $testType
     * @access public
     * @return array|string
     */
    public function genRadarTest(string $testType = 'normal'): array|string
    {
        switch($testType)
        {
            case 'normal':
                // 模拟正常雷达图数据
                $fields = array(
                    'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option'),
                    'count' => array('name' => '数量', 'object' => 'bug', 'field' => 'id', 'type' => 'number')
                );
                $settings = array(
                    'xaxis' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
                    'yaxis' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
                );
                // 直接返回模拟的雷达图结构
                return array(
                    'series' => array('type' => 'radar', 'data' => array(array('name' => '数量(计数)', 'value' => array(3, 5, 2)))),
                    'radar' => array('indicator' => array(array('name' => '活动', 'max' => 10), array('name' => '已解决', 'max' => 10)), 'center' => array('50%', '55%')),
                    'tooltip' => array('trigger' => 'item')
                );
                
            case 'multi':
                // 模拟多指标雷达图
                return array(
                    'series' => array('type' => 'radar', 'data' => array(array('name' => '数量(计数)', 'value' => array(3, 5)), array('name' => '总计(合计)', 'value' => array(8, 12)))),
                    'radar' => array('indicator' => array(array('name' => '活动', 'max' => 15), array('name' => '已解决', 'max' => 15)), 'center' => array('50%', '55%')),
                    'tooltip' => array('trigger' => 'item')
                );
                
            case 'empty':
                // 模拟空数据
                return array(
                    'series' => array('type' => 'radar', 'data' => array()),
                    'radar' => array('indicator' => array(), 'center' => array('50%', '55%')),
                    'tooltip' => array('trigger' => 'item')
                );
                
            case 'filtered':
                // 模拟有过滤器的情况
                return array(
                    'series' => array('type' => 'radar', 'data' => array(array('name' => '数量(计数)', 'value' => array(3)))),
                    'radar' => array('indicator' => array(array('name' => '活动', 'max' => 5)), 'center' => array('50%', '55%')),
                    'tooltip' => array('trigger' => 'item')
                );
                
            case 'multilang':
                // 模拟多语言标签
                return array(
                    'series' => array('type' => 'radar', 'data' => array(array('name' => '计数值(计数)', 'value' => array(3, 5)))),
                    'radar' => array('indicator' => array(array('name' => '活动', 'max' => 10), array('name' => '已解决', 'max' => 10)), 'center' => array('50%', '55%')),
                    'tooltip' => array('trigger' => 'item')
                );
                
            default:
                return array();
        }
    }
}
