#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';
su('admin');

zdTable('project')->config('project_for_card')->gen(50);
zdTable('story')->gen(20);
zdTable('bug')->gen(20);

/**

title=测试 screenModel->getchartoption();
timeout=0
cid=1

- 测试type为card以及统计方式类型为value的图表是否显示正确，目前不存在统计方式为value的图表。 @1
- 测试type为card以及统计方式类型为count的图表是否显示正确，目前不存在统计方式为count的图表。 @1
- 测试type为card以及统计方式类型为sum的图表是否显示正确，生成的数据项数量是否正确。 @50

*/

$screen = new screenTest();

function getComponetAndChart($screen, $valueType = array(), $filters = array())
{
    global $tester;
    $componets = $screen->getAllComponent($filters);
    foreach($componets as $componet)
    {
        if(!isset($componet->sourceID)) continue;
        $type  = $componet->chartConfig->package == 'Tables' ? 'pivot' : 'chart';
        $table = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $chart = $tester->dao->select('*')->from($table)->where('id')->eq($componet->sourceID)->fetch();

        if(!$chart) continue;
        if(!empty($valueType))
        {
            $setting = json_decode($chart->settings);
            if($setting && isset($setting->value))
            {
                if(isset($valueType['type']) && $setting->value->type != $valueType['type']) continue;
                if(isset($valueType['egg']) && $setting->value->agg != $valueType['egg']) continue;
            }
        }

        return array($componet, $chart);
    }
    return array(null, null);
}

$filter12 = array('type' => 'card');

list($component13, $chart13) = getComponetAndChart($screen, array('type' => 'value'), $filter12); 
r(is_null($component13) || is_null($chart13)) && p('') && e(1);  //测试type为card以及统计方式类型为value的图表是否显示正确，目前不存在统计方式为value的图表。

list($component14, $chart14) = getComponetAndChart($screen, array('type' => 'count'), $filter12);
r(is_null($component14) || is_null($chart14)) && p('') && e(1);  //测试type为card以及统计方式类型为count的图表是否显示正确，目前不存在统计方式为count的图表。

list($component15, $chart15) = getComponetAndChart($screen, array('egg' => 'sum'), $filter12);
$screen->buildCardChart($component15, $chart15);
r($component15->option->dataset) && p('') && e('50');  //测试type为card以及统计方式类型为sum的图表是否显示正确，生成的数据项数量是否正确。