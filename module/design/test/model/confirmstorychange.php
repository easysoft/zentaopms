#!/usr/bin/env php
<?php
/**

title=测试 designModel->confirmStoryChange();
cid=1

- 确认id 1 的设计需求变更 @1
- 确认id 2 的设计需求变更 @1
- 确认id 3 的设计需求变更 @1
- 确认id 4 的设计需求变更 @2
- 确认id 5 的设计需求变更 @1
- 确认id 0 的设计需求变更 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/design.unittest.class.php';
