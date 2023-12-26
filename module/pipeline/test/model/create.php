#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->create();
cid=1

- 获取添加的type属性type @Jenkins
- 获取添加的account属性account @JenkinsAccount
- 添加错误的url信息第url条的0属性 @『服务器地址』应当为合法的URL。
- 添加名称为空时第name条的0属性 @『应用名称』不能为空。
- 添加名称重复时第name条的0属性 @『应用名称』已经有『Jenkins』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->gen(0);

$pipelineTester = new pipelineTest();

$data = new stdClass();
$data->name     = 'Jenkins';
$data->url      = 'http://www.zentaopms.com/';
$data->account  = 'JenkinsAccount';
$data->token    = 'Jenkins';
$data->password = 'Jenkins';

$result1 = $pipelineTester->createTest('Jenkins', $data); // 获取添加的name
$result2 = $pipelineTester->createTest('gitlab', $data);  // 获取添加的account

$data->url = 'error url';
$result3   = $pipelineTester->createTest('Jenkins', $data); // 添加错误的url信息

$data->url  = 'http://www.zentaopms.com/';
$data->name = '';
$result4    = $pipelineTester->createTest('Jenkins', $data); // 添加名称为空时

$data->name = 'Jenkins';
$result5    = $pipelineTester->createTest('Jenkins', $data); // 添加名称重复时

r($result1) && p('type')    && e('Jenkins');                                                                                         // 获取添加的type
r($result2) && p('account') && e('JenkinsAccount');                                                                                  // 获取添加的account
r($result3) && p('url:0')   && e('『服务器地址』应当为合法的URL。');                                                                 // 添加错误的url信息
r($result4) && p('name:0')  && e('『应用名称』不能为空。');                                                                          // 添加名称为空时
r($result5) && p('name:0')  && e('『应用名称』已经有『Jenkins』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 添加名称重复时
