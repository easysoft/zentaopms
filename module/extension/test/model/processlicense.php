#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->processLicense();
timeout=0
cid=1

- 处理并返回授权协议内容为APACHE的协议内容。 @Apache License
- 处理并返回授权协议内容为BSD的协议内容。 @Copyright <year> <copyright holder>. All rights re
- 处理并返回授权协议内容为LGPL的协议内容。 @GNU LESSER GENERAL PUBLIC LICEN
- 处理并返回授权协议内容为GPL的协议内容。 @GNU GENERAL PUBLIC LICENSE
- 处理并返回授权协议内容为MIT的协议内容。 @Copyright (C) <year> by <copyright holde
- 处理并返回授权协议内容为ACCESS的协议内容。 @ACCESS
- 处理并返回授权协议内容为禅道的授权协议的协议内容。 @禅道的授权协议

*/

global $tester;
$tester->loadModel('extension');

r(trim(substr($tester->extension->processLicense('APACHE'), 0, 50))) && p() && e('Apache License');                                     // 处理并返回授权协议内容为APACHE的协议内容。
r(trim(substr($tester->extension->processLicense('BSD'),    0, 50))) && p() && e('Copyright <year> <copyright holder>. All rights re'); // 处理并返回授权协议内容为BSD的协议内容。
r(trim(substr($tester->extension->processLicense('LGPL'),   0, 50))) && p() && e('GNU LESSER GENERAL PUBLIC LICEN');                    // 处理并返回授权协议内容为LGPL的协议内容。
r(trim(substr($tester->extension->processLicense('GPL'),    0, 50))) && p() && e('GNU GENERAL PUBLIC LICENSE');                         // 处理并返回授权协议内容为GPL的协议内容。
r(trim(substr($tester->extension->processLicense('MIT'),    0, 40))) && p() && e('Copyright (C) <year> by <copyright holde');           // 处理并返回授权协议内容为MIT的协议内容。
r(trim(substr($tester->extension->processLicense('ACCESS'), 0, 50))) && p() && e('ACCESS');                                             // 处理并返回授权协议内容为ACCESS的协议内容。
r($tester->extension->processLicense('禅道的授权协议'))              && p() && e('禅道的授权协议');                                     // 处理并返回授权协议内容为禅道的授权协议的协议内容。
