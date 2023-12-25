<?php
declare(strict_types=1);

$config->zanode->form = new stdclass();

$config->zanode->form->create = array();
$config->zanode->form->create['hostType']      = array('type' => 'string', 'required' => true);
$config->zanode->form->create['parent']        = array('type' => 'int',    'required' => false);
$config->zanode->form->create['name']          = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->zanode->form->create['extranet']      = array('type' => 'string', 'required' => false, 'filter' => 'trim');
$config->zanode->form->create['image']         = array('type' => 'int',    'required' => true);
$config->zanode->form->create['cpuCores']      = array('type' => 'int',    'required' => false);
$config->zanode->form->create['memory']        = array('type' => 'float',  'required' => true);
$config->zanode->form->create['diskSize']      = array('type' => 'float',  'required' => true);
$config->zanode->form->create['osName']        = array('type' => 'string', 'required' => false);
$config->zanode->form->create['osNamePre']     = array('type' => 'string', 'required' => false);
$config->zanode->form->create['osNamePhysics'] = array('type' => 'string', 'required' => false);
$config->zanode->form->create['desc']          = array('type' => 'string', 'required' => false, 'control' => 'editor');

$config->zanode->form->edit = array();
$config->zanode->form->edit['name']     = array('type' => 'string', 'required' => false, 'filter' => 'trim');
$config->zanode->form->edit['extranet'] = array('type' => 'string', 'required' => false, 'filter' => 'trim');
$config->zanode->form->edit['memory']   = array('type' => 'float',  'required' => false);
$config->zanode->form->edit['diskSize'] = array('type' => 'float',  'required' => false);
$config->zanode->form->edit['osName']   = array('type' => 'string', 'required' => false);
$config->zanode->form->edit['desc']     = array('type' => 'string', 'required' => false, 'control' => 'editor');

$config->zanode->form->createImage = array();
$config->zanode->form->createImage['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->zanode->form->createImage['desc'] = array('type' => 'string', 'required' => false, 'control' => 'editor');

$config->zanode->form->createSnapshot = array();
$config->zanode->form->createSnapshot['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->zanode->form->createSnapshot['desc'] = array('type' => 'string', 'required' => false, 'control' => 'editor');
