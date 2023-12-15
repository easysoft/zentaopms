#!/usr/bin/env php
<?php
/**

title=测试 designModel->updateLinkedCommits();
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->config('design')->gen(1);
zdTable('relation')->gen(0);

$designs      = array(0, 1, 2);
$repos        = array(0, 1);
$revisions[0] = array();
$revisions[1] = array(1, 2, 3);

$designTester = new designTest();
r($designTester->updateLinkedCommitsTest($designs[0], $repos[0], $revisions[0])) && p()                                                 && e('0');                                    // 测试空数据
r($designTester->updateLinkedCommitsTest($designs[0], $repos[1], $revisions[1])) && p()                                                 && e('0');                                    // 测试designID为0的数据
r($designTester->updateLinkedCommitsTest($designs[1], $repos[0], $revisions[1])) && p()                                                 && e('0');                                    // 测试repoID为0的数据
r($designTester->updateLinkedCommitsTest($designs[1], $repos[1], $revisions[0])) && p()                                                 && e('0');                                    // 测试提交记录为空的数据
r($designTester->updateLinkedCommitsTest($designs[1], $repos[1], $revisions[1])) && p('5:project,product,AType,AID,BType,BID,relation') && e('11,0,commit,3,design,1,completedfrom'); // 测试正常数据
r($designTester->updateLinkedCommitsTest($designs[2], $repos[1], $revisions[1])) && p()                                                 && e('0');                                    // 测试designID不存在的数据
