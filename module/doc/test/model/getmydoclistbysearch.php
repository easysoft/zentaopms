#!/usr/bin/env php
<?php

/**

title=测试 docModel->getMyDocListBySearch();
cid=1

- 搜索queryID=0、没有可查看的任何文档/文档库时，按照id降序排列的文档信息 @0
- 搜索queryID=0、没有可查看的任何文档/文档库时，按照id升序排列的文档信息 @0
- 搜索queryID=0、没有可查看的任何文档/文档库时，按照title升序排列的文档信息 @0
- 搜索queryID=0、没有可查看的任何文档/文档库时，按照title降序排列的文档信息 @0
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、没有可查看的文档、有可查看文档库但数据不存在时，按照id降序排列的文档信息 @0
- 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息 @0
- 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息 @0
- 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息 @0
- 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息 @0
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照id降序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照id升序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照title升序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照title降序排列的文档信息
 - 第33条的lib属性 @25
 - 第33条的title属性 @执行文档33
- 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息 @0
- 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息 @0
- 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息 @0
- 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息 @0
- 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息 @0
- 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息 @0
- 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息 @0
- 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息 @0
- 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=1、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息 @0
- 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息 @0
- 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息 @0
- 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息 @0
- 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息 @0
- 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息 @0
- 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息 @0
- 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息 @0
- 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '%文档%' ) AND ( 1 ))`");
$userqueryTable->gen(1);

zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$queries = array(0, 1, 2);
$sorts   = array('id_desc', 'id_asc', 'title_asc', 'title_desc');

$hasPrivDocIdList[0] = array();
$hasPrivDocIdList[1] = range(1, 30);
$hasPrivDocIdList[2] = range(41, 60);
$hasPrivDocIdList[3] = range(51, 60);

$allLibIDList[0] = array();
$allLibIDList[1] = range(1, 20);
$allLibIDList[2] = range(21, 40);
$allLibIDList[3] = range(31, 40);

$docTester = new docTest();
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[0])) && p()               && e('0');             // 搜索queryID=0、没有可查看的任何文档/文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[1])) && p()               && e('0');             // 搜索queryID=0、没有可查看的任何文档/文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[2])) && p()               && e('0');             // 搜索queryID=0、没有可查看的任何文档/文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[3])) && p()               && e('0');             // 搜索queryID=0、没有可查看的任何文档/文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[0])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[1])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[2])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[3])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[2], $sorts[0])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[2], $sorts[1])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[2], $sorts[2])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[2], $sorts[3])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[0], $allLibIDList[3], $sorts[0])) && p()               && e('0');             // 搜索queryID=0、没有可查看的文档、有可查看文档库但数据不存在时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[0])) && p()               && e('0');             // 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[1])) && p()               && e('0');             // 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[2])) && p()               && e('0');             // 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[3])) && p()               && e('0');             // 搜索queryID=0、有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[0])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[1])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[2])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[3])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[2], $sorts[0])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[2], $sorts[1])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[2], $sorts[2])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[1], $allLibIDList[2], $sorts[3])) && p('41:lib,title') && e('26,产品文档41'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[2], $allLibIDList[2], $sorts[0])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[2], $allLibIDList[2], $sorts[1])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[2], $allLibIDList[2], $sorts[2])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[2], $allLibIDList[2], $sorts[3])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[3], $allLibIDList[2], $sorts[0])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[3], $allLibIDList[2], $sorts[1])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[3], $allLibIDList[2], $sorts[2])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[0], $hasPrivDocIdList[3], $allLibIDList[2], $sorts[3])) && p('33:lib,title') && e('25,执行文档33'); // 搜索queryID=0、有可查看的文档但数据不存在、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[0])) && p()               && e('0');             // 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[1])) && p()               && e('0');             // 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[2])) && p()               && e('0');             // 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[3])) && p()               && e('0');             // 搜索queryID=1、没有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[0])) && p()               && e('0');             // 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[1])) && p()               && e('0');             // 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[2])) && p()               && e('0');             // 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[3])) && p()               && e('0');             // 搜索queryID=1、有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[0])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[1])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[2])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[3])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[0])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[1])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[2])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[1], $hasPrivDocIdList[1], $allLibIDList[1], $sorts[3])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=1、有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[0])) && p()               && e('0');             // 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[1])) && p()               && e('0');             // 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[2])) && p()               && e('0');             // 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[0], $sorts[3])) && p()               && e('0');             // 搜索queryID=2、没有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[0])) && p()               && e('0');             // 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[1])) && p()               && e('0');             // 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[2])) && p()               && e('0');             // 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[1], $allLibIDList[0], $sorts[3])) && p()               && e('0');             // 搜索queryID=2、有可查看的文档、没有可查看文档库时，按照title降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[0])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照id降序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[1])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照id升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[2])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照title升序排列的文档信息
r($docTester->getMyDocListBySearchTest($queries[2], $hasPrivDocIdList[0], $allLibIDList[1], $sorts[3])) && p('1:lib,title')  && e('11,我的文档1');  // 搜索queryID=2、没有可查看的文档、有可查看文档库时，按照title降序排列的文档信息
