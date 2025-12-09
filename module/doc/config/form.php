<?php
$now = helper::now();

global $app, $config;

$config->doc->form = new stdclass();

$config->doc->form->createspace['name']      = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->createspace['parent']    = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createspace['type']      = array('type' => 'string',   'required' => false, 'default' => 'custom');
$config->doc->form->createspace['acl']       = array('type' => 'string',   'required' => false, 'default' => 'open');
$config->doc->form->createspace['vision']    = array('type' => 'string',   'required' => false, 'default' => $config->vision);
$config->doc->form->createspace['addedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->doc->form->createlib['name']      = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->createlib['spaceName'] = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->doc->form->createlib['parent']    = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['baseUrl']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['acl']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['type']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['product']   = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['project']   = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['execution'] = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['groups']    = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->createlib['users']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->createlib['vision']    = array('type' => 'string',   'required' => false, 'default' => $config->vision);
$config->doc->form->createlib['addedBy']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['addedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->doc->form->createlib['orderBy']   = array('type' => 'string',   'required' => false, 'default' => 'id_asc');

$config->doc->form->editlib['space']   = array('type' => 'string',   'required' => true,  'default' => '');
$config->doc->form->editlib['name']    = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->editlib['acl']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->editlib['groups']  = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->editlib['users']   = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');

$config->doc->form->create['title']         = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->create['version']       = array('type' => 'int',      'required' => false, 'default' => 1);
$config->doc->form->create['product']       = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['project']       = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['execution']     = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['module']        = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['lib']           = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['status']        = array('type' => 'string',   'required' => false, 'default' => 'normal');
$config->doc->form->create['parent']        = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['type']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['keywords']      = array('type' => 'string',   'required' => false, 'default' => '', 'skipRequired' => true);
$config->doc->form->create['acl']           = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['vision']        = array('type' => 'string',   'required' => false, 'default' => $config->vision);
$config->doc->form->create['addedBy']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['addedDate']     = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->doc->form->create['editedBy']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['editedDate']    = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->doc->form->create['groups']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['users']         = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['readGroups']    = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['readUsers']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['mailto']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['contentType']   = array('type' => 'string',   'required' => false, 'default' => 'doc');
$config->doc->form->create['content']       = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor', 'skipRequired' => true);
$config->doc->form->create['rawContent']    = array('type' => 'string',   'required' => false, 'default' => '', 'skipRequired' => true, 'specialchars' => 'no');
$config->doc->form->create['template']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['templateType']  = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['chapterType']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['isDeliverable'] = array('type' => 'string',   'required' => false, 'default' => '0');

$config->doc->form->createtemplate = $config->doc->form->create;
$config->doc->form->createtemplate['templateDesc'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->doc->form->edit['title']         = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->edit['product']       = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['project']       = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['execution']     = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['module']        = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['lib']           = array('type' => 'int',      'required' => false, 'default' => 0, 'skipRequired' => true);
$config->doc->form->edit['status']        = array('type' => 'string',   'required' => false, 'default' => 'normal');
$config->doc->form->edit['parent']        = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['type']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['keywords']      = array('type' => 'string',   'required' => false, 'default' => '', 'skipRequired' => true);
$config->doc->form->edit['acl']           = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['contentType']   = array('type' => 'string',   'required' => false, 'default' => 'doc');
$config->doc->form->edit['content']       = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor', 'skipRequired' => true);
$config->doc->form->edit['rawContent']    = array('type' => 'string',   'required' => false, 'default' => '', 'skipRequired' => true, 'specialchars' => 'no');
$config->doc->form->edit['groups']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['users']         = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['readGroups']    = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['readUsers']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['mailto']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['editedBy']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['editedDate']    = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->doc->form->edit['fromVersion']   = array('type' => 'string',  'required' => false, 'default' => '');
$config->doc->form->edit['files']         = array('type' => 'string',  'required' => false, 'default' => '');
$config->doc->form->edit['isDeliverable'] = array('type' => 'string',   'required' => false, 'default' => '0');
$config->doc->form->edit['deleteFiles']   = array('type' => 'array',    'required' => false, 'default' => array(), 'filter' => 'join');

$config->doc->form->edittemplate = $config->doc->form->edit;
$config->doc->form->edittemplate['templateDesc'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->doc->form->edittemplate['module']       = array('type' => 'int',    'required' => true,  'default' => 0);

$config->doc->form->movelib['space']  = array('type' => 'string',   'required' => true,  'default' => '');
$config->doc->form->movelib['acl']    = array('type' => 'string',   'required' => true,  'default' => '');
$config->doc->form->movelib['groups'] = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->movelib['users']  = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');

$config->doc->form->movedoc['lib']        = array('type' => 'int',      'required' => true,  'default' => '');
$config->doc->form->movedoc['module']     = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->movedoc['acl']        = array('type' => 'string',   'required' => true,  'default' => '');
$config->doc->form->movedoc['groups']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->movedoc['users']      = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->movedoc['readGroups'] = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->movedoc['readUsers']  = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->movedoc['parent']     = array('type' => 'int',      'required' => false, 'default' => 0);

$config->doc->form->movetemplate['lib']    = array('type' => 'int',    'required' => true,  'default' => '');
$config->doc->form->movetemplate['module'] = array('type' => 'int',    'required' => true,  'default' => 0);
$config->doc->form->movetemplate['parent'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->doc->form->movetemplate['acl']    = array('type' => 'string', 'required' => true,  'default' => '');

$config->doc->form->batchmovedoc['lib']    = array('type' => 'int',    'required' => true,  'default' => 0);
$config->doc->form->batchmovedoc['module'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->doc->form->batchmovedoc['acl']    = array('type' => 'string', 'required' => true,  'default' => '');
$config->doc->form->batchmovedoc['groups'] = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->batchmovedoc['users']  = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');

$config->doc->form->addTemplateType['name']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->addTemplateType['root']   = array('type' => 'int',    'required' => true,  'default' => 1);
$config->doc->form->addTemplateType['parent'] = array('type' => 'int',    'required' => false, 'default' => 0);
