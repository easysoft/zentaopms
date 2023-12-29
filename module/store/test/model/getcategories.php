#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getCategories().
cid=1

- 测试获取类别列表 @数据库 项目管理 企业IM 持续集成 企业管理 DevOps 代码检查 文档系统 网盘服务 安全 搜索引擎 网站分析 内容管理

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$store = new storeTest();
r($store->getCategoriesTest()) && p() && e('数据库 项目管理 企业IM 持续集成 企业管理 DevOps 代码检查 文档系统 网盘服务 安全 搜索引擎 网站分析 内容管理'); //测试获取类别列表
