#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getCloneUrl();
timeout=0
cid=18051

- 执行repo模块的getCloneUrlAvailableTest方法，参数是1 @1
- 执行repo模块的getCloneUrlAvailableTest方法，参数是2, 'ssh' @1
- 执行repo模块的getCloneUrlAvailableTest方法，参数是3 @1
- 执行repo模块的getCloneUrlAvailableTest方法，参数是4 @1
- 获取空项目 @empty

*/

global $tester;
$tester->dao->delete()->from(TABLE_ENTRY)->where('code')->eq('gitfox')->exec();
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repouser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml',   'path' => 'https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git', 'SCM' => 'Gitlab',      'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'Monitoring', 'path' => 'https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/Monitoring.git', 'SCM' => 'Git',         'scmType' => 'git', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'unittest',   'path' => 'https://giteadev.qc.oop.cc/gitea/unittest.git',                      'SCM' => 'Gitea',       'scmType' => 'git', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'testSvn',    'path' => 'https://svn.qc.oop.cc/svn/unittest/',                                'SCM' => 'Subversion',  'scmType' => 'svn', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData)
{
    $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoData['id'], 'account' => 'admin'))->exec();
}

$repo       = new repoModelTest();
$repo->seedGitFoxEntry();

r($repo->getCloneUrlAvailableTest(1))        && p() && e('1');
r($repo->getCloneUrlAvailableTest(2, 'ssh')) && p() && e('1');
r($repo->getCloneUrlAvailableTest(3))        && p() && e('1');
r($repo->getCloneUrlAvailableTest(4))        && p() && e('1');
r($repo->getCloneUrlTest(0)) && p() && e('empty');
