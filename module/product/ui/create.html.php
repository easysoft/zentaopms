<?php
common::sortFeatureMenu();

$form = form();
$form->buildForm($fields);

$content = block();
$content->from = $form;

$page = page('create');
$page->right->content = $content;
$page->x('create');
