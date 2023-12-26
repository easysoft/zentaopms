#!/usr/bin/env php
<?php

/**

title=测试 ciModel->transformAnsiToHtml();
timeout=0
cid=1

- 不带特殊颜色标识的内容 @normal text.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

$normalText = 'normal text.';

global $tester;
$ci = $tester->loadModel('ci');
r($ci->transformAnsiToHtml($normalText)) && p() && e('normal text.'); // 不带特殊颜色标识的内容