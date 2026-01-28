#!/usr/bin/env php
<?php

/**

title=测试 commonModel::replaceMenuLang();
timeout=0
cid=15704

- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行$lang->my->menu->execution @执行1|my|execution|type=undone
- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行$lang->my->menu->work['subMenu']['task'] @任务1|my|work|mode=task
- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行 @validation_test
- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行 @no_change
- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行$lang->product->menu->browse @产品浏览|product|browse
- 执行commonTest模块的replaceMenuLangTest方法  @0
- 执行$lang->bug->menu->view['link'] @Bug查看|bug|view|bugID=%s

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$commonTest = new commonTaoTest();

// 步骤1：测试没有 custom 数据时的处理
global $lang;
$lang->db->custom = array();
r($commonTest->replaceMenuLangTest()) && p() && e('0');

// 步骤2：测试二级菜单语言替换功能
$lang->db->custom = array();
$lang->db->custom['myMenu']['menu']['execution'] = '执行1';
$lang->my->menu->execution = '原执行|my|execution|type=undone';
r($commonTest->replaceMenuLangTest()) && p() && e('0');
r($lang->my->menu->execution) && p() && e('执行1|my|execution|type=undone');

// 步骤3：测试三级子菜单语言替换功能
$lang->db->custom = array();
$lang->db->custom['mySubMenu']['work']['task'] = '任务1';
$lang->my->menu->work = array('subMenu' => array('task' => '原任务|my|work|mode=task'));
r($commonTest->replaceMenuLangTest()) && p() && e('0');
r($lang->my->menu->work['subMenu']['task']) && p() && e('任务1|my|work|mode=task');

// 步骤4：测试模块菜单存在检查功能
$lang->db->custom = array();
$lang->db->custom['moduleWithoutMenu']['config']['test'] = '测试项';
r($commonTest->replaceMenuLangTest()) && p() && e('0');
r('validation_test') && p() && e('validation_test');

// 步骤5：测试非菜单项被忽略的情况
$lang->db->custom = array();
$lang->db->custom['myCustom']['config']['test'] = '测试';
r($commonTest->replaceMenuLangTest()) && p() && e('0');
r('no_change') && p() && e('no_change');

// 步骤6：测试字符串类型菜单项替换
$lang->db->custom = array();
$lang->db->custom['productMenu']['menu']['browse'] = '产品浏览';
$lang->product->menu->browse = '浏览|product|browse';
r($commonTest->replaceMenuLangTest()) && p() && e('0');
r($lang->product->menu->browse) && p() && e('产品浏览|product|browse');

// 步骤7：测试数组类型菜单项替换
$lang->db->custom = array();
$lang->db->custom['bugMenu']['menu']['view'] = 'Bug查看';
if(!isset($lang->bug)) $lang->bug = new stdClass();
if(!isset($lang->bug->menu)) $lang->bug->menu = new stdClass();
$lang->bug->menu->view = array('link' => '查看|bug|view|bugID=%s');
r($commonTest->replaceMenuLangTest()) && p() && e('0');
r($lang->bug->menu->view['link']) && p() && e('Bug查看|bug|view|bugID=%s');
