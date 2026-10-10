#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 installZen::getInstallPlan();
timeout=0
cid=0

- 测试安装 zentao.sql 时返回 SQL 语句 @1
- 测试安装 zentao.sql 时返回变更内容 @1
- 测试文件名称为空时使用配置的安装文件 @1
- 测试GitFox独立版安装计划包含独立版初始化 SQL @1
- 测试非GitFox版本安装计划不包含独立版初始化 SQL @0
- 测试GitFox独立版安装计划的SQL数量多于开源版 @1
- 测试安装文件不存在时仍执行数据库视图 SQL @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$installZen = new installZenTest();

r($installZen->getInstallPlanSQLCountTest('zentao.sql') > 0)                                        && p() && e('1'); // 测试安装 zentao.sql 时返回 SQL 语句
r($installZen->getInstallPlanChangesCountTest('zentao.sql') > 0)                                    && p() && e('1'); // 测试安装 zentao.sql 时返回变更内容
r($installZen->getInstallPlanSQLCountTest('') == $installZen->getInstallPlanSQLCountTest('zentao.sql')) && p() && e('1'); // 测试文件名称为空时使用配置的安装文件
r($installZen->getInstallPlanContainsTest("VALUES ('gitfox', 'DEVOPSADMIN'", 'zentao.sql', 'gitfox')) && p() && e('1'); // 测试GitFox独立版安装计划包含独立版初始化 SQL
r($installZen->getInstallPlanContainsTest("VALUES ('gitfox', 'DEVOPSADMIN'", 'zentao.sql', 'ipd'))    && p() && e('0'); // 测试非GitFox版本安装计划不包含独立版初始化 SQL
r($installZen->getInstallPlanSQLCountTest('zentao.sql', 'gitfox') > $installZen->getInstallPlanSQLCountTest('zentao.sql', 'ipd')) && p() && e('1'); // 测试GitFox独立版安装计划的SQL数量多于开源版
r($installZen->getInstallPlanSQLCountTest('not-exists.sql') > 0)                                    && p() && e('1'); // 测试安装文件不存在时仍执行数据库视图 SQL
