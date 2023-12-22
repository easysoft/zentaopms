#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildPieChart();
timeout=0
cid=1

- 检查生成的饼图表头信息是否正确
 -  @状态
 - 属性1 @id
- 检查生成的饼图数据是否正确
 - 第0条的状态属性 @未设置
 - 第0条的id属性 @8
 - 第1条的状态属性 @未开始
 - 第1条的id属性 @4
 - 第2条的状态属性 @进行中
 - 第2条的id属性 @4
 - 第3条的状态属性 @已完成
 - 第3条的id属性 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('action')->config('action_for_pie')->gen(20);
zdTable('task')->gen(10);
zdTable('user')->gen(10);

$screen = new screenTest();

$components = $screen->getAllComponent();

$componentList = array();
foreach($components as $component)
{
    if(isset($component->sourceID) && $component->sourceID)
    {
        $chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component->sourceID)->fetch();

        if(!isset($chart->type)) continue;
        if(isset($chart->settings) && isset($chart->sql))
        {
            if(!isset($componentList['pie']) && $chart->type == 'pie')
            {
                if($chart->builtin !== 0)
                {
                    $componentList['pie'] = $component;
                    break;
                }
            }
        }
    }
}

isset($componentList['pie']) && $screen->buildPieChart($componentList['pie'], $chart);
$pie = $componentList['pie'] ?? null;

r($pie->option->dataset->dimensions) && p('0,1') && e('状态,id');                                                               //检查生成的饼图表头信息是否正确
r($pie->option->dataset->source) && p('0:状态,id;1:状态,id;2:状态,id;3:状态,id') && e('未设置,8;未开始,4;进行中,4;已完成,4');   //检查生成的饼图数据是否正确
