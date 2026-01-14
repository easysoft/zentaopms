#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 storeModel::getCategories();
timeout=0
cid=18454

- 执行store模块的getCategoriesTest方法，参数是'normal'  @数据库 项目管理 企业IM 持续集成 企业管理 DevOps 代码检查 文档系统 网盘服务 安全 搜索引擎 网站分析 内容管理 人工智能
- 执行store模块的getCategoriesTest方法，参数是'structure'
 - 属性hasCategories @1
 - 属性hasTotal @1
 - 属性categoriesType @array
 - 属性totalType @integer
- 执行store模块的getCategoriesTest方法，参数是'count'  @14
- 执行store模块的getCategoriesTest方法，参数是'empty'
 - 属性categoriesCount @0
 - 属性total @0
- 执行store模块的getCategoriesTest方法，参数是'api_error'
 - 属性categoriesCount @0
 - 属性total @0
 - 属性isEmptyResult @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$store = new storeModelTest();

r($store->getCategoriesTest('normal')) && p() && e('数据库 项目管理 企业IM 持续集成 企业管理 DevOps 代码检查 文档系统 网盘服务 安全 搜索引擎 网站分析 内容管理 人工智能');
r($store->getCategoriesTest('structure')) && p('hasCategories,hasTotal,categoriesType,totalType') && e('1,1,array,integer');
r($store->getCategoriesTest('count')) && p() && e('14');
r($store->getCategoriesTest('empty')) && p('categoriesCount,total') && e('0,0');
r($store->getCategoriesTest('api_error')) && p('categoriesCount,total,isEmptyResult') && e('0,0,1');