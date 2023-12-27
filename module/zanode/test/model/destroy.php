#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->destroy().
timeout=0
cid=1

- 测试删除一个不存在的执行节点，返回空表示无操作。 @0
- 测试删除一个hostType为virtual的执行节点，需要调用远端服务，返回空表示删除成功。 @执行失败，请检查宿主机和执行节点状态
- 测试删除一个hostType为physics的执行节点，不需要调用远端服务,返回空表示删除成功。 @0
- 判断是否生成了执行节点id为3的删除的操作记录。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(4);
su('admin');

$zanode = new zanodeTest();

$destroyIDList = array(0, 1, 3, 100);
r($zanode->destroy($destroyIDList[0])) && p('') && e('0');                                      //测试删除一个不存在的执行节点，返回空表示无操作。
r($zanode->destroy($destroyIDList[1])) && p('') && e('执行失败，请检查宿主机和执行节点状态');   //测试删除一个hostType为virtual的执行节点，需要调用远端服务，返回空表示删除成功。
r($zanode->destroy($destroyIDList[2])) && p('') && e('0');                                      //测试删除一个hostType为physics的执行节点，不需要调用远端服务,返回空表示删除成功。

$action = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectTYpe')->eq('zanode')->andWhere('objectID')->eq(3)->fetch();
r($action->action == 'deleted') && p('') && e('1');  //判断是否生成了执行节点id为3的删除的操作记录。