<?php
$config->action->form = new stdclass();
$config->action->form->comment = array();
$config->action->form->comment['actioncomment'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->action->form->editComment = array();
$config->action->form->editComment['lastComment'] = array('type' => 'string', 'required' => true,  'default' => '', 'control' => 'editor');
$config->action->form->editComment['uid']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->action->form->editComment['deleteFiles'] = array('type' => 'array',  'required' => false, 'default' => array());
$config->action->form->editComment['renameFiles'] = array('type' => 'array',  'required' => false, 'default' => array());
