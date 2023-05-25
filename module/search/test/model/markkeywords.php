#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

/**

title=测试 searchModel->markKeywords();
cid=1
pid=1

测试查找带骤字的bug >> 12304 27493 <span class='text-danger'>骤</span> 12305 12304 32467 26524 12305 12304 26399 26395 12305

*/

$search = new searchTest();

r($search->markKeywordsTest(1, 39588)) && p() && e("12304 27493 <span class='text-danger'>骤</span> 12305 12304 32467 26524 12305 12304 26399 26395 12305"); //测试查找带骤字的bug