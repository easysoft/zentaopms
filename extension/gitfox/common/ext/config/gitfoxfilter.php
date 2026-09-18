<?php
/**
 * GitFox 独立版本：空间下的应用列表/编辑复用 product 模块，但 product 的方法签名里没有 spaceID，这里加上。
 */
global $filter;

$filter->product->browse->get['spaceID'] = 'int';

$filter->product->edit = isset($filter->product->edit) ? $filter->product->edit : new stdclass();
$filter->product->edit->get['spaceID'] = 'int';
