#!/usr/bin/env php
<?php

/**

title=测试 commonModel->replaceMenuLang();
cid=0

- 没有 db->custom 数据。 @0
- 替换二级导航语言项。属性link @执行1|my|execution|type=undone
- 替换三级导航语言项。属性link @任务1|my|work|mode=task
- 替换二级导航下拉的语言项。属性link @需求1|execution|story|executionID=%s

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('common');

$tester->common->lang->db->custom = array();
r($tester->common->replaceMenuLang()) && p() && e('0'); //没有 db->custom 数据。

$tester->common->lang->db->custom['myMenu']['menu']['execution'] = '执行1';
$tester->common->lang->db->custom['mySubMenu']['work']['task']   = '任务1';
$tester->common->lang->db->custom['executionMenu']['storyGroupDropMenu']['story'] = '需求1';
$tester->common->replaceMenuLang();

r($tester->common->lang->my->menu->execution)                            && p('link') && e("执行1|my|execution|type=undone");       // 替换二级导航语言项。
r($tester->common->lang->my->menu->work['subMenu']->task)                && p('link') && e("任务1|my|work|mode=task");              // 替换三级导航语言项。
r($tester->common->lang->execution->menu->storyGroup['dropMenu']->story) && p('link') && e("需求1|execution|story|executionID=%s"); // 替换二级导航下拉的语言项。
