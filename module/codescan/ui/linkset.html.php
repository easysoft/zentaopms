<?php
declare(strict_types=1);
/**
 * The linkruleset file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li<liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$footToolbar = array(
    'items' => array
    (
        array(
            'text'      => $lang->codescan->linkSet,
            'className' => 'batch-btn ajax-btn',
            'data-app'  => $app->tab,
            'data-url'  => inLink('linkSet', "solutionID={$solutionID}"),
        )
    )
);

modalHeader(set::title($title), set::entityText($solution->name), set::entityID($solutionID));

$config->codescan->ruleset->dtable->fieldList['id']['checkbox'] = true;
$config->codescan->ruleset->dtable->fieldList['id']['type']     = 'checkID';
unset($config->codescan->ruleset->dtable->fieldList['createdBy']);
unset($config->codescan->ruleset->dtable->fieldList['createdAt']);
unset($config->codescan->ruleset->dtable->fieldList['updatedBy']);
unset($config->codescan->ruleset->dtable->fieldList['updatedAt']);
unset($config->codescan->ruleset->dtable->fieldList['actions']);

$config->codescan->ruleset->dtable->fieldList['id']['sortType']         = false;
$config->codescan->ruleset->dtable->fieldList['name']['sortType']       = false;
$config->codescan->ruleset->dtable->fieldList['lang']['sortType']       = false;
$config->codescan->ruleset->dtable->fieldList['plugin']['sortType']     = false;
$config->codescan->ruleset->dtable->fieldList['rulesCount']['sortType'] = false;
$config->codescan->ruleset->dtable->fieldList['status']['sortType']     = false;
$config->codescan->ruleset->dtable->fieldList['desc']['sortType']       = false;

div
(
    on::click('#table-codescan-linkset .batch-btn > a, #table-codescan-linkset .batch-btn')->call('handleClickBatchBtn', jsRaw('$this')),
    dtable
    (
        set::data($rulesetList),
        set::cols($config->codescan->ruleset->dtable->fieldList),
        set::checkable(true),
        set::orderBy($orderBy),
        set::footToolbar($footToolbar),
        set::loadPartial(true)
    )
);
