#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getBugs();
cid=1
pid=1

查询正常产品 没有分支 查看全部 没有模块的全部bug的标题拼接1 >> BUG3,BUG2,BUG1
查询正常产品 不存在的分支主干 查看全部 没有模块的全部bug的标题拼接 >> BUG3,BUG2,BUG1
查询正常产品 没有分支 查看未关闭 没有模块的全部bug的标题拼接 >> BUG3,BUG2,BUG1
查询正常产品 没有分支 查看全部 模块1821的全部bug的标题拼接 >> BUG1
查询正常产品 没有分支 查看全部 模块不存在的全部bug的标题拼接 >> BUG3,BUG2,BUG1
查询多分支产品 没有分支 查看全部 没有模块的全部bug的标题拼接59 >> BUG177,bug176,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;175
查询多分支产品 主干 查看全部 没有模块的全部bug的标题拼接 >> BUG177,bug176,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;175
查询多分支产品 分支37 查看全部 没有模块的全部bug的标题拼接 >> 0
查询多分支产品 没有分支 查看未关闭 没有模块的全部bug的标题拼接 >> BUG177,bug176,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;175
查询多分支产品 没有分支 查看全部 模块2053的全部bug的标题拼接 >> 0
查询不存在的产品的全部bug的标题拼接 >> 0

*/

$productIDList  = array('1', '59', '1000001');
$branchList     = array('0', 'trunk', '37', '1000001');
$browseTypeList = array('all', 'unclosed');
$moduleIDList   = array('0', '1821', '2053', '1000001');

$bug=new bugTest();

r($bug->getBugsTest($productIDList[0], $branchList[0], $browseTypeList[0], $moduleIDList[0])) && p('title') && e('BUG3,BUG2,BUG1'); // 查询正常产品 没有分支 查看全部 没有模块的全部bug的标题拼接1
r($bug->getBugsTest($productIDList[0], $branchList[1], $browseTypeList[0], $moduleIDList[0])) && p('title') && e('BUG3,BUG2,BUG1'); // 查询正常产品 不存在的分支主干 查看全部 没有模块的全部bug的标题拼接
r($bug->getBugsTest($productIDList[0], $branchList[0], $browseTypeList[1], $moduleIDList[0])) && p('title') && e('BUG3,BUG2,BUG1'); // 查询正常产品 没有分支 查看未关闭 没有模块的全部bug的标题拼接
r($bug->getBugsTest($productIDList[0], $branchList[0], $browseTypeList[0], $moduleIDList[1])) && p('title') && e('BUG1'); // 查询正常产品 没有分支 查看全部 模块1821的全部bug的标题拼接
r($bug->getBugsTest($productIDList[0], $branchList[0], $browseTypeList[0], $moduleIDList[3])) && p('title') && e('BUG3,BUG2,BUG1'); // 查询正常产品 没有分支 查看全部 模块不存在的全部bug的标题拼接
r($bug->getBugsTest($productIDList[1], $branchList[0], $browseTypeList[0], $moduleIDList[0])) && p('title') && e('BUG177,bug176,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;175'); // 查询多分支产品 没有分支 查看全部 没有模块的全部bug的标题拼接59
r($bug->getBugsTest($productIDList[1], $branchList[1], $browseTypeList[0], $moduleIDList[0])) && p('title') && e('BUG177,bug176,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;175'); // 查询多分支产品 主干 查看全部 没有模块的全部bug的标题拼接
r($bug->getBugsTest($productIDList[1], $branchList[2], $browseTypeList[0], $moduleIDList[0])) && p('title') && e('0'); // 查询多分支产品 分支37 查看全部 没有模块的全部bug的标题拼接
r($bug->getBugsTest($productIDList[1], $branchList[0], $browseTypeList[1], $moduleIDList[0])) && p('title') && e('BUG177,bug176,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;175'); // 查询多分支产品 没有分支 查看未关闭 没有模块的全部bug的标题拼接
r($bug->getBugsTest($productIDList[1], $branchList[0], $browseTypeList[0], $moduleIDList[2])) && p('title') && e('0'); // 查询多分支产品 没有分支 查看全部 模块2053的全部bug的标题拼接
r($bug->getBugsTest($productIDList[2], $branchList[0], $browseTypeList[0], $moduleIDList[0])) && p('title') && e('0'); // 查询不存在的产品的全部bug的标题拼接