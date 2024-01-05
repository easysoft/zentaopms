<?php
declare(strict_types=1);
/**
 * The view view file of todo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('selectProduct',   $lang->todo->selectProduct);
jsVar('selectExecution', $lang->execution->selectExecution);
jsVar('todoID',          $todo->id);
jsVar('vision',          $config->vision);

$isInModal = isAjaxRequest('modal');

/* Generate title suffix for bug,task,story type. */
$fnGenerateTitleSuffix = function() use($todo)
{
    if($todo->type == 'bug')   return btn(set::url(createLink('bug',   'view', "id={$todo->objectID}")), set::text('  BUG#'   . $todo->objectID), setClass('ghost'));
    if($todo->type == 'task')  return btn(set::url(createLink('task',  'view', "id={$todo->objectID}")), set::text('  TASK#'  . $todo->objectID), setClass('ghost'));
    if($todo->type == 'story') return btn(set::url(createLink('story', 'view', "id={$todo->objectID}")), set::text('  STORY#' . $todo->objectID), setClass('ghost'));
};

/* Render modal for creating story. */
if(hasPriv('story', 'create') && $config->vision != 'lite')
{
    modal
    (
        setID('productModal'),
        set::modalProps(array('title' => $lang->product->select)),
        empty($products) ? div
        (
            setClass('text-center pb-8 mt-2'),
            span($lang->product->noProduct),
            btn
            (
                $lang->product->create,
                set
                (
                    array(
                        'id'    => 'createProductBtn',
                        'class' => 'secondary-pale',
                        'icon'  => 'plus'
                    )
                ),
                on::click('createProduct')
            )
        ) : form
        (
            setClass('mt-2'),
            set::actions(array()),
            formGroup
            (
                inputGroup
                (
                    picker
                    (
                        set::name('product'),
                        set::items($products),
                        set::required(true)
                    ),
                    btn
                    (
                        setID('toStoryButton'),
                        setClass('primary'),
                        $lang->todo->reasonList['story'],
                        on::click('toStory')
                    )
                )
            )
        )
    );
}

if(hasPriv('story', 'create') && $config->vision == 'lite')
{
    modal
    (
        setID('projectModal'),
        set::modalProps(array('title' => $lang->project->select)),
        form
        (
            setClass('mt-2'),
            set::actions(array()),
            formGroup
            (
                inputGroup
                (
                    picker
                    (
                        set::name('projectToStory'),
                        set::items($projects),
                        set::required(true)
                    ),
                    btn
                    (
                        setID('toStoryButton'),
                        setClass('primary'),
                        $lang->todo->reasonList['story'],
                        on::click('toStory')
                    )
                )
            )
        )
    );
}

/* Render modal for creating task. */
if(hasPriv('task', 'create'))
{
    modal
    (
        setID('executionModal'),
        set::modalProps(array('title' => $lang->execution->selectExecution)),
        to::footer
        (
            div
            (
                setClass('toolbar gap-4 w-full justify-center'),
                btn($lang->todo->reasonList['task'], setID('toTaskButton'), setClass('primary')),
                on::click('toTask')
            )
        ),
        form
        (
            setClass('mt-2'),
            set::actions(array()),
            formGroup
            (
                set::label($lang->todo->project),
                picker
                (
                    on::change('getExecutionByProject'),
                    set::name('project'),
                    set::items($projects),
                    set::required(true)
                )
            ),
            formGroup
            (
                set::label($lang->todo->execution),
                picker
                (
                    set::name('execution'),
                    set::items($executions),
                    set::required(true)
                )
            )
        )
    );
}

/* Render modal for creating bug. */
if(hasPriv('bug', 'create'))
{
    modal
    (
        setID('projectProductModal'),
        set::modalProps(array('title' => $lang->product->select)),
        to::footer
        (
            div
            (
                setClass('toolbar gap-4 w-full justify-center'),
                btn($lang->todo->reasonList['bug'], setID('toBugButton'), setClass('primary')),
                on::click('toBug')
            )
        ),
        form
        (
            setClass('mt-2'),
            set::actions(array()),
            formGroup
            (
                set::label($lang->todo->project),
                picker
                (
                    set::name('bugProject'),
                    set::items($projects),
                    set::required(true),
                    on::change('getProductByProject')
                )
            ),
            formGroup
            (
                set::label($lang->todo->product),
                picker
                (
                    set::name('bugProduct'),
                    set::items($projectProducts),
                    set::required(true)
                )
            )
        )
    );
}

/* Generate goback url. */
$fnGenerateGoBackUrl = function() use ($app, $todo, $user)
{
    if($this->session->todoList)
    {
        $browseLink = empty($todo->deleted) ? $this->session->todoList : $this->createLink('action', 'trash');
    }
    elseif($todo->account == $app->user->account)
    {
        $browseLink = $this->createLink('my', 'todo');
    }
    else
    {
        $browseLink = $this->createLink('user', 'todo', "userID=$user->id");
    }

    return $browseLink;
};

