<?php
namespace zin;
global $lang;

$fields = defineFieldList('task.close');

/* Set module field. */
$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->rows('5')
    ->control('editor');