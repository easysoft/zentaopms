#!/usr/bin/env php
<?php

/**

title=测试 groupModel->remove();
timeout=0
cid=1

- 删除id为1的组 @1
- 删除id为8的组 @1
- 删除id为0的组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('group')->gen(5);

$group = new groupTest();
r($group->removeTest(1)) && p() && e('1'); // 删除id为1的组
r($group->removeTest(8)) && p() && e('1'); // 删除id为8的组
r($group->removeTest(0)) && p() && e('1'); // 删除id为0的组