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

jsVar('statusList', $lang->metric->statusList);
jsVar('confirmList', $lang->metric->confirmList);

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}"),
    li(searchToggle(set::open($type == 'bysearch'), set::module('metric'))),
);

$footToolbar = common::hasPriv('user', 'batchEdit') ? array(
    'items' => array(array('text' => $lang->edit, 'className' => 'secondary batch-btn', 'data-url' => createLink('user', 'batchEdit'))),
    'btnProps' => array('size' => 'sm', 'btnType' => 'secondary')
) : null;

$tableData = initTableData($metrics, $this->config->metric->dtable->definition->fieldList, $this->loadModel('metric'));
dtable
(
    setID('metricList'),
    set::cols($this->config->metric->dtable->definition->fieldList),
    set::data($tableData),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();

