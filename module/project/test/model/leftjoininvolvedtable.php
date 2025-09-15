#!/usr/bin/env php
<?php

/**

title=测试 projectModel::leftJoinInvolvedTable();
timeout=0
cid=0

- 执行projectTester模块的leftJoinInvolvedTableTest方法，参数是$stmt1  @object
- 执行$result2->sqlBuilder['join'] @~team~
- 执行$result3->sqlBuilder['join'] @~stakeholder~
- 执行$result4->sqlBuilder['join'] @~t1.id = t2.root~
- 执行$result5->sqlBuilder['join'] @~t1.id=t3.objectID~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->gen(3);

su('admin');

$projectTester = new Project();
global $tester;

$stmt1 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
r($projectTester->leftJoinInvolvedTableTest($stmt1)) && p('0') && e('object');

$stmt2 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result2 = $projectTester->leftJoinInvolvedTableTest($stmt2);
r($result2->sqlBuilder['join']) && p() && e('~team~');

$stmt3 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result3 = $projectTester->leftJoinInvolvedTableTest($stmt3);
r($result3->sqlBuilder['join']) && p() && e('~stakeholder~');

$stmt4 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result4 = $projectTester->leftJoinInvolvedTableTest($stmt4);
r($result4->sqlBuilder['join']) && p() && e('~t1.id = t2.root~');

$stmt5 = $tester->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1');
$result5 = $projectTester->leftJoinInvolvedTableTest($stmt5);
r($result5->sqlBuilder['join']) && p() && e('~t1.id=t3.objectID~');