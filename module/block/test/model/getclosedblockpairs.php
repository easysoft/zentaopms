#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 blockModel->getClosedBlockPairs();
timeout=0
cid=15230

- 测试配置项存在且module不等于code的结果属性product|list @产品|产品列表

- 测试配置项存在且module等于code的结果属性assigntome|assigntome @待处理

- 测试配置项不存在且module不等于code的结果属性product|haha @产品|haha

- 测试配置项不存在且module等于code的结果属性haha|haha @haha

- 测试配置项不存在且module不等于code的结果属性haha|assigntome @haha|assigntome

*/

global $tester;
$tester->loadModel('block');
$data = $tester->block->getClosedBlockPairs('product|list,assigntome|assigntome,product|haha, haha|haha, haha|assigntome');
r($data) && p('product|list') && e('产品|产品列表');       // 测试配置项存在且module不等于code的结果
r($data) && p('assigntome|assigntome') && e('待处理');     // 测试配置项存在且module等于code的结果
r($data) && p('product|haha')     && e('产品|haha');       // 测试配置项不存在且module不等于code的结果
r($data) && p('haha|haha')        && e('haha');            // 测试配置项不存在且module等于code的结果
r($data) && p('haha|assigntome')  && e('haha|assigntome'); // 测试配置项不存在且module不等于code的结果
