#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::getSquareCategoryArray();
timeout=0
cid=15086

- 执行aiappTest模块的getSquareCategoryArrayTest方法，参数是array 
 - 属性collection @我的收藏
 - 属性discovery @发现
 - 属性latest @最新
- 执行aiappTest模块的getSquareCategoryArrayTest方法，参数是array 
 - 属性collection @我的收藏
 - 属性discovery @发现
 - 属性latest @~~
- 执行aiappTest模块的getSquareCategoryArrayTest方法 
 - 属性collection @我的收藏
 - 属性discovery @发现
- 执行aiappTest模块的getSquareCategoryArrayTest方法，参数是array 
 - 属性collection @我的收藏
 - 属性discovery @发现
 - 属性latest @最新
- 执行aiappTest模块的getSquareCategoryArrayTest方法，参数是null, 1 
 - 属性collection @我的收藏
 - 属性discovery @发现
 - 属性latest @最新

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$miniPrograms = zenData('ai_miniprogram');
$miniPrograms->id->range('1-3');
$miniPrograms->name->range('test{3}');
$miniPrograms->category->range('work');
$miniPrograms->published->range('1');
$miniPrograms->publishedDate->range('`(-1 weeks)`:`(now)`:1D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$miniPrograms->deleted->range('0');
$miniPrograms->createdBy->range('admin');
$miniPrograms->createdDate->range('`(-1 weeks)`:`(now)`:1D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$miniPrograms->editedBy->range('admin');
$miniPrograms->editedDate->range('`(-1 weeks)`:`(now)`:1D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$miniPrograms->prompt->range('test prompt');
$miniPrograms->builtIn->range('1');
$miniPrograms->gen(3);

su('admin');

$aiappTest = new aiappModelTest();

r($aiappTest->getSquareCategoryArrayTest(array('1', '2', '3'), 5)) && p('collection,discovery,latest') && e('我的收藏,发现,最新');
r($aiappTest->getSquareCategoryArrayTest(array('1', '2'), 0)) && p('collection,discovery;latest') && e('我的收藏,发现;~~');
r($aiappTest->getSquareCategoryArrayTest()) && p('collection,discovery') && e('我的收藏,发现');
r($aiappTest->getSquareCategoryArrayTest(array(), 3)) && p('collection,discovery,latest') && e('我的收藏,发现,最新');
r($aiappTest->getSquareCategoryArrayTest(null, 1)) && p('collection,discovery,latest') && e('我的收藏,发现,最新');