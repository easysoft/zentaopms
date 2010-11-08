#!/usr/bin/env php
<?php
/**
 * 测试setMember方法。
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      chunsheng.wang <chunsheng@cnezsoft.com>
 * @package     Testing
 * @version     $Id: case002.php 133 2010-09-11 07:22:48Z wwccss $
 * @link        http://www.zentao.net
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';
$config = new stdClass();

/* 测试一维属性的修改。*/
$config->user = 'wwccss';
helper::setMember('config', 'user', 'chunsheng');
echo $config->user . "\n";

/* 赋值的变量含有单双引号。*/
$config->name = 'wwccss';
helper::setMember('config', 'name', "wang'chun\"sheng");
echo $config->name . "\n";

/* 赋值的变量为一个数组。*/
$config->users = array(1,2,3);
helper::setMember('config', 'users', array('a', 'b', 'c'));
print_r($config->users);

/* 赋值的变量为一个对象。*/
$config->obj = array(1,2,3);
helper::setMember('config', 'obj', new stdClass());
print_r($config->obj);

/* 测试二维属性的修改。*/
$config->db->host = 'localhost';
$config->db->user = 'wwccss';
$config->db->param = array();
helper::setMember('config', 'db.host', "localhost");
helper::setMember('config', 'db.user', "chunsheng'.wang");
helper::setMember('config', 'db.param', array('1', '2', '3'));
echo $config->db->host . "\n";
echo $config->db->user . "\n";
print_r($config->db->param);
?>
