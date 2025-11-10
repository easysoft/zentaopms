#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getMenuItems();
timeout=0
cid=0

- 执行pivotTest模块的getMenuItemsTest方法，参数是$menusWithUrl  @3
- 执行pivotTest模块的getMenuItemsTest方法，参数是$emptyMenus  @0
- 执行pivotTest模块的getMenuItemsTest方法，参数是$menusWithoutUrl  @0
- 执行pivotTest模块的getMenuItemsTest方法，参数是$mixedMenus  @2
- 执行pivotTest模块的getMenuItemsTest方法，参数是$menusWithOtherProps  @0
- 执行getMenuItemsTest($menusWithUrl)[0]模块的id方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

su('admin');

$pivotTest = new pivotZenTest();

$menusWithUrl = array();
$menu1 = new stdclass();
$menu1->id = 1;
$menu1->name = 'Menu1';
$menu1->url = 'http://example.com/menu1';
$menusWithUrl[] = $menu1;

$menu2 = new stdclass();
$menu2->id = 2;
$menu2->name = 'Menu2';
$menu2->url = 'http://example.com/menu2';
$menusWithUrl[] = $menu2;

$menu3 = new stdclass();
$menu3->id = 3;
$menu3->name = 'Menu3';
$menu3->url = 'http://example.com/menu3';
$menusWithUrl[] = $menu3;

$menusWithoutUrl = array();
$menu4 = new stdclass();
$menu4->id = 4;
$menu4->name = 'Menu4';
$menusWithoutUrl[] = $menu4;

$menu5 = new stdclass();
$menu5->id = 5;
$menu5->name = 'Menu5';
$menusWithoutUrl[] = $menu5;

$mixedMenus = array();
$menu6 = new stdclass();
$menu6->id = 6;
$menu6->name = 'Menu6';
$menu6->url = 'http://example.com/menu6';
$mixedMenus[] = $menu6;

$menu7 = new stdclass();
$menu7->id = 7;
$menu7->name = 'Menu7';
$mixedMenus[] = $menu7;

$menu8 = new stdclass();
$menu8->id = 8;
$menu8->name = 'Menu8';
$menu8->url = 'http://example.com/menu8';
$mixedMenus[] = $menu8;

$emptyMenus = array();

$menusWithOtherProps = array();
$menu9 = new stdclass();
$menu9->id = 9;
$menu9->name = 'Menu9';
$menu9->parent = 0;
$menu9->children = array();
$menusWithOtherProps[] = $menu9;

r(count($pivotTest->getMenuItemsTest($menusWithUrl))) && p() && e('3');
r(count($pivotTest->getMenuItemsTest($emptyMenus))) && p() && e('0');
r(count($pivotTest->getMenuItemsTest($menusWithoutUrl))) && p() && e('0');
r(count($pivotTest->getMenuItemsTest($mixedMenus))) && p() && e('2');
r(count($pivotTest->getMenuItemsTest($menusWithOtherProps))) && p() && e('0');
r($pivotTest->getMenuItemsTest($menusWithUrl)[0]->id) && p() && e('1');