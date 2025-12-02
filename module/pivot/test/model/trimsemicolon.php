#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->trimSemicolon().
timeout=0
cid=17436

- 测试末尾存在分号。 @select id,name from zt_project

- 测试末尾存在空格。 @select id,name from zt_project

- 测试末尾存在多个分号。 @select id,name from zt_project

- 测试末尾存在多个空格。 @select id,name from zt_project

- 测试末尾存在分号和空格。 @select id,name from zt_project

- 测试末尾存在空格和分号。 @select id,name from zt_project

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

r($pivot->trimSemicolon('select id,name from zt_project;'))    && p() && e('select id,name from zt_project'); // 测试末尾存在分号。
r($pivot->trimSemicolon('select id,name from zt_project '))    && p() && e('select id,name from zt_project'); // 测试末尾存在空格。
r($pivot->trimSemicolon('select id,name from zt_project;;;'))  && p() && e('select id,name from zt_project'); // 测试末尾存在多个分号。
r($pivot->trimSemicolon('select id,name from zt_project   '))  && p() && e('select id,name from zt_project'); // 测试末尾存在多个空格。
r($pivot->trimSemicolon('select id,name from zt_project;   ')) && p() && e('select id,name from zt_project'); // 测试末尾存在分号和空格。
r($pivot->trimSemicolon('select id,name from zt_project   ;')) && p() && e('select id,name from zt_project'); // 测试末尾存在空格和分号。
