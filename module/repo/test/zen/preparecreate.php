#!/usr/bin/env php
<?php

/**

title=测试 repoZen::prepareCreate();
timeout=0
cid=0

- 执行result1 = $repoZenTest模块的prepareCreateTest方法，参数是$normalFormData, false, 'normal' 属性acl @{"acl":"open"}
- 执行result2 = $repoZenTest模块的prepareCreateTest方法，参数是$containerFormData, false, 'container_git' 属性acl @{"acl":"open"}
- 执行result3 = $repoZenTest模块的prepareCreateTest方法，参数是$gitlabFormData, false, 'gitlab' 属性extra @123
- 执行result4 = $repoZenTest模块的prepareCreateTest方法，参数是$normalFormData, false, 'acl_error'  @0
- 执行result5 = $repoZenTest模块的prepareCreateTest方法，参数是$normalFormData, true, 'duplicate_project'  @0
- 执行repoZenTest模块的prepareCreateTest方法，参数是$normalFormData, false, 'normal'  @1
- 执行repoZenTest模块的prepareCreateTest方法，参数是$normalFormData, false, 'normal')), 'open') !== false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

// 准备测试数据
$normalFormData = array(
    'SCM' => 'Git',
    'client' => 'git',
    'name' => 'test-repo',
    'path' => '/test/path',
    'product' => '1',
    'projects' => '1',
    'acl' => array('acl' => 'open')
);

$containerFormData = array(
    'SCM' => 'Git',
    'client' => '',
    'name' => 'container-repo',
    'path' => '/test/path'
);

$gitlabFormData = array(
    'SCM' => 'Gitlab',
    'path' => '/test/gitlab',
    'serviceProject' => '123',
    'name' => 'gitlab-repo'
);

// 测试步骤1: 正常Git仓库创建
r($result1 = $repoZenTest->prepareCreateTest($normalFormData, false, 'normal')) && p('acl') && e('{"acl":"open"}');

// 测试步骤2: 容器环境下Git仓库
r($result2 = $repoZenTest->prepareCreateTest($containerFormData, false, 'container_git')) && p('acl') && e('{"acl":"open"}');

// 测试步骤3: Gitlab仓库特殊字段处理 - 验证extra字段
r($result3 = $repoZenTest->prepareCreateTest($gitlabFormData, false, 'gitlab')) && p('extra') && e('123');

// 测试步骤4: ACL配置验证失败
r($result4 = $repoZenTest->prepareCreateTest($normalFormData, false, 'acl_error')) && p() && e('0');

// 测试步骤5: 流水线服务器重复项目检查
r($result5 = $repoZenTest->prepareCreateTest($normalFormData, true, 'duplicate_project')) && p() && e('0');

// 测试步骤6: 正常非流水线服务器场景 - 验证返回对象类型
r(is_object($repoZenTest->prepareCreateTest($normalFormData, false, 'normal'))) && p() && e('1');

// 测试步骤7: 验证ACL字段格式包含open
r(strpos(json_encode($repoZenTest->prepareCreateTest($normalFormData, false, 'normal')), 'open') !== false) && p() && e('1');