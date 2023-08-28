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

$fnGenerateScopeMenu = function() use ($scope, $scopeText, $scopeList)
{
    $link = $this->createLink('metric', 'browse', "scope={key}");

    return dropmenu
    (
        set::className('scope-menu btn'),
        set::defaultValue($scope),
        set::text($scopeText),
        set::caret(false),
        set::popWidth(200),
        set::popClass('popup text-md'),
        set::data(array('search' => false, 'checkIcon' => false, 'link' => $link, 'data' => $scopeList)),
    );
};

featureBar
(
    to::before($fnGenerateScopeMenu($scope, $scopeText, $scopeList)),
    li(searchToggle(set::open($type == 'bysearch'), set::module('metric'))),
);

toolbar
(
    common::hasPriv('metric', 'create') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('metric', 'create')),
        set('data-toggle', 'modal'),
        $lang->metric->create
    ) : null,
);

sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $metricTree,
        'activeKey' => $type == 'byTree' ? $param : 0,
        'closeLink' => $closeLink,
        'showDisplay' => false,
    )))
);

$tableData = initTableData($metrics, $this->config->metric->dtable->definition->fieldList, $this->loadModel('metric'));
dtable
(
    setID('metricList'),
    set::cols($this->config->metric->dtable->definition->fieldList),
    set::data($tableData),
    set::footPager(usePager()),
);

render();

