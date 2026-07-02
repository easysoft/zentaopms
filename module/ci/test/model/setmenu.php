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

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->product->range('1{5},2{5}');
$repo->name->range('Git仓库{3},SVN仓库{2},Gitlab仓库{3},Github仓库{2}');
$repo->SCM->range('Git{3},SVN{2},Gitlab{3},Github{2}');
$repo->serviceHost->range('1-5');
$repo->deleted->range('0{8},1{2}');
$repo->gen(10);

zenData('pipeline')->gen(5);

/* ops_repo / ops_repouser 表结构由 test/data 下的 sql 文件建，数据由 zenData + loadYaml 灌入。 */
global $tester, $app;
$dataRoot = $app->getAppRoot() . 'test' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
foreach(array('ops_repo.sql', 'ops_repouser.sql') as $schema)
{
    $schemaFile = $dataRoot . $schema;
    if(file_exists($schemaFile)) $tester->dbh->exec(file_get_contents($schemaFile));
}

zenData('ops_repo')->loadYaml('ops_repo')->gen(5);
zenData('ops_repouser')->loadYaml('ops_repouser')->gen(5);

/* 让 gitfoxModel::getServer() 返回非空，避免 processGitService 里 sprintf(null,...) fatal。 */
zenData('entry')->loadYaml('entry')->gen(1);
$tester->dbh->exec("REPLACE INTO `zt_entry` (`id`,`name`,`account`,`code`,`key`,`freePasswd`,`ip`,`createdBy`,`createdDate`,`calledTime`,`editedBy`,`editedDate`,`deleted`) VALUES (1,'GitFox入口','admin','gitfox','testkey1234567890testkey1234567',0,'*','admin','2026-01-01 00:00:00',0,'admin','2026-01-01 00:00:00','0')");

su('admin');

/* stub HTTP，让 apiGetSingleRepo 返回带 gitURL 的对象，避免读 array 属性告警。 */
if(!class_exists('ciSetMenuStubHttpClient'))
{
    class ciSetMenuStubHttpClient
    {
        public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
        {
            return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'importing' => false)));
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
