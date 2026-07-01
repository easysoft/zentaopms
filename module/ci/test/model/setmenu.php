#!/usr/bin/env php
<?php

/**

title=测试 ciModel::setMenu();
timeout=0
cid=15592

- 执行ci模块的setMenuTest方法 第code条的link属性 @代码|repo|browse|repoID=1
- 执行ci模块的setMenuTest方法，参数是2 第code条的link属性 @代码|repo|browse|repoID=2
- 执行ci模块的setMenuTest方法，参数是5 属性mr @~~
- 执行ci模块的setMenuTest方法，参数是2, 'gitlab' 第code条的link属性 @代码|repo|browse|repoID=%s
- 执行ci模块的setMenuTest方法，参数是0, 'ci' 第code条的link属性 @代码|repo|browse|repoID=1
- 执行ci模块的setMenuTest方法，参数是1 第code条的link属性 @代码|repo|browse|repoID=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester, $app;
$opsRepoSql = $app->getAppRoot() . 'test/data/ops_repo.sql';
if(file_exists($opsRepoSql)) $tester->dbh->exec(file_get_contents($opsRepoSql));

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->product->range('1{5},2{5}');
$repo->name->range('Git仓库{3},SVN仓库{2},Gitlab仓库{3},Github仓库{2}');
$repo->SCM->range('Git{3},SVN{2},Gitlab{3},Github{2}');
$repo->serviceHost->range('1-5');
$repo->deleted->range('0{8},1{2}');
$repo->gen(10);

zenData('pipeline')->gen(5);
$tester->dbh->exec("DELETE FROM `zt_entry` WHERE `code`='gitfox'");
$tester->dbh->exec("INSERT INTO `zt_entry` (`id`,`name`,`account`,`code`,`key`,`freePasswd`,`ip`,`createdBy`,`createdDate`,`calledTime`,`editedBy`,`editedDate`,`deleted`) VALUES (1,'GitFox入口','admin','gitfox','testkey1234567890testkey1234567',0,'*','admin','2026-01-01 00:00:00',0,'admin','2026-01-01 00:00:00','0') ON DUPLICATE KEY UPDATE `code`='gitfox'");
su('admin');

/* 静默 gitfox HTTP 请求，避免打真实服务。 */
if(!class_exists('ciSetMenuStubHttpClient'))
{
    class ciSetMenuStubHttpClient
    {
        public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
        {
            return json_encode(array('code' => 'fail', 'message' => 'stub'));
        }
    }
}
common::$httpClient = new ciSetMenuStubHttpClient();

$ci = new ciModelTest();

r($ci->setMenuTest(0)) && p('code:link') && e('代码|repo|browse|repoID=1');
r($ci->setMenuTest(2)) && p('code:link') && e('代码|repo|browse|repoID=2');
r($ci->setMenuTest(5)) && p('mr') && e('~~');
r($ci->setMenuTest(2, 'gitlab')) && p('code:link') && e('代码|repo|browse|repoID=%s');
r($ci->setMenuTest(0, 'ci')) && p('code:link') && e('代码|repo|browse|repoID=1');
r($ci->setMenuTest(1)) && p('code:link') && e('代码|repo|browse|repoID=1');

common::$httpClient = null;
