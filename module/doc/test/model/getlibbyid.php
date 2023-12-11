#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLibById();
cid=1

- 测试空数据 @0
- 获取ID=1的文档库信息
 - 属性type @api
 - 属性name @项目接口库1
- 获取ID=4的文档库信息
 - 属性type @api
 - 属性name @产品接口库4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(5);
zdTable('user')->gen(5);
su('admin');

$idList = array(0, 1, 4);

$docTester = new docTest();
r($docTester->getLibByIdTest($idList[0])) && p()            && e('0');               // 测试空数据
r($docTester->getLibByIdTest($idList[1])) && p('type,name') && e('api,项目接口库1'); // 获取ID=1的文档库信息
r($docTester->getLibByIdTest($idList[2])) && p('type,name') && e('api,产品接口库4'); // 获取ID=4的文档库信息
