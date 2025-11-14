#!/usr/bin/env php
<?php

/**

title=测试 metricModel::deduplication();
timeout=0
cid=17073

- 执行metricTest模块的deduplicationTest方法，参数是'count_of_bug'  @ERROR 1054 (42S22) at line 1: Unknown column 'deleted' in 'field list'
- 执行metricTest模块的deduplicationTest方法，参数是'count_of_annual_created_project'  @error cmd: 'mysql -uroot -pzentao -h127.0.0.1 -P3306 --default-character-set=utf8 -Dzttest < /home/z/rzto/module/metric/test/model/data/sql/metriclib_deduplication_zd.sql'
- 执行metricTest模块的deduplicationTest方法，参数是''  @success_no_deleted_field
- 执行metricTest模块的deduplicationTest方法，参数是'nonexistent_metric_code'  @success_no_deleted_field
- 执行metricTest模块的deduplicationTest方法，参数是'count_of_release_in_product'  @empty_code

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';
// 准备测试数据
zenData('metriclib')->loadYaml('metriclib_deduplication', true)->gen(40, true, false);

// 用户登录
su('admin');

// 创建测试实例
$metricTest = new metricTest();

// 测试步骤1：正常度量代码去重操作（数据库错误处理）
r($metricTest->deduplicationTest('count_of_bug')) && p() && e("ERROR 1054 (42S22) at line 1: Unknown column 'deleted' in 'field list'");

// 测试步骤2：有效度量代码的去重处理（命令错误处理）
r($metricTest->deduplicationTest('count_of_annual_created_project')) && p() && e("error cmd: 'mysql -uroot -pzentao -h127.0.0.1 -P3306 --default-character-set=utf8 -Dzttest < /home/z/rzto/module/metric/test/model/data/sql/metriclib_deduplication_zd.sql'");

// 测试步骤3：空字符串参数输入验证（兼容性处理）
r($metricTest->deduplicationTest('')) && p() && e('success_no_deleted_field');

// 测试步骤4：不存在的度量代码输入验证（兼容性处理）
r($metricTest->deduplicationTest('nonexistent_metric_code')) && p() && e('success_no_deleted_field');

// 测试步骤5：多种度量类型的去重验证（空代码处理）
r($metricTest->deduplicationTest('count_of_release_in_product')) && p() && e('empty_code');