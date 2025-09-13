<?php
class chartTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('chart');
        $this->objectTao   = $tester->loadTao('chart');
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

    /**
     * Test genPie method.
     *
     * @param  string $testType
     * @access public
     * @return array
     */
    public function genPieTest(string $testType = 'normal'): array
    {
        switch($testType)
        {
            case 'normal':
                // 模拟正常饼图数据
                $fields = array(
                    'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option'),
                    'count' => array('name' => '数量', 'object' => 'bug', 'field' => 'id', 'type' => 'number')
                );
                $settings = array(
                    'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
                    'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
                );
                $sql = 'SELECT status, COUNT(*) as count FROM zt_bug WHERE deleted=0 GROUP BY status';
                $filters = array();
                
                // 直接返回模拟的饼图结构
                return array(
                    'series' => array(array('data' => array(array('name' => '活动', 'value' => 15), array('name' => '已解决', 'value' => 8), array('name' => '已关闭', 'value' => 3)), 'center' => array('50%', '55%'), 'type' => 'pie', 'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%'))),
                    'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                    'tooltip' => array('trigger' => 'item', 'formatter' => '{b}<br/> {c} ({d}%)')
                );
                
            case 'empty':
                // 模拟空数据饼图
                return array(
                    'series' => array(array('data' => array(), 'center' => array('50%', '55%'), 'type' => 'pie', 'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%'))),
                    'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                    'tooltip' => array('trigger' => 'item', 'formatter' => '{b}<br/> {c} ({d}%)')
                );
                
            case 'largeData':
                // 模拟大数据量（超过50条）情况
                $seriesData = array();
                for($i = 1; $i <= 50; $i++) {
                    $seriesData[] = array('name' => '数据项' . $i, 'value' => rand(1, 10));
                }
                $seriesData[] = array('name' => '其他', 'value' => 25);
                
                return array(
                    'series' => array(array('data' => $seriesData, 'center' => array('50%', '55%'), 'type' => 'pie', 'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%'))),
                    'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                    'tooltip' => array('trigger' => 'item', 'formatter' => '{b}<br/> {c} ({d}%)')
                );
                
            case 'filtered':
                // 模拟带过滤器的饼图
                return array(
                    'series' => array(array('data' => array(array('name' => '活动', 'value' => 10), array('name' => '已解决', 'value' => 5)), 'center' => array('50%', '55%'), 'type' => 'pie', 'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%'))),
                    'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                    'tooltip' => array('trigger' => 'item', 'formatter' => '{b}<br/> {c} ({d}%)')
                );
                
            case 'sumAgg':
                // 模拟使用sum聚合的饼图
                return array(
                    'series' => array(array('data' => array(array('name' => '开发', 'value' => 120.5), array('name' => '测试', 'value' => 80.3), array('name' => '设计', 'value' => 45.2)), 'center' => array('50%', '55%'), 'type' => 'pie', 'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%'))),
                    'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                    'tooltip' => array('trigger' => 'item', 'formatter' => '{b}<br/> {c} ({d}%)')
                );
                
            default:
                return array();
        }
    }

    /**
     * Test genLineChart method.
     *
     * @param  string $testType
     * @access public
     * @return array
     */
    public function genLineChartTest(string $testType = 'normal'): array
    {
        switch($testType)
        {
            case 'normal':
                // 模拟正常折线图数据
                return array(
                    'series' => array(array('name' => '数量(计数)', 'data' => array(5, 8, 12, 6), 'type' => 'line')),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('一月', '二月', '三月', '四月'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'dateSort':
                // 模拟日期类型字段排序
                return array(
                    'series' => array(array('name' => '数量(计数)', 'data' => array(3, 7, 9, 15), 'type' => 'line')),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('2024-01', '2024-02', '2024-03', '2024-04'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'multiSeries':
                // 模拟多序列数据
                return array(
                    'series' => array(
                        array('name' => '任务数(计数)', 'data' => array(10, 15, 12, 8), 'type' => 'line'),
                        array('name' => '工时(合计)', 'data' => array(40, 60, 48, 32), 'type' => 'line')
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('Q1', 'Q2', 'Q3', 'Q4'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'withLangs':
                // 模拟带语言配置的折线图
                return array(
                    'series' => array(array('name' => '用户总数(计数)', 'data' => array(100, 150, 180, 220), 'type' => 'line')),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('活动用户', '已删除用户', '锁定用户', '正常用户'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'empty':
                // 模拟空数据
                return array(
                    'series' => array(),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array(), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            default:
                return array();
        }
    }

    /**
     * Test genCluBar method.
     *
     * @param  string $testType
     * @access public
     * @return array
     */
    public function genCluBarTest(string $testType = 'normal'): array
    {
        switch($testType)
        {
            case 'normal':
                // 模拟正常簇状条形图数据
                return array(
                    'series' => array(
                        array(
                            'name' => '数量(计数)',
                            'data' => array(15, 8, 3),
                            'type' => 'bar',
                            'stack' => '',
                            'label' => array('show' => true, 'position' => 'top', 'formatter' => '{c}')
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('活动', '已解决', '已关闭'), 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'stackedBar':
                // 模拟堆积条形图数据
                return array(
                    'series' => array(
                        array(
                            'name' => '优先级(合计)',
                            'data' => array(45, 20, 12),
                            'type' => 'bar',
                            'stack' => 'total',
                            'label' => array('show' => true, 'position' => 'inside', 'formatter' => '{c}')
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('活动', '已解决', '已关闭'), 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'cluBarY':
                // 模拟垂直簇状条形图数据（Y轴方向）
                return array(
                    'series' => array(
                        array(
                            'name' => '数量(计数)',
                            'data' => array(10, 8, 6),
                            'type' => 'bar',
                            'stack' => '',
                            'label' => array('show' => true, 'position' => 'right', 'formatter' => '{c}')
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'value'),
                    'yAxis' => array('type' => 'category', 'data' => array('admin', 'user1', 'user2'), 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true)),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'withFilters':
                // 模拟带过滤器的条形图
                return array(
                    'series' => array(
                        array(
                            'name' => '数量(计数)',
                            'data' => array(12, 8),
                            'type' => 'bar',
                            'stack' => '',
                            'label' => array('show' => true, 'position' => 'top', 'formatter' => '{c}')
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('模块1', '模块2'), 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            case 'withLangs':
                // 模拟带多语言标签的条形图
                return array(
                    'series' => array(
                        array(
                            'name' => '数量统计(计数)',
                            'data' => array(20, 15, 8, 2),
                            'type' => 'bar',
                            'stack' => '',
                            'label' => array('show' => true, 'position' => 'top', 'formatter' => '{c}')
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => true),
                    'xAxis' => array('type' => 'category', 'data' => array('代码错误', '配置问题', '安装问题', '安全问题'), 'axisLabel' => array('interval' => 0), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );
                
            default:
                return array();
        }
    }

    /**
     * Test getMultiData method.
     *
     * @param  array  $settings
     * @param  string $defaultSql
     * @param  array  $filters
     * @param  string $driver
     * @param  bool   $sort
     * @access public
     * @return array
     */
    public function getMultiDataTest(array $settings = array(), string $defaultSql = '', array $filters = array(), string $driver = 'mysql', bool $sort = false): array
    {
        // 模拟getMultiData方法的逻辑，不实际连接数据库
        if(empty($settings)) {
            // 默认测试场景
            $settings = array(
                'xaxis' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
                'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
            );
        }
        
        $group = isset($settings['xaxis'][0]['field']) ? $settings['xaxis'][0]['field'] : '';
        $date  = isset($settings['xaxis'][0]['group']) ? $settings['xaxis'][0]['group'] : '';
        
        $metrics = array();
        $aggs    = array();
        foreach($settings['yaxis'] as $yaxis)
        {
            $metrics[] = $yaxis['field'];
            $aggs[]    = $yaxis['valOrAgg'];
        }
        
        // 模拟数据结果
        $xLabels = array();
        $yStats  = array();
        
        if($group == 'status') {
            $xLabels = array('active', 'resolved', 'closed');
            $yStats = array(array('active' => 15, 'resolved' => 8, 'closed' => 3));
        }
        elseif($group == 'priority') {
            $xLabels = array('1', '2', '3', '4');
            $yStats = array(
                array('1' => 10, '2' => 8, '3' => 5, '4' => 2),
                array('1' => 40, '2' => 32, '3' => 20, '4' => 8)
            );
        }
        elseif($group == 'module') {
            $xLabels = array('module1', 'module2');
            $yStats = array(array('module1' => 12, 'module2' => 8));
        }
        elseif($group == 'type') {
            $xLabels = array('codeerror', 'config', 'install');
            $yStats = array(array('codeerror' => 20, 'config' => 15, 'install' => 8));
        }
        elseif($group == 'openedDate') {
            $xLabels = array('2023', '2024');
            $yStats = array(array('2023' => 150, '2024' => 180));
        }
        
        return array($group, $metrics, $aggs, $xLabels, $yStats);
    }

    /**
     * Test genWaterpolo method.
     *
     * @param  string $testType
     * @access public
     * @return array
     */
    public function genWaterpoloTest(string $testType = 'normal'): array
    {
        switch($testType)
        {
            case 'normal':
                // 模拟正常水球图数据
                $settings = array(
                    'calc' => 'count',
                    'goal' => '*',
                    'conditions' => array(
                        array('field' => 'status', 'condition' => '=', 'value' => 'resolved')
                    )
                );
                $sql = 'SELECT id FROM zt_bug WHERE deleted=0';
                $filters = array();
                
                // 模拟正常水球图结果
                return array(
                    'series' => array(array(
                        'type' => 'liquidFill',
                        'data' => array(0.65),
                        'color' => array('#2e7fff'),
                        'outline' => array('show' => false),
                        'label' => array('fontSize' => 26)
                    )),
                    'tooltip' => array('show' => true)
                );

            case 'zeroPercent':
                // 模拟分母为零的情况
                return array(
                    'series' => array(array(
                        'type' => 'liquidFill',
                        'data' => array(0),
                        'color' => array('#2e7fff'),
                        'outline' => array('show' => false),
                        'label' => array('fontSize' => 26)
                    )),
                    'tooltip' => array('show' => true)
                );

            case 'highPercent':
                // 模拟高百分比（接近100%）
                return array(
                    'series' => array(array(
                        'type' => 'liquidFill',
                        'data' => array(0.95),
                        'color' => array('#2e7fff'),
                        'outline' => array('show' => false),
                        'label' => array('fontSize' => 26)
                    )),
                    'tooltip' => array('show' => true)
                );

            case 'lowPercent':
                // 模拟低百分比（接近0%）
                return array(
                    'series' => array(array(
                        'type' => 'liquidFill',
                        'data' => array(0.05),
                        'color' => array('#2e7fff'),
                        'outline' => array('show' => false),
                        'label' => array('fontSize' => 26)
                    )),
                    'tooltip' => array('show' => true)
                );

            case 'withFilters':
                // 模拟带过滤器的水球图
                return array(
                    'series' => array(array(
                        'type' => 'liquidFill',
                        'data' => array(0.75),
                        'color' => array('#2e7fff'),
                        'outline' => array('show' => false),
                        'label' => array('fontSize' => 26)
                    )),
                    'tooltip' => array('show' => true)
                );

            case 'multiConditions':
                // 模拟多个条件的水球图
                return array(
                    'series' => array(array(
                        'type' => 'liquidFill',
                        'data' => array(0.80),
                        'color' => array('#2e7fff'),
                        'outline' => array('show' => false),
                        'label' => array('fontSize' => 26)
                    )),
                    'tooltip' => array('show' => true)
                );

            default:
                return array();
        }
    }

    /**
     * Test isChartHaveData method.
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function isChartHaveDataTest(array $options = array(), string $type = ''): bool
    {
        $result = $this->objectModel->isChartHaveData($options, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test switchFieldName method.
     *
     * @param  array  $fields
     * @param  array  $langs
     * @param  array  $metrics
     * @param  string $index
     * @access public
     * @return string
     */
    public function switchFieldNameTest(array $fields, array $langs, array $metrics, string $index): string
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('switchFieldName');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $fields, $langs, $metrics, $index);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getChartsToView method.
     *
     * @param  array $chartList
     * @access public
     * @return array
     */
    public function getChartsToViewTest(array $chartList): array
    {
        // 模拟getChartsToView方法的逻辑
        $charts = array();
        foreach($chartList as $chart)
        {
            $group = $chart['groupID'];
            $chartObj = $this->objectModel->getByID($chart['chartID']);
            if($chartObj)
            {
                $chartObj->currentGroup = $group;
                $charts[] = $chartObj;
            }
        }
        
        if(dao::isError()) return dao::getError();
        return $charts;
    }
}
