#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getUndeleteParamsByObjectType().
timeout=0
cid=1

- 执行actionTest模块的getUndeleteParamsByObjectType方法，参数是'project'  @`zt_project`
- 执行actionTest模块的getUndeleteParamsByObjectType方法，参数是'project' 属性3 @id
- 执行actionTest模块的getUndeleteParamsByObjectType方法，参数是'product' 属性2 @id, name, code, acl
- 执行actionTest模块的getUndeleteParamsByObjectType方法，参数是'doc' 属性1 @version desc
- 执行actionTest模块的getUndeleteParamsByObjectType方法，参数是''  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('project')->gen(5);
zenData('product')->gen(5);
zenData('doc')->gen(5);

$actionTest = new actionTest();

r($actionTest->getUndeleteParamsByObjectType('project')) && p('0')      && e('`zt_project`');
r($actionTest->getUndeleteParamsByObjectType('project')) && p('3')      && e('id');
r($actionTest->getUndeleteParamsByObjectType('product')) && p('2', '|') && e('id, name, code, acl');
r($actionTest->getUndeleteParamsByObjectType('doc'))     && p('1')      && e('version desc');
r($actionTest->getUndeleteParamsByObjectType(''))        && p('0')      && e('~~');