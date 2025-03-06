#!/usr/bin/env php
<?php

/**
title=项目发布关联和移除Bug
timeout=0
cid=73

- 项目发布关联bug
 - 测试结果 @发布关联bug成功
 - 最终测试状态 @SUCCESS
- 单个移除bug
 - 测试结果 @单个移除bug成功
 - 最终测试状态 @SUCCESS
- 移除全部bug
 - 测试结果 @移除全部bug成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/releaselinkbug.ui.class.php';

zendata('bug')->loadYaml('bug', false, 1)->gen(5);
zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$tester = new releaseLinkBugTester();
$tester->login();

r($tester->linkBug())        && p('message,status') && e('发布关联bug成功,SUCCESS'); // 项目发布关联bug
r($tester->unlinkBug())      && p('message,status') && e('单个移除bug成功,SUCCESS'); // 单个移除bug
r($tester->batchUnlinkBug()) && p('message,status') && e('移除全部bug成功,SUCCESS'); // 移除全部bug

$tester->closeBrowser();
