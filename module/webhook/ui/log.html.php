<?php
declare(strict_types=1);
/**
 * The log view file of webhook module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     webhook
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar();
toolbar
(
    item(set(array
    (
        'id'    => 'createBtn',
        'text'  => $lang->webhook->create,
        'icon'  => 'plus',
        'class' => 'primary',
        'url'   => (common::hasPriv('webhook', 'create') and ($app->rawMethod == 'browse') or $app->rawMethod == 'log') ? createLink('webhook', 'create') : null
    ))),
);

$cols = $config->webhook->dtable->log->fieldList;
$data = array();
foreach($logs as $log)
{
    $iframe = zget($log, 'dialog', 0) == 1 ? 'data-toggle="modal" data-type="iframe"' : '';
    if(zget($log, 'dialog', 0) == 1) $log->actionURL = createLink($log->module, 'view', "id=$log->moduleID");
    $log->action = $log->action ? html::a($log->actionURL, $log->action, '', $iframe) : $lang->webhook->approval;

    $data[] = $log;
}

panel
(
    to::heading
    (
        div
        (
            btn(set::url(createLink('webhook', 'browse')), set::icon('back'), $lang->goback),
            span(setClass('pl-2'), $webhook->name . $lang->webhook->log)
        )
    ),
    common::hasPriv('admin', 'log') ? to::headingActions
    (
        btn(set::url(createLink('admin', 'log')), setClass('primary mr-2'), set('data-toggle', 'modal'), set::icon('cog'), $lang->webhook->setting)
    ) : null,
    dtable
    (
        set::id('logList'),
        set::checkable(false),
        set::cols($cols),
        set::data($data),
        set::sortLink(createLink('webhook', 'log', "id={$webhook->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::orderBy($orderBy),
        set::footPager(usePager())
    )
);

render();

