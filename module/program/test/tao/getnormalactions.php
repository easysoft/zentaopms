#!/usr/bin/env php
<?php

/**
 *
title=测试programTao::getNormalActions();
timeout=0
cid=17718

- 项目集第0条的name属性 @edit
- 项目集第1条的name属性 @create
- 项目集第2条的name属性 @delete
- 项目集第0条的name属性 @edit
- 项目第0条的name属性 @edit

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->gen(20);
su('admin');

$tester = new programTaoTest();

r($tester->getNormalActionsTest(1))  && p('0:name') && e('edit');   // 项目集
r($tester->getNormalActionsTest(1))  && p('1:name') && e('create'); // 项目集
r($tester->getNormalActionsTest(1))  && p('2:name') && e('delete'); // 项目集
r($tester->getNormalActionsTest(2))  && p('0:name') && e('edit');   // 项目集
r($tester->getNormalActionsTest(11)) && p('0:name') && e('edit');   // 项目
