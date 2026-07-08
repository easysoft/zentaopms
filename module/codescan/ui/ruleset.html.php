<?php
declare(strict_types=1);
/**
 * The ruleset view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('defaultTip', $lang->codescan->notice->defaultRuleset);

$queryMenuLink = createLink('codescan', 'ruleset', "type=bysearch&queryID={queryID}");
featureBar
(
    set::current($type),
    set::linkParams("type={key}"),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    div(setClass('ml-2'), searchToggle(set::module('codeScanRuleset'), set::open($type == 'bySearch')))
);

hasPriv('codescan', 'createruleset') ? toolbar
(
    item(set(array(
        'text'        => $lang->codescan->createRuleset,
        'url'         => inLink('createruleset'),
        'class'       => 'primary',
        'icon'        => 'plus',
        'data-toggle' => 'modal'
    )))
) : null;

$cols = $this->loadModel('datatable')->getSetting('codescan', 'ruleset');
if(isset($cols['plugin'])) $cols['plugin']['map'] = $pluginList;
if(isset($cols['lang']))   $cols['lang']['map']   = $langList;

$rulesetList = initTableData($rulesets, $cols);
$urlParams   = array(
    'type'       => $type,
    'queryID'    => $queryID,
    'orderBy'    => '{name}_{sortType}',
    'recPerPage' => $pager->recPerPage,
    'pageID'     => $pager->pageID
);
dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($rulesetList),
    set::sortLink(createLink('codescan', 'ruleset', $urlParams)),
    set::userMap($users),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
