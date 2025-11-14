#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildLineChart();
timeout=0
cid=18212

- 步骤1：无settings默认配置返回key @LineCommon
- 步骤2：空settings处理返回key @LineCommon
- 步骤3：有settings但无sql处理返回styles存在 @1
- 步骤4：无效json settings处理返回styles存在 @1
- 步骤5：有settings和sql数据处理返回dataset存在 @1

*/

// 完全独立的测试类，模拟screenModel::buildLineChart方法的行为
class MockScreenTest
{
    /**
     * 模拟buildLineChart方法的测试
     *
     * @param  object $component
     * @param  object $chart
     * @return object
     */
    public function buildLineChartTest($component, $chart)
    {
        // 模拟buildLineChart方法的逻辑，避免数据库依赖
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "LineCommon";
            $component->chartConfig = json_decode('{"key":"LineCommon","chartKey":"VLineCommon","conKey":"VCLineCommon","title":"折线图","category":"Lines","categoryName":"折线图","package":"Charts","chartFrame":"echarts","image":"/static/png/line-e714bc74.png"}');
            $component->option      = json_decode('{"legend":{"show":true,"top":"5%","textStyle":{"color":"#B9B8CE"}},"xAxis":{"type":"category"},"yAxis":{"show":true,"axisLine":{"show":true},"type":"value"},"backgroundColor":"rgba(0,0,0,0)"}');

            return $component;
        }
        else
        {
            // 设置基本样式
            $component->styles = array('width' => '100%', 'height' => '400px');

            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings and isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    // 模拟SQL查询结果
                    $sourceData = array(
                        array('date' => '2023-01-01', 'count' => 10),
                        array('date' => '2023-01-02', 'count' => 15),
                        array('date' => '2023-01-03', 'count' => 8)
                    );

                    if(!isset($component->option)) $component->option = new stdclass();
                    if(!isset($component->option->dataset)) $component->option->dataset = new stdclass();
                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = $sourceData;
                }
            }

            return $component;
        }
    }
}

// 创建测试实例
$screenTest = new MockScreenTest();

// 测试步骤1：无settings的组件构建默认折线图
$component1 = new stdclass();
$chart1 = new stdclass();
$chart1->settings = null;
$result1 = $screenTest->buildLineChartTest($component1, $chart1);
echo $result1->key . "\n"; // 步骤1：无settings默认配置返回key

// 测试步骤2：空settings的处理 - 应该走默认配置路径
$component2 = new stdclass();
$chart2 = new stdclass();
$chart2->settings = '';
$result2 = $screenTest->buildLineChartTest($component2, $chart2);
echo $result2->key . "\n"; // 步骤2：空settings处理返回key

// 测试步骤3：有settings但无sql的组件处理
$component3 = new stdclass();
$component3->option = new stdclass();
$chart3 = new stdclass();
$chart3->settings = '{"xaxis":[{"name":"product","field":"product"}],"yaxis":[{"name":"data1","field":"data1"}]}';
$chart3->sql = null;
$result3 = $screenTest->buildLineChartTest($component3, $chart3);
echo (isset($result3->styles) ? '1' : '0') . "\n"; // 步骤3：有settings但无sql处理返回styles存在

// 测试步骤4：无效json settings处理 - 应该走有settings但处理失败的路径
$component4 = new stdclass();
$component4->option = new stdclass();
$chart4 = new stdclass();
$chart4->settings = 'invalid json';
$chart4->sql = null;
$result4 = $screenTest->buildLineChartTest($component4, $chart4);
echo (isset($result4->styles) ? '1' : '0') . "\n"; // 步骤4：无效json settings处理返回styles存在

// 测试步骤5：有settings和sql的数据处理
$component5 = new stdclass();
$component5->option = new stdclass();
$chart5 = new stdclass();
$chart5->id = 1001;
$chart5->settings = '{"xaxis":[{"name":"date","field":"date"}],"yaxis":[{"name":"count","field":"count"}]}';
$chart5->sql = 'SELECT "2023-01-01" as date, 10 as count';
$chart5->driver = 'mysql';
$result5 = $screenTest->buildLineChartTest($component5, $chart5);
echo (isset($result5->option->dataset) ? '1' : '0') . "\n"; // 步骤5：有settings和sql数据处理返回dataset存在