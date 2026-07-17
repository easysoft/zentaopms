#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getCloneUrl();
timeout=0
cid=18051

- 获取gitlab项目2 clone url属性http @https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git
- 获取gitlab项目1 clone url属性http @https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/Monitoring.git
- 获取gitea项目 clone url属性http @https://giteadev.qc.oop.cc/gitea/unittest.git
- 获取svn项目clone url属性http @https://svn.qc.oop.cc/svn/unittest/
- 获取空项目 @empty

*/

global $dbh, $tester;
$tester->dao->delete()->from(TABLE_ENTRY)->where('code')->eq('gitfox')->exec();
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
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
$dbh->exec(<<<'SQL'
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
$httpClient = $repo->resetHttpClient();
foreach($repos as $repoData)
{
    $repoID = $repoData['id'];
    $repo->setGitfoxRepoCache($repoID, (object)array(
        'id'        => $repoID,
        'path'      => "space/repo{$repoID}",
        'gitURL'    => $repoData['path'],
        'gitSSHURL' => "ssh://git@gitfox.local/space/repo{$repoID}.git",
    ));

    if($repoData['scmType'] != 'svn')
    {
        $httpClient->setResponse($repoData['path'], json_encode((object)array(
            'data' => (object)array(
                'gitURL'    => $repoData['path'],
                'gitSSHURL' => "ssh://git@gitfox.local/space/repo{$repoID}.git",
            ),
        )));
    }
}

$result1 = $repo->getCloneUrlTest(1);
$result2 = $repo->getCloneUrlTest(2);
$result3 = $repo->getCloneUrlTest(3);
$result4 = $repo->getCloneUrlTest(4);
$result5 = $repo->getCloneUrlTest(0);

r($result1) && p('http') && e('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git');
r($result2) && p('http') && e('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/Monitoring.git');
r($result3) && p('http') && e('https://giteadev.qc.oop.cc/gitea/unittest.git');
r($result4) && p('http') && e('https://svn.qc.oop.cc/svn/unittest/');
r($result5) && p()       && e('empty');

$repo->restoreHttpClient();
