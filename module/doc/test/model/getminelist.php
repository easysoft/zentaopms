#!/usr/bin/env php
<?php

/**

title=测试 docModel->getMineList();
cid=1

- 获取最近浏览的所有文档
 - 第3条的title属性 @我的文档3
 - 第3条的status属性 @normal
- 获取最近浏览的所有草稿文档
 - 第6条的title属性 @我的草稿文档6
 - 第6条的status属性 @draft
- 获取最近浏览并且文档名字中有“ 文档” 的文档
 - 第3条的title属性 @我的文档3
 - 第3条的status属性 @normal
- 获取最近收藏的所有文档
 - 第1条的title属性 @我的文档1
 - 第1条的status属性 @normal
- 获取最近收藏的所有草稿文档
 - 第7条的title属性 @我的草稿文档7
 - 第7条的status属性 @draft
- 获取最近收藏并且文档名字中有“ 文档” 的文档
 - 第1条的title属性 @我的文档1
 - 第1条的status属性 @normal
- 获取我创建的所有文档
 - 第1条的title属性 @我的文档1
 - 第1条的status属性 @normal
- 获取我创建的所有草稿文档
 - 第7条的title属性 @我的草稿文档7
 - 第7条的status属性 @draft
- 获取我创建并且文档名字中有“ 文档” 的文档
 - 第1条的title属性 @我的文档1
 - 第1条的status属性 @normal
- 获取我编辑的所有文档
 - 第1条的title属性 @我的文档1
 - 第1条的status属性 @normal
- 获取我编辑的所有草稿文档
 - 第7条的title属性 @我的草稿文档7
 - 第7条的status属性 @draft
- 获取我编辑并且文档名字中有“ 文档” 的文档
 - 第1条的title属性 @我的文档1
 - 第1条的status属性 @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '%文档%' ) AND ( 1 ))`");
$userqueryTable->gen(1);

zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('docaction')->config('docaction')->gen(20);
zdTable('action')->config('action')->gen(20);
zdTable('user')->gen(5);
su('admin');

$types       = array('view', 'collect', 'createdby', 'editedby');
$browseTypes = array('all', 'draft', 'bysearch');
$queries     = array(0, 1);

$docTester = new docTest();
r($docTester->getMineListTest($types[0], $browseTypes[0], $queries[0])) && p('3:title,status') && e('我的文档3,normal');    // 获取最近浏览的所有文档
r($docTester->getMineListTest($types[0], $browseTypes[1], $queries[0])) && p('6:title,status') && e('我的草稿文档6,draft'); // 获取最近浏览的所有草稿文档
r($docTester->getMineListTest($types[0], $browseTypes[2], $queries[1])) && p('3:title,status') && e('我的文档3,normal');    // 获取最近浏览并且文档名字中有“ 文档” 的文档
r($docTester->getMineListTest($types[1], $browseTypes[0], $queries[0])) && p('1:title,status') && e('我的文档1,normal');    // 获取最近收藏的所有文档
r($docTester->getMineListTest($types[1], $browseTypes[1], $queries[0])) && p('7:title,status') && e('我的草稿文档7,draft'); // 获取最近收藏的所有草稿文档
r($docTester->getMineListTest($types[1], $browseTypes[2], $queries[1])) && p('1:title,status') && e('我的文档1,normal');    // 获取最近收藏并且文档名字中有“ 文档” 的文档
r($docTester->getMineListTest($types[2], $browseTypes[0], $queries[0])) && p('1:title,status') && e('我的文档1,normal');    // 获取我创建的所有文档
r($docTester->getMineListTest($types[2], $browseTypes[1], $queries[0])) && p('7:title,status') && e('我的草稿文档7,draft'); // 获取我创建的所有草稿文档
r($docTester->getMineListTest($types[2], $browseTypes[2], $queries[1])) && p('1:title,status') && e('我的文档1,normal');    // 获取我创建并且文档名字中有“ 文档” 的文档
r($docTester->getMineListTest($types[3], $browseTypes[0], $queries[0])) && p('1:title,status') && e('我的文档1,normal');    // 获取我编辑的所有文档
r($docTester->getMineListTest($types[3], $browseTypes[1], $queries[0])) && p('7:title,status') && e('我的草稿文档7,draft'); // 获取我编辑的所有草稿文档
r($docTester->getMineListTest($types[3], $browseTypes[2], $queries[1])) && p('1:title,status') && e('我的文档1,normal');    // 获取我编辑并且文档名字中有“ 文档” 的文档
