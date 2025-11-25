#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('api')->gen(0);

/**

title=测试 apiModel->create();
timeout=0
cid=15090

- 测试正常添加接口文档。 @1
- 测试接口名称为空。 @0
- 测试接口名称为空的错误提示信息。第title条的0属性 @『接口名称』不能为空。
- 测试请求路径为空。 @0
- 测试请求路径为空的错误提示信息。第path条的0属性 @『请求路径』不能为空。
- 测试请求路径为空。 @0
- 测试请求路径为空的错误提示信息。第title条的0属性 @『接口名称』已经有『API接口』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试请求路径为空。 @0
- 测试请求路径为空的错误提示信息。第path条的0属性 @『请求路径』已经有『bug-getList』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

global $tester;
$tester->loadModel('api');

$formData = new stdclass();
$formData->module = 0;
$formData->lib    = 1;
$formData->title  = 'API接口';
$formData->path   = 'bug-getList';
$formData->method = 'GET';

r($tester->api->create($formData)) && p() && e(1); // 测试正常添加接口文档。

$formData = new stdclass();
$formData->module = 0;
$formData->lib    = 1;
$formData->title  = '';
$formData->path   = 'bug-getList';
$formData->method = 'GET';

r($tester->api->create($formData)) && p() && e(0);                 // 测试接口名称为空。
r(dao::getError()) && p('title:0') && e('『接口名称』不能为空。'); // 测试接口名称为空的错误提示信息。

$formData = new stdclass();
$formData->module = 0;
$formData->lib    = 1;
$formData->title  = 'API接口';
$formData->path   = '';
$formData->method = 'GET';

r($tester->api->create($formData)) && p() && e(0);                // 测试请求路径为空。
r(dao::getError()) && p('path:0') && e('『请求路径』不能为空。'); // 测试请求路径为空的错误提示信息。

$formData = new stdclass();
$formData->module     = 0;
$formData->lib        = 1;
$formData->title      = 'API接口';
$formData->path       = 'project-getList';
$formData->method     = 'GET';

r($tester->api->create($formData)) && p() && e(0);                                                                                          // 测试请求路径为空。
r(dao::getError()) && p('title:0') && e('『接口名称』已经有『API接口』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试请求路径为空的错误提示信息。

$formData = new stdclass();
$formData->module     = 0;
$formData->lib        = 1;
$formData->title      = 'API接口2';
$formData->path       = 'bug-getList';
$formData->method     = 'GET';

r($tester->api->create($formData)) && p() && e(0);                                                                                             // 测试请求路径为空。
r(dao::getError()) && p('path:0') && e('『请求路径』已经有『bug-getList』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试请求路径为空的错误提示信息。