/* Generate action buttons and related menus within float toolbar. */
$fnGenerateFloatToolbarBtns = function() use ($lang, $config, $todo, $projects, $isInModal, $fnGenerateGoBackUrl)
{
    /* Deleted item without action buttons. */
    if($todo->deleted) return array();

    /* Verify privilege of current account. */
    if(!$this->app->user->admin && $this->app->user->account != $todo->account && $this->app->user->account != $todo->assignedTo) return array();

    /* Prepare variables for verifying. */
    $status      = $todo->status;
    $canStart    = hasPriv('todo', 'start');
    $canActivate = hasPriv('todo', 'activate');
    $canClose    = hasPriv('todo', 'close');
    $canEdit     = hasPriv('todo', 'edit');
    $canDelete   = hasPriv('todo', 'delete');

    $actionList = array('prefix' => array(), 'main' => array(), 'suffix' => array());

    !$isInModal && $actionList['prefix'][] = array('icon' => 'back', 'url' => $fnGenerateGoBackUrl(), 'hint' => $lang->goback . $lang->backShortcutKey, 'text' => $lang->goback);

    /* Common action buttons. */
    $canStart    && $status == 'wait'                          ? $actionList['main'][] = array('icon' => 'play',  'url' => createLink('todo', 'start',    "todoID={$todo->id}"), 'text' => $lang->todo->abbr->start) : null;
    $canActivate && ($status == 'done' || $status == 'closed') ? $actionList['main'][] = array('icon' => 'magic', 'url' => createLink('todo', 'activate', "todoID={$todo->id}"), 'text' => $lang->activate) : null;
    $canClose    && $status == 'done'                          ? $actionList['main'][] = array('icon' => 'off',   'url' => createLink('todo', 'close',    "todoID={$todo->id}"), 'text' => $lang->close) : null;
    $canEdit                                                   ? $actionList['main'][] = array('icon' => 'edit',  'url' => createLink('todo', 'edit',     "todoID={$todo->id}"), 'data-load' => $isInModal ? 'modal' : null, 'text' => $lang->edit) : null;
    $canDelete                                                 ? $actionList['main'][] = array('icon' => 'trash', 'url' => createLink('todo', 'delete',   "todoID={$todo->id}&confirm=yes"), 'text' => $lang->delete, 'class' => 'ajax-submit', 'data-confirm' => $lang->todo->confirmDelete) : null;

    /* The status is 'done' or 'closed' without more action buttons. */
    if($status == 'done' || $status == 'closed') return $actionList;

    $actionList['main'][] = array('icon' => 'checked', 'url' => createLink('todo', 'finish', "todoID={$todo->id}"), 'text' => $lang->todo->abbr->finish);

    $canCreateStory = hasPriv('story', 'create');
    $canCreateTask  = hasPriv('task',  'create');
    $canCreateBug   = hasPriv('bug',   'create') && $config->vision != 'lite';
    $printBtn       = $config->vision == 'lite' && empty($projects) ? false : true;

    /* Render more button. */
    if($printBtn && ($canCreateStory || $canCreateTask || $canCreateBug))
    {
        $actionList['suffix'][] = array('url' => '#navActions', 'text' => $lang->todo->transform, 'data-toggle' => 'dropdown', 'data-placement' => 'top-end', 'caret' => 'up');
    }

    /* Popup menu of more button. */
    $storyTarget = $canCreateStory && $config->vision == 'lite' ? '#projectModal' : '#productModal';
    $suffixItems = array();
    if($canCreateStory) $suffixItems[] =  array('text' => $lang->todo->reasonList['story'], 'id' => 'toStoryLink', 'data-url' => '###', 'data-toggle' => 'modal', 'data-target' => $storyTarget,           'data-backdrop' => false, 'data-moveable' => true, 'data-position' => 'center', 'data-size' => 'sm');
    if($canCreateTask)  $suffixItems[] =  array('text' => $lang->todo->reasonList['task'],  'id' => 'toTaskLink',  'data-url' => '###', 'data-toggle' => 'modal', 'data-target' => '#executionModal',      'data-backdrop' => false, 'data-moveable' => true, 'data-position' => 'center', 'data-size' => 'sm');
    if($canCreateBug)   $suffixItems[] =  array('text' => $lang->todo->reasonList['bug'],   'id' => 'toBugLink',   'data-url' => '###', 'data-toggle' => 'modal', 'data-target' => '#projectProductModal', 'data-backdrop' => false, 'data-moveable' => true, 'data-position' => 'center', 'data-size' => 'sm');
    menu
    (
        setID('navActions'),
        setClass('menu dropdown-menu'),
        set::items($suffixItems)
    );

    return $actionList;
};
$actionList = $fnGenerateFloatToolbarBtns();

