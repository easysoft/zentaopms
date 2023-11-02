<?php
declare(strict_types=1);
/**
 * The browse view file of webhook module of ZenTaoPMS.
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
        'url'   => (common::hasPriv('webhook', 'create') and ($app->rawMethod == 'browse') or $app->rawMethod == 'log') ? createLink('webhook', 'create') : null,
    ))),
);

$cols = $config->webhook->dtable->fieldList;
$data = array();
foreach($webhooks as $webhook)
{
    $webhook->actions = array();
    $canChooseDept = (($webhook->type == 'dinguser' or $webhook->type == 'feishuuser') and common::hasPriv('webhook', 'chooseDept'));
    $canBind       = ($webhook->type == 'wechatuser' and common::hasPriv('webhook', 'bind'));
    $canSeeLog     = common::hasPriv('webhook', 'log');
    $canEdit       = common::hasPriv('webhook', 'edit');
    $canDelete     = common::hasPriv('webhook', 'delete');
    if(!$canBind) $webhook->actions[] = array('name' => 'chooseDept', 'disabled' => !$canChooseDept, 'url' => createLink('webhook', 'chooseDept', "webhookID={$webhook->id}"), 'title' => $lang->webhook->chooseDept);
    if($canBind)  $webhook->actions[] = array('name' => 'bind', 'url' => createLink('webhook', 'bind',       "webhookID={$webhook->id}"), 'title' => $lang->webhook->bind);
    $webhook->actions[] = array('name' => 'log',    'disabled' => !$canSeeLog, 'url' => createLink('webhook', 'log',    "webhookID={$webhook->id}"), 'title' => $lang->webhook->log);
    $webhook->actions[] = array('name' => 'edit',   'disabled' => !$canEdit,   'url' => createLink('webhook', 'edit',   "webhookID={$webhook->id}"), 'title' => $lang->webhook->edit);
    $webhook->actions[] = array('name' => 'delete', 'disabled' => !$canDelete, 'url' => createLink('webhook', 'delete', "webhookID={$webhook->id}&confirm=yes"), 'title' => $lang->webhook->delete, 'className' => 'ajax-submit', 'data-confirm' => $lang->webhook->confirmDelete);

    $webhook->type = zget($lang->webhook->typeList, $webhook->type);
    $data[] = $webhook;
}
dtable
(
    set::id('webhookList'),
    set::checkable(false),
    set::cols($cols),
    set::data($data),
    set::sortLink(createLink('webhook', 'browse', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager()),
);

render();

