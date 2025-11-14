#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->buildEpicSearchForm();
timeout=0
cid=17272

- 测试获取queryID 0 actionURL actionURL1 的搜索表单
 - 属性module @epicEpic
 - 属性queryID @0
 - 属性actionURL @actionURL1
- 测试获取queryID 1 actionURL actionURL2 的搜索表单
 - 属性module @epicEpic
 - 属性queryID @1
 - 属性actionURL @actionURL2
- 测试获取queryID 2 actionURL actionURL3 的搜索表单
 - 属性module @epicEpic
 - 属性queryID @2
 - 属性actionURL @actionURL3
- 测试获取queryID 3 actionURL actionURL3 的搜索表单
 - 属性module @epicEpic
 - 属性queryID @3
 - 属性actionURL @actionURL4
- 测试获取queryID 4 actionURL actionURL4 的搜索表单
 - 属性module @epicEpic
 - 属性queryID @4
 - 属性actionURL @actionURL5

*/

global $lang;
$lang->SRCommon = '研发需求';
$lang->URCommon = '用户需求';
$lang->ERCommon = '业务需求';

$my = new myTest();

$queryID   = array(0, 1, 2, 3, 4);
$actionURL = array('actionURL1', 'actionURL2', 'actionURL3', 'actionURL4', 'actionURL5');
$config1 = $my->buildEpicSearchFormTest($queryID[0], $actionURL[0]);
$config2 = $my->buildEpicSearchFormTest($queryID[1], $actionURL[1]);
$config3 = $my->buildEpicSearchFormTest($queryID[2], $actionURL[2]);
$config4 = $my->buildEpicSearchFormTest($queryID[3], $actionURL[3]);
$config5 = $my->buildEpicSearchFormTest($queryID[4], $actionURL[4]);
r($config1) && p('module,queryID,actionURL') && e('epicEpic,0,actionURL1'); // 测试获取queryID 0 actionURL actionURL1 的搜索表单
r($config2) && p('module,queryID,actionURL') && e('epicEpic,1,actionURL2'); // 测试获取queryID 1 actionURL actionURL2 的搜索表单
r($config3) && p('module,queryID,actionURL') && e('epicEpic,2,actionURL3'); // 测试获取queryID 2 actionURL actionURL3 的搜索表单
r($config4) && p('module,queryID,actionURL') && e('epicEpic,3,actionURL4'); // 测试获取queryID 3 actionURL actionURL3 的搜索表单
r($config5) && p('module,queryID,actionURL') && e('epicEpic,4,actionURL5'); // 测试获取queryID 4 actionURL actionURL4 的搜索表单
