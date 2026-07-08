<?php
declare(strict_types=1);
/**
 * The linkrule file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$footToolbar = array(
    'items' => array
    (
        array(
            'text'      => $lang->codescan->linkRule,
            'className' => 'batch-btn ajax-btn',
            'data-app'  => $app->tab,
            'data-url'  => inLink('linkRule', "setID={$setID}")
        )
    )
);

modalHeader(set::title($title), set::entityText($ruleSet->name), set::entityID($setID));
searchForm
(
    set::module('codeScanRule'),
    set::simple(true),
    set::show(true),
    set::extraHeight('+144')
);

$config->codescan->dtable->fieldList['id']['checkbox'] = true;
$config->codescan->dtable->fieldList['id']['type']     = 'checkID';
$config->codescan->dtable->fieldList['lang']['map']    = $langList;
$config->codescan->dtable->fieldList['plugin']['map']  = $pluginList;
unset($config->codescan->dtable->fieldList['actions']);
div
(
    on::click('#table-codescan-linkrule_table .batch-btn > a, #table-codescan-linkrule_table .batch-btn')->call('handleClickBatchBtn', jsRaw('$this')),
    dtable
    (
        set::data($ruleList),
        set::cols($config->codescan->dtable->fieldList),
        set::checkable(true),
        set::orderBy($orderBy),
        set::footToolbar($footToolbar),
        set::loadPartial(true),
        set::sortLink(inLink('linkRule', "setID={$setID}&type={$type}&orderBy={name}_{sortType}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::footPager(usePager()),
        set::onRenderCell(jsRaw('window.renderRuleCell'))
    )
);
