<?php
declare(strict_types=1);
/**
 * The view file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Liu <liuyue@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('selectProduct',   $lang->todo->selectProduct);
jsVar('selectExecution', $lang->execution->selectExecution);
jsVar('todoID',          $todo->id);

/**
 * 构建操作栏按钮，用于页面底部显示。
 * Build button group for todo.
 *
 * @param  object $todo
 * @param  object $config
 * @param  object $session
 * @param  object $app
 * @param  object $user
 * @param  array  $projects
 * @return mixed
 *
 */
function buildBtnGroup(object $todo, object $config, object $session, object $app, object $user, array $projects): mixed
{
    global $lang;

    if($session->todoList)
    {
        $browseLink = empty($todo->deleted) ? $session->todoList : createLink('action', 'trash');
    }
    elseif($todo->account == $app->user->account)
    {
        $browseLink = createLink('my', 'todo');
    }
    else
    {
        $browseLink = createLink('user', 'todo', "userID=$user->id");
    }

    $buttons = array(
        array(
            'icon'  => 'play',
            'url'   => createLink('todo', 'start', "todoID=$todo->id"),
            'text'  => $lang->todo->start,
        ),
        array(
            'icon'  => 'magic',
            'url'   => createLink('todo', 'activate', "todoID=$todo->id"),
            'text'  => $lang->activate,
        ),
        array(
            'icon'  => 'off',
            'url'   => createLink('todo', 'close', "todoID=$todo->id"),
            'text'  => $lang->close,
        ),
        array(
            'icon'  => 'edit',
            'url'   => createLink('todo', 'edit', "todoID=$todo->id"),
            'text'  => $lang->edit,
        ),
        array(
            'icon'  => 'trash',
            'url'   => createLink('todo', 'delete', "todoID=$todo->id"),
            'text'  => $lang->delete,
        ),
        array(
            'icon'  => 'checked',
            'url'   => createLink('todo', 'finish', "todoID=$todo->id"),
            'text'  => $lang->todo->finish,
        )
    );

    if($todo->status != 'wait') unset($buttons[0]);
    if($todo->status != 'done') unset($buttons[2]);
    if(!($todo->status == 'done' || $todo->status == 'closed')) unset($buttons[1]);
    if(!($todo->status != 'done' && $todo->status != 'closed')) unset($buttons[5]);

    $toolbarDropdownItems = buildToolbarDropdown($config, $projects);

    return div
    (
        setClass('flex', 'justify-center', 'absolute', 'w-full', 'bottom-0'),
        floatToolbar
        (
            set::prefix
            (
                array(
                    array(
                        'icon'  => 'back',
                        'url'   => $browseLink,
                        'text'  => $lang->goback,
                    )
                )
            ),
            set::main($buttons),
            $todo->status != 'done' && $todo->status != 'closed' ?
            to::suffix
            (
                array(
                    dropdown
                    (
                        btn
                        (
                            set
                            (
                                array(
                                    'text'  => $lang->more,
                                    'class' => 'ghost text-white'
                                )
                            )
                        ),
                        set::items($toolbarDropdownItems)
                    )
                )
            ) : null
        )
    );
}

/**
 * 构建工具栏下拉菜单选项。
 * Build toolbar dropdown items.
 *
 * @param  object $config
 * @param  array  $projects
 * @access public
 * @return mixed
 */
function buildToolbarDropdown(object $config, array $projects): mixed
{
    global $lang;

    $createStoryPriv = common::hasPriv('story', 'create');
    $createTaskPriv  = common::hasPriv('task', 'create');
    $createBugPriv   = common::hasPriv('bug', 'create');
    $printBtn        = $config->vision == 'lite' && empty($projects);

    $dropdownItems = array();
    if($printBtn && $createStoryPriv || $createTaskPriv || $createBugPriv)
    {

        if($createStoryPriv && $config->vision == 'lite')
        {
            $dropdownItems[] = array(
                'text'        => $lang->todo->reasonList['story'],
                'id'          => 'toStoryLink',
                'data-toggle' => 'modal',
                'data-target' => '#projectModal'
            );
        }
        else
        {
            $dropdownItems[] = array(
                'text'        => $lang->todo->reasonList['story'],
                'id'          => 'toStoryLink',
                'data-toggle' => 'modal',
                'data-target' => '#productModal'
            );
        }

        if($createTaskPriv)
        {
            $dropdownItems[] = array(
                'text'        => $lang->todo->reasonList['task'],
                'id'          => 'toTaskLink',
                'data-toggle' => 'modal',
                'data-target' => '#executionModal'
            );
        }

        if($createBugPriv && $config->vision == 'rnd')
        {
            $dropdownItems[] = array(
                'text'        => $lang->todo->reasonList['bug'],
                'id'          => 'toBugLink',
                'data-toggle' => 'modal',
                'data-target' => '#projectProductModal'
            );
        }
    }

    return $dropdownItems;
}

