#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getPrivsAfterVersion();
timeout=0
cid=1

- 获取所有带版本号的权限，判断是否包含doc-sort属性doc-sort @doc-sort
- 获取所有18.0之后的权限，判断是否包含doc-sort属性doc-sort @` `

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

$group = new groupTest();

r($group->getPrivsAfterVersionTest(''))     && p('doc-sort') && e('doc-sort'); // 获取所有带版本号的权限，判断是否包含doc-sort
r($group->getPrivsAfterVersionTest('18.0')) && p('doc-sort') && e('` `');      // 获取所有18.0之后的权限，判断是否包含doc-sort