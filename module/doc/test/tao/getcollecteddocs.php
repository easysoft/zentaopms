#!/usr/bin/env php
<?php
/**

title=测试 docModel->getCollectedDocs();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('docaction')->config('docaction')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$sorts = array('id_desc', 'id_asc', 'title_asc', 'title_desc');

$hasPrivDocIdList[0] = array();
$hasPrivDocIdList[1] = range(1, 30);
$hasPrivDocIdList[2] = range(40, 60);
$hasPrivDocIdList[3] = range(51, 60);

$docTester = new docTest();
r($docTester->getCollectedDocsTest($hasPrivDocIdList[0], $sorts[0])) && p()          && e('0');         // 获取没有可查看的文档时，按照id倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[0], $sorts[1])) && p()          && e('0');         // 获取没有可查看的文档时，按照id正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[0], $sorts[2])) && p()          && e('0');         // 获取没有可查看的文档时，按照标题正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[0], $sorts[3])) && p()          && e('0');         // 获取没有可查看的文档时，按照标题倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[1], $sorts[0])) && p('1:title') && e('我的文档1'); // 获取有可查看的文档时，按照id倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[1], $sorts[1])) && p('1:title') && e('我的文档1'); // 获取有可查看的文档时，按照id正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[1], $sorts[2])) && p('1:title') && e('我的文档1'); // 获取有可查看的文档时，按照标题正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[1], $sorts[3])) && p('1:title') && e('我的文档1'); // 获取有可查看的文档时，按照标题倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[2], $sorts[0])) && p()          && e('0');         // 获取有可查看的文档但没有收藏文档时，按照id倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[2], $sorts[1])) && p()          && e('0');         // 获取有可查看的文档但没有收藏文档时，按照id正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[2], $sorts[2])) && p()          && e('0');         // 获取有可查看的文档但没有收藏文档时，按照标题正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[2], $sorts[3])) && p()          && e('0');         // 获取有可查看的文档但没有收藏文档时，按照标题倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[3], $sorts[0])) && p()          && e('0');         // 获取有可查看的文档但数据不存在时，按照id倒序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[3], $sorts[1])) && p()          && e('0');         // 获取有可查看的文档但数据不存在时，按照id正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[3], $sorts[2])) && p()          && e('0');         // 获取有可查看的文档但数据不存在时，按照标题正序排序的已收藏文档
r($docTester->getCollectedDocsTest($hasPrivDocIdList[3], $sorts[3])) && p()          && e('0');         // 获取有可查看的文档但数据不存在时，按照标题倒序排序的已收藏文档
