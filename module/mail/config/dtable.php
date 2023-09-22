<?php
global $lang, $app;
if(!isset($lang->mail->index)) $app->loadLang('mail');

$config->mail->browse = new stdclass();
$config->mail->browse->dtable = new stdclass();

$config->mail->browse->dtable->fieldList['id']['name']     = 'id';
$config->mail->browse->dtable->fieldList['id']['title']    = $lang->idAB;
$config->mail->browse->dtable->fieldList['id']['type']     = 'checkID';
$config->mail->browse->dtable->fieldList['id']['checkbox'] = true;
$config->mail->browse->dtable->fieldList['id']['sortType'] = true;
$config->mail->browse->dtable->fieldList['id']['required'] = true;
$config->mail->browse->dtable->fieldList['id']['group']    = 1;

$config->mail->browse->dtable->fieldList['toList']['name']     = 'toList';
$config->mail->browse->dtable->fieldList['toList']['title']    = $lang->mail->toList;
$config->mail->browse->dtable->fieldList['toList']['type']     = 'user';
$config->mail->browse->dtable->fieldList['toList']['sortType'] = true;
$config->mail->browse->dtable->fieldList['toList']['required'] = true;
$config->mail->browse->dtable->fieldList['toList']['group']    = 2;

$config->mail->browse->dtable->fieldList['subject']['name']     = 'subject';
$config->mail->browse->dtable->fieldList['subject']['title']    = $lang->mail->subject;
$config->mail->browse->dtable->fieldList['subject']['type']     = 'text';
$config->mail->browse->dtable->fieldList['subject']['sortType'] = true;
$config->mail->browse->dtable->fieldList['subject']['required'] = true;
$config->mail->browse->dtable->fieldList['subject']['group']    = 3;

$config->mail->browse->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->mail->browse->dtable->fieldList['createdBy']['title']    = $lang->mail->createdBy;
$config->mail->browse->dtable->fieldList['createdBy']['type']     = 'user';
$config->mail->browse->dtable->fieldList['createdBy']['sortType'] = true;
$config->mail->browse->dtable->fieldList['createdBy']['required'] = true;
$config->mail->browse->dtable->fieldList['createdBy']['group']    = 4;

$config->mail->browse->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->mail->browse->dtable->fieldList['createdDate']['title']    = $lang->mail->createdDate;
$config->mail->browse->dtable->fieldList['createdDate']['type']     = 'date';
$config->mail->browse->dtable->fieldList['createdDate']['sortType'] = true;
$config->mail->browse->dtable->fieldList['createdDate']['required'] = true;
$config->mail->browse->dtable->fieldList['createdDate']['group']    = 5;

$config->mail->browse->dtable->fieldList['sendTime']['name']     = 'sendTime';
$config->mail->browse->dtable->fieldList['sendTime']['title']    = $lang->mail->sendTime;
$config->mail->browse->dtable->fieldList['sendTime']['type']     = 'date';
$config->mail->browse->dtable->fieldList['sendTime']['sortType'] = true;
$config->mail->browse->dtable->fieldList['sendTime']['required'] = true;
$config->mail->browse->dtable->fieldList['sendTime']['group']    = 6;

$config->mail->browse->dtable->fieldList['status']['name']      = 'status';
$config->mail->browse->dtable->fieldList['status']['title']     = $lang->mail->status;
$config->mail->browse->dtable->fieldList['status']['type']      = 'status';
$config->mail->browse->dtable->fieldList['status']['statusMap'] = $lang->mail->statusList;
$config->mail->browse->dtable->fieldList['status']['sortType']  = true;
$config->mail->browse->dtable->fieldList['status']['required']  = true;
$config->mail->browse->dtable->fieldList['status']['group']     = 7;

$config->mail->browse->dtable->fieldList['failReason']['name']     = 'failReason';
$config->mail->browse->dtable->fieldList['failReason']['title']    = $lang->mail->failReason;
$config->mail->browse->dtable->fieldList['failReason']['type']     = 'text';
$config->mail->browse->dtable->fieldList['failReason']['required'] = true;
$config->mail->browse->dtable->fieldList['failReason']['group']    = 8;

$config->mail->browse->dtable->fieldList['actions']['name']     = 'actions';
$config->mail->browse->dtable->fieldList['actions']['type']     = 'actions';
$config->mail->browse->dtable->fieldList['actions']['minWidth'] = 108;

$config->mail->browse->dtable->fieldList['actions']['menu'] = array('delete', 'resend');
$config->mail->browse->dtable->fieldList['actions']['list'] = array
(
    'delete' => array(
        'icon'         => 'trash',
        'url'          => helper::createLink('mail', 'delete', 'id={id}'),
        'hint'         => $lang->delete,
        'className'    => 'ajax-submit',
        'data-confirm' => $lang->mail->confirmDelete
    ),
    'resend' => array(
        'icon'      => 'share',
        'url'       => helper::createLink('mail', 'resend', 'id={id}'),
        'hint'      => $lang->mail->resend,
        'className' => 'ajax-submit',
    ),
);
