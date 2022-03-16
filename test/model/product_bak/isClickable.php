#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productBox.class.php';
su('admin');

/**

title=测试productModel->isClickable();
cid=1
pid=1

status为normal,action为close >> true
status为close,action为close >> false
status为normal,action为start >> true
status为close,action为start >> true

*/

$adminTester = new productBox('admin');

r($adminTester->testIsClickable(2, 'close'))  && p() && e('true');  // status为normal,action为close
r($adminTester->testIsClickable(75, 'close')) && p() && e('false'); // status为close,action为close
r($adminTester->testIsClickable(2, 'start'))  && p() && e('true');  // status为normal,action为start
r($adminTester->testIsClickable(75, 'start')) && p() && e('true');  // status为close,action为start