/**
 * 构建待办详细信息。
 * Build todo information.
 *
 * @param  object $todo
 * @param  array  $users
 * @param  array  $times
 * @return mixed
 */
function buildTodoInfo(object $todo, array $users, array $times): mixed
{
    global $lang;

    $cols = array(
        $lang->todo->pri         => zget($lang->todo->priList, $todo->pri),
        $lang->todo->status      => $lang->todo->statusList[$todo->status],
        $lang->todo->type        => $lang->todo->typeList[$todo->type],
        $lang->todo->account     => zget($users, $todo->account),
        $lang->todo->date        => $todo->date == '20300101' ? $lang->todo->periods['future'] : formatTime($todo->date, DT_DATE1),
        $lang->todo->beginAndEnd => $times[$todo->begin] ? ($times[$todo->begin] . ' ~ ' . $times[$todo->end]) : ''
    );

    $allCols = $cols;

    if(isset($todo->assignedTo))
    {
        $allCols = array_merge($cols, array(
            $lang->todo->assignedTo   => zget($users, $todo->assignedTo),
            $lang->todo->assignedBy   => zget($users, $todo->assignedBy),
            $lang->todo->assignedDate => formatTime($todo->assignedDate, DT_DATE1)
        ));
    }

    $todoInfo = div();

    $infoClass = array(
        $lang->todo->pri    => 'pri-'  . zget($lang->todo->priList, $todo->pri),
        $lang->todo->status => 'status-' . $todo->status
    );

    foreach($allCols as $key => $col)
    {
        $todoInfo->add
        (
            div
            (
                setClass('flex', 'items-center', 'py-1'),
                div($key, setClass('w-14', 'text-gray', 'text-right', 'mr-4')),
                div
                (
                    $key == $lang->todo->status ? span(setClass('label', 'label-dot', 'mr-1', 'shadow-none')) : null,
                    isset($infoClass[$key]) ? setClass($infoClass[$key]) : null,
                    $col
                )
            )
        );
    }

    return $todoInfo;
}

/**
 * 构建周期性待办周期信息。
 * Build cycle todo info.
 *
 * @param  object $todo
 * @access public
 * @return mixed
 */
function buildCycleTodoInfo(object $todo): mixed
{
    global $lang;

    $todo->config = json_decode($todo->config);

    /* Get cycle config by todo type.*/
    if($todo->config->type == 'day')
    {
        if(isset($todo->config->day)) $cycleConfig = $lang->todo->every . $todo->config->day . $lang->day;
        if(isset($todo->config->specifiedDate))
        {
            $cycleConfig = $lang->todo->specify;
            if(isset($todo->config->cycleYear)) $cycleConfig .= $lang->todo->everyYear;
            $cycleConfig .= zget($lang->datepicker->monthNames, $todo->config->specify->month) . $todo->config->specify->day . $lang->todo->day;
        }
    }
    elseif($todo->config->type == 'week')
    {
        foreach(explode(',', $todo->config->week) as $week) $cycleConfig = $lang->todo->dayNames[$week] . ' ';
    }
    elseif($todo->config->type == 'month')
    {
        foreach(explode(',', $todo->config->month) as $month) $cycleConfig = $month . ' ';
    }

    if($todo->config->beforeDays) $lblBeforeDays = sprintf($lang->todo->lblBeforeDays, $todo->config->beforeDays);

    return div
    (
        p
        (
            setClass('font-bold', 'text-lg', 'mt-6', 'pt-6', 'border-t'),
            $lang->todo->cycle
        ),
        div
        (
            setClass('flex', 'items-top', 'py-1'),
            div($lang->todo->beginAndEnd, setClass('w-14', 'text-gray', 'text-right', 'mr-4')),
            div($todo->config->begin . " ~ " . $todo->config->end)
        ),
        div
        (
            setClass('flex', 'items-top', 'py-1'),
            div($lang->todo->cycleConfig, setClass('w-14', 'text-gray', 'text-right', 'mr-4')),
            div
            (
                $cycleConfig,
                p($lblBeforeDays)
            )
        )
    );
}

/**
 * 构建产品转需求弹窗内表单。
 * Build Form for product modal.
 *
 * @param array  $products
 * @return mixed
 */
