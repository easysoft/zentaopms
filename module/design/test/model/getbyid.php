#!/usr/bin/env php
<?php
/**

title=测试 designModel->getByID();
cid=1

- 获取ID=0的设计信息 @0
- 获取ID=3的设计信息
 - 属性project @11
 - 属性product @1
 - 属性name @设计3
 - 属性desc @这是设计描述3
- 获取ID不存在的设计信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->config('design')->gen(3);
zdTable('file')->gen(0);
zdTable('product')->config('product')->gen(1);
zdTable('relation')->config('relation')->gen(5);

$idList = array(0, 3, 4);

$designTester = new designTest();
r($designTester->getByIDTest($idList[0])) && p()                            && e('0');                        // 获取ID=0的设计信息
r($designTester->getByIDTest($idList[1])) && p('project,product,name,desc') && e('11,1,设计3,这是设计描述3'); // 获取ID=3的设计信息
r($designTester->getByIDTest($idList[2])) && p()                            && e('0');                        // 获取ID不存在的设计信息
