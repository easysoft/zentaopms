#!/usr/bin/env php
<?php
/**
 * 测试array2Object方法。
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      chunsheng.wang <chunsheng@cnezsoft.com>
 * @package     Testing
 * @version     $Id: case001.php 133 2010-09-11 07:22:48Z wwccss $
 * @link        http://www.zentao.net
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';
$array['a1'] = '1';
$array['a2'] = '2';
$array['a3']['b1'] = '3';
$array['a3']['b2'] = '4';
$array['a4']['b3']['c1'] = '5';
$array['a5'] = '6';
$array['a6']['b4'] = '7';
$array['a7']['b5']['c2'] = '8';

$config = new stdClass();
eval (helper::array2object($array, 'config'));
print_r($config);
echo $config->a3->b1;
echo "\n";
helper::setMember('config', 'a3.b1', 10);
echo $config->a3->b1;
echo "\n";
?>
