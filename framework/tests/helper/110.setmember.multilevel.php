#!/usr/bin/env php
<?php
<<<TC
title: testing the setMember method with multi level.
TC;

/* Include the helper class. */
include '../../helper.class.php';

/* Create two objects named obj and obj2. */
$obj = new stdclass();
$obj->user = new stdclass();
$obj->user->name = 'Tom';

helper::setMember('obj', 'user.name', 'Mary');    // overide the exists key.
helper::setMember('obj', 'user.age',  20);        // add a child key to an existing key.
helper::setMember('obj', 'home.address', new stdclass());    // add a child key even the parent doesn't exist.
helper::setMember('obj', 'home.address.postcode', '10000');  // three level.

echo $obj->user->name . "\n";
echo $obj->user->age . "\n";
print_r($obj->home->address);
