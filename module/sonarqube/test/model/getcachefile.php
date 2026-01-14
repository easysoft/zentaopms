#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::getCacheFile();
timeout=0
cid=18384

- 测试步骤1：正常情况下获取缓存文件路径 @path format correct
- 测试步骤2：使用不同sonarqubeID获取缓存文件 @path format correct
- 测试步骤3：使用特殊字符项目key获取缓存文件 @path format correct
- 测试步骤4：使用空项目key获取缓存文件 @path format correct
- 测试步骤5：使用长项目key获取缓存文件 @path format correct

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$sonarqube = new sonarqubeModelTest();

r($sonarqube->getCacheFileTest(1, 'unit_test')) && p('') && e('path format correct'); // 测试步骤1：正常情况下获取缓存文件路径
r($sonarqube->getCacheFileTest(2, 'project_key')) && p('') && e('path format correct'); // 测试步骤2：使用不同sonarqubeID获取缓存文件
r($sonarqube->getCacheFileTest(3, 'project@test#key!')) && p('') && e('path format correct'); // 测试步骤3：使用特殊字符项目key获取缓存文件
r($sonarqube->getCacheFileTest(4, '')) && p('') && e('path format correct'); // 测试步骤4：使用空项目key获取缓存文件
r($sonarqube->getCacheFileTest(5, 'very_long_project_key_that_might_exceed_normal_length_limits_for_cache_file_naming')) && p('') && e('path format correct'); // 测试步骤5：使用长项目key获取缓存文件