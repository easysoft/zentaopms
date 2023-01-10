<?php
common::sortFeatureMenu();

$form = form();
$form->buildForm($fields, "<form class='main-form form-ajax'>");
$form->buildFormAction();

$content = block();
$content->from = $form;

$page = page('create');
$page->right->content = $content;
$page->x();
