#!/usr/bin/env php
<?php

/**

title=测试 biModel::getDuckdbBinConfig();
timeout=0
cid=15167

- 测试正常情况下返回完整配置数组
 - 属性file @duckdb
 - 属性path @/opt/zbox/bin/
- 测试返回配置包含扩展字段属性extension @mysql_scanner.duckdb_extension
- 测试不同数据库驱动的扩展配置
 - 属性extension_dm @sync2parquet
 - 属性extension_mysql @mysql_scanner.duckdb_extension
- 测试扩展下载URL配置
 - 属性extensionUrl_dm @https://dl.zentao.net/duckdb/linux/amd64/sync2parquet
 - 属性extensionUrl_mysql @https://dl.zentao.net/duckdb/linux/amd64/mysql_scanner.duckdb_extension.zip
- 测试duckdb二进制文件下载URL属性fileUrl @https://dl.zentao.net/duckdb/linux/amd64/duckdb.zip
- 测试配置返回值类型 @1
- 测试配置字段完整性 @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

r($biTest->getDuckdbBinConfigTest()) && p('file,path') && e('duckdb,/opt/zbox/bin/'); // 测试正常情况下返回完整配置数组
r($biTest->getDuckdbBinConfigTest()) && p('extension') && e('mysql_scanner.duckdb_extension'); // 测试返回配置包含扩展字段
r($biTest->getDuckdbBinConfigTest()) && p('extension_dm,extension_mysql') && e('sync2parquet,mysql_scanner.duckdb_extension'); // 测试不同数据库驱动的扩展配置
r($biTest->getDuckdbBinConfigTest()) && p('extensionUrl_dm,extensionUrl_mysql') && e('https://dl.zentao.net/duckdb/linux/amd64/sync2parquet,https://dl.zentao.net/duckdb/linux/amd64/mysql_scanner.duckdb_extension.zip'); // 测试扩展下载URL配置
r($biTest->getDuckdbBinConfigTest()) && p('fileUrl') && e('https://dl.zentao.net/duckdb/linux/amd64/duckdb.zip'); // 测试duckdb二进制文件下载URL
r(is_array($biTest->getDuckdbBinConfigTest())) && p() && e('1'); // 测试配置返回值类型
r(count($biTest->getDuckdbBinConfigTest())) && p() && e('8'); // 测试配置字段完整性