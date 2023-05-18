#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

function initData()
{
    zdTable('project')->config('program')->gen(40);
}
initData();

/**
title=programTao->getRootProgramList();
cid=2

 */

$tester = new programTest('admin');

$programList = $tester->program->getRootProgramList();

r(count($programList)) && p('') && e('10');
