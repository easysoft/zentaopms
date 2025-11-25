#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::replaceTableNames();
timeout=0
cid=17429

- 执行pivot模块的replaceTableNamesTest方法，参数是''  @0
- 执行pivot模块的replaceTableNamesTest方法，参数是'select * from TABLE_USER'  @select * from zt_user
- 执行pivot模块的replaceTableNamesTest方法，参数是'select u.*, g.name from TABLE_USER u left join TABLE_GROUP g on u.group = g.id'  @select u.*, g.name from zt_user u left join zt_group g on u.group = g.id

- 执行pivot模块的replaceTableNamesTest方法，参数是'select * from TABLE_UNDEFINED where id = 1'  @select * from TABLE_UNDEFINED where id = 1
- 执行pivot模块的replaceTableNamesTest方法，参数是"select * from TABLE_USER where status = '!'"  @select * from zt_user where status !=''
- 执行pivot模块的replaceTableNamesTest方法，参数是"select u.* from TABLE_USER u where u.status = '!' and u.role = 'admin'"  @select u.* from zt_user u where u.status !='' and u.role = 'admin'
- 执行pivot模块的replaceTableNamesTest方法，参数是'select id, name from users where active = 1'  @select id, name from users where active = 1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

r($pivot->replaceTableNamesTest('')) && p('') && e('0');
r($pivot->replaceTableNamesTest('select * from TABLE_USER')) && p('') && e('select * from zt_user');
r($pivot->replaceTableNamesTest('select u.*, g.name from TABLE_USER u left join TABLE_GROUP g on u.group = g.id')) && p('') && e('select u.*, g.name from zt_user u left join zt_group g on u.group = g.id');
r($pivot->replaceTableNamesTest('select * from TABLE_UNDEFINED where id = 1')) && p('') && e('select * from TABLE_UNDEFINED where id = 1');
r($pivot->replaceTableNamesTest("select * from TABLE_USER where status = '!'")) && p('') && e("select * from zt_user where status !=''");
r($pivot->replaceTableNamesTest("select u.* from TABLE_USER u where u.status = '!' and u.role = 'admin'")) && p('') && e("select u.* from zt_user u where u.status !='' and u.role = 'admin'");
r($pivot->replaceTableNamesTest('select id, name from users where active = 1')) && p('') && e('select id, name from users where active = 1');