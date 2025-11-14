#!/usr/bin/env php
<?php

/**

title=测试 docZen::setAclForCreateLib();
timeout=0
cid=16222

- 执行docTest模块的setAclForCreateLibTest方法，参数是'custom'
 - 属性hasDefault @0
 - 属性hasOpen @1
 - 属性hasPrivate @1
- 执行docTest模块的setAclForCreateLibTest方法，参数是'mine'
 - 属性hasDefault @0
 - 属性hasOpen @0
 - 属性hasPrivate @1
 - 属性count @1
- 执行docTest模块的setAclForCreateLibTest方法，参数是'product'
 - 属性hasDefault @1
 - 属性hasOpen @0
 - 属性hasPrivate @1
- 执行docTest模块的setAclForCreateLibTest方法，参数是'project'
 - 属性hasDefault @1
 - 属性hasOpen @0
 - 属性hasPrivate @1
- 执行docTest模块的setAclForCreateLibTest方法，参数是'execution'
 - 属性hasDefault @1
 - 属性hasOpen @0
 - 属性hasPrivate @1
- 执行docTest模块的setAclForCreateLibTest方法，参数是'api'
 - 属性hasDefault @1
 - 属性hasOpen @1
 - 属性hasPrivate @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->setAclForCreateLibTest('custom')) && p('hasDefault,hasOpen,hasPrivate') && e('0,1,1');
r($docTest->setAclForCreateLibTest('mine')) && p('hasDefault,hasOpen,hasPrivate,count') && e('0,0,1,1');
r($docTest->setAclForCreateLibTest('product')) && p('hasDefault,hasOpen,hasPrivate') && e('1,0,1');
r($docTest->setAclForCreateLibTest('project')) && p('hasDefault,hasOpen,hasPrivate') && e('1,0,1');
r($docTest->setAclForCreateLibTest('execution')) && p('hasDefault,hasOpen,hasPrivate') && e('1,0,1');
r($docTest->setAclForCreateLibTest('api')) && p('hasDefault,hasOpen,hasPrivate') && e('1,1,1');