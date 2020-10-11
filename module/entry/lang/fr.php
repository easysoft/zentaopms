<?php
$lang->entry->common  = 'Application';
$lang->entry->list    = 'Applications';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Log';
$lang->entry->setting = 'Param?trages';

$lang->entry->browse    = 'Voir';
$lang->entry->create    = 'Ajouter Application';
$lang->entry->edit      = 'Editer';
$lang->entry->delete    = 'Supprimer';
$lang->entry->createKey = 'Reg?n?rer la Cl? Secr?te';

$lang->entry->id          = 'ID';
$lang->entry->name        = 'Nom';
$lang->entry->account     = 'Compte';
$lang->entry->code        = 'Code';
$lang->entry->freePasswd  = 'Password-Free Login';
$lang->entry->key         = 'Cl?';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = 'Description';
$lang->entry->createdBy   = 'Cr?? par';
$lang->entry->createdDate = 'Cr?? le';
$lang->entry->editedby    = 'Edit? par';
$lang->entry->editedDate  = 'Edit? le';
$lang->entry->date        = 'Requesting Time';
$lang->entry->url         = 'Requesting URL';

$lang->entry->confirmDelete = 'Voulez-vous supprimer cette entr?e ?';
$lang->entry->help          = 'Aide';
$lang->entry->notify        = 'Notification';

$lang->entry->helpLink   = 'https://www.zentao.pm/book/zentaomanual/scrum-tool-open-source-integrate-third-party-application-221.html';
$lang->entry->notifyLink = 'https://www.zentao.pm/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Nom';
$lang->entry->note->code    = 'Code doit ?tre compos? de lettres et de chiffres';
$lang->entry->note->ip      = "Utilisez la virgule pour s?parer les IPs. IP segment est support?, ex: 192.168.1.*";
$lang->entry->note->allIP   = 'Toutes IPs';
$lang->entry->note->account = 'Compte Application';

$lang->entry->freePasswdList[1] = 'On';
$lang->entry->freePasswdList[0] = 'Off';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Parameter code manquant.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Parameter token manquante.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Session code manquant.';
$lang->entry->errmsg['EMPTY_KEY']             = 'Cl? Secr?te manquante.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Token Invalide.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Echec de v?rification de Session.';
$lang->entry->errmsg['IP_DENIED']             = 'IP interdite.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'Compte non li?.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'Compte Invalide.';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'Application innexistante.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token expir?';
