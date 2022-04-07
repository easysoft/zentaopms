#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->buildSearchForm();
cid=1
pid=1

测试设置的url参数 >> model/caselib/buildsearchform.php?m=caselib&f=browse&t=&libID=201&browseType=bySearch&queryID=myQueryID
测试设置的queryID值是否正确 >> 10

*/

$caselib   = new caselibTest();
$libID     = 201;
$queryID   = 10;
$actionURL = helper::createLink('caselib', 'browse', "libID=$libID&browseType=bySearch&queryID=myQueryID");
$libs      = $tester->loadModel('caselib')->getLibraries();

$caselib->buildSearchFormTest($libID, $libs, $queryID, $actionURL);
$search = $tester->config->testcase->search;

r($search) && p('actionURL') && e('model/caselib/buildsearchform.php?m=caselib&f=browse&t=&libID=201&browseType=bySearch&queryID=myQueryID'); //测试设置的url参数
r($search) && p('queryID')   && e('10'); //测试设置的queryID值是否正确