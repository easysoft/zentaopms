#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getProjectStatusOverview();
cid=1
pid=1

获取项目状态 >> wait:24;doing:44;suspended:11;closed:11;
获取 admin pm92 pm1 为团队成员的项目状态 >> wait:1;
获取 user1 user10 为团队成员的项目状态 >> wait:1;
获取 pm92 po2 为团队成员的项目状态 >> wait:1;doing:1;
获取 user20 user21 为团队成员的项目状态 >> doing:2;
获取 pm92 po10 为团队成员的项目状态 >> wait:1;doing:1;

*/
$account = array('admin,pm92,pm1', 'user1,user10', ' pm92,po2', 'user20,user21', 'pm92,po10');

$report = new reportTest();

r($report->getProjectStatusOverviewTest())            && p() && e('wait:24;doing:44;suspended:11;closed:11;'); // 获取项目状态
r($report->getProjectStatusOverviewTest($account[0])) && p() && e('wait:1;');                                  // 获取 admin pm92 pm1 为团队成员的项目状态
r($report->getProjectStatusOverviewTest($account[1])) && p() && e('wait:1;');                                  // 获取 user1 user10 为团队成员的项目状态
r($report->getProjectStatusOverviewTest($account[2])) && p() && e('wait:1;doing:1;');                          // 获取 pm92 po2 为团队成员的项目状态
r($report->getProjectStatusOverviewTest($account[3])) && p() && e('doing:2;');                                 // 获取 user20 user21 为团队成员的项目状态
r($report->getProjectStatusOverviewTest($account[4])) && p() && e('wait:1;doing:1;');                          // 获取 pm92 po10 为团队成员的项目状态