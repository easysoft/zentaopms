#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getGiteaProjects();
timeout=0
cid=0

- 测试步骤1：有效的服务器ID获取项目列表
 - 第gitea/unittest条的id属性 @1
 - 第gitea/unittest条的full_name属性 @gitea/unittest
- 测试步骤2：服务器ID为0的边界值测试 @0
- 测试步骤3：无效的服务器ID测试 @0
- 测试步骤4：不存在的服务器ID测试 @0
- 测试步骤5：有效服务器ID的项目ID验证第gitea/unittest条的id属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(5);
su('admin');

$mrTest = new mrTest();

r($mrTest->getGiteaProjectsTester(4))   && p('gitea/unittest:id,full_name') && e('1,gitea/unittest'); // 测试步骤1：有效的服务器ID获取项目列表
r($mrTest->getGiteaProjectsTester(0))   && p()                              && e('0');                // 测试步骤2：服务器ID为0的边界值测试
r($mrTest->getGiteaProjectsTester(1))   && p()                              && e('0');                // 测试步骤3：无效的服务器ID测试
r($mrTest->getGiteaProjectsTester(999)) && p()                              && e('0');                // 测试步骤4：不存在的服务器ID测试
r($mrTest->getGiteaProjectsTester(4))   && p('gitea/unittest:id')           && e('1');                // 测试步骤5：有效服务器ID的项目ID验证