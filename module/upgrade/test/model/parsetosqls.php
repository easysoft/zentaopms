#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->parseToSqls();
cid=1

- 测试将 18.1 的升级 sql 文件转为 sql 语句 获取前30个字符 @DELETE FROM `zt_config` WHERE 
- 测试将 18.2 的升级 sql 文件转为 sql 语句 获取前30个字符 @ALTER TABLE `zt_lang` MODIFY C
- 测试将 18.3 的升级 sql 文件转为 sql 语句 获取前30个字符 @DELETE FROM `zt_chart` where i
- 测试将 18.4 的升级 sql 文件转为 sql 语句 获取前30个字符 @REPLACE INTO `zt_priv` (`modul
- 测试将 18.5 的升级 sql 文件转为 sql 语句 获取前30个字符 @UPDATE `zt_chart` SET `sql` = 

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

$versions = array('18.1', '18.2', '18.3', '18.4', '18.5');
r($upgrade->parseToSqlsTest($versions[0])) && p() && e('DELETE FROM `zt_config` WHERE '); // 测试将 18.1 的升级 sql 文件转为 sql 语句 获取前30个字符
r($upgrade->parseToSqlsTest($versions[1])) && p() && e('ALTER TABLE `zt_lang` MODIFY C'); // 测试将 18.2 的升级 sql 文件转为 sql 语句 获取前30个字符
r($upgrade->parseToSqlsTest($versions[2])) && p() && e('DELETE FROM `zt_chart` where i'); // 测试将 18.3 的升级 sql 文件转为 sql 语句 获取前30个字符
r($upgrade->parseToSqlsTest($versions[3])) && p() && e('REPLACE INTO `zt_priv` (`modul'); // 测试将 18.4 的升级 sql 文件转为 sql 语句 获取前30个字符
r($upgrade->parseToSqlsTest($versions[4])) && p() && e('UPDATE `zt_chart` SET `sql` = '); // 测试将 18.5 的升级 sql 文件转为 sql 语句 获取前30个字符
