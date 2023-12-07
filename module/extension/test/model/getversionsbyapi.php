#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getVersionsByAPI();
timeout=0
cid=1

- 判断返回的数据是否是数组。 @1
- 判断返回的数据是否包含id,releaseVersion,compatibleVersion,code字段。
 -  @id
 - 属性1 @releaseVersion
 - 属性2 @compatibleVersion
 - 属性3 @code

*/

global $tester;
$tester->loadModel('extension');

$extension = 'zentaopatch';
$apiVersions = $tester->extension->getVersionsByAPI('zentaopatch');

r(is_array($apiVersions)) && p() && e('1');                                                                      // 判断返回的数据是否是数组。
r(array_keys((array)$apiVersions[$extension])) && p('0,1,2,3') && e('id,releaseVersion,compatibleVersion,code'); // 判断返回的数据是否包含id,releaseVersion,compatibleVersion,code字段。
