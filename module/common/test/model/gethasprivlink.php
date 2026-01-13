#!/usr/bin/env php
<?php
/**

title=测试 commonModel::getHasPrivLink();
timeout=0
cid=1

- 测试空数据 @0
- 测试获取有权限的需求链接
 - 属性1 @my
 - 属性2 @contribute
- 测试获取有权限的产品链接
 - 属性1 @product
 - 属性2 @all
- 测试获取有权限的项目链接
 - 属性1 @project
 - 属性2 @browse
- 测试获取有权限的质量保证计划
 - 属性1 @auditplan
 - 属性2 @browse

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');
$commonTest = new commonTest();

$menuList[] = array();
$menuList[] = array('link' => '需求|my|contribute|mode=story');
$menuList[] = array('link' => '产品|product|all|');
$menuList[] = array('link' => '项目列表|project|browse|');
$menuList[] = array('link' => '质量保证计划|auditplan|browse|projectID=%s', 'links' => array('project|deliverableChecklist|projectID=%s', 'nc|browse|project=%s&from=project'));

r($commonTest->getHasPrivLinkTest($menuList[0])) && p()      && e('0');                // 测试空数据
r($commonTest->getHasPrivLinkTest($menuList[1])) && p('1,2') && e('my,contribute');    // 测试获取有权限的需求链接
r($commonTest->getHasPrivLinkTest($menuList[2])) && p('1,2') && e('product,all');      // 测试获取有权限的产品链接
r($commonTest->getHasPrivLinkTest($menuList[3])) && p('1,2') && e('project,browse');   // 测试获取有权限的项目链接
r($commonTest->getHasPrivLinkTest($menuList[4])) && p('1,2') && e('auditplan,browse'); // 测试获取有权限的质量保证计划
