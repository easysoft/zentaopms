#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getByResolvedbyme();
cid=1
pid=1

查询产品1 29 98 不存在的产品10001 下由我解决的bug >> BUG294,BUG293,BUG292,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;87,BUG86,BUG85
查询产品1 98下由我解决的bug >> BUG294,BUG293,BUG292
查询产品29下由我解决的bug >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;87,BUG86,BUG85
查询不存在的产品10001 下由我解决的bug >> 0

*/

$productIDList = array('1,29,98,1000001', '1,98', '29', '1000001');

$bug=new bugTest();

r($bug->getByResolvedbymeTest($productIDList[0])) && p('title') && e('BUG294,BUG293,BUG292,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;87,BUG86,BUG85'); // 查询产品1 29 98 不存在的产品10001 下由我解决的bug
r($bug->getByResolvedbymeTest($productIDList[1])) && p('title') && e('BUG294,BUG293,BUG292');                                                                                     // 查询产品1 98下由我解决的bug
r($bug->getByResolvedbymeTest($productIDList[2])) && p('title') && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;87,BUG86,BUG85');                      // 查询产品29下由我解决的bug
r($bug->getByResolvedbymeTest($productIDList[3])) && p('title') && e('0');                                                                                                        // 查询不存在的产品10001 下由我解决的bug