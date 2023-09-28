<?php
$config->transfer->form = new stdclass();
$config->transfer->form->export = array();
$config->transfer->form->export['encode']       = array('type' => 'string',  'control' => 'select',       'required' => true,  'default' => '',  'filter'  => 'trim');
$config->transfer->form->export['exportType']   = array('type' => 'string',  'control' => 'select',       'required' => true,  'default' => '',  'filter'  => 'trim');
$config->transfer->form->export['fileName']     = array('type' => 'string',  'control' => 'text',         'required' => true,  'default' => '',  'filter'  => 'trim');
$config->transfer->form->export['fileType']     = array('type' => 'string',  'control' => 'select',       'required' => true,  'default' => '',  'filter'  => 'trim');
$config->transfer->form->export['limit']        = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '',  'filter'  => 'trim');
$config->transfer->form->export['title']        = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '',  'filter'  => 'trim');
$config->transfer->form->export['template']     = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0);
$config->transfer->form->export['part']         = array('type' => 'int',     'control' => 'checkbox',     'required' => false, 'default' => 0);
$config->transfer->form->export['public']       = array('type' => 'int',     'control' => 'checkbox',     'required' => false, 'default' => 1);
$config->transfer->form->export['exportFields'] = array('type' => 'array',   'control' => 'multi-select', 'required' => true,  'default' => array());
