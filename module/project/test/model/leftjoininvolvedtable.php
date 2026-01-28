#!/usr/bin/env php
<?php

/**

title=测试 projectModel::leftJoinInvolvedTable();
timeout=0
cid=17862

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(3);

su('admin');

$projectTester = new projectModelTest();
global $tester;

$stmt1 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result1 = $projectTester->leftJoinInvolvedTableTest($stmt1);
r(!empty($result1)) && p() && e('1');

$stmt2 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result2 = $projectTester->leftJoinInvolvedTableTest($stmt2);
r(!empty($result2)) && p() && e('1');

$stmt3 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result3 = $projectTester->leftJoinInvolvedTableTest($stmt3);
r(!empty($result3)) && p() && e('1');

$stmt4 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result4 = $projectTester->leftJoinInvolvedTableTest($stmt4);
r(!empty($result4)) && p() && e('1');

$stmt5 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result5 = $projectTester->leftJoinInvolvedTableTest($stmt5);
r(!empty($result5)) && p() && e('1');
