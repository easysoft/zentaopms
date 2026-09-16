<?php
/**
 * The config file of reviewcreatedbyme block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang LI <liyang@easycorp.ltd>
 * @package     block
 * @link        https://www.zentao.net
 */
if($config->edition != 'gitfox' && $config->vision != 'gitfox') return;

global $lang, $app;
$app->loadLang('ppm');

$config->block->reviewcreatedbyme = new stdclass();
$config->block->reviewcreatedbyme->dtable = new stdclass();
$config->block->reviewcreatedbyme->dtable->fieldList = array();

$config->block->reviewcreatedbyme->dtable->fieldList['id']['name']  = 'id';
$config->block->reviewcreatedbyme->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->reviewcreatedbyme->dtable->fieldList['id']['type']  = 'id';
$config->block->reviewcreatedbyme->dtable->fieldList['id']['width'] = '60';

$config->block->reviewcreatedbyme->dtable->fieldList['title']['name']     = 'title';
$config->block->reviewcreatedbyme->dtable->fieldList['title']['title']    = $lang->ppm->title;
$config->block->reviewcreatedbyme->dtable->fieldList['title']['type']     = 'shortTitle';
$config->block->reviewcreatedbyme->dtable->fieldList['title']['data-app'] = 'ppm';
$config->block->reviewcreatedbyme->dtable->fieldList['title']['link']     = array('module' => 'ppm', 'method' => 'view', 'params' => 'id={id}');
$config->block->reviewcreatedbyme->dtable->fieldList['title']['flex']     = 1;

$config->block->reviewcreatedbyme->dtable->fieldList['status']['name']      = 'status';
$config->block->reviewcreatedbyme->dtable->fieldList['status']['title']     = $lang->ppm->status;
$config->block->reviewcreatedbyme->dtable->fieldList['status']['type']      = 'status';
$config->block->reviewcreatedbyme->dtable->fieldList['status']['statusMap'] = $lang->ppm->statusList;
$config->block->reviewcreatedbyme->dtable->fieldList['status']['width']     = '90';

$config->block->reviewcreatedbyme->dtable->fieldList['createdDate']['name']  = 'createdDate';
$config->block->reviewcreatedbyme->dtable->fieldList['createdDate']['title'] = $lang->ppm->createdDate;
$config->block->reviewcreatedbyme->dtable->fieldList['createdDate']['type']  = 'datetime';
$config->block->reviewcreatedbyme->dtable->fieldList['createdDate']['width'] = '110';

$config->block->reviewcreatedbyme->dtable->fieldList['sourceBranch']['name']  = 'sourceBranch';
$config->block->reviewcreatedbyme->dtable->fieldList['sourceBranch']['title'] = $lang->ppm->sourceBranch;
$config->block->reviewcreatedbyme->dtable->fieldList['sourceBranch']['type']  = 'text';
$config->block->reviewcreatedbyme->dtable->fieldList['sourceBranch']['width'] = '140';

$config->block->reviewcreatedbyme->dtable->fieldList['targetBranch']['name']  = 'targetBranch';
$config->block->reviewcreatedbyme->dtable->fieldList['targetBranch']['title'] = $lang->ppm->targetBranch;
$config->block->reviewcreatedbyme->dtable->fieldList['targetBranch']['type']  = 'text';
$config->block->reviewcreatedbyme->dtable->fieldList['targetBranch']['width'] = '140';

$config->block->reviewcreatedbyme->dtable->fieldList['type']['name']  = 'type';
$config->block->reviewcreatedbyme->dtable->fieldList['type']['title'] = $lang->ppm->requestType;
$config->block->reviewcreatedbyme->dtable->fieldList['type']['type']  = 'category';
$config->block->reviewcreatedbyme->dtable->fieldList['type']['map']   = $lang->ppm->requestTypeList;
$config->block->reviewcreatedbyme->dtable->fieldList['type']['width'] = '100';

$config->block->reviewcreatedbyme->dtable->short = new stdclass();
$config->block->reviewcreatedbyme->dtable->short->fieldList['id']     = $config->block->reviewcreatedbyme->dtable->fieldList['id'];
$config->block->reviewcreatedbyme->dtable->short->fieldList['title']  = $config->block->reviewcreatedbyme->dtable->fieldList['title'];
$config->block->reviewcreatedbyme->dtable->short->fieldList['status'] = $config->block->reviewcreatedbyme->dtable->fieldList['status'];
