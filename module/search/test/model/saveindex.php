#!/usr/bin/env php
<?php

/**

title=测试 searchModel::saveIndex();
timeout=0
cid=0

- 步骤1：首次保存索引时返回成功 @1
- 步骤2：首次保存后会写入 build 类型索引 @build
- 步骤3：首次保存后标题会被拆分并保留 unicode 编码 @1
- 步骤4：首次保存后会同步写入搜索字典 @你
- 步骤5：相同对象二次保存时仍返回成功 @1
- 步骤6：相同对象二次保存时只保留一条索引 @1
- 步骤7：相同对象二次保存时标题会被新内容替换 @ build  beta_

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
$tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

su('admin');

$search = new searchModelTest();

$build = (object) array(
    'id'       => 101,
    'name'     => 'Build Alpha 你好',
    'desc'     => 'desc content',
    'filePath' => '/tmp/pkg',
    'scmPath'  => '/repo/main',
    'date'     => '2026-07-07 10:00:00',
    'comment'  => 'extra note',
    'vision'   => 'rnd',
);

$firstSave = $search->saveIndexTest('build', $build);
$firstRow  = $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->where('objectType')->eq('build')->andWhere('objectID')->eq(101)->fetch();
$firstDict = $tester->dao->select('`key`,value')->from(TABLE_SEARCHDICT)->orderBy('`key`')->fetchPairs();

$build->name = 'Build Beta';
$build->desc = 'new desc';

$secondSave  = $search->saveIndexTest('build', $build);
$savedRows   = $tester->dao->select('id,title')->from(TABLE_SEARCHINDEX)->where('objectType')->eq('build')->andWhere('objectID')->eq(101)->fetchAll();
$updatedRow  = $tester->dao->select('title')->from(TABLE_SEARCHINDEX)->where('objectType')->eq('build')->andWhere('objectID')->eq(101)->fetch();

r($firstSave)                                            && p() && e('1');           // 步骤1：首次保存索引时返回成功
r($firstRow->objectType)                                 && p() && e('build');       // 步骤2：首次保存后会写入 build 类型索引
r(strpos($firstRow->title, '20320 22909') !== false)     && p() && e('1');           // 步骤3：首次保存后标题会被拆分并保留 unicode 编码
r($firstDict[20320])                                     && p() && e('你');          // 步骤4：首次保存后会同步写入搜索字典
r($secondSave)                                           && p() && e('1');           // 步骤5：相同对象二次保存时仍返回成功
r(count($savedRows))                                     && p() && e('1');           // 步骤6：相同对象二次保存时只保留一条索引
r($updatedRow->title)                                    && p() && e(' build  beta_'); // 步骤7：相同对象二次保存时标题会被新内容替换
