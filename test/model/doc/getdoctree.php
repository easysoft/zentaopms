#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getDocTree();
cid=1
pid=1

获取libID 1 的文档树 >> 目录1:文档标题1,子目录1,子目录2;
获取libID 3 的文档树 >> 目录3:子目录5,子目录6;
获取libID 8 的文档树 >> 目录8:子目录15,子目录16;
获取libID 20 的文档树 >> 目录20:子目录39,子目录40;
获取libID 101 的文档树 >> 0
获取libID 191 的文档树 >> 0
获取libID 821 的文档树 >> 0
获取libID 901 的文档树 >> 0

*/

$libID = array(1, 3, 8, 20, 101, 191, 821, 901);

$doc = new docTest();

r($doc->getDocTreeTest($libID[0])) && p() && e('目录1:文档标题1,子目录1,子目录2;'); // 获取libID 1 的文档树
r($doc->getDocTreeTest($libID[1])) && p() && e('目录3:子目录5,子目录6;');           // 获取libID 3 的文档树
r($doc->getDocTreeTest($libID[2])) && p() && e('目录8:子目录15,子目录16;');         // 获取libID 8 的文档树
r($doc->getDocTreeTest($libID[3])) && p() && e('目录20:子目录39,子目录40;');        // 获取libID 20 的文档树
r($doc->getDocTreeTest($libID[4])) && p() && e('0');                                // 获取libID 101 的文档树
r($doc->getDocTreeTest($libID[5])) && p() && e('0');                                // 获取libID 191 的文档树
r($doc->getDocTreeTest($libID[6])) && p() && e('0');                                // 获取libID 821 的文档树
r($doc->getDocTreeTest($libID[7])) && p() && e('0');                                // 获取libID 901 的文档树