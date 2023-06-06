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

featureBar
(
    set::current($type),
    set::linkParams("date={key}&userID={$app->user->id}&status=undone"),
    inputGroup
    (
        set::class('ml-4'),
        input
        (
            set::name('date'),
            set::type('date'),
            set::value($date),
        )
    )
);

$todos = initTableData($todos, $config->my->todo->dtable->fieldList, $this->todo);

$cols = array_values($config->my->todo->dtable->fieldList);
$data = array_values($todos);
toolbar
(
    item
    (
        set(array(
            'text'  => $lang->todo->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => createLink('todo', 'export', "userID={$user->id}&orderBy=$orderBy"),
            'data-toggle' => 'modal'
        ))
    ),
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('todo', 'create')),
            $lang->todo->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array
            (
                array('text' => $lang->todo->create, 'url' => helper::createLink('todo', 'create'), 'data-toggle' => 'modal'),
                array('text' => $lang->todo->batchCreate, 'url' => helper::createLink('todo', 'batchCreate'), 'data-toggle' => 'modal')
            )),
            set::placement('bottom-end')
        )
    )
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('0.44'),
    set::footPager(usePager()),
);

render();
