#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->buildTicketSearchForm();
timeout=0
cid=17278

- 测试获取queryID 1 actionURL actionURL1 的搜索表单 @1
- 测试获取queryID 0 actionURL actionURL2 的搜索表单 @1
- 测试获取queryID 0 actionURL actionURL2 的搜索表单 @1
- 测试获取queryID 0 actionURL actionURL3 的搜索表单 @1
- 测试获取queryID 1 actionURL actionURL1 的搜索表单 @1
- 测试获取queryID 1 actionURL actionURL3 的搜索表单 @1

*/

$my = new myTest();

$queryID   = array(0, 1);
$actionURL = array('actionURL1', 'actionURL2', 'actionURL3');
$config1 = $my->buildTicketSearchFormTest($queryID[0], $actionURL[0]);
$config2 = $my->buildTicketSearchFormTest($queryID[1], $actionURL[1]);
r($config1) && p() && e('1'); // 测试获取queryID 1 actionURL actionURL1 的搜索表单
r($config2) && p() && e('1'); // 测试获取queryID 0 actionURL actionURL2 的搜索表单
r($my->buildTicketSearchFormTest($queryID[0], $actionURL[1])) && p() && e('1'); // 测试获取queryID 0 actionURL actionURL2 的搜索表单
r($my->buildTicketSearchFormTest($queryID[0], $actionURL[2])) && p() && e('1'); // 测试获取queryID 0 actionURL actionURL3 的搜索表单
r($my->buildTicketSearchFormTest($queryID[1], $actionURL[0])) && p() && e('1'); // 测试获取queryID 1 actionURL actionURL1 的搜索表单
r($my->buildTicketSearchFormTest($queryID[1], $actionURL[2])) && p() && e('1'); // 测试获取queryID 1 actionURL actionURL3 的搜索表单