function buildProductModalForm(array $products): mixed
{
    global $lang;

    if(empty($products))
    {
        return div
        (
            setClass('text-center', 'pb-8'),
            span($lang->product->noProduct),
            btn
            (
                $lang->product->create,
                set
                (
                    array(
                        'url'   => createLink('product', 'create'),
                        'id'    => 'createProduct',
                        'class' => 'secondary-pale',
                        'icon'  => 'plus'
                    )
                )
            )
        );
    }

    return form
    (
        formGroup
        (
            inputGroup
            (
                select
                (
                    on::change('getProgramByProduct(this)'),
                    set
                    (
                        array(
                            'id'    => 'product',
                            'class' => 'form-control',
                            'name'  => 'product',
                            'items' => $products,
                        )
                    )
                ),
                input
                (
                    set::type('hidden'),
                    set::value(0)
                ),
                btn
                (
                    on::click('toStory'),
                    set
                    (
                        array(
                            'id'    => 'toStoryButton',
                            'class' => 'primary',
                            'text'  => $lang->todo->reasonList['story']
                        )
                    )
                )
            )
        ),
        set::actions(array()),
        setClass('pb-6')
    );
}

/**
 * 构建页面标题信息。
 * build panel heading dom.
 *
 * @param  object $todo
 * @access public
 * @return mixed
 */
function buildPanelHeading(object $todo): mixed
{
    global $lang;

    $panelHeading = div
    (
        setClass('mb-4 flex items-center'),
        label
        (
            $todo->id,
            setClass('mr-4 todo-id')
        ),
        span
        (
            $todo->name,
            setClass('font-bold text-lg')
        )
    );

    if($todo->deleted)
    {
        $panelHeading->add
        (
            label
            (
                $lang->todo->deleted,
                setClass('danger', 'ml-4')
            )
        );
    }

    return $panelHeading;
}

div
(
    setClass('panel-form mx-auto bg-white py-6 px-8'),
    div
    (
        setClass('flex', 'gap-4'),
        div
        (
            buildPanelHeading($todo),
            setClass('w-2/3', 'relative'),
            div
            (
                div
                (
                    setClass('font-bold text-lg mb-3'),
                    span($lang->todo->desc),
                    ($todo->type == 'bug' || $todo->type == 'task' || $todo->type == 'story') ?
                    a
                    (
                        strtoupper($todo->type) . '#' . $todo->objectID,
                        set::href(createLink('bug', 'view', "id={$todo->objectID}")),
                        setClass('ml-2')
                    ) : null
                ),
                div
                (
                    setClass('pb-2'),
                    $todo->desc
                ),
                buildBtnGroup($todo, $config, $this->session, $app, $user, $projects)
            )
        ),
        div
        (
            setClass('w-1/3', 'border-l-2', 'pl-4'),
            p
            (
                setClass('font-bold', 'text-lg', 'mb-2'),
                $lang->todo->legendBasic
            ),
            buildTodoInfo($todo, $users, $times),
            $todo->cycle ?
            buildCycleTodoInfo($todo) : null
        )
    )
);

modal
(
    setID('productModal'),
    set::modalProps(array('title' => $lang->product->select)),
    buildProductModalForm($products)
);

modal
(
    setID('executionModal'),
    set::modalProps(array('title' => $lang->execution->selectExecution)),
    form
    (
        setClass('text-center', 'pb-4'),
        set::actions(array()),
        formGroup
        (
            set::label($lang->todo->project),
            select
            (
                on::change('getExecutionByProject(this)'),
                set
                (
                    array(
                        'id'    => 'project',
                        'name'  => 'project',
                        'items' => $projects
                    )
                )
            )
        ),
        formGroup
        (
            set::label($lang->todo->execution),
            select
            (
                setID('execution'),
                set::name('execution'),
                set::items($executions)
            )
        ),
        btn
        (
            $lang->todo->reasonList['task'],
            on::click('toTask'),
            set
            (
                array(
                    'id'    => 'toTaskButton',
                    'class' => array('primary', 'text-center')
                )
            )
        )
    )
);

modal
(
    setID('projectProductModal'),
    set::modalProps(array('title' => $lang->product->select)),
    form
    (
        setClass('text-center', 'pb-4'),
        set::actions(array()),
        formGroup
        (
            set::label($lang->todo->project),
            select
            (
                on::change('getProductByProject(this)'),
                set
                (
                    array(
                        'id'    => 'bugProject',
                        'name'  => 'bugProject',
                        'items' => $projects
                    )
                )
            )
        ),
        formGroup
        (
            set::label($lang->todo->product),
            select
            (
                setID('bugProduct'),
                set::name('bugProduct'),
                set::items($projectProducts)
            )
        ),
        btn
        (
            $lang->todo->reasonList['bug'],
            on::click('toBug'),
            set
            (
                array(
                    'id'    => 'toBugButton',
                    'class' => array('primary', 'text-center')
                )
            )
        )
    )
);

render();
