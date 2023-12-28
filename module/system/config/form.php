<?php
$config->system->form = new stdclass();
$config->system->form->editDomain['customDomain'] = array('type' => 'string',   'required' => true,  'default' => '');
$config->system->form->editDomain['certPem']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->system->form->editDomain['certKey']      = array('type' => 'string',   'required' => false, 'default' => '');
