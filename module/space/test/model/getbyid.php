#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->getByID();
cid=18394

- 获取ID=0的空间 @0
- 获取ID=1的空间
 - 属性name @空间1
 - 属性k8space @quickon-system
 - 属性owner @admin
 - 属性default @0
- 获取ID=6的空间 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('space')->loadYaml('space')->gen(5);

$idList = array(0, 1, 6);

$spaceTester = new spaceModelTest();
r($spaceTester->getByIDTest($idList[0])) && p()                             && e('0');                            // 获取ID=0的空间
r($spaceTester->getByIDTest($idList[1])) && p('name,k8space,owner,default') && e('空间1,quickon-system,admin,0'); // 获取ID=1的空间
r($spaceTester->getByIDTest($idList[2])) && p()                             && e('0');                            // 获取ID=6的空间
