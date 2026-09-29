<?php
/**
 * The config file of reviewbyme block of ZenTaoPMS.
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

$config->block->reviewbyme = new stdclass();
$config->block->reviewbyme->dtable = new stdclass();
$config->block->reviewbyme->dtable->fieldList = array();

$config->block->reviewbyme->dtable->fieldList['id']['name']  = 'id';
$config->block->reviewbyme->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->reviewbyme->dtable->fieldList['id']['type']  = 'id';
$config->block->reviewbyme->dtable->fieldList['id']['width'] = '60';

$config->block->reviewbyme->dtable->fieldList['title']['name']     = 'title';
$config->block->reviewbyme->dtable->fieldList['title']['title']    = $lang->ppm->title;
$config->block->reviewbyme->dtable->fieldList['title']['type']     = 'shortTitle';
$config->block->reviewbyme->dtable->fieldList['title']['data-app'] = 'ppm';
$config->block->reviewbyme->dtable->fieldList['title']['link']     = array('module' => 'ppm', 'method' => 'view', 'params' => 'id={id}');
$config->block->reviewbyme->dtable->fieldList['title']['flex']     = 1;

$config->block->reviewbyme->dtable->fieldList['status']['name']      = 'status';
$config->block->reviewbyme->dtable->fieldList['status']['title']     = $lang->ppm->status;
$config->block->reviewbyme->dtable->fieldList['status']['type']      = 'status';
$config->block->reviewbyme->dtable->fieldList['status']['statusMap'] = $lang->ppm->statusList;
$config->block->reviewbyme->dtable->fieldList['status']['width']     = '90';

$config->block->reviewbyme->dtable->fieldList['createdDate']['name']  = 'createdDate';
$config->block->reviewbyme->dtable->fieldList['createdDate']['title'] = $lang->ppm->createdDate;
$config->block->reviewbyme->dtable->fieldList['createdDate']['type']  = 'datetime';
$config->block->reviewbyme->dtable->fieldList['createdDate']['width'] = '110';

$config->block->reviewbyme->dtable->fieldList['sourceBranch']['name']  = 'sourceBranch';
$config->block->reviewbyme->dtable->fieldList['sourceBranch']['title'] = $lang->ppm->sourceBranch;
$config->block->reviewbyme->dtable->fieldList['sourceBranch']['type']  = 'text';
$config->block->reviewbyme->dtable->fieldList['sourceBranch']['width'] = '140';

$config->block->reviewbyme->dtable->fieldList['targetBranch']['name']  = 'targetBranch';
$config->block->reviewbyme->dtable->fieldList['targetBranch']['title'] = $lang->ppm->targetBranch;
$config->block->reviewbyme->dtable->fieldList['targetBranch']['type']  = 'text';
$config->block->reviewbyme->dtable->fieldList['targetBranch']['width'] = '140';

$config->block->reviewbyme->dtable->fieldList['type']['name']  = 'type';
$config->block->reviewbyme->dtable->fieldList['type']['title'] = $lang->ppm->requestType;
$config->block->reviewbyme->dtable->fieldList['type']['type']  = 'category';
$config->block->reviewbyme->dtable->fieldList['type']['map']   = $lang->ppm->requestTypeList;
$config->block->reviewbyme->dtable->fieldList['type']['width'] = '100';

/* 区块列表数据固定按提交时间倒序展示，隐藏列头上的排序图标。 */
foreach(array_keys($config->block->reviewbyme->dtable->fieldList) as $field)
{
    $config->block->reviewbyme->dtable->fieldList[$field]['sortType'] = false;
}

$config->block->reviewbyme->dtable->short = new stdclass();
$config->block->reviewbyme->dtable->short->fieldList['id']     = $config->block->reviewbyme->dtable->fieldList['id'];
$config->block->reviewbyme->dtable->short->fieldList['title']  = $config->block->reviewbyme->dtable->fieldList['title'];
$config->block->reviewbyme->dtable->short->fieldList['status'] = $config->block->reviewbyme->dtable->fieldList['status'];
