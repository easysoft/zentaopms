#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getSystemSearchQuery();
timeout=0
cid=0

- queryID=0 @
- queryID=1 @
- queryID=-1 @
- 大queryID @
- queryID=0重复 @

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->getSystemSearchQueryTest(0)) && p() && e('');    // queryID=0
r($zenTest->getSystemSearchQueryTest(1)) && p() && e('');    // queryID=1
r($zenTest->getSystemSearchQueryTest(-1)) && p() && e('');   // queryID=-1
r($zenTest->getSystemSearchQueryTest(999)) && p() && e('');  // 大queryID
r($zenTest->getSystemSearchQueryTest(0)) && p() && e('');    // queryID=0重复