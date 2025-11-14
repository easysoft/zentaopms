#!/usr/bin/env php
<?php

/**

title=测试 projectreleaseZen::buildReleaseForCreate();
timeout=0
cid=17975

- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1
 - 属性name @Release 1.0
 - 属性product @1
- 执行$result2属性name @Release 2.0
- 执行$result2->system > 5 @1
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1
 - 属性name @Release 3.0
 - 属性releasedDate @~~
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是2
 - 属性name @Release 4.0
 - 属性project @2
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1
 - 属性name @Release 5.0
 - 属性branch @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->loadYaml('buildreleaseforcreate/product', false, 2)->gen(10);
zendata('project')->loadYaml('buildreleaseforcreate/project', false, 2)->gen(10);
zendata('system')->loadYaml('buildreleaseforcreate/system', false, 2)->gen(5);
zendata('release')->loadYaml('buildreleaseforcreate/release', false, 2)->gen(5);
zendata('user')->gen(5);

su('admin');

$projectreleaseTest = new projectreleaseZenTest();

// 测试步骤1:正常情况下创建发布,使用已有系统,状态为normal
$_POST['name'] = 'Release 1.0';
$_POST['product'] = 1;
$_POST['branch'] = 0;
$_POST['build'] = array(1);
$_POST['system'] = 1;
$_POST['newSystem'] = 0;
$_POST['status'] = 'normal';
$_POST['releasedDate'] = '2025-01-01';
r($projectreleaseTest->buildReleaseForCreateTest(1)) && p('name,product') && e('Release 1.0,1');

// 测试步骤2:创建发布,选择新建系统并填写系统名称
$_POST['name'] = 'Release 2.0';
$_POST['product'] = 1;
$_POST['branch'] = 0;
$_POST['build'] = array(1);
$_POST['system'] = 0;
$_POST['newSystem'] = 1;
$_POST['systemName'] = '新系统1';
$_POST['status'] = 'normal';
$result2 = $projectreleaseTest->buildReleaseForCreateTest(1);
r($result2) && p('name') && e('Release 2.0');

// 测试步骤3:测试新建系统的system字段
r($result2->system > 5) && p() && e('1');

// 测试步骤4:创建发布,新建系统并设置status为terminate,releasedDate为null
$_POST['name'] = 'Release 3.0';
$_POST['product'] = 1;
$_POST['branch'] = 0;
$_POST['build'] = array(1);
$_POST['system'] = 0;
$_POST['newSystem'] = 1;
$_POST['systemName'] = '新系统2';
$_POST['status'] = 'terminate';
$_POST['releasedDate'] = '2025-01-01';
r($projectreleaseTest->buildReleaseForCreateTest(1)) && p('name,releasedDate') && e('Release 3.0,~~');

// 测试步骤5:创建发布,新建系统并设置project参数
$_POST['name'] = 'Release 4.0';
$_POST['product'] = 1;
$_POST['branch'] = 0;
$_POST['build'] = array(1);
$_POST['system'] = 0;
$_POST['newSystem'] = 1;
$_POST['systemName'] = '新系统3';
$_POST['status'] = 'normal';
$_POST['releasedDate'] = '2025-01-01';
r($projectreleaseTest->buildReleaseForCreateTest(2)) && p('name,project') && e('Release 4.0,2');

// 测试步骤6:创建发布,新建系统并设置branch参数
$_POST['name'] = 'Release 5.0';
$_POST['product'] = 1;
$_POST['branch'] = 1;
$_POST['build'] = array(1);
$_POST['system'] = 0;
$_POST['newSystem'] = 1;
$_POST['systemName'] = '新系统4';
$_POST['status'] = 'normal';
$_POST['releasedDate'] = '2025-01-01';
r($projectreleaseTest->buildReleaseForCreateTest(1)) && p('name,branch') && e('Release 5.0,1');