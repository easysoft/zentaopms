#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->setBackSession();
timeout=0
cid=0

- type=list @1
- type=view @1
- type=list withOther=true @1
- type=edit @1
- type=list默认 @1

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->setBackSessionTest('list')) && p() && e('1');               // type=list
r($zenTest->setBackSessionTest('view')) && p() && e('1');               // type=view
r($zenTest->setBackSessionTest('list', true)) && p() && e('1');         // type=list withOther=true
r($zenTest->setBackSessionTest('edit')) && p() && e('1');               // type=edit
r($zenTest->setBackSessionTest()) && p() && e('1');                     // type=list默认