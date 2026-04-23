<?php
declare(strict_types=1);
/**
 * The solution view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('defaultTip', $lang->codescan->notice->defaultSolution);

$queryMenuLink = createLink('codescan', 'solution', "type=bysearch&queryID={queryID}");
featureBar
(
    set::current($type),
    set::linkParams("type={key}"),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    div(setClass('ml-2'), searchToggle(set::module('codeScanSolution'), set::open($type == 'bySearch')))
);

hasPriv('codescan', 'createsolution') ? toolbar
(
    item(set(array(
        'text'        => $lang->codescan->createSolution,
        'url'         => inLink('createsolution'),
        'class'       => 'primary',
        'icon'        => 'plus',
        'data-toggle' => 'modal'
    )))
) : null;

$cols = $this->loadModel('datatable')->getSetting('codescan', 'solution');
if(isset($cols['plugin'])) $cols['plugin']['map'] = $pluginList;
if(isset($cols['lang']))   $cols['lang']['map']   = $langList;
$solutionList = initTableData($solutions, $cols);
dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($solutionList),
    set::sortLink(createLink('codescan', 'solution', "type={$type}&queryID={$queryID}&orderBy={name}_{sortType}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::userMap($users),
    set::footPager(usePager())
);
