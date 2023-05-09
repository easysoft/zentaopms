<?php
global $lang;
$config->caselib->form = new stdclass();
$config->caselib->form->create = array();
$config->caselib->form->create['name'] = array('type' => 'string',  'control' => 'text',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->caselib->form->create['desc'] = array('type' => 'string',  'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');
