<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('product.edit', 'product');

$fields->field('program')->wrapAfter();

$fields->field('line')
    ->hidden(empty(data('fields.program.default')))
    ->control('picker')
    ->items(data('fields.line.options'))
    ->value(data('fields.line.default'));
