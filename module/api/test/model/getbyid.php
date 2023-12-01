#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api')->gen(10);
zdTable('apispec')->gen(10);
zdTable('api_lib_release')->gen(10);

/**

title=测试 apiModel->getById();
timeout=0
cid=1

- 获取接口文档ID为1的接口。
 - 属性id @1
 - 属性title @BUG接口1
 - 属性path @bug-getList
 - 属性status @doing
- 获取接口文档ID为2的接口。
 - 属性id @2
 - 属性title @BUG接口2
 - 属性path @bug-getList
 - 属性status @done
- 获取接口文档ID为1, 版本为2的接口。
 - 属性id @1
 - 属性title @BUG接口1
 - 属性path @bug-getList
 - 属性status @done
- 获取接口文档ID为1, 版本为2, 发布为1的接口。
 - 属性id @1
 - 属性title @BUG接口1
 - 属性path @bug-getList
 - 属性status @done

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getByID(1))     && p('id,title,path,status') && e('1,BUG接口1,bug-getList,doing'); //获取接口文档ID为1的接口。
r($tester->api->getByID(2))     && p('id,title,path,status') && e('2,BUG接口2,bug-getList,done');  //获取接口文档ID为2的接口。
r($tester->api->getByID(1,2))   && p('id,title,path,status') && e('1,BUG接口1,bug-getList,done');  //获取接口文档ID为1, 版本为2的接口。
r($tester->api->getByID(1,2,1)) && p('id,title,path,status') && e('1,BUG接口1,bug-getList,done');  //获取接口文档ID为1, 版本为2, 发布为1的接口。
