#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeZen::sortAndPage();
timeout=0
cid=18391

- 执行sonarqubeTest模块的sortAndPageTest方法，参数是$dataList, 'id_desc', 5, 1 第0条的id属性 @10
- 执行sonarqubeTest模块的sortAndPageTest方法，参数是$dataList, 'id_asc', 5, 1 第0条的id属性 @1
- 执行sonarqubeTest模块的sortAndPageTest方法，参数是$dataList, 'id_desc', 3, 2 第0条的id属性 @7
- 执行sonarqubeTest模块的sortAndPageTest方法，参数是array  @0
- 执行sonarqubeTest模块的sortAndPageTest方法，参数是$dataList, 'id_desc', 20, 1  @10
- 执行sonarqubeTest模块的sortAndPageTest方法，参数是$dataList, 'name_asc', 5, 1 第0条的name属性 @name1
- 执行sonarqubeTest模块的sortAndPageTest方法，参数是$dataList, 'title_desc', 4, 1 第0条的title属性 @title9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqubeZen.unittest.class.php';

global $app;
$app->rawModule = 'sonarqube';
$app->rawMethod = 'browse';
$app->setModuleName('sonarqube');
$app->setMethodName('ajaxtest');

su('admin');

$sonarqubeTest = new sonarqubeZenTest();

$dataList = array();
for($i = 1; $i <= 10; $i++)
{
    $data = new stdclass();
    $data->id = $i;
    $data->name = 'name' . $i;
    $data->title = 'title' . $i;
    $dataList[] = $data;
}

r($sonarqubeTest->sortAndPageTest($dataList, 'id_desc', 5, 1)) && p('0:id') && e('10');
r($sonarqubeTest->sortAndPageTest($dataList, 'id_asc', 5, 1)) && p('0:id') && e('1');
r($sonarqubeTest->sortAndPageTest($dataList, 'id_desc', 3, 2)) && p('0:id') && e('7');
r(count($sonarqubeTest->sortAndPageTest(array(), 'id_desc', 5, 1))) && p() && e('0');
r(count($sonarqubeTest->sortAndPageTest($dataList, 'id_desc', 20, 1))) && p() && e('10');
r($sonarqubeTest->sortAndPageTest($dataList, 'name_asc', 5, 1)) && p('0:name') && e('name1');
r($sonarqubeTest->sortAndPageTest($dataList, 'title_desc', 4, 1)) && p('0:title') && e('title9');