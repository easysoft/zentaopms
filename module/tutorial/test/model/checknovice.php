#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->checkNovice();
cid=1

- 检查当account是 guest 的时候的返回值 modifyPassword 0。 @0
- 检查当account是 guest 的时候的返回值 modifyPassword 1。 @0
- 检查当account是 admin 的时候的返回值 modifyPassword 0 动态数量大于 10。 @0
- 检查当account是 admin 的时候的返回值 modifyPassword 1 动态数量大于 10。 @0
- 检查当account是 user1 的时候的返回值 modifyPassword 0 动态数量小于等于 10。 @1
- 检查当account是 user1 的时候的返回值 modifyPassword 1 动态数量小于等于 10。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('action')->gen(40);
zdTable('user')->gen(5);


$tutorial = new tutorialTest();

$modifyPassword = array(0, 1);

r($tutorial->checkNoviceTest($modifyPassword[0])) && p() && e('0'); //检查当account是 guest 的时候的返回值 modifyPassword 0。
r($tutorial->checkNoviceTest($modifyPassword[1])) && p() && e('0'); //检查当account是 guest 的时候的返回值 modifyPassword 1。

su('admin');
r($tutorial->checkNoviceTest($modifyPassword[0])) && p() && e('0'); //检查当account是 admin 的时候的返回值 modifyPassword 0 动态数量大于 10。
r($tutorial->checkNoviceTest($modifyPassword[1])) && p() && e('0'); //检查当account是 admin 的时候的返回值 modifyPassword 1 动态数量大于 10。

su('user1');
r($tutorial->checkNoviceTest($modifyPassword[0])) && p() && e('1'); //检查当account是 user1 的时候的返回值 modifyPassword 0 动态数量小于等于 10。
r($tutorial->checkNoviceTest($modifyPassword[1])) && p() && e('0'); //检查当account是 user1 的时候的返回值 modifyPassword 1 动态数量小于等于 10。
