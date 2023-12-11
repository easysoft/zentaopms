#!/usr/bin/env php
<?php

/**

title=测试 docModel->buildSearchForm();
cid=1

- 构造我的空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造我的空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造项目空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造项目空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造执行空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造执行空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造产品空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造产品空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造自定义空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号
- 构造自定义空间搜索表单
 - 第fields条的title属性 @文档标题
 - 第fields条的id属性 @ID
 - 第fields条的lib属性 @所属库
 - 第fields条的status属性 @文档状态
 - 第fields条的addedDate属性 @创建日期
 - 第fields条的editedDate属性 @修改日期
 - 第fields条的keywords属性 @关键字
 - 第fields条的version属性 @版本号

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '%文档%' ) AND ( 1 ))`");
$userqueryTable->gen(1);

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('user')->gen(5);
su('admin');

$libIdList    = array(11, 16, 20, 26, 6);
$libs         = range(1, 5);
$queryIdList  = array(0, 1);
$typeList     = array('mine', 'project', 'execution', 'product', 'custom');

$docTester = new docTest();
r($docTester->buildSearchFormTest($libIdList[0], $libs, $queryIdList[0], $typeList[0])) && p('fields:title,id,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,文档状态,创建日期,修改日期,关键字,版本号'); // 构造我的空间搜索表单
r($docTester->buildSearchFormTest($libIdList[0], $libs, $queryIdList[1], $typeList[0])) && p('fields:title,id,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,文档状态,创建日期,修改日期,关键字,版本号'); // 构造我的空间搜索表单
r($docTester->buildSearchFormTest($libIdList[1], $libs, $queryIdList[0], $typeList[1])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造项目空间搜索表单
r($docTester->buildSearchFormTest($libIdList[1], $libs, $queryIdList[1], $typeList[1])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造项目空间搜索表单
r($docTester->buildSearchFormTest($libIdList[2], $libs, $queryIdList[0], $typeList[2])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造执行空间搜索表单
r($docTester->buildSearchFormTest($libIdList[2], $libs, $queryIdList[1], $typeList[2])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造执行空间搜索表单
r($docTester->buildSearchFormTest($libIdList[3], $libs, $queryIdList[0], $typeList[3])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造产品空间搜索表单
r($docTester->buildSearchFormTest($libIdList[3], $libs, $queryIdList[1], $typeList[3])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造产品空间搜索表单
r($docTester->buildSearchFormTest($libIdList[4], $libs, $queryIdList[0], $typeList[4])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造自定义空间搜索表单
r($docTester->buildSearchFormTest($libIdList[4], $libs, $queryIdList[1], $typeList[4])) && p('fields:title,id,lib,status,addedDate,editedDate,keywords,version') && e('文档标题,ID,所属库,文档状态,创建日期,修改日期,关键字,版本号'); // 构造自定义空间搜索表单
