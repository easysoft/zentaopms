#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getModulesByAPI();
timeout=0
cid=1

- 判断返回的数据是否是数组。 @1
- 判断返回的数据是否包含id,name,parent,url字段。
 -  @id
 - 属性1 @name
 - 属性2 @parent
 - 属性3 @url

*/

global $tester;
$tester->loadModel('extension');
$apiModules = $tester->extension->getModulesByAPI();

r(is_array($apiModules)) && p() && e('1');                                       // 判断返回的数据是否是数组。
r(array_keys((array)$apiModules[0])) && p('0,1,2,3') && e('id,name,parent,url'); // 判断返回的数据是否包含id,name,parent,url字段。
