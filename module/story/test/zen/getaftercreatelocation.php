#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterCreateLocation();
timeout=0
cid=18677

- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '0', 0, 1, 'story', ''  @getaftercreatelocation.php?m=product&f=browse&productID=1&branch=0&browseType=&param=0&storyType=story&orderBy=id_desc
- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '0', 0, 1, 'story', ''  @story-browse-1.html
- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '0', 6, 1, 'story', ''  @getaftercreatelocation.php?m=execution&f=story&objectID=6
- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '0', 6, 1, 'story', ''  @execution-story-6.html
- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '0', 0, 1, 'story', ''  @getaftercreatelocation.php?m=story&f=view&storyID=1
- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '1', 0, 1, 'story', ''  @getaftercreatelocation.php?m=product&f=browse&productID=1&branch=all&browseType=&param=0&storyType=story
- 执行storyTest模块的getAfterCreateLocationTest方法，参数是1, '0', 11, 1, 'story', ''  @getaftercreatelocation.php?m=execution&f=story&t=&objectID=11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

su('admin');

$storyTest = new storyZenTest();

$_SESSION['storyList'] = '';
r($storyTest->getAfterCreateLocationTest(1, '0', 0, 1, 'story', '')) && p() && e('getaftercreatelocation.php?m=product&f=browse&productID=1&branch=0&browseType=&param=0&storyType=story&orderBy=id_desc');
$_SESSION['storyList'] = 'story-browse-1.html';
r($storyTest->getAfterCreateLocationTest(1, '0', 0, 1, 'story', '')) && p() && e('story-browse-1.html');
$_SESSION['storyList'] = '';
r($storyTest->getAfterCreateLocationTest(1, '0', 6, 1, 'story', '')) && p() && e('getaftercreatelocation.php?m=execution&f=story&objectID=6');
$_SESSION['storyList'] = 'execution-story-6.html';
r($storyTest->getAfterCreateLocationTest(1, '0', 6, 1, 'story', '')) && p() && e('execution-story-6.html');
global $app;
$app->viewType = 'xhtml';
r($storyTest->getAfterCreateLocationTest(1, '0', 0, 1, 'story', '')) && p() && e('getaftercreatelocation.php?m=story&f=view&storyID=1');
$app->viewType = '';
$_SESSION['storyList'] = 'getaftercreatelocation.php?m=product&f=browse&productID=1&branch=1&browseType=&param=0&storyType=story';
$_POST['branches'] = array(1, 2);
r($storyTest->getAfterCreateLocationTest(1, '1', 0, 1, 'story', '')) && p() && e('getaftercreatelocation.php?m=product&f=browse&productID=1&branch=all&browseType=&param=0&storyType=story');
$_POST['branches'] = array();
$_SESSION['storyList'] = '';
$app->tab = 'project';
r($storyTest->getAfterCreateLocationTest(1, '0', 11, 1, 'story', '')) && p() && e('getaftercreatelocation.php?m=execution&f=story&t=&objectID=11');