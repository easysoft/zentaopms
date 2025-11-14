#!/usr/bin/env php
<?php

/**

title=测试 programModel::getChildrenPairsByID();
timeout=0
cid=17683

- 测试步骤1：获取有子项目集的项目集ID为1的子项目集和项目
 - 属性3 @子项目集1
 - 属性4 @子项目集2
- 测试步骤2：获取有单个子项目集的项目集ID为2的子项目集
 - 属性5 @子项目集3
 - 属性7 @项目2
- 测试步骤3：获取不存在的项目集ID的子项目集和项目 @0
- 测试步骤4：获取没有子项目集的项目集ID的子项目集和项目 @0
- 测试步骤5：获取已删除项目集的子项目集和项目（不包含已删除的子项目集） @Array

*/

// 使用系统命令预先插入数据，避免init时的检查
exec("mysql -h127.0.0.1 -uroot -pzentao zttest -e \"INSERT IGNORE INTO zt_company (id, name) VALUES (1, '测试公司')\" 2>/dev/null");
exec("mysql -h127.0.0.1 -uroot -pzentao zttest -e \"INSERT IGNORE INTO zt_user (id, account, password, realname, role) VALUES (1, 'admin', 'e3ceb5881a0a1fdaad01296d7554868d', 'Admin', 'admin')\" 2>/dev/null");

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

su('admin');

$tester->dao->exec("DELETE FROM " . TABLE_PROJECT);
$tester->dao->exec("
INSERT INTO " . TABLE_PROJECT . " (id, name, type, parent, deleted, status, grade, path) VALUES
(1, '项目集1', 'program', 0, '0', 'doing', 1, ',1,'),
(2, '项目集2', 'program', 0, '0', 'doing', 1, ',2,'),
(3, '子项目集1', 'program', 1, '0', 'doing', 2, ',1,3,'),
(4, '子项目集2', 'program', 1, '0', 'doing', 2, ',1,4,'),
(5, '子项目集3', 'program', 2, '0', 'doing', 2, ',2,5,'),
(6, '项目1', 'project', 1, '0', 'doing', 3, ',1,6,'),
(7, '项目2', 'project', 2, '0', 'doing', 3, ',2,7,'),
(8, '项目3', 'project', 3, '0', 'doing', 3, ',1,3,8,'),
(9, '已删除项目集', 'program', 0, '1', 'doing', 1, ',9,'),
(10, '子项目集5', 'program', 9, '0', 'doing', 2, ',9,10,')
");

$programTest = new programTest();

r($programTest->getChildrenPairsByIDTest(1)) && p('3,4') && e('子项目集1,子项目集2'); // 测试步骤1：获取有子项目集的项目集ID为1的子项目集和项目
r($programTest->getChildrenPairsByIDTest(2)) && p('5,7') && e('子项目集3,项目2'); // 测试步骤2：获取有单个子项目集的项目集ID为2的子项目集
r($programTest->getChildrenPairsByIDTest(999)) && p() && e('0'); // 测试步骤3：获取不存在的项目集ID的子项目集和项目
r($programTest->getChildrenPairsByIDTest(8)) && p() && e('0'); // 测试步骤4：获取没有子项目集的项目集ID的子项目集和项目
r($programTest->getChildrenPairsByIDTest(9)) && p() && e('Array'); // 测试步骤5：获取已删除项目集的子项目集和项目（不包含已删除的子项目集）