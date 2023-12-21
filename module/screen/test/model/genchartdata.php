#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 screenModel->genChartDataTest();
cid=1
pid=1

- 测试id为1的screen生成的chartData是否正确。@1300,3267,normal,dark;30,~~,second
- id为5的燃尽图，不需要做任何的图片处理。@N/A
- 测试传入的过滤参数是否正常赋值。@2022,1,admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screenTest = new screenTest();
$screenIDList = array(1, 5, 8);
$screenList   = array();

foreach($screenIDList as $screenID)
{
    $screenList[$screenID] = $tester->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();
}

$yearList = array(0, 2022);
$deptList = array(0 ,1);
$accountList = array('', 'admin');

list($chart, $filter) = $screenTest->genChartDataTest($screenList[1], $yearList[0], $deptList[0], $accountList[0]);

r($chart) && p('editCanvasConfig:width,height,blendMode,chartThemeColor;requestGlobalConfig:requestInterval,requestOriginUrl,requestIntervalUnit') && e('1300,3267,normal,dark;30,~~,second');  //测试id为1的screen生成的chartData是否正确。
r($chart->componentList) && p('0:id;43:id') && e('5scfjzqsbzo000;1j55da3c41vk00');

list($chart1, $filter) = $screenTest->genChartDataTest($screenList[5], $yearList[1], $deptList[1], $accountList[1]);
r($chart1) && p('editCanvasConfig') && e('~~');  //id为5的燃尽图，不需要做任何的图片处理。
r($filter) && p('year,dept,account') && e('2022,1,admin');  //测试传入的过滤参数是否正常赋值。
