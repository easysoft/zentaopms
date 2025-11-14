#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getStoryList();
timeout=0
cid=15500

- 测试传入空字符串获取story列表数据 @0
- 测试传入有效storyId列表获取story列表数据第1条的title属性 @需求1
- 测试传入不存在storyId列表获取story列表数据 @0
- 测试指定分支参数获取story列表数据第1条的stage属性 @wait
- 测试指定排序参数获取story列表数据第1条的pri属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('story')->loadYaml('story')->gen(10);
zenData('storystage')->gen(5);
zenData('user')->gen(5);
su('admin');

$buildTest = new buildTest();

r($buildTest->getStoryListTest('')) && p() && e('0'); // 测试传入空字符串获取story列表数据
r($buildTest->getStoryListTest('1,2,3,4,5')) && p('1:title') && e('需求1'); // 测试传入有效storyId列表获取story列表数据
r($buildTest->getStoryListTest('11,12,13,14,15')) && p() && e('0'); // 测试传入不存在storyId列表获取story列表数据
r($buildTest->getStoryListTest('1,2,3', 1)) && p('1:stage') && e('wait'); // 测试指定分支参数获取story列表数据
r($buildTest->getStoryListTest('1,2,3,4', 0, 'priOrder_desc')) && p('1:pri') && e('1'); // 测试指定排序参数获取story列表数据