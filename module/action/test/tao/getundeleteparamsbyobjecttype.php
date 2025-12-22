#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getUndeleteParamsByObjectType().
timeout=0
cid=14957

- 查看通过项目获取的字段 @`zt_project`
- 查看通过项目获取的字段属性3 @id
- 查看通过产品获取的字段属性2 @id, name, code, acl
- 查看通过产品获取的字段属性3 @id
- 查看通过文档获取的字段属性1 @version desc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->gen(5);
zenData('product')->gen(5);
zenData('doc')->gen(5);

global $tester;
$tester->loadModel('action');

r($tester->action->getUndeleteParamsByObjectType('project')) && p('0')      && e('`zt_project`');         // 查看通过项目获取的字段
r($tester->action->getUndeleteParamsByObjectType('project')) && p('3')      && e('id');                   // 查看通过项目获取的字段
r($tester->action->getUndeleteParamsByObjectType('product')) && p('2', '|') && e('id, name, code, acl');  // 查看通过产品获取的字段
r($tester->action->getUndeleteParamsByObjectType('product')) && p('3')      && e('id');                   // 查看通过产品获取的字段
r($tester->action->getUndeleteParamsByObjectType('doc'))     && p('1')      && e('version desc');         // 查看通过文档获取的字段