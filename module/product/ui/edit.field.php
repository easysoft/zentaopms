<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('product.edit', 'product');

$fields->field('status')
    ->disabled($config->vision == 'or')
    ->control('picker', array('required' => true))
    ->items(data('fields.status.options'))
    ->value(data('fields.status.default'));

if(empty($config->setCode))
{
    $fields->field('type')->width('1/4');
    $fields->field('status')->width('w-1/4');
    $fields->remove('code');
}
