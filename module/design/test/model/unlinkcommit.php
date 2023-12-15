#!/usr/bin/env php
<?php
/**

title=测试 designModel->unlinkCommit();
cid=1

- 测试空数据 @0
- 测试没有关联提交的设计解除关联 @0
- 测试有关联提交的设计解除关联
 - 第0条的AID属性 @3
 - 第0条的AType属性 @design
 - 第0条的BID属性 @2
 - 第0条的BType属性 @commit
 - 第0条的relation属性 @completedin
- 测试不存在的设计解除关联 @0
- 测试不存在的提交解除关联
 - 第0条的AID属性 @3
 - 第0条的AType属性 @design
 - 第0条的BID属性 @2
 - 第0条的BType属性 @commit
 - 第0条的relation属性 @completedin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('relation')->config('relation')->gen(3);
zdTable('design')->config('design')->gen(3);
zdTable('user')->gen(5);

$designs = array(0, 2, 3, 4);
$commits = array(0, 1, 4);

$designTester = new designTest();
r($designTester->unlinkCommitTest($designs[0], $commits[0])) && p()                                 && e('0');                             // 测试空数据
r($designTester->unlinkCommitTest($designs[1], $commits[1])) && p()                                 && e('0');                             // 测试没有关联提交的设计解除关联
r($designTester->unlinkCommitTest($designs[2], $commits[1])) && p('0:AID,AType,BID,BType,relation') && e('3,design,2,commit,completedin'); // 测试有关联提交的设计解除关联
r($designTester->unlinkCommitTest($designs[3], $commits[1])) && p()                                 && e('0');                             // 测试不存在的设计解除关联
r($designTester->unlinkCommitTest($designs[2], $commits[2])) && p('0:AID,AType,BID,BType,relation') && e('3,design,2,commit,completedin'); // 测试不存在的提交解除关联
