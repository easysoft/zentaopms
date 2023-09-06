<?php
$config->action->form = new stdclass();
$config->action->form->comment = array();
$config->action->form->comment['comment'] = array('type' => 'string', 'required' => true, 'default' => '', 'control' => 'editor');