/* Generate from data and item. */
$fnGenerateFrom = function() use ($app, $lang, $config, $todo)
{
    if(!in_array($todo->type, array('story', 'task', 'bug')) || empty($todo->object)) return array(null, null);

    /* Generate from data. */
    $app->loadLang($todo->type);
    $objectData = array();
    foreach($config->todo->related[$todo->type]['title'] as $index => $relatedTitle)
    {
        $content = zget($todo->object, $config->todo->related[$todo->type]['content'][$index], '');
        $objectData[] = item
        (
            set::title($lang->{$todo->type}->{$relatedTitle}),
            empty($content) ? $lang->noData : html($content)
        );
    }

    $fromItemData = section
    (
        set::title(zget($lang->todo->fromList, $todo->type)),
        sectionCard
        (
            entityLabel
            (
                set::entityID($todo->objectID),
                set::text($todo->name)
            ),
            $objectData
        )
    );

    /* Generate from item. */
    $fromItem = item
    (
        set::name(zget($lang->todo->fromList, $todo->type)),
        a
        (
            set::href(createLink($todo->type, 'view', "id={$todo->objectID}", '', false)),
            setData
            (
                array
                (
                    'toggle'    => 'modal',
                    'data-type' => 'html',
                    'type'      => 'ajax'
                )
            ),
            $todo->name
        )
    );

    return array($fromItem, $fromItemData);
};
list($fromItem, $fromItemData) = $fnGenerateFrom();

/* Generate cycle configuration information. */
$todo->config = json_decode($todo->config);
$fnGenerateCycleCfg = function() use ($lang, $todo)
{

    $cfg = '';

    if($todo->config->type == 'day')
    {
        if(isset($todo->config->day)) $cfg .= $lang->todo->every . $todo->config->day . $lang->day;

        if(isset($todo->config->specifiedDate))
        {
            $specifiedNotes = $lang->todo->specify;
            if(isset($todo->config->cycleYear)) $specifiedNotes .= $lang->todo->everyYear;
            $specifiedNotes .= zget($lang->datepicker->monthNames, $todo->config->specify->month) . $todo->config->specify->day . $lang->todo->day;
            $cfg .= $specifiedNotes;
        }
    }
    elseif($todo->config->type == 'week')
    {
        foreach(explode(',', $todo->config->week) as $week) $cfg .= $lang->todo->dayNames[$week] . ' ';
    }
    elseif($todo->config->type == 'month')
    {
        foreach(explode(',', $todo->config->month) as $month) $cfg .= $month . ' ';
    }
    $cfg .= '<br />';
    if($todo->config->beforeDays) $cfg .= sprintf($lang->todo->lblBeforeDays, $todo->config->beforeDays);

    return $cfg;
};

/* ZIN: layout. */
$isInModal && modalHeader();

!$isInModal && detailHeader
(
    $isInModal ? to::prefix('') : '',
    to::title
    (
        entityLabel
        (
            set::entityID($todo->id),
            set::level(1),
            set::text($todo->name)
        ),
        $todo->deleted ? span(setClass('label danger circle'), $lang->todo->deleted) : null
    )
);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->todo->desc),
            set::content(nl2br($todo->desc)),
            set::useHtml(true),
            to::actions($fnGenerateTitleSuffix())
        ),
        $fromItemData,
        history(),

        /* Render float toolbar. */
        $actionList ? center(floatToolbar(set($actionList))) : null
    ),
    detailSide
    (
        /* Basic information. */
        tabs(set::collapse(true), tabPane
        (
            set::key('legendBasic'),
            set::title($lang->todo->legendBasic),
            set::active(true),
            tableData
            (
                item(set::name($lang->todo->pri),    priLabel($todo->pri, set::text($lang->todo->priList))),
                item(set::name($lang->todo->status), zget($lang->todo->statusList, $todo->status)),
                item(set::name($lang->todo->type),   zget($lang->todo->typeList, $todo->type)),

                $fromItem,

                item(set::name($lang->todo->account),     zget($users, $todo->account)),
                item(set::name($lang->todo->date),        formatTime($todo->date, DT_DATE1)),
                item(set::name($lang->todo->beginAndEnd), isset($times[$todo->begin]) ? $times[$todo->begin] : '', isset($times[$todo->end]) ?  ' ~ ' . $times[$todo->end] : ''),

                !isset($todo->assignedTo) ? null : item(set::name($lang->todo->assignedTo),   zget($users, $todo->assignedTo)),
                !isset($todo->assignedTo) ? null : item(set::name($lang->todo->assignedBy),   zget($users, $todo->assignedBy)),
                !isset($todo->assignedTo) ? null : item(set::name($lang->todo->assignedDate), formatTime($todo->assignedDate, DT_DATE1))
            )
        )),
        /* Cycle information. */
        $todo->cycle ? tabs(set::collapse(true), tabPane
        (
            set::key('cycle'),
            set::title($lang->todo->cycle),
            set::active(true),
            tableData
            (
                item(set::name($lang->todo->beginAndEnd), $todo->config->begin . " ~ " . zget($todo->config, 'end', '')),
                item(set::name($lang->todo->cycleConfig), html($fnGenerateCycleCfg()))
            )
        )) : null
    )
);

render();
