#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDAO();
timeout=0
cid=17086

- 执行metricTest模块的getDAOTest方法 属性driver @mysql
- 执行metricTest模块的getDAOTest方法 属性driver @mysql
- 执行metricTest模块的getDAOTest方法 属性driver @mysql
- 执行metricTest模块的getDAOTest方法 属性driver @mysql
- 执行metricTest模块的getDAOTest方法 属性driver @mysql

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

global $config;
$originalType = isset($config->metricDB->type) ? $config->metricDB->type : 'mysql';

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况获取DAO对象
r($metricTest->getDAOTest()) && p('driver') && e('mysql');

// 步骤2：设置为mysql类型测试
if(!isset($config->metricDB)) $config->metricDB = new stdClass();
$config->metricDB->type = 'mysql';
r($metricTest->getDAOTest()) && p('driver') && e('mysql');

// 步骤3：测试默认情况返回原始dao
$config->metricDB->type = 'other';
r($metricTest->getDAOTest()) && p('driver') && e('mysql');

// 步骤4：测试配置为空字符串时的默认行为
$config->metricDB = new stdClass();
$config->metricDB->type = '';
r($metricTest->getDAOTest()) && p('driver') && e('mysql');

// 步骤5：恢复原始配置并再次测试
$config->metricDB = new stdClass();
$config->metricDB->type = $originalType;
r($metricTest->getDAOTest()) && p('driver') && e('mysql');