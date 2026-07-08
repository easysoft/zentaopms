#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

/**

title=jobModel->update();
timeout=0
cid=16853

- 测试job名称为空第name条的0属性 @『流水线名称』不能为空。
- 测试更新job名称属性name @这是一个job11
- 测试更新job引擎异常第frame条的0属性 @SonarQube工具/框架仅在构建引擎为JenKins的情况下使用
- 测试更新triggerType为schedule的job定时任务时间属性atDay @2
- 测试更新triggerType为tag的job定时任务时间属性atDay @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);
zenData('job')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

su('admin');

$jobID = 1;

$job_upName    = array('name' => '这是一个job11');
$job_emptyName = array('name' => '');
$job_upEngine  = array('engine' => 'gitlab');

$pipelineTest = new pipelineModelTest();
r($pipelineTest->updateTest($idList[0], $changeName))     && p()                  && e('0');                                                                                              // 测试id为0时，修改名称
r($pipelineTest->updateTest($idList[1], $changeName))     && p('0:field,old,new') && e('name,gitLab,修改名称1');                                                                          // 测试id为1时，修改名称
r($pipelineTest->updateTest($idList[1], $changeAccount))  && p('1:field,old,new') && e('account,account,root');                                                                           // 测试id为1时，修改账号
r($pipelineTest->updateTest($idList[1], $changePassword)) && p('1:field,old,new') && e('password,~~,654321');                                                                             // 测试id为1时，修改password
r($pipelineTest->updateTest($idList[1], $changeToken))    && p('2:field,old,new') && e('token,~~,123456');                                                                                // 测试id为1时，修改token
r($pipelineTest->updateTest($idList[1], $emptyName))      && p('name:0')          && e('『应用名称』不能为空。');                                                                         // 测试id为1时，修改名称为空
r($pipelineTest->updateTest($idList[1], $repeatName))     && p('name:0')          && e('『应用名称』已经有『gitLab』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试id为1时，修改名称重复
r($pipelineTest->updateTest($idList[2], $emptyAccount))   && p('account:0')       && e('『用户名』不能为空。');                                                                           // 测试id为1时，修改账号为空
r($pipelineTest->updateTest($idList[2], $emptyToken))     && p('token:0')         && e('『Token』不能为空。');                                                                            // 测试id为1时，修改token为空
r($pipelineTest->updateTest($idList[2], $emptyPassword))  && p('password:0')      && e('『密码』不能为空。');                                                                             // 测试id为1时，修改password为空
