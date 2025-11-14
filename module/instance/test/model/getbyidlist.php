#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(5);
zenData('space')->gen(5);

/**

title=instanceModel->getByIdList();
timeout=0
cid=16799

- 查看获取到的instance数量 @3
- 查看获取到的第一条instance的id和name
 - 第1条的id属性 @1
 - 第1条的name属性 @Subversion
- 查看获取到的第二条instance的id和name
 - 第2条的id属性 @2
 - 第2条的name属性 @禅道开源版
- 查看获取到的第三条instance的id和name
 - 第3条的id属性 @3
 - 第3条的name属性 @adminer
- 查看获取到的第一条instance的space的id和name
 - 属性id @1
 - 属性name @默认空间1
- 查看获取到的第二条instance的space的id和name
 - 属性id @2
 - 属性name @默认空间2
- 查看获取到的第三条instance的space的id和name
 - 属性id @3
 - 属性name @默认空间3

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByIdList(array(1,2,3,10000));

r(count($instance))            && p('') && e('3');   // 查看获取到的instance数量
r($instance) && p('1:id,name') && e('1,Subversion'); // 查看获取到的第一条instance的id和name
r($instance) && p('2:id,name') && e('2,禅道开源版'); // 查看获取到的第二条instance的id和name
r($instance) && p('3:id,name') && e('3,adminer');    // 查看获取到的第三条instance的id和name

r($instance[1]->spaceData) && p('id,name') && e('1,默认空间1'); // 查看获取到的第一条instance的space的id和name
r($instance[2]->spaceData) && p('id,name') && e('2,默认空间2'); // 查看获取到的第二条instance的space的id和name
r($instance[3]->spaceData) && p('id,name') && e('3,默认空间3'); // 查看获取到的第三条instance的space的id和name
