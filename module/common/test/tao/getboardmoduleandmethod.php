#!/usr/bin/env php
<?php

/**

title=测试 commonTao::getBoardModuleAndMethod();
timeout=0
cid=0

- 执行commonTest模块的getBoardModuleAndMethodTest方法，参数是'project', 'browse', array 
 -  @project
 - 属性1 @browse
- 执行commonTest模块的getBoardModuleAndMethodTest方法，参数是'board', 'view', array 
 -  @board
 - 属性1 @creation
- 执行commonTest模块的getBoardModuleAndMethodTest方法，参数是'board', 'createbytemplate', array 
 -  @board
 - 属性1 @createboard
- 执行commonTest模块的getBoardModuleAndMethodTest方法，参数是'board', 'browse', array 
 -  @board
 - 属性1 @browse
- 执行commonTest模块的getBoardModuleAndMethodTest方法，参数是'board', 'open', array 
 -  @board
 - 属性1 @creation

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$commonTest = new commonTaoTest();

r($commonTest->getBoardModuleAndMethodTest('project', 'browse', array())) && p('0,1') && e('project,browse');
r($commonTest->getBoardModuleAndMethodTest('board', 'view', array())) && p('0,1') && e('board,creation');
r($commonTest->getBoardModuleAndMethodTest('board', 'createbytemplate', array())) && p('0,1') && e('board,createboard');
r($commonTest->getBoardModuleAndMethodTest('board', 'browse', array())) && p('0,1') && e('board,browse');
r($commonTest->getBoardModuleAndMethodTest('board', 'open', array())) && p('0,1') && e('board,creation');