#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

/**

title=测试 todoModel->close();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('todo');

zdTable('todo')->config('close')->gen(1);

r($tester->todo->getByID(1)) && p('status') && e('wait');
r($tester->todo->close(1))   && p()         && e(1);
r($tester->todo->getByID(1)) && p('status') && e('closed');
