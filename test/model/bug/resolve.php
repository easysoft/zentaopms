#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->resolve();
cid=1
pid=1

解决原因为设计如此 指派人变化的bug >> resolvedBuild,,trunk;resolution,,bydesign
不填解决原因的bug >> 『解决方案』不能为空。
解决原因为重复Bug 不填bugID的bug >> 『重复ID』不能为空。
解决原因为重复Bug 填bugID的bug >> duplicateBug,0,1;status,active,resolved
解决原因为外部原因的bug >> resolvedBuild,,trunk;resolution,,external
解决原因为已解决的bug >> resolvedBuild,,trunk;resolution,,fixed
解决原因为无法重现的bug >> resolvedBuild,,trunk;resolution,,notrepro
解决原因为延期处理的bug >> resolvedBuild,,trunk;resolution,,postponed
解决原因为不予解决的bug >> resolvedBuild,,trunk;resolution,,willnotfix
解决已解决的bug >> resolvedBuild,,trunk;confirmed,0,1

*/

$bugIDList = array('1','2','3','4', '5', '6', '7','51',);

$bug1    = array('assignedTo' => 'user92', 'resolution' => 'bydesign');
$bug2    = array('assignedTo' => 'admin', 'resolution' => 'duplicate');
$bug2AB  = array('assignedTo' => 'admin', 'resolution' => 'duplicate', 'duplicateBug' => '1');
$bug3    = array('assignedTo' => 'admin', 'resolution' => 'external');
$bug4    = array('assignedTo' => 'admin', 'resolution' => 'fixed');
$bug5    = array('assignedTo' => 'admin', 'resolution' => 'notrepro');
$bug6    = array('assignedTo' => 'admin', 'resolution' => 'postponed');
$bug7    = array('assignedTo' => 'admin', 'resolution' => 'willnotfix');
$bug51   = array('assignedTo' => 'user96', 'resolution' => 'bydesign');

$bug = new bugTest();
r($bug->resolveTest($bugIDList[0],$bug1))    && p('0:field,old,new;4:field,old,new') && e('resolvedBuild,,trunk;resolution,,bydesign');   // 解决原因为设计如此 指派人变化的bug
r($bug->resolveTest($bugIDList[0]))          && p('resolution:0')                    && e('『解决方案』不能为空。');                      // 不填解决原因的bug
r($bug->resolveTest($bugIDList[1],$bug2))    && p('duplicateBug:0')                  && e('『重复ID』不能为空。');                        // 解决原因为重复Bug 不填bugID的bug
r($bug->resolveTest($bugIDList[1],$bug2AB))  && p('0:field,old,new;3:field,old,new') && e('duplicateBug,0,1;status,active,resolved');     // 解决原因为重复Bug 填bugID的bug
r($bug->resolveTest($bugIDList[2],$bug3))    && p('0:field,old,new;3:field,old,new') && e('resolvedBuild,,trunk;resolution,,external');   // 解决原因为外部原因的bug
r($bug->resolveTest($bugIDList[3],$bug4))    && p('0:field,old,new;3:field,old,new') && e('resolvedBuild,,trunk;resolution,,fixed');      // 解决原因为已解决的bug
r($bug->resolveTest($bugIDList[4],$bug5))    && p('0:field,old,new;3:field,old,new') && e('resolvedBuild,,trunk;resolution,,notrepro');   // 解决原因为无法重现的bug
r($bug->resolveTest($bugIDList[5],$bug6))    && p('0:field,old,new;3:field,old,new') && e('resolvedBuild,,trunk;resolution,,postponed');  // 解决原因为延期处理的bug
r($bug->resolveTest($bugIDList[6],$bug7))    && p('0:field,old,new;3:field,old,new') && e('resolvedBuild,,trunk;resolution,,willnotfix'); // 解决原因为不予解决的bug
r($bug->resolveTest($bugIDList[7],$bug51))   && p('0:field,old,new;3:field,old,new') && e('resolvedBuild,,trunk;confirmed,0,1');          // 解决已解决的bug
