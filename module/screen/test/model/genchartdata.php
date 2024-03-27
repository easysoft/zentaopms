#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 screenModel->genChartDataTest();
timeout=0
cid=1

- 测试id为1的大屏，宽度和高度。
 - 属性width @1300
 - 属性height @ 3267
- 执行$componentList
 - 第0条的id属性 @5scfjzqsbzo000
 - 第43条的id属性 @1j55da3c41vk00
- 测试传入的过滤参数是否正常赋值。
 - 属性year @2022
 - 属性dept @1
 - 属性account @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('screen')->gen(0);

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

list($schema, $filter) = $screenTest->genChartDataTest($screenList[1], $yearList[0], 0, $deptList[0], $accountList[0]);
$editCanvasConfig = $schema->editCanvasConfig;
$componentList    = $schema->componentList;

r($editCanvasConfig) && p('width,height') && e('1300, 3267'); // 测试id为1的大屏，宽度和高度。
r($componentList) && p('0:id;43:id') && e('5scfjzqsbzo000;1j55da3c41vk00');

list($schmea, $filter) = $screenTest->genChartDataTest($screenList[5], $yearList[1], 0, $deptList[1], $accountList[1]);
r($filter) && p('year,dept,account') && e('2022,1,admin');  //测试传入的过滤参数是否正常赋值。
