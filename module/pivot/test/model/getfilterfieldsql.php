#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getFilterFieldSQL().
timeout=0
cid=17386

- 测试mysql下的input。 @tt.`name`
- 测试duckdb下的input。 @cast(tt.`name` as varchar)
- 测试mysql下的select。 @tt.`name`
- 测试duckdb下的select。 @tt.`name`
- 测试测试project字段。 @tt.`project`

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot   = new pivotTest();
$filters = array
(
    array('type' => 'input'),
    array('type' => 'select')
);

r($pivot->getFilterFieldSQL($filters[0], 'name', 'mysql'))    && p() && e('tt.`name`');                  // 测试mysql下的input。
r($pivot->getFilterFieldSQL($filters[0], 'name', 'duckdb'))   && p() && e('cast(tt.`name` as varchar)'); // 测试duckdb下的input。
r($pivot->getFilterFieldSQL($filters[1], 'name', 'mysql'))    && p() && e('tt.`name`');                  // 测试mysql下的select。
r($pivot->getFilterFieldSQL($filters[1], 'name', 'duckdb'))   && p() && e('tt.`name`');                  // 测试duckdb下的select。
r($pivot->getFilterFieldSQL($filters[0], 'project', 'mysql')) && p() && e('tt.`project`');               // 测试测试project字段。