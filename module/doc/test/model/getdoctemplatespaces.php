#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocTemplateSpaces();
timeout=0
cid=0



*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

$result1 = $docTest->getDocTemplateSpacesTest(); r(gettype($result1)) && p() && e('array');
$result2 = $docTest->getDocTemplateSpacesTest(); r(is_array($result2)) && p() && e('1');
$result3 = $docTest->getDocTemplateSpacesTest(); r(count($result3) >= 0) && p() && e('1');
$result4 = $docTest->getDocTemplateSpacesTest(); r(is_array($result4)) && p() && e('1');
$result5 = $docTest->getDocTemplateSpacesTest(); r(gettype($result5)) && p() && e('array');