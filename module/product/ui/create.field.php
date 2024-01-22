<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('product.create', 'product');

$fields->field('QD')->foldable();
$fields->field('RD')->foldable();
$fields->field('acl')->foldable();

if($config->systemMode != 'light')
{
    $fields->field('line')
        ->control('inputGroup')
        ->items(false)
        ->item(field('line')->control('picker')->placeholder($lang->product->line)->name('line')->items(data('fields.line.options'))->value(data('fields.line.default')))
        ->item(field('lineName')->control('input')->className('hidden')->name('lineName'));

    if(hasPriv('product', 'manageLine')) $fields->field('line')->checkbox(array('text' => $lang->product->newLine, 'name' => 'newLine'));
}

if(!empty($config->setCode))
{
    $fields->field('code')->width('1/4');
    $fields->field('type')->width('1/4');
}
