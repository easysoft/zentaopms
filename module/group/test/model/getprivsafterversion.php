#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getPrivsAfterVersion();
timeout=0
cid=16709

- 步骤1：空版本号获取所有权限属性doc-sort @doc-sort
- 步骤2：版本号18.0获取权限属性doc-sort @~~
- 步骤3：下划线版本号18_0获取权限属性doc-sort @~~
- 步骤4：较早版本号1.0获取权限属性project-computeBurn @project-computeBurn
- 步骤5：不存在的高版本号999.0获取权限属性notexist @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

$group = new groupTest();

r($group->getPrivsAfterVersionTest('')) && p('doc-sort') && e('doc-sort'); // 步骤1：空版本号获取所有权限
r($group->getPrivsAfterVersionTest('18.0')) && p('doc-sort') && e('~~'); // 步骤2：版本号18.0获取权限
r($group->getPrivsAfterVersionTest('18_0')) && p('doc-sort') && e('~~'); // 步骤3：下划线版本号18_0获取权限
r($group->getPrivsAfterVersionTest('1.0')) && p('project-computeBurn') && e('project-computeBurn'); // 步骤4：较早版本号1.0获取权限
r($group->getPrivsAfterVersionTest('999.0')) && p('notexist') && e('~~'); // 步骤5：不存在的高版本号999.0获取权限