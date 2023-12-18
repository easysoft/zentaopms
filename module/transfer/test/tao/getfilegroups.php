#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('file')->gen(10);
su('admin');

/**

title=测试 transfer->getFileGroups();
timeout=0
cid=1

- 获取bug模块的附件第2[0]条的title属性 @文件标题2
- 获取bug模块的附件数量 @2
- 获取当ID不存在时获取附件 @0

*/
global $tester;
$tester->loadModel('transfer');

$fileList1 = $tester->transfer->getFileGroups('bug', array(2, 8));
$fileList2 = $tester->transfer->getFileGroups('bug', array());
$fileList3 = $tester->transfer->getFileGroups('bug', array(4));

r($fileList1)        && p('2[0]:title')  && e('文件标题2'); // 获取bug模块的附件
r(count($fileList2)) && p('')            && e('2');         // 获取bug模块的附件数量
r($fileList3)        && p('')            && e('0');         // 获取当ID不存在时获取附件
