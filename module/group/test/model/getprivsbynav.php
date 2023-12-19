#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getPrivsByNav();
timeout=0
cid=1

- 验证my菜单的权限
 - 属性my-todo @my-todo
 - 属性todo-view @todo-view
 - 属性mail-index @` `
- 验证admin菜单的权限
 - 属性mail-index @mail-index
 - 属性cron-index @cron-index
- 验证admin菜单在18版本后的权限
 - 属性editor-edit @editor-edit
 - 属性mail-index @``

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

$group = new groupTest();

r($group->getPrivsByNavTest('my'))            && p('my-todo,todo-view,mail-index') && e('my-todo,todo-view,` `');   // 验证my菜单的权限
r($group->getPrivsByNavTest('admin'))         && p('mail-index,cron-index')        && e('mail-index,cron-index');   // 验证admin菜单的权限
r($group->getPrivsByNavTest('admin', '18.0')) && p('editor-edit,mail-index')       && e('editor-edit,``');          // 验证admin菜单在18版本后的权限