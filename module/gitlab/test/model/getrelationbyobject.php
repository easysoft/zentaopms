#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getRelationByObject();
timeout=0
cid=1

- 获取任务18对应的issueID。属性issueID @4
- 获取bug5对应的issueID。属性issueID @3
- 获取需求8对应的issueID。属性issueID @2

*/

zdTable('relation')->config('relation')->gen(4);

$gitlab = new gitlabTest();

r($gitlab->getRelationByObjectTest('task', 18)) && p('issueID') && e('4'); // 获取任务18对应的issueID。
r($gitlab->getRelationByObjectTest('bug', 5))   && p('issueID') && e('3'); // 获取bug5对应的issueID。
r($gitlab->getRelationByObjectTest('story', 8)) && p('issueID') && e('2'); // 获取需求8对应的issueID。