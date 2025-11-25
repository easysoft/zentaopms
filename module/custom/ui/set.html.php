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

data('activeMenuItem', $module);

if(!in_array($module, array('block', 'baseline'))) include 'sidebar.html.php';

$formItems   = array();
$formActions = array('submit');
$headingTips = null;
$actionWidth = 'w-1/2';
if($module == 'project' && $field == 'unitList')
{
    $checkedUnitList = array();
    foreach($lang->project->unitList as $unit => $unitName)
    {
        if(in_array($unit, $unitList)) $checkedUnitList[$unit] = $unitName;
    }

    $formItems[] = formGroup
    (
        set::label($lang->custom->project->currencySetting),
        checkList
        (
            setClass('flex flex-wrap unit-box'),
            set::name('unitList[]'),
            set::value($unitList),
            set::inline(true),
            set::items($lang->project->unitList),
            on::change('changeUnit')
        )
    );

    $formItems[] = formGroup
    (
        set::width('1/3'),
        set::label($lang->custom->project->defaultCurrency),
        set::name('defaultCurrency'),
        set::items($checkedUnitList),
        set::value($defaultCurrency),
        set::required(true)
    );
    $actionWidth = 'w-full';
}
elseif(in_array($module, array('story', 'requirement', 'epic', 'demand')) && $field == 'reviewRules')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->reviewRule),
        set::name('reviewRules'),
        set::value($reviewRule),
        set::control('radioListInline'),
        set::items($lang->custom->reviewRules)
    );

    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->superReviewers),
        set::name('superReviewers'),
        set::value($superReviewers),
        set::control(array('control' => 'picker', 'multiple' => true)),
        set::items($users)
    );
}
elseif(in_array($module, array('story', 'requirement', 'epic')) && $field == 'gradeRule')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->gradeRule),
        set::name('gradeRule'),
        set::value($gradeRule),
        set::control('radioListInline'),
        set::items($lang->custom->gradeRuleList)
    );
    $formItems[] = div
    (
        setClass('gradeRuleNotice'),
        div($lang->custom->notice->gradeRule)
    );
}
elseif(in_array($module, array('epic', 'story', 'requirement', 'demand', 'testcase')) && $field == 'review')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::label($lang->custom->storyReview),
        set::name('needReview'),
        set::value($needReview),
        set::control('radioListInline'),
        set::items($lang->custom->reviewList),
        on::change('changeReview')
    );

    if(in_array($module, array('story', 'requirement', 'epic', 'demand')))
    {
        $formItems[] = formRow
        (
            setClass($needReview ? 'hidden' : ''),
            setClass('open-review'),
            formGroup
            (
                set::label(''),
                set::name(''),
                set::value(sprintf($lang->custom->notice->forceReview, $lang->$module->common) . $lang->custom->notice->storyReviewTip),
                set::control('static')
            )
        );

        $formItems[] = formRow
        (
            setClass($needReview ? '' : 'hidden'),
            setClass('close-review'),
            formGroup
            (
                set::label(''),
                set::name(''),
                set::value(sprintf($lang->custom->notice->forceNotReview, $lang->$module->common) . $lang->custom->notice->storyReviewTip),
                set::control('static')
            )
        );

        $app->loadLang('user');
        $space = ($app->getClientLang() != 'zh-cn' and $app->getClientLang() != 'zh-tw') ? ' ' : '';
        $formItems[] = div
        (
            setClass($needReview ? 'hidden' : ''),
            setClass('open-review'),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->account),
                set::name('forceReview'),
                set::value($forceReview),
                set::items($users),
                set::control(array('control' => 'picker', 'multiple' => true))
            ),
            formRow
            (
                setClass('my-2'),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->custom->forceReview . $space . $lang->custom->role),
                    set::name('forceReviewRoles'),
                    set::value($forceReviewRoles),
                    set::items($lang->user->roleList),
                    set::control(array('control' => 'picker', 'multiple' => true))
                )
            ),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceReview . $space . $lang->custom->dept),
                set::name('forceReviewDepts'),
                set::value($forceReviewDepts),
                set::items($depts),
                set::control(array('control' => 'picker', 'multiple' => true))
            )
        );
        $formItems[] = div
        (
            setClass($needReview ? '' : 'hidden'),
            setClass('close-review'),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceNotReview . $space . $lang->custom->account),
                set::name('forceNotReview'),
                set::value($forceNotReview),
                set::items($users),
                set::control(array('control' => 'picker', 'multiple' => true))
            ),
            formRow
            (
                setClass('my-2'),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->custom->forceNotReview . $space . $lang->custom->role),
                    set::name('forceNotReviewRoles'),
                    set::value($forceNotReviewRoles),
                    set::items($lang->user->roleList),
                    set::control(array('control' => 'picker', 'multiple' => true))
                )
            ),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->custom->forceNotReview . $space . $lang->custom->dept),
                set::name('forceNotReviewDepts'),
                set::value($forceNotReviewDepts),
                set::items($depts),
                set::control(array('control' => 'picker', 'multiple' => true))
            )
        );
    }
    elseif($module == 'testcase')
    {
        jsVar('+stopSubmit', true);
        jsVar('oldNeedReview', $needReview);
        jsVar('confirmReviewCase', $lang->custom->notice->confirmReviewCase);

        $formItems[] = div
        (
            setClass($needReview ? '' : 'hidden'),
            setClass('close-review'),
            formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    set::label($lang->custom->forceNotReview),
                    set::name('forceNotReview'),
                    set::value($forceNotReview),
                    set::items($users),
                    set::control(array('control' => 'picker', 'multiple' => true))
                ),
                icon
                (
                    'help',
                    setClass('pl-4 pt-2'),
                    setData
                    (
                        array
                        (
                            'toggle' => 'tooltip',
                            'title'  => sprintf($lang->custom->notice->forceNotReview, $lang->$module->common)
                        )
                    )
                )
            )
        );
        $formItems[] = div
        (
            setClass($needReview ? 'hidden' : ''),
            setClass('open-review'),
            formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    set::label($lang->custom->forceReview),
                    set::name('forceReview'),
                    set::value($forceReview),
                    set::items($users),
                    set::control(array('control' => 'picker', 'multiple' => true))
                ),
                icon
                (
                    'help',
                    setClass('pl-4 pt-2'),
                    setData
                    (
                        array
                        (
                            'toggle' => 'tooltip',
                            'title'  => sprintf($lang->custom->notice->forceReview, $lang->$module->common)
                        )
                    )
                )
            )
        );

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
            'suffix' => $lang->day
        ))
    );

    $headingTips = div
    (
        setClass('flex flex-auto items-center pt-1'),
        icon('info text-warning mr-1'),
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
            set::control('static')
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
                'type'     => 'picker',
                'multiple' => true
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
        picker
        (
            set::name('contactField'),
            set::value($config->user->contactField),
            set::multiple(true),
            set::items($lang->user->contactFieldList)
        )
    );

    if(common::hasPriv('custom', 'restore'))
    {
        $formActions[] = array(
            'url'          => inlink('restore', "module=user&field=contactField"),
            'text'         => $lang->custom->restore,
            'class'        => 'btn-wide ajax-submit',
            'data-confirm' => $lang->custom->confirmRestore
        );
    }
}
elseif($module == 'user' && $field == 'deleted')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::labelWidth('100px'),
        set::label($lang->custom->user->fields['deleted']),
        set::name('showDeleted'),
        set::value($showDeleted),
        set::control('radioListInline'),
        set::items($lang->custom->deletedList)
    );
}
elseif($module == 'feedback' && $field == 'review')
{
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::labelWidth('100px'),
        set::label($lang->custom->feedback->fields['review']),
        set::name('needReview'),
        set::value($needReview),
        set::control('radioListInline'),
        set::items($lang->custom->reviewList),
        on::change('changeNeedReview')
    );
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::labelWidth('100px'),
        set::hidden($needReview),
        set::label($lang->custom->forceReview),
        set::name('forceReview[]'),
        set::value($forceReview),
        set::control('picker'),
        set::items($users),
        set::multiple(true),
        set::tip(sprintf($lang->custom->notice->forceReview, $lang->feedback->common)),
    );
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::labelWidth('100px'),
        set::hidden(!$needReview),
        set::label($lang->custom->forceNotReview),
        set::name('forceNotReview[]'),
        set::value($forceNotReview),
        set::control('picker'),
        set::items($users),
        set::multiple(true),
        set::tip(sprintf($lang->custom->notice->forceNotReview, $lang->feedback->common)),
    );
    $users = arrayUnion(array(' ' => $lang->feedback->deptManager), $users);
    $formItems[] = formGroup
    (
        set::width('1/2'),
        set::labelWidth('100px'),
        set::label($lang->feedback->reviewedByAB),
        set::name('reviewer'),
        set::value(empty($reviewer) ? ' ' : $reviewer),
        set::control('picker'),
        set::items($users)
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

        div
        (
            setClass('hidden'),
            setID('customFieldRow'),

            formRow
            (
                input
                (
                    set::type('hidden'),
                    set::name('systems[]'),
                    set::value('0')
                ),
                formGroup
                (
                    set::width('1/2'),
                    set::label('addRow'),
                    set::name('values[]')
                ),
                span
                (
                    btn
                    (
                        setClass('add-item btn ghost'),
                        on::click('addRow'),
                        icon('plus')
                    ),
                    btn
                    (
                        setClass('del-item btn ghost'),
                        on::click('removeRow'),
                        icon('trash')
                    )
                )
            )
        );

        foreach($fieldList as $key => $value)
        {
            $system   = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;
            $keyLabel = $key === '' ? 'NULL' : $key;
            if($key === 0) $keyLabel = '0';

            $formItems[] = formRow
            (
                input
                (
                    set::type('hidden'),
                    set::name('keys[]'),
                    set::value($key)
                ),
                input
                (
                    set::type('hidden'),
                    set::name('systems[]'),
                    set::value($system)
                ),
                formGroup
                (
                    set::width('1/2'),
                    set::label($keyLabel),
                    set::name('values[]'),
                    set::value(isset($dbFields[$key]) ? $dbFields[$key]->value : $value),
                    set::readonly(empty($key))
                ),
                $canAdd ? span(
                    btn
                    (
                        setClass('add-item btn ghost'),
                        on::click('addRow'),
                        icon('plus')
                    ),
                    btn
                    (
                        setClass('del-item btn ghost'),
                        on::click('removeRow'),
                        icon ('trash')
                    )
                ) : null
            );
        }

        $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
        $formItems[] = formRow
        (
            setClass('lang-row'),
            formGroup
            (
                set::width('1/2'),
                set::label(''),
                set::name('lang'),
                set::items($appliedTo),
                set::value($lang2Set),
                set::control('radioListInline')
            )
        );

        if(common::hasPriv('custom', 'restore'))
        {
            $formActions[] = array(
                'url'          => inlink('restore', "module={$module}&field={$field}"),
                'text'         => $lang->custom->restore,
                'class'        => 'btn-wide ajax-submit',
                'data-confirm' => $lang->custom->confirmRestore
            );
        }

        if(!$canAdd)
        {
            $headingTips = div
            (
                setClass('flex flex-auto items-center pt-1'),
                icon('info text-warning mr-1'),
                $lang->custom->notice->canNotAdd
            );
        }
    }
}

div
(
    setClass('row has-sidebar-left'),
    isset($sidebarMenu) ? $sidebarMenu : null,
    formPanel
    (
        set::formID('settingForm'),
        set::headingClass('justify-start'),
        setClass('flex-auto'),
        setClass(!empty($sidebarMenu) ? 'ml-0.5' : null),
        set::actionsClass($actionWidth),
        set::actions($formActions),
        div
        (
            setClass('flex items-center'),
            span
            (
                setClass('text-md font-bold'),
                $lang->custom->$module->fields[$field]
            ),
            span
            (
                setClass('ml-2'),
                $headingTips
            )
        ),
        $formItems
    )
);

render();
