#!/usr/bin/env php
<?php
/**

title=测试 docModel->updateDocOrder();
cid=1

- 检查ID为1的文档顺序 @3
- 检查ID为2的文档顺序 @1
- 检查ID为3的文档顺序 @8
- 检查ID为4的文档顺序 @9
- 检查ID为5的文档顺序 @2
- 检查ID为6的文档顺序 @10
- 检查ID为7的文档顺序 @5
- 检查ID为8的文档顺序 @7
- 检查ID为9的文档顺序 @6
- 检查ID为10的文档顺序 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$docData = zenData('doc')->loadYaml('doc');
$docData->order->range('1-10');
$docData->gen(10);

zenData('user')->gen(5);
su('admin');
