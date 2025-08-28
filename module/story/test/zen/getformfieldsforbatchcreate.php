#!/usr/bin/env php
<?php
/**

title=测试 storyZen::getFormFieldsForBatchCreate();
timeout=0
cid=1

- 测试获取普通产品批量创建软件需求字段
 - 第assignedTo条的control属性 @select
 - 第assignedTo条的label属性 @指派给
- 测试获取普通产品批量创建用户需求字段
 - 第assignedTo条的control属性 @select
 - 第assignedTo条的label属性 @指派给
- 测试获取普通产品批量创建业务需求字段
 - 第assignedTo条的control属性 @select
 - 第assignedTo条的label属性 @指派给
- 测试获取多分支产品批量创建软件需求字段
 - 第branch条的control属性 @select
 - 第branch条的label属性 @平台/分支
- 测试获取多分支产品批量创建用户需求字段
 - 第branch条的control属性 @select
 - 第branch条的label属性 @平台/分支
- 测试获取多分支产品批量创建业务需求字段
 - 第branch条的control属性 @select
 - 第branch条的label属性 @平台/分支
- 测试获取多平台产品批量创建软件需求字段
 - 第branch条的control属性 @select
 - 第branch条的label属性 @平台/分支
- 测试获取多平台产品批量创建用户需求字段
 - 第branch条的control属性 @select
 - 第branch条的label属性 @平台/分支
- 测试获取多平台产品批量创建业务需求字段
 - 第branch条的control属性 @select
 - 第branch条的label属性 @平台/分支

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('product')->loadYaml('product')->gen(3);
zenData('user')->gen(5);
su('admin');

$products = range(1, 3);
$branch   = 'all';
$storyTypes = array('story', 'requirement', 'epic');
