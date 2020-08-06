<?php
$lang->entry->common  = 'Application';
$lang->entry->list    = 'Applications';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Log';
$lang->entry->setting = 'Paramétrages';

$lang->entry->browse    = 'Voir';
$lang->entry->create    = 'Ajouter Application';
$lang->entry->edit      = 'Editer';
$lang->entry->delete    = 'Supprimer';
$lang->entry->createKey = 'Regénérer la Clé Secrète';

$lang->entry->id          = 'ID';
$lang->entry->name        = 'Nom';
$lang->entry->account     = 'Compte';
$lang->entry->code        = 'Code';
$lang->entry->freePasswd  = 'Free Password Login';
$lang->entry->key         = 'Clé';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = 'Description';
$lang->entry->createdBy   = 'Créé par';
$lang->entry->createdDate = 'Créé le';
$lang->entry->editedby    = 'Edité par';
$lang->entry->editedDate  = 'Edité le';
$lang->entry->date        = 'Requesting Time';
$lang->entry->url         = 'Requesting URL';

$lang->entry->confirmDelete = 'Voulez-vous supprimer cette entrée ?';
$lang->entry->help          = 'Aide';
$lang->entry->notify        = 'Notification';

$lang->entry->helpLink   = 'https://www.zentao.pm/book/zentaomanual/scrum-tool-open-source-integrate-third-party-application-221.html';
$lang->entry->notifyLink = 'https://www.zentao.pm/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Nom';
$lang->entry->note->code    = 'Code doit être composé de lettres et de chiffres';
$lang->entry->note->ip      = "Utilisez la virgule pour séparer les IPs. IP segment est supporté, ex: 192.168.1.*";
$lang->entry->note->allIP   = 'Toutes IPs';
$lang->entry->note->account = 'Compte Application';

$lang->entry->freePasswdList[1] = 'On';
$lang->entry->freePasswdList[0] = 'Off';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Parameter code manquant.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Parameter token manquante.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Session code manquant.';
$lang->entry->errmsg['EMPTY_KEY']             = 'Clé Secrète manquante.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Token Invalide.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Echec de vérification de Session.';
$lang->entry->errmsg['IP_DENIED']             = 'IP interdite.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'Compte non lié.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'Compte Invalide.';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'Application innexistante.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token expiré';
