#!/usr/bin/env php
<?php

/**

title=测试 customZen::assignVarsForSet();
timeout=0
cid=15932

- 执行customTest模块的assignVarsForSetTest方法，参数是'story', 'priList', 'zh-cn', 'zh-cn' 属性lang2Set @zh-cn
- 执行customTest模块的assignVarsForSetTest方法，参数是'project', 'unitList', 'zh-cn', 'zh-cn'
 - 属性hasUnitList @1
 - 属性hasDefaultCurrency @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'story', 'reviewRules', 'zh-cn', 'zh-cn'
 - 属性hasReviewRule @1
 - 属性hasUsers @1
 - 属性hasSuperReviewers @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'requirement', 'gradeRule', 'zh-cn', 'zh-cn' 属性hasGradeRule @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'epic', 'grade', 'zh-cn', 'zh-cn' 属性hasStoryGrades @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'story', 'review', 'zh-cn', 'zh-cn'
 - 属性hasUsers @1
 - 属性hasNeedReview @1
 - 属性hasForceReview @1
 - 属性hasForceNotReview @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'testcase', 'review', 'zh-cn', 'zh-cn'
 - 属性hasUsers @1
 - 属性hasNeedReview @1
 - 属性hasForceReview @1
 - 属性hasForceNotReview @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'bug', 'longlife', 'zh-cn', 'zh-cn' 属性hasLonglife @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'block', 'closed', 'zh-cn', 'zh-cn'
 - 属性hasBlockPairs @1
 - 属性hasClosedBlock @1
- 执行customTest模块的assignVarsForSetTest方法，参数是'user', 'deleted', 'zh-cn', 'zh-cn' 属性hasShowDeleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(20);
zenData('dept')->gen(10);

su('admin');

$customTest = new customZenTest();

r($customTest->assignVarsForSetTest('story', 'priList', 'zh-cn', 'zh-cn')) && p('lang2Set') && e('zh-cn');
r($customTest->assignVarsForSetTest('project', 'unitList', 'zh-cn', 'zh-cn')) && p('hasUnitList;hasDefaultCurrency') && e('1;1');
r($customTest->assignVarsForSetTest('story', 'reviewRules', 'zh-cn', 'zh-cn')) && p('hasReviewRule;hasUsers;hasSuperReviewers') && e('1;1;1');
r($customTest->assignVarsForSetTest('requirement', 'gradeRule', 'zh-cn', 'zh-cn')) && p('hasGradeRule') && e('1');
r($customTest->assignVarsForSetTest('epic', 'grade', 'zh-cn', 'zh-cn')) && p('hasStoryGrades') && e('1');
r($customTest->assignVarsForSetTest('story', 'review', 'zh-cn', 'zh-cn')) && p('hasUsers;hasNeedReview;hasForceReview;hasForceNotReview') && e('1;1;1;1');
r($customTest->assignVarsForSetTest('testcase', 'review', 'zh-cn', 'zh-cn')) && p('hasUsers;hasNeedReview;hasForceReview;hasForceNotReview') && e('1;1;1;1');
r($customTest->assignVarsForSetTest('bug', 'longlife', 'zh-cn', 'zh-cn')) && p('hasLonglife') && e('1');
r($customTest->assignVarsForSetTest('block', 'closed', 'zh-cn', 'zh-cn')) && p('hasBlockPairs;hasClosedBlock') && e('1;1');
r($customTest->assignVarsForSetTest('user', 'deleted', 'zh-cn', 'zh-cn')) && p('hasShowDeleted') && e('1');