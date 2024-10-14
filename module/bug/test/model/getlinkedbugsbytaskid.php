#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('bug_linktask')->gen(6);
zenData('task')->loadYaml('task')->gen(5);

/**

title=bugModel->getLinkedBugsByTaskID();
cid=1
pid=1

*/

$taskIdList = array(1, 2, 3, 4, 100);

$bug=new bugTest();
