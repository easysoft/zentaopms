#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
/**

title=测试 systemModel::isClickable();
timeout=0
cid=1

- 上架状态，不显示上键按钮 @0
- 上架状态，显示下键按钮 @1
- 编辑按钮正常展示 @1
- 删除按钮正常展示 @1
- 下架状态，显示上键按钮 @1
- 下架状态，不显示下键按钮 @0
- 编辑按钮正常展示 @1
- 删除按钮正常展示 @1
*/
