<?php
js::set('programID', $product->program);

$form = form();
$form->buildForm($fields);
$form->buildFormAction();

$content = block();
$content->from = $form;

$page = page('create');
$page->right->content = $content;
$page->x();
