#!/usr/bin/env php
<?php

/**

title=测试 docModel->getApiLibs();
cid=1

- 获取全部的接口库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取包括id=6的全部的接口库
 - 第6条的type属性 @custom
 - 第6条的name属性 @自定义文档库6
- 获取未关联产品、项目的接口库 @0
- 获取包括id=6的未关联产品、项目的接口库
 - 第6条的type属性 @custom
 - 第6条的name属性 @自定义文档库6
- 获取产品接口库
 - 第4条的type属性 @api
 - 第4条的name属性 @产品接口库4
- 获取产品1接口库
 - 第4条的type属性 @api
 - 第4条的name属性 @产品接口库4
- 获取包括id=6的产品接口库
 - 第6条的type属性 @custom
 - 第6条的name属性 @自定义文档库6
- 获取项目接口库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取项目1接口库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取包括id=6的项目接口库
 - 第6条的type属性 @custom
 - 第6条的name属性 @自定义文档库6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(10);
zdTable('user')->gen(5);
su('admin');

$appendLibs  = array(0, 6);
$objectTypes = array('', 'nolink', 'product', 'project');
$objectIds   = array(0, 1, 11);

$docTester = new docTest();
r($docTester->getApiLibsTest($appendLibs[0], $objectTypes[0], $objectIds[0])) && p('1:type,name') && e('api,项目接口库1');      // 获取全部的接口库
r($docTester->getApiLibsTest($appendLibs[1], $objectTypes[0], $objectIds[0])) && p('6:type,name') && e('custom,自定义文档库6'); // 获取包括id=6的全部的接口库
r($docTester->getApiLibsTest($appendLibs[0], $objectTypes[1], $objectIds[0])) && p()              && e('0');                    // 获取未关联产品、项目的接口库
r($docTester->getApiLibsTest($appendLibs[1], $objectTypes[1], $objectIds[0])) && p('6:type,name') && e('custom,自定义文档库6'); // 获取包括id=6的未关联产品、项目的接口库
r($docTester->getApiLibsTest($appendLibs[0], $objectTypes[2], $objectIds[0])) && p('4:type,name') && e('api,产品接口库4');      // 获取产品接口库
r($docTester->getApiLibsTest($appendLibs[0], $objectTypes[2], $objectIds[1])) && p('4:type,name') && e('api,产品接口库4');      // 获取产品1接口库
r($docTester->getApiLibsTest($appendLibs[1], $objectTypes[2], $objectIds[1])) && p('6:type,name') && e('custom,自定义文档库6'); // 获取包括id=6的产品接口库
r($docTester->getApiLibsTest($appendLibs[0], $objectTypes[3], $objectIds[0])) && p('1:type,name') && e('api,项目接口库1');      // 获取项目接口库
r($docTester->getApiLibsTest($appendLibs[0], $objectTypes[3], $objectIds[2])) && p('1:type,name') && e('api,项目接口库1');      // 获取项目1接口库
r($docTester->getApiLibsTest($appendLibs[1], $objectTypes[3], $objectIds[2])) && p('6:type,name') && e('custom,自定义文档库6'); // 获取包括id=6的项目接口库
