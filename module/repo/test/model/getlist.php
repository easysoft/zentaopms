#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=18069

- 获取代码库列表第1条的name属性 @testHtml
- 获取代码库列表数量 @4
- 获取代码库列表第4条的name属性 @testSvn
- 获取代码库列表第3条的name属性 @unittest
- 获取所有代码库列表数量 @4

*/

zenData('user')->gen(5);
zenData('repo')->loadYaml('repo', true)->gen(4);

su('admin');

$repo = new repoModelTest();
r($repo->getListTest(0, 0, 'id_asc')) && p('1:name') && e('testHtml'); // 获取代码库列表
r(count($repo->getListTest(0, 0, 'id_asc'))) && p() && e('4');        // 获取代码库列表数量
r($repo->getListTest(0, 0, 'id_asc')) && p('4:name') && e('testSvn'); // 获取代码库列表
r($repo->getListTest(0, 0, 'id_asc')) && p('3:name') && e('unittest');     // 获取代码库列表
r(count($repo->getListTest(0, 0, 'id_asc'))) && p() && e('4');              // 获取所有代码库列表数量
