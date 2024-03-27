#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('dept')->gen(1);

/**

title=测试 screenModel->getByID();
timeout=0
cid=1

- 测试不存在的screenID @0
- 测试存在的screenID属性id @1
- 测试screen存在测试year值为2019的情况 @1
- 测试screen存在测试dept值为1的情况 @1
- 测试screen存在测试account值为admin的情况 @1
- 测试screenID为6的情况 @1

*/

zdTable('screen')->gen(0);

$screen = new screenTest();

$screenIDList = array(0, 1, 2, 3, 4, 5, 6, 7, 8);
$yearList     = array(0, 2019);
$deptList     = array(0, 1, 2);
$accountList  = array('', 'admin', 'user');

r($screen->getByIDTest($screenIDList[0], $yearList[0], 0, $deptList[0], $accountList[0])) && p('')   && e(0);  //测试不存在的screenID
r($screen->getByIDTest($screenIDList[1], $yearList[0], 0, $deptList[0], $accountList[0])) && p('id') && e(1);  //测试存在的screenID

$res = $screen->getByIDTest($screenIDList[2], $yearList[1], 0, $deptList[0], $accountList[0]);
$chart = null;
foreach($res->chartData->componentList as $item){
    if(!(isset($item->groupList) && is_array($item->groupList))) continue;

    foreach($item->groupList as $component)
    {
        if(isset($component->key) && $component->key == 'Select')
        {
            $chart = $component;
        }
    }
}
r($chart && $chart->option->value == '2019' && strpos($chart->option->onChange, 'location') !== false) && p() && e(1);  //测试screen存在测试year值为2019的情况

$res = $screen->getByIDTest($screenIDList[3], $yearList[0], 0, $deptList[1], $accountList[1]);
$chart     = null;
$chart1    = null;
$maxHeight = 0;
foreach($res->chartData->componentList as $item)
{
    $height = $item->attr->y + $item->attr->h;
    if($maxHeight < $height) $maxHeight = $height;

    if(!(isset($item->groupList) && is_array($item->groupList))) continue;

    foreach($item->groupList as $component)
    {
        if(isset($component->type) && $component->type == 'dept')
        {
            $chart = $component;
        }

        if(isset($component->type) && $component->type == 'account')
        {
            $chart1 = $component;
        }
    }
}

r($chart  && $chart->option->value  == 1       && strpos($chart->option->onChange,  'location') !== false) && p() && e(1);  //测试screen存在测试dept值为1的情况
r($chart1 && $chart1->option->value == 'admin' && strpos($chart1->option->onChange, 'location') !== false) && p() && e(1);  //测试screen存在测试account值为admin的情况

$result = $screen->getByIDTest($screenIDList[6], $yearList[0], 0, $deptList[0], $accountList[0]);
$chart2 = null;
foreach($result->chartData->componentList as $component)
{
    if(isset($component->chartConfig->package) && $component->chartConfig->package == 'Tables')
    {
        $chart2 = $component;
    }
}

r($chart2 && $chart2->chartConfig->dataset == $chart2->option->dataset) && p('') && e(1);  //测试screenID为6的情况
