<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('product.create', 'product');

$fields->field('QD')->foldable();
$fields->field('code')->foldable();
$fields->field('RD')->foldable();
$fields->field('acl')->foldable();

$fields->field('line')
    ->control('inputGroup')
    ->items(false)
    ->item(field('line')->control('picker')->placeholder($lang->product->line)->name('line')->items(data('fields.line.options'))->value(data('fields.line.default')))
    ->item(field('lineName')->control('input')->className('hidden')->name('lineName'));

if(hasPriv('product', 'manageLine')) $fields->field('line')->checkbox(array('text' => $lang->product->newLine, 'name' => 'newLine'));

$fields->field('code')->className('full:w-1/4 lite:w-1/2');

if(empty($config->setCode)) $fields->remove('code');
if(!empty($config->setCode)) $fields->field('type')->className('full:w-1/4 lite:w-1/2');
