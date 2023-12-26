#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createStoryLink();
timeout=0
cid=1

- 测试获取module 1821 project 11 product 1 的 story link属性url @projectstory-story-1-0--byModule-1-story.html
- 测试获取module 1821 project 11 product 1 的 story link属性url @projectstory-story-1-1--byModule-1-story.html
- 测试获取module 1821 project 11 product 1 的 story link属性url @product-browse-1-0-byModule-1-story.html
- 测试获取module 1821 project 11 product 1 的 story link属性url @execution-story-1-story-order_desc-byModule-1.html
- 测试获取module 1821 project 11 product 1 的 story link属性url @product-browse-1-0-byModule-1-story.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

global $config;
$config->requestType = 'PATH_INFO';

zdTable('module')->config('module')->gen(20);

$extra1 = array('branchID' => 0, 'projectID' => 1);
$extra2 = array('branchID' => 0, 'projectID' => 1,       'productID' => 1);
$extra3 = array('branchID' => 0, 'projectID' => array(), 'productID' => 1);
$extra4 = array('branchID' => 0, 'executionID' => 1);
$extra5 = array('branchID' => 0);

$tree = new treeTest();

r($tree->createStoryLinkTest(1, 0, $extra1)) && p('url') && e("projectstory-story-1-0--byModule-1-story.html");      // 测试获取module 1 project 1 的 story link
r($tree->createStoryLinkTest(1, 0, $extra2)) && p('url') && e("projectstory-story-1-1--byModule-1-story.html");      // 测试获取module 1 project 1 product 1 的 story link
r($tree->createStoryLinkTest(1, 0, $extra3)) && p('url') && e("product-browse-1-0-byModule-1-story.html");           // 测试获取module 1 product 1 的 story link
r($tree->createStoryLinkTest(1, 0, $extra4)) && p('url') && e("execution-story-1-story-order_desc-byModule-1.html"); // 测试获取module 1 exectution 1 的 story link
r($tree->createStoryLinkTest(1, 0, $extra5)) && p('url') && e("product-browse-1-0-byModule-1-story.html");           // 测试获取module 1 的 story link
