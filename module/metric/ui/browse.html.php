<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmDelist', $lang->metric->confirmDelist);
jsVar('upgradeTip', $lang->metric->upgradeTip);
jsVar('scope', $scope);
jsVar('metricSql', $lang->metric->oldMetric->sql);
jsVar('metricTip', $lang->metric->oldMetric->tip);
jsVar('implementType', $lang->metric->implementType);

$fnGenerateScopeMenu = function() use ($scope, $scopeText, $scopeList)
{
    $link = $this->createLink('metric', 'browse', "scope={key}");

    return dropmenu
    (
        set::defaultValue($scope),
        set::text($scopeText),
        set::caret(false),
        set::popWidth(200),
        set::popClass('popup text-md'),
        set::data(array('search' => false, 'checkIcon' => false, 'link' => $link, 'data' => $scopeList))
    );
};

featureBar
(
    set::current($stage),
    set::linkParams("scope=$scope&status={key}&param=$param&type=$type"),
    li(searchToggle(set::open($type == 'bysearch'), set::module('metric')))
);

toolbar
(
    setClass('clear-gap'),
    btn
    (
        setClass('btn primary-outline'),
        set::url(helper::createLink('metric', 'preview')),
        $lang->metric->exitManage
    ),
    common::hasPriv('metric', 'create') ? div
    (
        setClass('btn-divider')
    ) : null,
    common::hasPriv('metric', 'create') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('metric', 'create', "scope=$scope&period=nodate")),
        set('data-toggle', 'modal'),
        $lang->metric->create
    ) : null
);

sidebar
(
    moduleMenu
    (
        to::before(div(setClass('bg-canvas'), $fnGenerateScopeMenu($scope, $scopeText, $scopeList))),
        set::titleShow(false),
        set::modules($metricTree),
        set::activeKey($type == 'byTree' ? $param : 0),
        set::closeLink($closeLink),
        set::showDisplay(false)
    )
);

$tableData = initTableData($metrics, $this->config->metric->dtable->definition->fieldList, $this->loadModel('metric'));
list($cols, $tableData) = $this->metric->initActionBtn($tableData, $this->config->metric->dtable->definition->fieldList);

dtable
(
    setID('metricList'),
    set::userMap($users),
    set::cols($cols),
    set::data($tableData),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::footPager(usePager())
);

render();
