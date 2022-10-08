<?php
$config->test = new stdclass;
$config->test->dbPrefix = "db_"; //复制数据库的数据库前缀，便于区分复制数据库
$config->test->rawDB    = 'zentaounittest';    //要复制的数据库，也就是ztest init初始化的数据库,例子:zentaounittest
$config->test->dbNum    = 10;    //复制数据库的数量，可以根据自己的需要自行调节

$config->test->base     = ''; //api测试基准路径,例子:http://liyang.oop.cc:8072/max/api.php/v1
$config->test->account  = ''; //api测试登录账户,例子:admin
$config->test->password = ''; //api测试账户密码,例子:Ly123456
