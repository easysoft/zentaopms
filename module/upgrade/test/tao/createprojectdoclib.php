#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeTao->createProjectDocLib();
cid=1

- 测试为私有控制权限项目创建项目主库
 - 属性name @项目主库
 - 属性type @project
 - 属性main @1
 - 属性acl @private
- 测试为继承项目集权限项目创建项目主库
 - 属性name @项目主库
 - 属性type @project
 - 属性main @1
 - 属性acl @custom
- 测试为公开访问权限项目创建项目主库
 - 属性name @项目主库
 - 属性type @project
 - 属性main @1
 - 属性acl @open

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('project')->gen(30);
zdTable('user')->gen(5);
su('admin');

$upgrade = new upgradeTest();
r($upgrade->createProjectDocLibTest(11)) && p('name,type,main,acl') && e('项目主库,project,1,private'); //测试为私有控制权限项目创建项目主库
r($upgrade->createProjectDocLibTest(12)) && p('name,type,main,acl') && e('项目主库,project,1,custom');  //测试为继承项目集权限项目创建项目主库
r($upgrade->createProjectDocLibTest(13)) && p('name,type,main,acl') && e('项目主库,project,1,open');    //测试为公开访问权限项目创建项目主库
