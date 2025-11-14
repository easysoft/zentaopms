#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildMindConfig();
timeout=0
cid=19083

- 期望返回第一个配置项的key为module第0条的key属性 @module
- 期望返回第二个配置项的key为scene第1条的key属性 @scene
- 期望返回第三个配置项的值为ccc第2条的value属性 @ccc
- 期望返回第四个配置项的key为precondition第3条的key属性 @precondition
- 期望返回第五个配置项的值为opqrstuvwx第4条的value属性 @opqrstuvwx

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 所有有效配置参数
$_POST = array(
    'module' => 'test',
    'scene' => 'scene',
    'case' => 'case',
    'precondition' => 'pre',
    'pri' => 'pri',
    'group' => 'group'
);
r($testcaseTest->buildMindConfigTest('xmind')) && p('0:key') && e('module'); // 期望返回第一个配置项的key为module

// 步骤2：边界值 - 输入所有不同的有效配置参数
$_POST = array(
    'module' => 'moduleA',
    'scene' => 'sceneB',
    'case' => 'caseC',
    'precondition' => 'preD',
    'pri' => 'priE',
    'group' => 'groupF'
);
r($testcaseTest->buildMindConfigTest('xmind')) && p('1:key') && e('scene'); // 期望返回第二个配置项的key为scene

// 步骤3：边界条件 - 输入不同长度的参数
$_POST = array(
    'module' => 'a',
    'scene' => 'bb',
    'case' => 'ccc',
    'precondition' => 'dddd',
    'pri' => 'eeeee',
    'group' => 'ffffff'
);
r($testcaseTest->buildMindConfigTest('xmind')) && p('2:value') && e('ccc'); // 期望返回第三个配置项的值为ccc

// 步骤4：边界条件 - 输入不同的有效参数
$_POST = array(
    'module' => 'alpha',
    'scene' => 'beta',
    'case' => 'gamma',
    'precondition' => 'delta',
    'pri' => 'epsilon',
    'group' => 'zeta'
);
r($testcaseTest->buildMindConfigTest('xmind')) && p('3:key') && e('precondition'); // 期望返回第四个配置项的key为precondition

// 步骤5：边界条件 - 输入最长有效参数的情况
$_POST = array(
    'module' => 'abcdefghij',
    'scene' => 'klmnopqrst',
    'case' => 'uvwxyzabcd',
    'precondition' => 'efghijklmn',
    'pri' => 'opqrstuvwx',
    'group' => 'yzabcdefgh'
);
r($testcaseTest->buildMindConfigTest('xmind')) && p('4:value') && e('opqrstuvwx'); // 期望返回第五个配置项的值为opqrstuvwx