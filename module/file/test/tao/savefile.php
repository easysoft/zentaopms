#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

function initData()
{
    zenData('file')->gen(5);
}
initData();

/**
title=fileTao->saveFile();
cid=1

 */

global $app;
$tester = new fileTest('admin');

$file = array
(
    'pathname'   => '',
    'title'      => 'File 6',
    'objectType' => 'bug',
    'objectID'   => 100,
    'addedBy'    => '',
    'addedDate'  => helper::now(),
    'realpath'   => 'realpath',
    'extension'  => 'text',
    'extra'      => 'extra',
);

$lastInsertID = $tester->objectModel->saveFile($file, 'realpath');
r($lastInsertID) && p('') && e('6');

$fileList = $tester->objectModel->getByIdList(array($lastInsertID));
r($fileList[$lastInsertID]) && p('objectType,objectID') && e('bug,100');
