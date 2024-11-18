#!/usr/bin/env php
<?php
/**

title=测试 designModel->isClickable();
cid=1

- 确认needConfirm true 动作 confirmStoryChange 的设计是否可以操作 @1
- 确认needConfirm false 动作 confirmStoryChange 的设计是否可以操作 @0
- 确认needConfirm 不存在 动作 confirmStoryChange 的设计是否可以操作 @0
- 确认needConfirm true 动作 edit 的设计是否可以操作 @1
- 确认needConfirm false 动作 edit 的设计是否可以操作 @1
- 确认needConfirm 不存在 动作 edit 的设计是否可以操作 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/design.unittest.class.php';

zenData('design')->loadYaml('design')->gen(5);
