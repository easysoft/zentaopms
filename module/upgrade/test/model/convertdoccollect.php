#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('doc')->gen(5);
zenData('docaction')->gen(0);

/**

title=测试 upgradeModel->convertDocCollect();
cid=19508

- 检查 doc 表 collector 字段是否存在。 @0
- 检查 doc 表 collects 字段是否存在。 @1
- 检查 doc 表第一条记录 collects 字段值是否正确。属性collects @1
- 检查 doc 表第四条记录 collects 字段值是否正确。属性collects @4
- 检查 doc 表第五条记录 collects 字段值是否正确。属性collects @0
- 检查 docaction 表关于 doc 第一条记录 collect 记录数是否正确。 @1
- 检查 docaction 表关于 doc 第四条记录 collect 记录数是否正确。 @4
- 检查 docaction 表关于 doc 第五条记录 collect 记录数是否正确。 @0

**/

global $tester;
$upgradeModel = $tester->loadModel('upgrade');

$docs = $upgradeModel->dao->select('*')->from(TABLE_DOC)->fetchAll('id', false);

if(isset($docs[1]->collects))   $upgradeModel->dao->exec("ALTER TABLE " . TABLE_DOC . " DROP `collects`");
if(!isset($docs[1]->collector)) $upgradeModel->dao->exec("ALTER TABLE " . TABLE_DOC . " ADD `collector` varchar(255) NOT NULL DEFAULT ''");

$users = ['user1', 'user2', 'user3', 'user4'];
foreach($docs as $doc)
{
    $collectors = array_slice($users, 0, $doc->id % 5);
    $upgradeModel->dao->update(TABLE_DOC)->set('collector')->eq(implode(',', $collectors))->where('id')->eq($doc->id)->exec();
}

$upgradeModel->convertDocCollect();

$docs       = $upgradeModel->dao->select('*')->from(TABLE_DOC)->fetchAll('id', false);
$docActions = $upgradeModel->dao->select('*')->from(TABLE_DOCACTION)->fetchGroup('doc');


r((int)isset($docs[1]->collector)) && p()           && e('0');  // 检查 doc 表 collector 字段是否存在。
r((int)isset($docs[1]->collects))  && p()           && e('1');  // 检查 doc 表 collects 字段是否存在。
r($docs[1])                        && p('collects') && e('1');  // 检查 doc 表第一条记录 collects 字段值是否正确。
r($docs[4])                        && p('collects') && e('4');  // 检查 doc 表第四条记录 collects 字段值是否正确。
r($docs[5])                        && p('collects') && e('0');  // 检查 doc 表第五条记录 collects 字段值是否正确。
r(count($docActions[1]))           && p()           && e('1');  // 检查 docaction 表关于 doc 第一条记录 collect 记录数是否正确。
r(count($docActions[4]))           && p()           && e('4');  // 检查 docaction 表关于 doc 第四条记录 collect 记录数是否正确。
r((int)isset($docActions[5]))      && p()           && e('0');  // 检查 docaction 表关于 doc 第五条记录 collect 记录数是否正确。
