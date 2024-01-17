<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('product.edit', 'product');

$fields->field('program')->wrapAfter();
