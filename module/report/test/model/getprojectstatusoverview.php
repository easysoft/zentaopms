#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(50);
zenData('team')->loadYaml('team')->gen(50);
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getProjectStatusOverview();
cid=18166

- 获取项目状态 @doing:20;suspended:5;closed:5;wait:10;
- 获取 admin 的项目状态 @doing:1;
- 获取 dev17 的项目状态 @doing:1;closed:1;
- 获取 test18 的项目状态 @suspended:1;doing:1;closed:1;
- 获取 admin,dev17 的项目状态 @doing:2;closed:1;
- 获取 admin,test18 的项目状态 @doing:2;suspended:1;closed:1;

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'));

$report = new reportModelTest();

r($report->getProjectStatusOverviewTest())            && p() && e('doing:20;suspended:5;closed:5;wait:10;'); // 获取项目状态
r($report->getProjectStatusOverviewTest($account[0])) && p() && e('doing:1;');                               // 获取 admin 的项目状态
r($report->getProjectStatusOverviewTest($account[1])) && p() && e('doing:1;closed:1;');                      // 获取 dev17 的项目状态
r($report->getProjectStatusOverviewTest($account[2])) && p() && e('suspended:1;doing:1;closed:1;');          // 获取 test18 的项目状态
r($report->getProjectStatusOverviewTest($account[3])) && p() && e('doing:2;closed:1;');                      // 获取 admin,dev17 的项目状态
r($report->getProjectStatusOverviewTest($account[4])) && p() && e('doing:2;suspended:1;closed:1;');          // 获取 admin,test18 的项目状态
