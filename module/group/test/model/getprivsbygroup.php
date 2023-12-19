#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getPrivsByGroup();
timeout=0
cid=1

- 验证分组是否包含正确权限
 - 属性module1-method1 @module1-method1
 - 属性module6-method6 @module6-method6
- 验证分组是否不包含组外权限属性module2-method2 @` `

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('group')->gen(5);
zdTable('grouppriv')->config('grouppriv')->gen(10);

$group = new groupTest();

r($group->getPrivsByGroupTest(1)) && p('module1-method1,module6-method6') && e('module1-method1,module6-method6'); // 验证分组是否包含正确权限
r($group->getPrivsByGroupTest(1)) && p('module2-method2')                 && e('` `');                             // 验证分组是否不包含组外权限