#!/usr/bin/env php
<?php

/**

title=测试programTao::getRootProgramList();
timeout=0
cid=17719

- 执行$programList @10
- 执行$programList
 - 第0条的id属性 @1
 - 第0条的name属性 @项目集1
 - 第0条的PM属性 @pm1
 - 第0条的type属性 @program
- 执行$programList
 - 第1条的id属性 @2
 - 第1条的name属性 @项目集2
 - 第1条的PM属性 @pm2
 - 第1条的type属性 @program
- 执行$programList
 - 第2条的id属性 @3
 - 第2条的name属性 @项目集3
 - 第2条的PM属性 @pm3
 - 第2条的type属性 @program
- 执行$programList
 - 第5条的id属性 @6
 - 第5条的name属性 @项目集6
 - 第5条的PM属性 @pm6
 - 第5条的type属性 @program
- 执行$programList
 - 第9条的id属性 @10
 - 第9条的name属性 @项目集10
 - 第9条的PM属性 @pm10
 - 第9条的type属性 @program

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('project')->loadYaml('program')->gen(40);
su('admin');

$tester = new programTest();
$programList = $tester->program->getRootProgramList();

r(count($programList)) && p() && e('10');
r($programList) && p('0:id,name,PM,type') && e('1,项目集1,pm1,program');
r($programList) && p('1:id,name,PM,type') && e('2,项目集2,pm2,program');
r($programList) && p('2:id,name,PM,type') && e('3,项目集3,pm3,program');
r($programList) && p('5:id,name,PM,type') && e('6,项目集6,pm6,program');
r($programList) && p('9:id,name,PM,type') && e('10,项目集10,pm10,program');
