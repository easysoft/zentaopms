#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doc')->gen(5);
zenData('doccontent')->gen(0);

/**

title=测试 upgradeModel->moveDocContent();
cid=19538

- 检查 docContent 数。 @5
- 检查 doc 和 doccontent 标题是否一致。 @1
- 检查 doc 表 digest 字段是否存在。 @0
- 检查 doc 表 content 字段是否存在。 @0
- 检查 doc 表 url 字段是否存在。 @0
- 检查 doccontent 的 title,digest,content的值。
 - 属性title @文档标题1
 - 属性digest @digest_1
 - 属性content @content_1

**/

global $tester;
$upgradeModel = $tester->loadModel('upgrade');

$upgradeModel->dao->exec('ALTER TABLE ' . TABLE_DOC . ' ADD `digest` varchar(255) NOT NULL');
$upgradeModel->dao->exec('ALTER TABLE ' . TABLE_DOC . ' ADD `content` varchar(255) NOT NULL');
$upgradeModel->dao->exec('ALTER TABLE ' . TABLE_DOC . ' ADD `url` varchar(255) NOT NULL');

$upgradeModel->dao->update(TABLE_DOC)->set("`digest` = CONCAT('digest_', id)")->exec();
$upgradeModel->dao->update(TABLE_DOC)->set("`content` = CONCAT('content_', id)")->exec();

$upgradeModel->moveDocContent();

$docs        = $upgradeModel->dao->select('*')->from(TABLE_DOC)->fetchAll('id', false);
$docContents = $upgradeModel->dao->select('*')->from(TABLE_DOCCONTENT)->fetchAll('id', false);

r(count($docContents)) && p() && e('5');                                              // 检查 docContent 数。
r((int)($docs[1]->title == $docContents[1]->title)) && p() && e('1');                 // 检查 doc 和 doccontent 标题是否一致。
r((int)isset($docs[1]->digest)) && p() && e('0');                                     // 检查 doc 表 digest 字段是否存在。
r((int)isset($docs[1]->content)) && p() && e('0');                                    // 检查 doc 表 content 字段是否存在。
r((int)isset($docs[1]->url)) && p() && e('0');                                        // 检查 doc 表 url 字段是否存在。
r($docContents[1]) && p('title,digest,content') && e('文档标题1,digest_1,content_1');  // 检查 doccontent 的 title,digest,content的值。
