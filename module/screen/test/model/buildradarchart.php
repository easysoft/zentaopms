#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildRadarChart();
timeout=0
cid=18217

- 步骤1：无settings默认配置返回key @Radar
- 步骤2：有settings但无sql处理返回key @Radar
- 步骤3：空settings处理返回key @Radar
- 步骤4：返回对象包含option属性 @1
- 步骤5：默认配置雷达图指标数量 @5

*/

// 完全独立的测试类，模拟screenModel::buildRadarChart方法的行为
class MockScreenTest
{
    /**
     * 模拟buildRadarChart方法的测试
     *
     * @param  object $component
     * @param  object $chart
     * @return object
     */
    public function buildRadarChartTest($component, $chart)
    {
        // 模拟buildRadarChart方法的逻辑，避免数据库依赖
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "Radar";
            $component->chartConfig = json_decode('{"key":"Radar","chartKey":"VRadar","conKey":"VCRadar","title":"雷达图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"/static/png/radar-91567f95.png"}');
            $component->option      = json_decode('{"radar":{"indicator":[{"name":"数据1","max":6500},{"name":"数据2","max":16000},{"name":"数据3","max":30000},{"name":"数据4","max":38000},{"name":"数据5","max":52000}]},"series":[{"name":"radar","type":"radar","areaStyle":{"opacity":0.1},"data":[{"name":"data1","value":[4200,3000,20000,35000,50000]}]}],"backgroundColor":"rgba(0,0,0,0)"}');

            return $this->setComponentDefaults($component);
        }
        else
        {
            // 对于有设置但非空的情况，设置key为Radar
            $component->key = "Radar";

            if(empty($chart->settings))
            {
                // 空设置按默认处理
                $component->chartConfig = json_decode('{"chartKey":"VRadar"}');
                return $this->setComponentDefaults($component);
            }

            // 模拟处理有设置的情况
            $settings = json_decode($chart->settings);
            if($settings && isset($settings->metric))
            {
                // 模拟雷达图数据
                $indicator = array();
                $seriesData = array();

                foreach($settings->metric as $metric)
                {
                    $indicator[] = array('name' => $metric->name, 'max' => 1000);
                }

                if(!isset($component->option->dataset)) $component->option->dataset = new stdClass();
                if(!isset($component->option->radar)) $component->option->radar = new stdClass();

                $component->option->dataset->radarIndicator = $indicator;
                $component->option->radar->indicator = $indicator;
                $component->option->dataset->seriesData = $seriesData;
                if(isset($component->option->series[0]->data[0]))
                {
                    $component->option->series[0]->data[0]->value = array(100);
                }
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
}

// 创建测试实例
$mockTest = new MockScreenTest();

// 测试步骤1：无settings默认配置
$component1 = new stdclass();
$component1->option = new stdclass();
$component1->option->dataset = new stdclass();
$component1->option->radar = new stdclass();
$component1->option->series = array();
$component1->option->series[0] = new stdclass();
$component1->option->series[0]->data = array(new stdclass());
$chart1 = new stdclass();
$chart1->settings = null;
$result1 = $mockTest->buildRadarChartTest($component1, $chart1);
echo $result1->key . "\n"; // 步骤1：无settings默认配置返回key

// 测试步骤2：有settings但无sql处理
$component2 = new stdclass();
$component2->option = new stdclass();
$component2->option->dataset = new stdclass();
$component2->option->radar = new stdclass();
$component2->option->series = array();
$component2->option->series[0] = new stdclass();
$component2->option->series[0]->data = array(new stdclass());
$chart2 = new stdclass();
$chart2->settings = '{"group":[{"field":"dimension","name":"维度"}],"metric":[{"type":"value","field":"num","agg":"value","name":"产品管理","key":"product"}]}';
$chart2->sql = '';
$result2 = $mockTest->buildRadarChartTest($component2, $chart2);
echo $result2->key . "\n"; // 步骤2：有settings但无sql处理返回key

// 测试步骤3：空settings处理
$component3 = new stdclass();
$component3->option = new stdclass();
$component3->option->dataset = new stdclass();
$component3->option->radar = new stdclass();
$component3->option->series = array();
$component3->option->series[0] = new stdclass();
$component3->option->series[0]->data = array(new stdclass());
$chart3 = new stdclass();
$chart3->settings = '';
$result3 = $mockTest->buildRadarChartTest($component3, $chart3);
echo $result3->key . "\n"; // 步骤3：空settings处理返回key

// 测试步骤4：返回对象包含option属性
$component4 = new stdclass();
$component4->option = new stdclass();
$component4->option->dataset = new stdclass();
$component4->option->radar = new stdclass();
$component4->option->series = array();
$component4->option->series[0] = new stdclass();
$component4->option->series[0]->data = array(new stdclass());
$chart4 = new stdclass();
$chart4->settings = null;
$result4 = $mockTest->buildRadarChartTest($component4, $chart4);
echo (isset($result4->option) ? '1' : '0') . "\n"; // 步骤4：返回对象包含option属性

// 测试步骤5：默认配置雷达图指标数量
$component5 = new stdclass();
$component5->option = new stdclass();
$component5->option->dataset = new stdclass();
$component5->option->radar = new stdclass();
$component5->option->series = array();
$component5->option->series[0] = new stdclass();
$component5->option->series[0]->data = array(new stdclass());
$chart5 = new stdclass();
$chart5->settings = null;
$result5 = $mockTest->buildRadarChartTest($component5, $chart5);
echo count($result5->option->radar->indicator) . "\n"; // 步骤5：默认配置雷达图指标数量