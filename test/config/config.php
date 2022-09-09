<?php
$config->test = new stdclass;
$config->test->dbPrefix = "db_";         //复制数据库的数据库前缀，便于区分复制数据库
$config->test->rawDB    = 'zentao16pms'; //要复制的数据库，也就是ztest init初始化的数据库
$config->test->dbNum    = 10;            //复制数据库的数量，可以根据自己的需要自行调节

$config->test->base     = 'http://liyang.oop.cc:8072/max/api.php/v1'; //api测试基准路径
$config->test->account  = 'admin';                                    //api测试登录账户
$config->test->password = 'Ly123456';                                 //api测试账户密码
