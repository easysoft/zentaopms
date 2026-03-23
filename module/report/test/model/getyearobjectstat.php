#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->loadYaml('action_annual')->gen(200);
zenData('task')->gen(60);
zenData('bug')->gen(60);
zenData('story')->gen(60);
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getYearObjectStat();
timeout=0
cid=18183

- 测试获取 admin story @draft:3;
- 测试获取 admin task @wait:3;
- 测试获取 admin bug @0
- 测试获取 dev17 story @draft:3;
- 测试获取 dev17 task @doing:2;
- 测试获取 dev17 bug @active:1;
- 测试获取 test18 story @active:3;
- 测试获取 test18 task @0
- 测试获取 test18 bug @active:3;
- 测试获取 admin dev17 story @draft:6;
- 测试获取 admin dev17 task @wait:3;doing:2;
- 测试获取 admin dev17 bug @active:1;
- 测试获取 admin test18 story @draft:3;active:3;
- 测试获取 admin test18 task @wait:3;
- 测试获取 admin test18 bug @active:3;
- 测试获取 所有用户 story @draft:6;active:3;
- 测试获取 所有用户 task @wait:3;doing:2;
- 测试获取 所有用户 bug @active:4;

*/
$accounts   = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());
$objectType = array('story', 'task', 'bug');

$report = new reportModelTest();

r($report->getYearObjectStatTest($accounts[0], $objectType[0])) && p('', '|') && e('draft:3;');          // 测试获取 admin story
r($report->getYearObjectStatTest($accounts[0], $objectType[1])) && p('', '|') && e('wait:3;');           // 测试获取 admin task
r($report->getYearObjectStatTest($accounts[0], $objectType[2])) && p('', '|') && e('0');                 // 测试获取 admin bug
r($report->getYearObjectStatTest($accounts[1], $objectType[0])) && p('', '|') && e('draft:3;');          // 测试获取 dev17 story
r($report->getYearObjectStatTest($accounts[1], $objectType[1])) && p('', '|') && e('doing:2;');          // 测试获取 dev17 task
r($report->getYearObjectStatTest($accounts[1], $objectType[2])) && p('', '|') && e('active:1;');         // 测试获取 dev17 bug
r($report->getYearObjectStatTest($accounts[2], $objectType[0])) && p('', '|') && e('active:3;');         // 测试获取 test18 story
r($report->getYearObjectStatTest($accounts[2], $objectType[1])) && p('', '|') && e('0');                 // 测试获取 test18 task
r($report->getYearObjectStatTest($accounts[2], $objectType[2])) && p('', '|') && e('active:3;');         // 测试获取 test18 bug
r($report->getYearObjectStatTest($accounts[3], $objectType[0])) && p('', '|') && e('draft:6;');          // 测试获取 admin dev17 story
r($report->getYearObjectStatTest($accounts[3], $objectType[1])) && p('', '|') && e('wait:3;doing:2;');   // 测试获取 admin dev17 task
r($report->getYearObjectStatTest($accounts[3], $objectType[2])) && p('', '|') && e('active:1;');         // 测试获取 admin dev17 bug
r($report->getYearObjectStatTest($accounts[4], $objectType[0])) && p('', '|') && e('draft:3;active:3;'); // 测试获取 admin test18 story
r($report->getYearObjectStatTest($accounts[4], $objectType[1])) && p('', '|') && e('wait:3;');           // 测试获取 admin test18 task
r($report->getYearObjectStatTest($accounts[4], $objectType[2])) && p('', '|') && e('active:3;');         // 测试获取 admin test18 bug
r($report->getYearObjectStatTest($accounts[5], $objectType[0])) && p('', '|') && e('draft:6;active:3;'); // 测试获取 所有用户 story
r($report->getYearObjectStatTest($accounts[5], $objectType[1])) && p('', '|') && e('wait:3;doing:2;');   // 测试获取 所有用户 task
r($report->getYearObjectStatTest($accounts[5], $objectType[2])) && p('', '|') && e('active:4;');         // 测试获取 所有用户 bug