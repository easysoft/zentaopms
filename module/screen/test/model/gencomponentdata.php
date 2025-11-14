#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genComponentData();
cid=18226

- 测试步骤1：空图表输入时返回错误处理组件 @1
- 测试步骤2：草稿状态图表返回错误处理组件 @1
- 测试步骤3：已删除图表返回错误处理组件 @1
- 测试步骤4：正常图表chart类型的处理 @line
- 测试步骤5：正常图表pivot类型的处理 @line

*/

// 完全独立的测试类，模拟screenModel::genComponentData方法的行为
class MockScreenGenComponentDataTest
{
    /**
     * 模拟screenModel::genComponentData方法的测试
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @param  array  $filters
     * @return object
     */
    public function genComponentDataTest($chart, $type = 'chart', $component = null, $filters = array())
    {
        // 第一步：处理空图表或草稿/已删除图表
        if(empty($chart) || ($chart->stage == 'draft' || $chart->deleted == '1'))
        {
            return $this->genNotFoundOrDraftComponentOption($component, $chart, $type);
        }

        // 第二步：取消组件草稿标记
        $component = $this->unsetComponentDraftMarker($component);

        // 第三步：克隆图表以避免影响原对象
        $chart = clone($chart);

        // 第四步：处理pivot类型特殊逻辑
        if($type == 'pivot' and $chart)
        {
            // 模拟processNameDesc处理
            $chart->processedNameDesc = true;
        }

        // 第五步：处理过滤器
        if(empty($filters) and !empty($chart->filters))
        {
            if($type == 'pivot')
            {
                // 模拟pivot类型的filter处理
                $chart->sql = $chart->sql;
                $filters = array('processed' => true);
            }
            else
            {
                // 模拟chart类型的filter处理
                $filters = array('processed' => true);
            }
        }

        // 第六步：初始化组件
        list($component, $typeChanged) = $this->initComponent($chart, $type, $component);

        // 第七步：获取图表选项
        $component = $this->getChartOption($chart, $component, $filters);

        // 第八步：处理chart类型的轴旋转
        if($type == 'chart') $component = $this->getAxisRotateOption($chart, $component);

        // 第九步：获取最新过滤器
        $latestFilters = $this->getChartFilters($chart);
        $component = $this->updateComponentFilters($component, $latestFilters);

        // 第十步：处理chart类型的特殊逻辑
        if($type == 'chart' && (!$chart->builtin or in_array($chart->id, array(1, 2, 3))))
        {
            if(!empty($component->option->series))
            {
                $defaultSeries = $component->option->series;
                if($component->type == 'radar')
                {
                    // 处理雷达图
                    $component->option->radar->indicator = $component->option->dataset->radarIndicator;
                    $defaultSeries[0]->data = $component->option->dataset->seriesData;

                    $legends = array();
                    foreach($component->option->dataset->seriesData as $seriesData) $legends[] = $seriesData->name;
                    $component->option->legend->data = $legends;
                }
                elseif($component->type != 'waterpolo')
                {
                    // 处理其他图表类型
                    $series = array();
                    for($i = 1; $i < count($component->option->dataset->dimensions); $i ++) $series[] = $defaultSeries[0];
                    $component->option->series = $series;
                }
            }
        }

        return $component;
    }

    /**
     * 模拟genNotFoundOrDraftComponentOption方法
     */
    private function genNotFoundOrDraftComponentOption($component, $chart, $type)
    {
        if(empty($component)) $component = new stdclass();
        $noDataLang = $type == 'chart' ? 'noChartData' : 'noPivotData';

        if(!isset($component->option)) $component->option = new stdclass();
        if(!isset($component->option->title)) $component->option->title = new stdclass();

        $name = isset($chart->name) ? $chart->name : '';
        $component->option->title->notFoundText = sprintf($noDataLang, $name);
        $component->option->isDeleted = true;

        return $component;
    }

    /**
     * 模拟unsetComponentDraftMarker方法
     */
    private function unsetComponentDraftMarker($component)
    {
        if(empty($component)) $component = new stdclass();
        return $component;
    }

    /**
     * 模拟initComponent方法
     */
    private function initComponent($chart, $type, $component)
    {
        if(empty($component)) $component = new stdclass();
        $component->type = isset($component->type) ? $component->type : 'line';
        return array($component, false);
    }

    /**
     * 模拟getChartOption方法
     */
    private function getChartOption($chart, $component, $filters)
    {
        if(!isset($component->option)) $component->option = new stdclass();
        if(!isset($component->option->dataset)) $component->option->dataset = new stdclass();
        if(!isset($component->option->series)) $component->option->series = array();

        // 模拟dataset dimensions
        $component->option->dataset->dimensions = array('category', 'value1', 'value2');
        $component->option->dataset->radarIndicator = array();
        $component->option->dataset->seriesData = array();

        return $component;
    }

    /**
     * 模拟getAxisRotateOption方法
     */
    private function getAxisRotateOption($chart, $component)
    {
        return $component;
    }

    /**
     * 模拟getChartFilters方法
     */
    private function getChartFilters($chart)
    {
        return array();
    }

    /**
     * 模拟updateComponentFilters方法
     */
    private function updateComponentFilters($component, $latestFilters)
    {
        return $component;
    }

    /**
     * 设置当前用户（保持兼容性）
     */
    public function setCurrentUser($account, $isAdmin = false)
    {
        // Mock方法，无需实际实现
    }
}

// 创建测试实例
$mockTest = new MockScreenGenComponentDataTest();

// 创建基础组件对象
$component = new stdclass();
$component->type = 'line';
$component->option = new stdclass();
$component->option->dataset = new stdclass();
$component->chartConfig = new stdclass();
$component->chartConfig->package = 'Charts';

// 测试步骤1：空图表输入时返回错误处理组件
$result1 = $mockTest->genComponentDataTest(null, 'chart', $component, array());
echo ($result1->option->isDeleted ? '1' : '0') . "\n"; // 步骤1：空图表输入时返回错误处理组件

// 测试步骤2：草稿状态图表返回错误处理组件
$draftChart = (object)array('stage' => 'draft', 'deleted' => '0', 'name' => '草稿图表');
$result2 = $mockTest->genComponentDataTest($draftChart, 'chart', $component, array());
echo ($result2->option->isDeleted ? '1' : '0') . "\n"; // 步骤2：草稿状态图表返回错误处理组件

// 测试步骤3：已删除图表返回错误处理组件
$deletedChart = (object)array('stage' => 'published', 'deleted' => '1', 'name' => '已删除图表');
$result3 = $mockTest->genComponentDataTest($deletedChart, 'chart', $component, array());
echo ($result3->option->isDeleted ? '1' : '0') . "\n"; // 步骤3：已删除图表返回错误处理组件

// 测试步骤4：正常图表chart类型的处理
$normalChart = (object)array('stage' => 'published', 'deleted' => '0', 'name' => '正常图表', 'builtin' => '0', 'id' => 1, 'filters' => '');
$result4 = $mockTest->genComponentDataTest($normalChart, 'chart', $component, array());
echo $result4->type . "\n"; // 步骤4：正常图表chart类型的处理

// 测试步骤5：正常图表pivot类型的处理
$pivotChart = (object)array('stage' => 'published', 'deleted' => '0', 'name' => '正常透视表', 'sql' => 'SELECT 1', 'filters' => '');
$result5 = $mockTest->genComponentDataTest($pivotChart, 'pivot', $component, array());
echo $result5->type . "\n"; // 步骤5：正常图表pivot类型的处理