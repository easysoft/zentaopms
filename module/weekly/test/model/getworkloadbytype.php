#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$execution = zenData('project');
$execution->id->range('100-110');
$execution->name->range('迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,迭代7,迭代8,迭代9,迭代10');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);
zenData('task')->gen(50);

/**

title=测试 weeklyModel->getWorkloadByType();
timeout=0
cid=19738

- 测试project值为0
 - 属性design @8.00
 - 属性devel @1.00
 - 属性test @2.00
 - 属性study @3.00
 - 属性discuss @4.00
 - 属性ui @5.00
 - 属性affair @6.00
 - 属性misc @7.00
- 测试project值为1 @0
- 测试project值为11
 - 属性design @0
 - 属性devel @0
 - 属性test @0
 - 属性study @0
 - 属性discuss @0
 - 属性ui @0
 - 属性affair @0
 - 属性misc @0
- 测试project值为41
 - 属性affair @0
 - 属性test @0
 - 属性study @0
 - 属性discuss @0

*/

$projectList = array(0, 1, 11, 41);

$weekly = new weeklyModelTest();

r($weekly->getWorkloadByTypeTest($projectList[0])) && p('design,devel,test,study,discuss,ui,affair,misc') && e('8.00,1.00,2.00,3.00,4.00,5.00,6.00,7.00'); //测试project值为0
r($weekly->getWorkloadByTypeTest($projectList[1])) && p()                                                 && e('0');                                       //测试project值为1
r($weekly->getWorkloadByTypeTest($projectList[2])) && p('design,devel,test,study,discuss,ui,affair,misc') && e('0,0,0,0,0,0,0,0');                         //测试project值为11
r($weekly->getWorkloadByTypeTest($projectList[3])) && p('affair,test,study,discuss')                      && e('0,0,0,0');                                 //测试project值为41