#!/usr/bin/env php
<?php

/**

title=测试 searchTao::appendFiles();
timeout=0
cid=18313

- 执行searchTest模块的appendFilesTest方法 属性id @1
- 执行searchTest模块的appendFilesTest方法 属性id @2
- 执行searchTest模块的appendFilesTest方法 属性id @6
- 执行searchTest模块的appendFilesTest方法 属性id @7
- 执行searchTest模块的appendFilesTest方法 属性id @999

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('doccontent')->loadYaml('appendfiles/doccontent', false, 2)->gen(10);
zenData('file')->loadYaml('appendfiles/file', false, 2)->gen(5);

su('admin');

$searchTest = new searchTaoTest();

r($searchTest->appendFilesTest((object)array('id' => 1, 'title' => 'test'))) && p('id') && e('1');
r($searchTest->appendFilesTest((object)array('id' => 2, 'title' => 'test'))) && p('id') && e('2');
r($searchTest->appendFilesTest((object)array('id' => 6, 'title' => 'test'))) && p('id') && e('6');
r($searchTest->appendFilesTest((object)array('id' => 7, 'title' => 'test'))) && p('id') && e('7');
r($searchTest->appendFilesTest((object)array('id' => 999, 'title' => 'test'))) && p('id') && e('999');