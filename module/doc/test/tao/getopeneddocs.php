#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOpenedDocs();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$sorts = array('id_desc', 'id_asc', 'title_asc', 'title_desc');

$hasPrivDocIdList[0] = array();
$hasPrivDocIdList[1] = range(1, 30);
$hasPrivDocIdList[2] = range(41, 60);
$hasPrivDocIdList[3] = range(51, 60);

$docTester = new docTest();
r($docTester->getOpenedDocsTest($hasPrivDocIdList[0], $sorts[0])) && p()                   && e('0');                // 获取没有可查看文档时，按id倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[0], $sorts[1])) && p()                   && e('0');                // 获取没有可查看文档时，按id正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[0], $sorts[2])) && p()                   && e('0');                // 获取没有可查看文档时，按标题正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[0], $sorts[3])) && p()                   && e('0');                // 获取没有可查看文档时，按标题倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[1], $sorts[0])) && p('1:title,addedBy')  && e('我的文档1,admin');  // 获取有可查看文档时，按id倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[1], $sorts[1])) && p('1:title,addedBy')  && e('我的文档1,admin');  // 获取有可查看文档时，按id正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[1], $sorts[2])) && p('1:title,addedBy')  && e('我的文档1,admin');  // 获取有可查看文档时，按标题正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[1], $sorts[3])) && p('1:title,addedBy')  && e('我的文档1,admin');  // 获取有可查看文档时，按标题倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[2], $sorts[0])) && p('41:title,addedBy') && e('产品文档41,admin'); // 获取有可查看文档时，按id倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[2], $sorts[1])) && p('41:title,addedBy') && e('产品文档41,admin'); // 获取有可查看文档时，按id正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[2], $sorts[2])) && p('41:title,addedBy') && e('产品文档41,admin'); // 获取有可查看文档时，按标题正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[2], $sorts[3])) && p('41:title,addedBy') && e('产品文档41,admin'); // 获取有可查看文档时，按标题倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[3], $sorts[0])) && p()                   && e('0');                // 获取有可查看文档但没有数据时，按id倒序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[3], $sorts[1])) && p()                   && e('0');                // 获取有可查看文档但没有数据时，按id正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[3], $sorts[2])) && p()                   && e('0');                // 获取有可查看文档但没有数据时，按标题正序排序的文档
r($docTester->getOpenedDocsTest($hasPrivDocIdList[3], $sorts[3])) && p()                   && e('0');                // 获取有可查看文档但没有数据时，按标题倒序排序的文档
