<?php
declare(strict_types=1);
/**
 * The todo view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('changeDateLabel', $lang->todo->changeDate);

featureBar
(
    set::current($type),
    set::linkParams("type={key}&userID={$user->id}&status=all&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    set::itemLink(array('before' => createLink($app->rawModule, $app->rawMethod, "type=before&userID={$user->id}&status=undone&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"))),
    datePicker
    (
        set::_class('w-32'),
        set::value($date),
        set::onChange(jsRaw("(value) => loadPage($.createLink('my', 'todo', 'date=' + (value ? zui.formatDate(value, 'yyyyMMdd') : '')), '#mainContent,#featureBar')"))
    )
);

$canCreate      = hasPriv('todo', 'create');
$canBatchCreate = hasPriv('todo', 'batchCreate');

toolbar
(
    hasPriv('todo', 'export') ? item
    (
        set(array(
            'text'  => $lang->todo->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => createLink('todo', 'export', "userID={$user->id}&orderBy=$orderBy"),
            'data-toggle' => 'modal'
        ))
    ) : null,
    $canCreate || $canBatchCreate ? btngroup
    (
        $canCreate ? btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            setData(array('toggle' => 'modal')),
            set::url(helper::createLink('todo', 'create')),
            $lang->todo->create
        ) : btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('todo', 'batchCreate')),
            $lang->todo->batchCreate
        ),
        $canCreate && $canBatchCreate ? dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array
            (
                array('text' => $lang->todo->create, 'url' => helper::createLink('todo', 'create'), 'data-toggle' => 'modal'),
                array('text' => $lang->todo->batchCreate, 'url' => helper::createLink('todo', 'batchCreate'), 'data-toggle' => 'modal', 'data-size' => 'lg')
            )),
            set::placement('bottom-end')
        ) : null
    ) : null
);

$batchEdit   = hasPriv('todo', 'batchEdit');
$batchFinish = hasPriv('todo', 'batchFinish');
$batchClose  = hasPriv('todo', 'batchClose');
$footToolbar = array('items' => array
(
    $batchEdit   ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('todo', 'batchEdit', "from=myTodo&type=$type&userID={$user->id}&status=$status")) : null,
    $batchFinish ? array('text' => $lang->todo->finish, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('todo', 'batchFinish')) : null,
    $batchClose  ? array('text' => $lang->todo->close, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('todo', 'batchClose')) : null
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

if($type == 'assignedToOther') unset($config->my->todo->dtable->fieldList['assignedBy']);
if($type != 'assignedToOther') unset($config->my->todo->dtable->fieldList['assignedTo']);

$todos          = initTableData($todos, $config->my->todo->dtable->fieldList, $this->todo);
$cols           = array_values($config->my->todo->dtable->fieldList);
$data           = array_values($todos);
$defaultSummary = sprintf($lang->todo->summary, count($todos), $waitCount, $doingCount);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::checkable(true),
    set::defaultSummary(array('html' => $defaultSummary)),
    set::checkedSummary($lang->todo->checkedSummary),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', 'todo', "type={$type}&userID={$user->id}&status={$status}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::emptyTip($lang->my->noTodo),
    set::createTip($lang->todo->create),
    set::createLink($canCreate ? createLink('todo', 'create') : ''),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::footer(array('checkbox', 'toolbar', hasPriv('todo', 'import2Today') && $type != 'today' ? jsRaw('window.generateHtml') : '', 'checkedInfo', 'flex', 'pager'))
);

render();
