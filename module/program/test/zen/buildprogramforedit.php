#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildProgramForEdit();
timeout=0
cid=17725

- 执行programTest模块的buildProgramForEditTest方法，参数是1 属性status @doing
- 执行programTest模块的buildProgramForEditTest方法，参数是2 属性end @2059-12-31
- 执行programTest模块的buildProgramForEditTest方法，参数是3 属性whitelist @~~
- 执行programTest模块的buildProgramForEditTest方法，参数是4
 - 属性name @编辑后的项目集名称
 - 属性lastEditedBy @admin
- 执行programTest模块的buildProgramForEditTest方法，参数是5
 - 属性id @5
 - 属性lastEditedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

$project = zenData('project');
$project->loadYaml('buildprogramforedit', false, 2)->gen(10);

su('admin');

$programTest = new programTest();

$_POST['name'] = '待启动项目集编辑';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['PM'] = 'user1';
$_POST['acl'] = 'private';
$_POST['longTime'] = 0;
$_POST['realBegan'] = '2024-01-10';
r($programTest->buildProgramForEditTest(1)) && p('status') && e('doing');

unset($_POST);
$_POST['name'] = '长期项目集编辑';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['longTime'] = 1;
$_POST['acl'] = 'private';
r($programTest->buildProgramForEditTest(2)) && p('end') && e('2059-12-31');

unset($_POST);
$_POST['name'] = '开放访问项目集';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['acl'] = 'open';
$_POST['whitelist'] = array('admin', 'user1');
$_POST['longTime'] = 0;
r($programTest->buildProgramForEditTest(3)) && p('whitelist') && e('~~');

unset($_POST);
$_POST['name'] = '编辑后的项目集名称';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['PM'] = 'admin';
$_POST['budget'] = '200000';
$_POST['budgetUnit'] = 'CNY';
$_POST['acl'] = 'private';
$_POST['whitelist'] = array('admin', 'user1');
$_POST['desc'] = '这是编辑后的描述';
$_POST['longTime'] = 0;
r($programTest->buildProgramForEditTest(4)) && p('name,lastEditedBy') && e('编辑后的项目集名称,admin');

unset($_POST);
$_POST['name'] = '验证默认值';
$_POST['begin'] = '2024-03-01';
$_POST['end'] = '2024-09-30';
$_POST['acl'] = 'private';
$_POST['longTime'] = 0;
r($programTest->buildProgramForEditTest(5)) && p('id,lastEditedBy') && e('5,admin');