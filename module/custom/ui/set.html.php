<?php
declare(strict_types=1);
/**
 * The set view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

if(!in_array($module, array('block', 'baseline'))) include 'sidebar.html.php';

$formItems   = array();
$formActions = array('submit');
$headingTips = null;
$actionWidth = 'w-1/2';
if($module == 'project' && $field == 'unitList')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->project->currencySetting),
        set::name('unitList'),
        set::value($unitList),
        set::control('checkListInline'),
        set::items($lang->project->unitList),
    );

    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->project->defaultCurrency),
        set::control('select'),
        set::name('defaultCurrency'),
        set::value($lang->project->unitList),
        set::items($defaultCurrency)
    );
}
elseif($module == 'story' && $field == 'reviewRules')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->reviewRule),
        set::name('reviewRules'),
        set::value($needReview),
        set::control('radioListInline'),
        set::items($lang->custom->reviewRules),
    );

    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->superReviewers),
        set::name('superReviewers'),
        set::value($superReviewers),
        set::control(array('type' => 'select', 'multiple' => true)),
        set::items($users),
    );
}
elseif(($module == 'story' || $module == 'testcase') && $field == 'review')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->storyReview),
        set::name('needReview'),
        set::value($needReview),
        set::control('radioListInline'),
        set::items($lang->custom->reviewList),
    );

    if($module == 'story')
    {
        $formItems[] = formGroup
        (
            set::width('1/2'),
            setClass($needReview ? 'hidden' : ''),
            set::label(''),
            set::name(''),
            set::value(sprintf($lang->custom->notice->forceReview, $lang->$module->common) . $lang->custom->notice->storyReviewTip),
            set::control('static'),
        );

        $formItems[] = formGroup
        (
            set::width('1/2'),
            setClass($needReview ? '' : 'hidden'),
            set::label(''),
            set::name(''),
            set::value(sprintf($lang->custom->notice->forceNotReview, $lang->$module->common) . $lang->custom->notice->storyReviewTip),
            set::control('static'),
        );

        $space = ($app->getClientLang() != 'zh-cn' and $app->getClientLang() != 'zh-tw') ? ' ' : '';
        if($needReview)
        {
            $formItems[] = formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->account),
                set::name('forceNotReview'),
                set::value($forceNotReview),
                set::items($users),
                set::control(array('type' => 'select', 'multiple' => true)),
            );

            $formItems[] = formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->role),
                set::name('forceNotReviewRoles'),
                set::value($forceNotReviewRoles),
                set::items($lang->user->roleList),
                set::control(array('type' => 'select', 'multiple' => true)),
            );

            $formItems[] = formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->dept),
                set::name('forceNotReviewDepts'),
                set::value($forceNotReviewDepts),
                set::items($depts),
                set::control(array('type' => 'select', 'multiple' => true)),
            );
        }
        else
        {
            $formItems[] = formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->account),
                set::name('forceReview'),
                set::value($forceReview),
                set::items($users),
                set::control(array('type' => 'select', 'multiple' => true)),
            );

            $formItems[] = formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->role),
                set::name('forceReviewRoles'),
                set::value($forceReviewRoles),
                set::items($lang->user->roleList),
                set::control(array('type' => 'select', 'multiple' => true)),
            );

            $formItems[] = formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->dept),
                set::name('forceReviewDepts'),
                set::value($forceReviewDepts),
                set::items($depts),
                set::control(array('type' => 'select', 'multiple' => true)),
            );
        }
    }
    elseif($module == 'testcase')
    {
        if($needReview)
        {
            $formItems[] = formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    set::label($lang->custom->forceReview . $space . $lang->custom->forceNotReview),
                    set::name('forceNotReview'),
                    set::value($forceNotReview),
                    set::items($users),
                    set::control(array('type' => 'select', 'multiple' => true)),
                ),
                span(
                    setClass('pl-4'),
                    sprintf($lang->custom->notice->forceReview, $lang->$module->common)
                )
            );
        }
        else
        {
            $formItems[] = formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    set::label($lang->custom->forceReview . $space . $lang->custom->forceReview),
                    set::name('forceReview'),
                    set::value($forceReview),
                    set::items($users),
                    set::control(array('type' => 'select', 'multiple' => true)),
                ),
                span(
                    setClass('pl-4'),
                    sprintf($lang->custom->notice->forceReview, $lang->$module->common)
                )
            );
        }

        $actionWidth = 'w-2/3';
    }
}
elseif($module == 'bug' && $field == 'longlife')
{
    $formItems[] = formGroup
    (
        set::label($lang->custom->bug->fields['longlife']),
        set::name('longlife'),
        set::value($longlife),
        set::width('1/3'),
        set::control(array(
            'type'   => 'inputControl',
            'suffix' => $lang->day,
        ))
    );

    $headingTips = div
    (
        setClass('flex-auto pt-1'),
        icon('info text-warning mr-2'),
        $lang->custom->notice->longlife
    );

    $actionWidth = 'w-1/3';
}
elseif($module == 'block' && $field == 'closed')
{
    if(empty($blockPairs))
    {
        $formItems[] = formGroup
        (
            set::width('1/2'),
            set::label($lang->custom->block->fields['closed']),
            set::name('closed'),
            set::value($lang->custom->notice->noClosedBlock),
            set::control('static'),
        );
        $formActions = array();
    }
    else
    {
        $formItems[] = formGroup
        (
            set::width('1/2'),
            set::label($lang->custom->block->fields['closed']),
            set::name('closed'),
            set::value($closedBlock),
            set::control(array(
                'type'     => 'select',
                'multiple' => true,
            )),
            set::items($blockPairs)
        );
    }
}
elseif($module == 'user' && $field == 'contactField')
{
    $this->app->loadConfig('user');
    $this->app->loadLang('user');

    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->user->fields['contactField']),
        set::name('contactField'),
        set::value($config->user->contactField),
        set::control(array(
            'type'     => 'select',
            'multiple' => true,
        )),
        set::items($lang->user->contactFieldList)
    );

    if(common::hasPriv('custom', 'restore'))
    {
        $formActions[] = array(
            'url'          => inlink('restore', "module=user&field=contactField&confirm=yes"),
            'text'         => $lang->custom->restore,
            'class'        => 'btn-wide ajax-submit',
            'data-confirm' => $lang->custom->confirmRestore,
        );
    }
}
elseif($module == 'user' && $field == 'deleted')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->user->fields['deleted']),
        set::name('showDeleted'),
        set::value($showDeleted),
        set::control('radioListInline'),
        set::items($lang->custom->deletedList)
    );
}
else
{
    if(!empty($fieldList) && is_array($fieldList))
    {
        $formItems[] = formGroup
        (
            set::width('1/2'),
            set::label($lang->custom->key),
            set::name(''),
            set::value($lang->custom->value),
            set::control('static')
        );

        foreach($fieldList as $key => $value)
        {
            $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;

            $formItems[] = formRow
            (
                formGroup
                (
                    set::width('1/2'),
                    set::label($key === '' ? 'NULL' : $key),
                    set::name('values'),
                    set::value(isset($dbFields[$key]) ? $dbFields[$key]->value : $value),
                    set::readonly(empty($key))
                ),
                $canAdd ? span(
                    setClass('pl-4'),
                    icon
                    (
                        setClass('add-item'),
                        'plus',
                    ),
                    icon
                    (
                        setClass('del-item ml-2'),
                        'trash',
                    ),
                ) : null,
            );
        }

        $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
        $formItems[] = formGroup
        (
            set::width('1/2'),
            set::label(''),
            set::name('lang'),
            set::items($appliedTo),
            set::value($lang2Set),
            set::control('radioListInline'),
        );

        if(common::hasPriv('custom', 'restore'))
        {
            $formActions[] = array(
                'url'          => inlink('restore', "module={$module}&field={$field}&confirm=yes"),
                'text'         => $lang->custom->restore,
                'class'        => 'btn-wide ajax-submit',
                'data-confirm' => $lang->custom->confirmRestore,
            );
        }

        if(!$canAdd)
        {
            $headingTips = div
            (
                setClass('flex-auto pt-1'),
                icon('info text-warning mr-2'),
                $lang->custom->notice->canNotAdd
            );
        }
    }
}

div
(
    setClass('flex'),
    $sidebarMenu,
    formPanel
    (
        set::headingClass('justify-start'),
        to::headingActions($headingTips),
        setClass('flex-auto'),
        setClass($sidebarMenu ? 'ml-4' : null),
        set::actionsClass($actionWidth),
        set::title($lang->custom->$module->fields[$field]),
        set::actions($formActions),
        $formItems,
    )
);

render();
