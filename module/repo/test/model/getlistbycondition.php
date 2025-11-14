#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('repo')->loadYaml('repo_getlistbycondition')->gen(5);

su('admin');

/**

title=测试 repoModel::getListByCondition();
timeout=0
cid=18070

- 执行repoTest模块的getListByConditionTest方法，参数是'', ''  @1
- 执行repoTest模块的getListByConditionTest方法，参数是'', ''  @5
- 执行repoTest模块的getListByConditionTest方法，参数是"name='testHtml'", ''  @2
- 执行repoTest模块的getListByConditionTest方法，参数是'', 'Gitlab'  @3
- 执行$results第0条的id属性 @1
- 执行repoTest模块的getListByConditionTest方法，参数是'', '', 'id_desc', $pager  @2
- 执行repoTest模块的getListByConditionTest方法，参数是"name like '%test%'", ''  @5
- 执行repoTest模块的getListByConditionTest方法，参数是"name = 'nonexistent'", ''  @2

*/

$repoTest = new repoTest();

$pager = new stdclass();
$pager->recPerPage = 2;
$pager->pageID     = 1;
$repoTest->objectModel->app->loadClass('pager', true);
$pager = pager::init(0, $pager->recPerPage, $pager->pageID);

r(is_array($repoTest->getListByConditionTest('', ''))) && p() && e('1');
r(count($repoTest->getListByConditionTest('', ''))) && p() && e('5');
r(count($repoTest->getListByConditionTest("name='testHtml'", ''))) && p() && e('2');
r(count($repoTest->getListByConditionTest('', 'Gitlab'))) && p() && e('3');
$results = $repoTest->getListByConditionTest('', '', 'id_asc');
r(array_values($results)) && p('0:id') && e('1');
r(count($repoTest->getListByConditionTest('', '', 'id_desc', $pager))) && p() && e('2');
r(count($repoTest->getListByConditionTest("name like '%test%'", ''))) && p() && e('5');
r(count($repoTest->getListByConditionTest("name = 'nonexistent'", ''))) && p() && e('2');