#!/usr/bin/env php
<?php

/**

title=测试 programModel::getTopByPath();
timeout=0
cid=17702

- 传入一个path，返回最顶级path @2
- 传入一个path，返回最顶级path @1
- 传入一个path，返回最顶级path @3
- 传入一个path，返回最顶级path @5
- 传入一个path，返回最顶级path @100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->loadYaml('program')->gen(15);

su('admin');

global $tester;
$tester->loadModel('program');
r($tester->program->getTopByPath(',2,3,4,'))     && p() && e('2');   // 传入一个path，返回最顶级path
r($tester->program->getTopByPath(',1,100,101,')) && p() && e('1');   // 传入一个path，返回最顶级path
r($tester->program->getTopByPath(',3,100,200,')) && p() && e('3');   // 传入一个path，返回最顶级path
r($tester->program->getTopByPath(',5,6,7,'))     && p() && e('5');   // 传入一个path，返回最顶级path
r($tester->program->getTopByPath('100,101'))     && p() && e('100'); // 传入一个path，返回最顶级path
