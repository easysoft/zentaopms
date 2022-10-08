#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getPreAndNextDoc();
cid=1
pid=1



*/

$doc = new docTest();

$docIDList = array(0, 1, 2, 1000);
$libIDList = array(0, 1, 2, 1000);

r($doc->getPreAndNextDocTest($docIDList[0], $libIDList[0])) && p('pre,next') && e(','); // 查询空数据
r($doc->getPreAndNextDocTest($docIDList[0], $libIDList[1])) && p('pre,next') && e(','); // 查询文档ID为空的数据
r($doc->getPreAndNextDocTest($docIDList[0], $libIDList[2])) && p('pre,next') && e(','); // 查询文档ID为空的数据
r($doc->getPreAndNextDocTest($docIDList[0], $libIDList[3])) && p('pre,next') && e(','); // 查询文档ID为空的数据
r($doc->getPreAndNextDocTest($docIDList[1], $libIDList[0])) && p('pre,next') && e(','); // 查询文档库ID为空的数据
r($doc->getPreAndNextDocTest($docIDList[2], $libIDList[0])) && p('pre,next') && e(','); // 查询文档库ID为空的数据
r($doc->getPreAndNextDocTest($docIDList[3], $libIDList[0])) && p('pre,next') && e(','); // 查询文档库ID为空的数据
r($doc->getPreAndNextDocTest($docIDList[1], $libIDList[1])) && p('pre,next') && e(','); // 查询文档ID为1并且文档库ID为1的数据
r($doc->getPreAndNextDocTest($docIDList[2], $libIDList[1])) && p('pre,next') && e(','); // 查询文档ID为2并且文档库ID为1的数据
r($doc->getPreAndNextDocTest($docIDList[3], $libIDList[1])) && p('pre,next') && e(','); // 查询文档ID不存在并且文档库ID为1的数据
r($doc->getPreAndNextDocTest($docIDList[1], $libIDList[2])) && p('pre,next') && e(','); // 查询文档ID为1并且文档库ID为2的数据
r($doc->getPreAndNextDocTest($docIDList[2], $libIDList[2])) && p('pre,next') && e(','); // 查询文档ID为2并且文档库ID为2的数据
r($doc->getPreAndNextDocTest($docIDList[3], $libIDList[2])) && p('pre,next') && e(','); // 查询文档ID不存在并且文档库ID为2的数据
r($doc->getPreAndNextDocTest($docIDList[1], $libIDList[3])) && p('pre,next') && e(','); // 查询文档ID为1并且文档库ID不存在的数据
r($doc->getPreAndNextDocTest($docIDList[2], $libIDList[3])) && p('pre,next') && e(','); // 查询文档ID为2并且文档库ID不存在的数据
r($doc->getPreAndNextDocTest($docIDList[3], $libIDList[3])) && p('pre,next') && e(','); // 查询文档ID不存在并且文档库ID不存在的数据