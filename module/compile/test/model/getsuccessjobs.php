#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('compile')->gen(10);
su('admin');

/**

title=测试 compileModel->getSuccessJobs();
cid=1
pid=1

- 检查传入参数为空时候的返回结果。 @0
- 检查传入正确参数时的返回结果。属性1 @1

*/

$tester->loadModel('compile');
r($tester->compile->getSuccessJobs(array()))            && p()  && e('0'); //检查传入参数为空时候的返回结果。
r($tester->compile->getSuccessJobs(array('1,2,3,4,5'))) && p(1) && e('1'); //检查传入正确参数时的返回结果。
