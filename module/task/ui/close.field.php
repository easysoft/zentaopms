<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('task.close');

/* Set module field. */
$fields->field('comment')
    ->label($lang->comment)
    ->rows('5')
    ->control('editor');