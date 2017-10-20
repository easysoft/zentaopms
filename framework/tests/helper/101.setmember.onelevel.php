#!/usr/bin/env php
<?php
<<<TC
title: testing the setMember method.
TC;

/* Include the helper class. */
include '../../helper.class.php';

/* Create two objects named obj and obj2. */
$obj = new stdclass();
$obj->key1 = 'value1';

$obj2 = new stdclass();
$obj2->key1 = 'value2.1';

helper::setMember('obj', 'key1', 'value1.1');     // overide the exists key.
helper::setMember('obj', 'key2', 'value2');       // add a new key.
helper::setMember('obj', 'key3', 3);              // set an int value.
helper::setMember('obj', 'key4', array(1, 2, 3)); // set an array value.
helper::setMember('obj', 'key5', $obj2);          // set an object value.

echo $obj->key1 . "\n";
echo $obj->key2 . "\n";
echo $obj->key3 . "\n";
print_r($obj->key4);
print_r($obj->key5);
