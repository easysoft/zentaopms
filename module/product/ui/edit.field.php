<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('product.edit', 'product');

$fields->field('status')
    ->control('picker', array('required' => true))
    ->items(data('fields.status.options'))
    ->value(data('fields.status.default'));

if(empty($config->setCode))
{
    $fields->field('type')->className('w-1/4');
    $fields->field('status')->className('w-1/4');
}
