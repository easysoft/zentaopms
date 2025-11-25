#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->buildRiskSearchForm();
timeout=0
cid=17275

- 测试获取queryID 1 actionURL actionURL1 的搜索表单
 - 属性module @Risk
 - 属性queryID @0
 - 属性actionURL @actionURL1
- 测试获取queryID 0 actionURL actionURL2 的搜索表单
 - 属性module @Risk
 - 属性queryID @1
 - 属性actionURL @actionURL2

*/

$my = new myTest();

$queryID   = array(0, 1);
$actionURL = array('actionURL1', 'actionURL2');
$config1 = $my->buildRiskSearchFormTest($queryID[0], $actionURL[0]);
$config2 = $my->buildRiskSearchFormTest($queryID[1], $actionURL[1]);
r($config1) && p('module,queryID,actionURL') && e('Risk,0,actionURL1'); // 测试获取queryID 1 actionURL actionURL1 的搜索表单
r($config2) && p('module,queryID,actionURL') && e('Risk,1,actionURL2'); // 测试获取queryID 0 actionURL actionURL2 的搜索表单