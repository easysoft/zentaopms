#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createRequirementLink();
timeout=0
cid=1

- 测试获取module 1 的 requirement link属性url @product-browse-1-all-byModule-1-requirement.html
- 测试获取module 2 的 requriement link属性url @product-browse-1-all-byModule-2-requirement.html
- 测试获取module 1 branchID 1 的 requriement link属性url @product-browse-1-1-byModule-1-requirement.html
- 测试获取module 1 projectID 1 的 requriement link属性url @projectstory-story-1-0--byModule-1-requirement.html
- 测试获取module 1 executionID 1 的 requriement link属性url @execution-story-1-requirement-order_desc-byModule-1.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

$tree = new treeTest();

$extra1 = array('branchID'    => '1');
$extra2 = array('projectID'   => '1');
$extra3 = array('executionID' => '1');

r($tree->createRequirementLinkTest(1)) && p('url') && e("product-browse-1-all-byModule-1-requirement.html");      // 测试获取module 1 的 requirement link
r($tree->createRequirementLinkTest(2)) && p('url') && e("product-browse-1-all-byModule-2-requirement.html");      // 测试获取module 2 的 requriement link

r($tree->createRequirementLinkTest(1, 0, $extra1)) && p('url') && e("product-browse-1-1-byModule-1-requirement.html");           // 测试获取module 1 branchID 1 的 requriement link
r($tree->createRequirementLinkTest(1, 0, $extra2)) && p('url') && e("projectstory-story-1-0--byModule-1-requirement.html");      // 测试获取module 1 projectID 1 的 requriement link
r($tree->createRequirementLinkTest(1, 0, $extra3)) && p('url') && e("execution-story-1-requirement-order_desc-byModule-1.html"); // 测试获取module 1 executionID 1 的 requriement link
