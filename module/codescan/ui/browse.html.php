<?php
declare(strict_types=1);
/**
 * The browse view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
$queryMenuLink = createLink('codescan', 'browse', "type=bysearch&language={$language}&queryID={queryID}");
featureBar
(
    set::current($type),
    set::linkParams("type={key}&language={$language}"),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    div(setClass('ml-2'), searchToggle(set::module('codeScanRule'), set::open($type == 'bySearch')))
);

foreach($langs as $index => $lang)
{
    if(empty($lang->lang))
    {
        unset($langs[$index]);
        continue;
    }

    $lang->parent = 0;
    $lang->name   = $lang->lang;
    $lang->url    = inLink('browse', "type=all&language={$lang->id}&queryID=0&orderBy={$orderBy}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
}

sidebar
(
    moduleMenu
    (
        set::modules($langs),
        set::activeKey($language),
        set::showDisplay(false),
        set::closeLink(inLink('browse', "type={$type}"))
    )
);

$cols = $this->loadModel('datatable')->getSetting('codescan');
if(isset($cols['plugin'])) $cols['plugin']['map'] = $pluginList;
if(isset($cols['lang']))   $cols['lang']['map']   = $langList;

$ruleList  = initTableData($rules, $cols);
$urlParams = array(
    'type'       => $type,
    'language'   => $language,
    'queryID'    => $queryID,
    'orderBy'    => '{name}_{sortType}',
    'recPerPage' => $pager->recPerPage,
    'pageID'     => $pager->pageID
);
dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($ruleList),
    set::sortLink(createLink('codescan', 'browse', $urlParams)),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderRuleCell')),
    set::footPager(usePager())
);
