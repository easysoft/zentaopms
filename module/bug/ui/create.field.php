<?php
namespace zin;

global $lang;

$fields = defineFieldList('bug.create', 'bug');

$fields->field('module')->width('1/4');

$fields->field('openedBuild')->width('1/4');
