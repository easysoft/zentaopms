#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->extractKETableCSS();
cid=1
pid=1

未过滤数据查询 >> 0
过滤后数据查询 >> .ke-table1{border:1px #000000 solid}

*/
global $tester;
$doc = $tester->loadModel('doc');
$content = 'test';
$css     = '<table class="ke-table1" style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">';

r($doc->extractKETableCSS($content)) && p() && e('0');                                   //未过滤数据查询
r($doc->extractKETableCSS($css))     && p() && e('.ke-table1{border:1px #000000 solid}');//过滤后数据查询