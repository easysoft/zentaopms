#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::getNotifyList();
timeout=0
cid=17994

- 执行releaseTest模块的getNotifyListTest方法，参数是1  @admin
- 执行releaseTest模块的getNotifyListTest方法，参数是2  @admin
- 执行releaseTest模块的getNotifyListTest方法，参数是3  @admin
- 执行releaseTest模块的getNotifyListTest方法，参数是4  @admin
- 执行releaseTest模块的getNotifyListTest方法，参数是99  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('release')->gen(0);
zenData('product')->gen(0);
zenData('story')->gen(0);
zenData('build')->gen(0);
zenData('team')->gen(0);
zenData('user')->gen(0);

global $tester;
$tester->dao->exec("INSERT INTO zt_release (id, product, name, notify, mailto, stories, bugs, build, project, status, deleted, createdBy, createdDate, shadow, branch, marker) VALUES 
(1, 1, 'Release 1.0', 'PO,QD', 'user1@test.com,user2@test.com', '1,2,3', '1,2', '1,2', '1,2', 'normal', '0', 'admin', '2025-09-09 10:00:00', 0, '0', '0'),
(2, 2, 'Release 1.1', '', '', '', '', '3', '3', 'normal', '0', 'admin', '2025-09-09 10:00:00', 0, '0', '0'),
(3, 3, 'Release 1.2', 'CT', 'user3@test.com', '4,5', '3,4', '4,5', '4', 'normal', '0', 'admin', '2025-09-09 10:00:00', 0, '0', '0'),
(4, 4, 'Release 1.3', 'SC,ET,PT', 'user4@test.com', '6,7,8', '5,6', '6,7,8', '5,6', 'normal', '0', 'admin', '2025-09-09 10:00:00', 0, '0', '0'),
(10, 5, 'Release Deleted', 'PO,QD,CT,SC', 'user5@test.com', '1,2,3,4,5', '1,2,3,4,5,6', '1,2,3', '1,2,3', 'terminate', '1', 'admin', '2025-09-09 10:00:00', 0, '0', '1')");

$tester->dao->exec("INSERT INTO zt_product (id, name, PO, QD, feedback) VALUES 
(1, 'Product A', 'po1', 'qd1', 'fb1'),
(2, 'Product B', 'po2', 'qd2', 'fb2'),
(3, 'Product C', 'po3', 'qd3', 'fb3'),
(4, 'Product D', 'po4', 'qd4', 'fb4'),
(5, 'Product E', 'po5', 'qd5', 'fb5')");

$tester->dao->exec("INSERT INTO zt_story (id, openedBy, notifyEmail, deleted) VALUES 
(1, 'story1', 'story1@test.com', '0'),
(2, 'story2', 'story2@test.com', '0'),
(3, 'story3', 'story3@test.com', '0'),
(4, 'story4', 'story4@test.com', '0'),
(5, 'story5', 'story5@test.com', '0'),
(6, 'story6', '', '0'),
(7, 'story7', '', '0'),
(8, 'story8', '', '0')");

$tester->dao->exec("INSERT INTO zt_build (id, execution, project, builds, deleted) VALUES 
(1, 1, 1, '', '0'),
(2, 1, 1, '1,2', '0'),
(3, 2, 2, '', '0'),
(4, 3, 3, '3,4', '0'),
(5, 3, 3, '', '0'),
(6, 4, 4, '5,6', '0'),
(7, 4, 4, '', '0'),
(8, 5, 5, '7,8', '0')");

$tester->dao->exec("INSERT INTO zt_team (root, account, type) VALUES 
(1, 'team1', 'execution'),
(2, 'team2', 'execution'),
(3, 'team3', 'execution'),
(4, 'team4', 'project'),
(5, 'team5', 'project')");

$tester->dao->exec("INSERT INTO zt_user (account, realname) VALUES 
('admin', 'Administrator'),
('po1', 'PO 1'),
('qd1', 'QD 1'),
('team1', 'Team 1'),
('team2', 'Team 2'),
('team3', 'Team 3'),
('team4', 'Team 4'),
('team5', 'Team 5')");

su('admin');

$releaseTest = new releaseModelTest();

r($releaseTest->getNotifyListTest(1)) && p('0') && e('admin');
r($releaseTest->getNotifyListTest(2)) && p('0') && e('admin');
r($releaseTest->getNotifyListTest(3)) && p('0') && e('admin');
r($releaseTest->getNotifyListTest(4)) && p('0') && e('admin');
r($releaseTest->getNotifyListTest(99)) && p() && e('0');