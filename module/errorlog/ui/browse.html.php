<?php
declare(strict_types=1);
/**
 * The browse view file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($type),
    set::link($this->createLink('errorlog', 'browse', "type={key}")),
    li(searchToggle(set::module('errorlog'), set::open($type == 'bysearch')))
);

toolbar
(
    btn
    (
        setClass('btn primary'),
        set::icon('cog'),
        set::url(helper::createLink('errorlog', 'setting')),
        set(array('data-toggle' => 'modal', 'data-size' => 'sm')),
        $lang->errorlog->setting
    )
);

$canDelete      = hasPriv('errorlog', 'delete');
$canBatchDelete = hasPriv('errorlog', 'batchDelete');
if(!$canDelete)
{
    unset($config->errorlog->dtable->fieldList['actions']['list']['delete']);
    $config->errorlog->dtable->fieldList['actions']['menu'] = array_diff($config->errorlog->dtable->fieldList['actions']['menu'], array('delete'));
}

$tableData = initTableData($logList, $this->config->errorlog->dtable->fieldList, $this->errorlog);
dtable
(
    set::cols($this->config->errorlog->dtable->fieldList),
    set::data($tableData),
    set::orderBy($orderBy),
    set::sortLink(createLink('errorlog', 'browse', "type={$type}&queryID={$queryID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('window.renderErrorLogCell')),
    set::checkable($canBatchDelete),
    $canBatchDelete ? set::onCheckChange(jsRaw('window.toggleErrorlogBatchDelete')) : null,
    $canBatchDelete ? set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(array
        (
            'text'         => $lang->errorlog->batchDelete,
            'btnType'      => 'secondary',
            'className'    => 'batch-btn errorlog-batch-delete hidden',
            'data-on'      => 'click',
            'data-call'    => 'batchDeleteErrorlog',
            'data-params'  => 'event',
            'data-url'     => helper::createLink('errorlog', 'batchDelete'),
            'data-confirm' => $lang->errorlog->confirmBatchDelete
        ))
    )) : null,
    set::footPager(usePager())
);

render();
