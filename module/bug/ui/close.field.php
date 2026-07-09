<?php
namespace zin;

global $lang;

$fields = defineFieldList('bug.close');

$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
