<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('product.create', 'product');

$fields->field('program')
    ->wrapAfter(!$config->setCode)
    ->label(!empty(data('programID')) ? $lang->product->program . ' & ' . $lang->product->line : $lang->product->program)
    ->checkbox(array('text' => $lang->product->newLine, 'name' => 'newLine'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('program')->control('picker')->items(data('fields.program.options'))->value(data('fields.program.default'))->itemEnd()
    ->item(field('line')->control('picker')->placeholder($lang->product->line)->width('1/4')->name('line')->items(data('fields.line.options'))->value(data('fields.line.default')))
    ->item(field('lineName')->width('full')->className('hidden')->name('lineName'));
