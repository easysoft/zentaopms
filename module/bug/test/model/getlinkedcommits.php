#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    zenData('repohistory')->loadYaml('repohistory_getlinkedcommits')->gen(10);
    zenData('relation')->loadYaml('relation_getlinkedcommits')->gen(8);
    zenData('bug')->loadYaml('bug_getlinkedcommits')->gen(5);
}

/**

title=测试 bugModel::getLinkedCommits();
timeout=0
cid=0

- 执行bug模块的getLinkedCommits方法，参数是1, array  @2
- 执行bug模块的getLinkedCommits方法，参数是999, array  @0
- 执行bug模块的getLinkedCommits方法，参数是1, array  @0
- 执行bug模块的getLinkedCommits方法，参数是1, array  @0
- 执行bug模块的getLinkedCommits方法，参数是1, array  @0

*/

global $tester;
$tester->loadModel('bug');

initData();

r($tester->bug->getLinkedCommits(1, array('abc123', 'def456'))) && p() && e('2');
r($tester->bug->getLinkedCommits(999, array('abc123', 'def456'))) && p() && e('0');
r($tester->bug->getLinkedCommits(1, array())) && p() && e('0');
r($tester->bug->getLinkedCommits(1, array('nonexistent'))) && p() && e('0');
r($tester->bug->getLinkedCommits(1, array('xyz999'))) && p() && e('0');