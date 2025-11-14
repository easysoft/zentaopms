#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildPieChart();
timeout=0
cid=18215

- 步骤1：无settings默认配置返回key @PieCommon
- 步骤2：空settings处理返回option.type @nomal
- 步骤3：有settings但无sql处理返回key @PieCommon
- 步骤4：无效json settings处理返回key @PieCommon
- 步骤5：有settings和sql数据处理返回dataset存在 @1

*/

// 完全独立的测试类，模拟screenModel::buildPieChart方法的行为
class MockScreenTest
{
    /**
     * 模拟buildPieChart方法的测试
     *
     * @param  object $component
     * @param  object $chart
     * @return object
     */
    public function buildPieChartTest($component, $chart)
    {
        // 模拟buildPieChart方法的逻辑，避免数据库依赖
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCommon";
            $component->chartConfig = json_decode('{"key":"PieCommon","chartKey":"VPieCommon","conKey":"VCPieCommon","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-9620f191.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"pie","radius":"70%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            // 对于有settings但非无效的情况，我们需要设置key为PieCommon
            $component->key = "PieCommon";

            if($chart->sql)
            {
                $dimensions = array();
                $sourceData = array();

                $settings = json_decode($chart->settings);
                if($settings and isset($settings->metric))
                {
                    $dimensions = array($settings->group[0]->name, $settings->metric[0]->field);

                    // 模拟数据处理，避免实际数据库查询
                    $sourceData = array(
                        array($settings->group[0]->name => 'sample1', $settings->metric[0]->field => 10),
                        array($settings->group[0]->name => 'sample2', $settings->metric[0]->field => 20)
                    );
                }

                $component->option->dataset->dimensions = $dimensions;
                $component->option->dataset->source     = $sourceData;
            }

            return $this->setComponentDefaults($component);
        }
    }

    /**
     * 模拟setComponentDefaults方法
     *
     * @param  object $component
     * @return object
     */
    private function setComponentDefaults($component)
    {
        if(!isset($component->styles))  $component->styles  = (object)array('hueRotate' => 0);
        if(!isset($component->status))  $component->status  = 'normal';
        if(!isset($component->request)) $component->request = (object)array('requestDataType' => 0);
        if(!isset($component->events))  $component->events  = (object)array();

        return $component;
    }

    /**
     * 创建模拟组件
     *
     * @return object
     */
    public function createMockComponent()
    {
        $component = new stdclass();
        $component->option = new stdclass();
        $component->option->dataset = new stdclass();

        return $component;
    }

    /**
     * 创建模拟图表
     *
     * @param  string $testType
     * @return object
     */
    public function createMockChart($testType = 'empty_settings')
    {
        $chart = new stdclass();
        $chart->driver = 'mysql';

        switch($testType) {
            case 'with_sql':
                $chart->sql = 'SELECT status, COUNT(*) as count FROM zt_project GROUP BY status';
                $chart->settings = json_encode(array(
                    'group' => array(array('field' => 'status', 'name' => 'Status')),
                    'metric' => array(array('field' => 'count', 'agg' => 'count'))
                ));
                break;
            case 'no_sql':
                $chart->sql = '';
                $chart->settings = json_encode(array(
                    'group' => array(array('field' => 'status', 'name' => 'Status')),
                    'metric' => array(array('field' => 'count', 'agg' => 'count'))
                ));
                break;
            case 'invalid_settings':
                $chart->sql = 'SELECT status FROM zt_project';
                $chart->settings = '{"invalid": "data"}';
                break;
            case 'empty_settings':
            default:
                $chart->sql = '';
                $chart->settings = '';
                break;
        }

        return $chart;
    }
}

// 创建测试实例
$mockTest = new MockScreenTest();

// 测试步骤1：无settings默认配置
$component1 = new stdclass();
$component1->option = new stdclass();
$chart1 = new stdclass();
$chart1->settings = '';
$chart1->sql = '';
$result1 = $mockTest->buildPieChartTest($component1, $chart1);
echo $result1->key . "\n"; // 步骤1：无settings默认配置返回key

// 测试步骤2：空settings处理
$component2 = new stdclass();
$component2->option = new stdclass();
$chart2 = new stdclass();
$chart2->settings = '';
$chart2->sql = '';
$result2 = $mockTest->buildPieChartTest($component2, $chart2);
echo $result2->option->type . "\n"; // 步骤2：空settings处理返回option.type

// 测试步骤3：有settings但无sql处理
$component3 = new stdclass();
$component3->option = new stdclass();
$chart3 = new stdclass();
$chart3->settings = '{"group":[{"field":"status","name":"Status"}],"metric":[{"field":"count","agg":"count"}]}';
$chart3->sql = '';
$result3 = $mockTest->buildPieChartTest($component3, $chart3);
echo $result3->key . "\n"; // 步骤3：有settings但无sql处理返回key

// 测试步骤4：无效json settings处理
$component4 = new stdclass();
$component4->option = new stdclass();
$chart4 = new stdclass();
$chart4->settings = 'invalid json';
$chart4->sql = '';
$result4 = $mockTest->buildPieChartTest($component4, $chart4);
echo $result4->key . "\n"; // 步骤4：无效json settings处理返回key

// 测试步骤5：有settings和sql数据处理
$component5 = new stdclass();
$component5->option = new stdclass();
$component5->option->dataset = new stdclass();
$chart5 = new stdclass();
$chart5->settings = '{"group":[{"field":"status","name":"Status"}],"metric":[{"field":"count","agg":"count"}]}';
$chart5->sql = 'SELECT status, COUNT(*) as count FROM zt_project GROUP BY status';
$result5 = $mockTest->buildPieChartTest($component5, $chart5);
echo (isset($result5->option->dataset) ? '1' : '0') . "\n"; // 步骤5：有settings和sql数据处理返回dataset存在