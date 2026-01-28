#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getColumnSummary();
timeout=0
cid=17376

- 执行pivotTest模块的getColumnSummaryTest方法，参数是$data[0], 'total' 第col1条的value属性 @25
- 执行pivotTest模块的getColumnSummaryTest方法，参数是$data[1], 'summary' 第col1条的value属性 @text
- 执行pivotTest模块的getColumnSummaryTest方法，参数是$data[2], 'empty' 第empty条的value属性 @$total$
- 执行pivotTest模块的getColumnSummaryTest方法，参数是$data[3], 'grouped' 第col1条的value属性 @30
- 执行pivotTest模块的getColumnSummaryTest方法，参数是$data[4], 'decimal' 第col1条的value属性 @25.89

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$data = [
    [
        ['col1' => ['value' => 10, 'isGroup' => 0]],
        ['col1' => ['value' => 15, 'isGroup' => 0]]
    ],
    [['col1' => ['value' => 'text', 'isGroup' => 0]]],
    [],
    [['col1' => ['value' => 30, 'isGroup' => 1]]],
    [
        ['col1' => ['value' => 10.555, 'isGroup' => 0]],
        ['col1' => ['value' => 15.333, 'isGroup' => 0]]
    ]
];

$pivotTest = new pivotModelTest();

r($pivotTest->getColumnSummaryTest($data[0], 'total'))   && p('col1:value')  && e('25');
r($pivotTest->getColumnSummaryTest($data[1], 'summary')) && p('col1:value')  && e('text');
r($pivotTest->getColumnSummaryTest($data[2], 'empty'))   && p('empty:value') && e('$total$');
r($pivotTest->getColumnSummaryTest($data[3], 'grouped')) && p('col1:value')  && e('30');
r($pivotTest->getColumnSummaryTest($data[4], 'decimal')) && p('col1:value')  && e('25.89');