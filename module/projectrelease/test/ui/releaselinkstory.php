#!/usr/bin/env php
<?php

/**

title=项目发布关联和移除研发需求
timeout=0
cid=73

- 项目发布关联研发需求最终测试状态 @SUCCESS
- 单个移除研发需求最终测试状态 @SUCCESS
- 批量移除研发需求
 - 测试结果 @批量移除需求成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/releaselinkstory.ui.class.php';

zendata('story')->loadYaml('story', false, 1)->gen(5);
zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('product')->loadYaml('product', false, 1)->gen(1);
zendata('storyspec')->loadYaml('storyspec', false, 1)->gen(5);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$tester = new releaseLinkStoryTester();
$tester->login();

r($tester->linkStory())        && p('status')         && e('SUCCESS');                   // 项目发布关联研发需求
r($tester->unlinkStory())      && p('status')         && e('SUCCESS');                   // 单个移除研发需求
r($tester->batchUnlinkStory()) && p('message,status') && e('批量移除需求成功,SUCCESS');  // 批量移除研发需求

$tester->closeBrowser();
