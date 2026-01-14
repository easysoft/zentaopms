#!/usr/bin/env php
<?php

/**

title=测试 projectTao::buildLinkForProject();
timeout=0
cid=17888

- 步骤1:测试execution方法 @m=project&f=execution&status=all&projectID=%s
- 步骤2:测试managePriv方法 @m=project&f=group&projectID=%s
- 步骤3:测试showerrornone方法 @m=projectstory&f=story&projectID=%s
- 步骤4:测试bug方法 @m=project&f=bug&projectID=%s
- 步骤5:测试testcase方法 @m=project&f=testcase&projectID=%s
- 步骤6:测试testtask方法 @m=project&f=testtask&projectID=%s
- 步骤7:测试testreport方法 @m=project&f=testreport&projectID=%s
- 步骤8:测试build方法 @m=project&f=build&projectID=%s
- 步骤9:测试dynamic方法 @m=project&f=dynamic&projectID=%s
- 步骤10:测试view方法 @m=project&f=view&projectID=%s
- 步骤11:测试manageproducts方法 @m=project&f=manageproducts&projectID=%s
- 步骤12:测试team方法 @m=project&f=team&projectID=%s
- 步骤13:测试managemembers方法 @m=project&f=managemembers&projectID=%s
- 步骤14:测试whitelist方法 @m=project&f=whitelist&projectID=%s
- 步骤15:测试addwhitelist方法 @m=project&f=addwhitelist&projectID=%s
- 步骤16:测试group方法 @m=project&f=group&projectID=%s
- 步骤17:测试空字符串 @0
- 步骤18:测试未定义的方法browse @0
- 步骤19:测试未定义的方法edit @0
- 步骤20:测试未定义的方法delete @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$projectTest = new projectTaoTest();

// 4. 强制要求:必须包含至少5个测试步骤
r($projectTest->buildLinkForProjectTest('execution')) && p() && e('m=project&f=execution&status=all&projectID=%s'); // 步骤1:测试execution方法
r($projectTest->buildLinkForProjectTest('managePriv')) && p() && e('m=project&f=group&projectID=%s'); // 步骤2:测试managePriv方法
r($projectTest->buildLinkForProjectTest('showerrornone')) && p() && e('m=projectstory&f=story&projectID=%s'); // 步骤3:测试showerrornone方法
r($projectTest->buildLinkForProjectTest('bug')) && p() && e('m=project&f=bug&projectID=%s'); // 步骤4:测试bug方法
r($projectTest->buildLinkForProjectTest('testcase')) && p() && e('m=project&f=testcase&projectID=%s'); // 步骤5:测试testcase方法
r($projectTest->buildLinkForProjectTest('testtask')) && p() && e('m=project&f=testtask&projectID=%s'); // 步骤6:测试testtask方法
r($projectTest->buildLinkForProjectTest('testreport')) && p() && e('m=project&f=testreport&projectID=%s'); // 步骤7:测试testreport方法
r($projectTest->buildLinkForProjectTest('build')) && p() && e('m=project&f=build&projectID=%s'); // 步骤8:测试build方法
r($projectTest->buildLinkForProjectTest('dynamic')) && p() && e('m=project&f=dynamic&projectID=%s'); // 步骤9:测试dynamic方法
r($projectTest->buildLinkForProjectTest('view')) && p() && e('m=project&f=view&projectID=%s'); // 步骤10:测试view方法
r($projectTest->buildLinkForProjectTest('manageproducts')) && p() && e('m=project&f=manageproducts&projectID=%s'); // 步骤11:测试manageproducts方法
r($projectTest->buildLinkForProjectTest('team')) && p() && e('m=project&f=team&projectID=%s'); // 步骤12:测试team方法
r($projectTest->buildLinkForProjectTest('managemembers')) && p() && e('m=project&f=managemembers&projectID=%s'); // 步骤13:测试managemembers方法
r($projectTest->buildLinkForProjectTest('whitelist')) && p() && e('m=project&f=whitelist&projectID=%s'); // 步骤14:测试whitelist方法
r($projectTest->buildLinkForProjectTest('addwhitelist')) && p() && e('m=project&f=addwhitelist&projectID=%s'); // 步骤15:测试addwhitelist方法
r($projectTest->buildLinkForProjectTest('group')) && p() && e('m=project&f=group&projectID=%s'); // 步骤16:测试group方法
r($projectTest->buildLinkForProjectTest('')) && p() && e('0'); // 步骤17:测试空字符串
r($projectTest->buildLinkForProjectTest('browse')) && p() && e('0'); // 步骤18:测试未定义的方法browse
r($projectTest->buildLinkForProjectTest('edit')) && p() && e('0'); // 步骤19:测试未定义的方法edit
r($projectTest->buildLinkForProjectTest('delete')) && p() && e('0'); // 步骤20:测试未定义的方法delete