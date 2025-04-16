<?php
$config->deliverable->form = new stdclass();
$config->deliverable->form->create = array();
$config->deliverable->form->create['name']   = array('type' => 'string', 'required' => true);
$config->deliverable->form->create['module'] = array('type' => 'string', 'required' => true);
$config->deliverable->form->create['method'] = array('type' => 'string', 'required' => true);
$config->deliverable->form->create['model']  = array('type' => 'string', 'required' => true);
$config->deliverable->form->create['desc']   = array('type' => 'text',   'required' => false, 'control' => 'editor');