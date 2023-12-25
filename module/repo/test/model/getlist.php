#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=1

- 获取Gitlab类型版本库列表第1条的name属性 @testHtml
- 获取Gitlab类型版本库列表数量 @2
- 获取Subversion类型版本库列表第4条的name属性 @testSvn
- 获取Gitea类型版本库列表第3条的name属性 @unittest
- 获取所有类型版本库列表数量 @4

*/

zdTable('repo')->config('repo', true)->gen(4);

$repo = new repoTest();
r($repo->getListTest(0, 'Gitlab')) && p('1:name') && e('testHtml'); // 获取Gitlab类型版本库列表
r(count($repo->getListTest(0, 'Gitlab'))) && p() && e('2');        // 获取Gitlab类型版本库列表数量
r($repo->getListTest(0, 'Subversion')) && p('4:name') && e('testSvn'); // 获取Subversion类型版本库列表
r($repo->getListTest(0, 'Gitea')) && p('3:name') && e('unittest');     // 获取Gitea类型版本库列表
r(count($repo->getListTest(0, ''))) && p() && e('4');              // 获取所有类型版本库列表数量