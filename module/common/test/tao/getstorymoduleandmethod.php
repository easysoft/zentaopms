#!/usr/bin/env php
<?php

/**

title=测试 commonTao::getStoryModuleAndMethod();
timeout=0
cid=0

- 执行commonTest模块的getStoryModuleAndMethodTest方法，参数是'story', 'create', array 
 -  @story
 - 属性1 @create
- 执行commonTest模块的getStoryModuleAndMethodTest方法，参数是'story', 'create', array 
 -  @requirement
 - 属性1 @create
- 执行commonTest模块的getStoryModuleAndMethodTest方法，参数是'product', 'browse', array 
 -  @product
 - 属性1 @requirement
- 执行commonTest模块的getStoryModuleAndMethodTest方法，参数是'story', 'processstorychange', array 
 -  @story
 - 属性1 @processstorychange
- 执行commonTest模块的getStoryModuleAndMethodTest方法，参数是'story', 'linkrequirements', array 
 -  @requirement
 - 属性1 @linkrequirements

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$commonTest = new commonTaoTest();

r($commonTest->getStoryModuleAndMethodTest('story', 'create', array())) && p('0,1') && e('story,create');
r($commonTest->getStoryModuleAndMethodTest('story', 'create', array('storyType' => 'requirement'))) && p('0,1') && e('requirement,create');
r($commonTest->getStoryModuleAndMethodTest('product', 'browse', array('storyType' => 'requirement'))) && p('0,1') && e('product,requirement');
r($commonTest->getStoryModuleAndMethodTest('story', 'processstorychange', array())) && p('0,1') && e('story,processstorychange');
r($commonTest->getStoryModuleAndMethodTest('story', 'linkrequirements', array())) && p('0,1') && e('requirement,linkrequirements');