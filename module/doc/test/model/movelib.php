#!/usr/bin/env php
<?php

/**

title=测试 docModel->moveLib();
timeout=0
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->loadYaml('doclib')->gen(30);
