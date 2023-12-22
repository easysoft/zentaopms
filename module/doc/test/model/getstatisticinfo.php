#!/usr/bin/env php
<?php

/**

title=测试 docModel->getStatisticInfo();
cid=1

- 获取登录用户为admin时，文档总数、今日编辑文档数、用户编辑过的文档数、用户创建的文档数
 - 属性totalDocs @50
 - 属性todayEditedDocs @0
 - 属性myEditedDocs @10
 - 属性myDocs @50
- 获取登录用户为admin时，用户浏览跟收藏的文档数
 - 第myDoc条的docViews属性 @0
 - 第myDoc条的docCollects属性 @0
- 获取登录用户为user1时，文档总数、今日编辑文档数、用户编辑过的文档数、用户创建的文档数
 - 属性totalDocs @50
 - 属性todayEditedDocs @0
 - 属性myEditedDocs @10
 - 属性myDocs @0
- 获取登录用户为user1时，用户浏览跟收藏的文档数
 - 第myDoc条的docViews属性 @``
 - 第myDoc条的docCollects属性 @``

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('action')->config('action')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);

$docTester = new docTest();
$adminInfo = $docTester->getStatisticInfoTest('admin');
$user1Info = $docTester->getStatisticInfoTest('user1');

/* Admin statistic information. */
r($adminInfo) && p('totalDocs,todayEditedDocs,myEditedDocs,myDocs') && e('50,0,10,50'); // 获取登录用户为admin时，文档总数、今日编辑文档数、用户编辑过的文档数、用户创建的文档数
r($adminInfo) && p('myDoc:docViews,docCollects')                    && e('0,0');        // 获取登录用户为admin时，用户浏览跟收藏的文档数

/* User1 statistic information.*/
r($user1Info) && p('totalDocs,todayEditedDocs,myEditedDocs,myDocs') && e('50,0,10,0'); // 获取登录用户为user1时，文档总数、今日编辑文档数、用户编辑过的文档数、用户创建的文档数
r($user1Info) && p('myDoc:docViews,docCollects')                    && e('``,``');   // 获取登录用户为user1时，用户浏览跟收藏的文档数
