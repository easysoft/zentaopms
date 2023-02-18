<?php
declare(strict_types=1);
/**
 * The edit file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        http://www.zentao.net
 */
namespace zin;

jsVar('confirmChangeProduct', $lang->bug->confirmChangeProduct);
jsVar('oldProjectID',         $bug->project);
jsVar('oldProductID',         $product->id);
jsVar('oldStoryID',           $bug->story);
jsVar('oldTaskID',            $bug->task);
jsVar('oldOpenedBuild',       $bug->openedBuild);
jsVar('oldResolvedBuild',     $bug->resolvedBuild);
jsVar('moduleID',             $moduleID);
jsVar('tab',                  $this->app->tab);
jsVar('bugBranch',            $bug->branch);
jsVar('isClosedBug',          $bug->status == 'closed');
jsVar('released',             $lang->build->released);
if($this->app->tab == 'execution') jsVar('objectID', $bug->execution);
if($this->app->tab == 'project')   jsVar('objectID', $bug->project);

panel
(
    set::title($bug->title),
    form
    (
        on::change('#product',   'changeProduct'),
        on::change('#branch',    'changeBranch'),
        on::change('#project',   'changeProject'),
        on::change('#execution', 'changeExecution'),
        on::change('#module',    'changeModule'),
        on::click('#refresh',    'clickRefresh'),
        on::click('#allBuilds',  'loadAllBuilds'),
        on::click('#allUsers',   'loadAllUsers'),
        div
        (
            set::class('flex'),
            cell
            (
                set::width('70%'),
                formGroup
                (
                    set::label($lang->bug->title),
                    set::name('title'),
                    set::value($bug->title)
                ),
                formGroup
                (
                    set::label($lang->bug->legendSteps),
                    editor
                    (
                        set::name('steps'),
                        set::value(htmlSpecialString($bug->steps))
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->fromCase),
                    set::control(array('type' => 'select', 'items' => $cases)),
                    set::name('case'),
                    set::value($bug->case)
                ),
                formGroup
                (
                    set::label($lang->bug->files),
                    set::name('files[]'),
                    set::control('file')
                ),
            ),
            cell
            (
                set::width('30%'),
                formGroup
                (
                    set::label($lang->bug->product),
                    inputGroup
                    (
                        select
                        (
                            set::name('product'),
                            set::items($products),
                            set::value($product->id)
                        ),
                        $product->type != 'normal' ? select
                        (
                            set::width('100px'),
                            set::name('branch'),
                            set::items($branchTagOption),
                            set::value($bug->branch)
                        ) : null
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->module),
                    inputGroup
                    (
                        set('id', 'moduleBox'),
                        select
                        (
                            set::name('module'),
                            set::items($moduleOptionMenu),
                            set::value($currentModuleID)
                        ),
                        count($moduleOptionMenu) == 1 ? span
                        (
                            set('class', 'input-group-addon'),
                            a
                            (
                                set('class', 'mr-2'),
                                set('href', $this->createLink('tree', 'browse', "rootID={$product->ID}&view=bug&currentModuleID=0&branch={$bug->branch}")),
                                set('data-toggle', 'modal'),
                                $lang->tree->manage
                            ),
                            a
                            (
                                set('id', 'refreshModule'),
                                set('class', 'text-black'),
                                set('href', 'javascript:void(0)'),
                                icon('refresh')
                            )
                        ) : null
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->productplan),
                    inputGroup
                    (
                        set('id', 'planBox'),
                        select
                        (
                            set::name('plan'),
                            set::items($plans),
                            set::value($bug->plan)
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->type),
                    set::control(array('type' => 'select', 'items' => $lang->bug->typeList)),
                    set::name('type'),
                    set::value($bug->type)
                ),
                formGroup
                (
                    set::label($lang->bug->severity),
                    set::control(array('type' => 'select', 'items' => $lang->bug->severityList)),
                    set::name('severity'),
                    set::value($bug->severity)
                ),
                formGroup
                (
                    set::label($lang->bug->pri),
                    set::control(array('type' => 'select', 'items' => $lang->bug->priList)),
                    set::name('pri'),
                    set::value($bug->pri)
                ),
                formGroup
                (
                    set::disabled(true),
                    set::label($lang->bug->status),
                    set::control(array('type' => 'select', 'items' => $lang->bug->statusList)),
                    set::value($bug->status)
                ),
                formGroup
                (
                    set::disabled(true),
                    set::label($lang->bug->confirmed),
                    set::control(array('type' => 'select', 'items' => $lang->bug->confirmedList)),
                    set::value($bug->confirmed)
                ),
                formGroup
                (
                    set::label($lang->bug->assignedTo),
                    inputGroup
                    (
                        select
                        (
                            set::name('assignedTo'),
                            set::items($assignedToList),
                            set::value($bug->assignedTo)
                        ),
                        span
                        (
                            set('class', 'input-group-addon'),
                            a
                            (
                                set('id', 'allUsers'),
                                set('href', 'javascript:;'),
                                $lang->bug->allUsers
                            )
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->deadline),
                    datePicker
                    (
                        set::name('deadline'),
                        set::value($bug->deadline)
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->feedbackBy),
                    set::name('feedbackBy'),
                    set::value($bug->feedbackBy)
                ),
                formGroup
                (
                    set::label($lang->bug->notifyEmail),
                    set::name('notifyEmail'),
                    set::value($bug->notifyEmail)
                ),
                formGroup
                (
                    set::label($lang->bug->os),
                    set::control(array('type' => 'select', 'items' => $lang->bug->osList, /*'multiple' => true*/)),
                    set::name('os'),
                    set::value($bug->os)
                ),
                formGroup
                (
                    set::label($lang->bug->browser),
                    set::control(array('type' => 'select', 'items' => $lang->bug->browserList)),
                    set::name('browser'),
                    set::value($bug->browser)
                ),
                formGroup
                (
                    set::label($lang->bug->keywords),
                    set::name('keywords'),
                    set::value($bug->keywords)
                ),
                formGroup
                (
                    set::label($lang->bug->mailto),
                    set::control(array('type' => 'select', 'items' => $users, /*('multiple' => true*/)),
                    set::name('mailto'),
                    set::value($bug->mailto)
                ),
                formGroup
                (
                    set::label($lang->bug->project),
                    inputGroup
                    (
                        set('id', 'projectBox'),
                        select
                        (
                            set::name('project'),
                            set::items($projects),
                            set::value($bug->project)
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->execution),
                    inputGroup
                    (
                        set('id', 'executionBox'),
                        select
                        (
                            set::name('execution'),
                            set::items($executions),
                            set::value($bug->execution)
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->story),
                    inputGroup
                    (
                        set('id', 'storyBox'),
                        select
                        (
                            set::name('story'),
                            set::items($stories),
                            set::value($bug->story)
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->task),
                    inputGroup
                    (
                        set('id', 'taskBox'),
                        select
                        (
                            set::name('task'),
                            set::items($tasks),
                            set::value($bug->task)
                        )
                    )
                ),
                formGroup
                (
                    set::disabled(true),
                    set::label($lang->bug->openedBy),
                    set::control(array('type' => 'select', 'items' => $users)),
                    set::value($bug->openedBy)
                ),
                formGroup
                (
                    set::label($lang->bug->openedBuild),
                    inputGroup
                    (
                        set('id', 'openedBuildBox'),
                        select
                        (
                            //set::multiple(true),
                            set::name('openedBuild'),
                            set::items($openedBuilds),
                            set::value($bug->openedBuild)
                        ),
                        span
                        (
                            set('class', 'input-group-addon'),
                            a
                            (
                                set('id', 'allBuilds'),
                                set('href', 'javascript:;'),
                                $lang->bug->allBuilds
                            )
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->resolvedBy),
                    set::control(array('type' => 'select', 'items' => $users)),
                    set::name('resolvedBy'),
                    set::value($bug->resolvedBy)
                ),
                formGroup
                (
                    set::label($lang->bug->resolvedDate),
                    datePicker
                    (
                        set::name('resolvedDate'),
                        set::value($bug->resolvedDate)
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->resolvedBuild),
                    inputGroup
                    (
                        set('id', 'resolvedBuildBox'),
                        select
                        (
                            set::name('resolvedBuild'),
                            set::items($resolvedBuilds),
                            set::value($bug->resolvedBuild)
                        ),
                        span
                        (
                            set('class', 'input-group-addon'),
                            a
                            (
                                set('id', 'allBuilds'),
                                set('href', 'javascript:;'),
                                $lang->bug->allBuilds
                            )
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->resolution),
                    set::control(array('type' => 'select', 'items' => $lang->bug->resolutionList)),
                    set::name('resolution'),
                    set::value($bug->resolution)
                ),
                formGroup
                (
                    set::label($lang->bug->closedBy),
                    set::control(array('type' => 'select', 'items' => $users)),
                    set::name('closedBy'),
                    set::value($bug->closedBy)
                ),
                formGroup
                (
                    set::label($lang->bug->closedDate),
                    datePicker
                    (
                        set::name('closedDate'),
                        set::value($bug->closedDate)
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->linkBug),
                    inputGroup
                    (
                        set('id', 'linkBugsBox'),
                        select
                        (
                            //set::multiple(true),
                            set::name('resolvedBuild'),
                            set::items($bug->linkBugTitles),
                            set::value()
                        ),
                        span
                        (
                            set('class', 'input-group-addon'),
                            a
                            (
                                set('id', 'linkBug'),
                                set('href', 'javascript:;'),
                                $lang->bug->linkBugs
                            )
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->testtask),
                    inputGroup
                    (
                        set('id', 'testtaskBox'),
                        select
                        (
                            set::name('testtask'),
                            set::items($testtasks),
                            set::value($bug->testtask)
                        )
                    )
                ),
                formGroup
                (
                    set::label($lang->bug->fromCase),
                    inputGroup
                    (
                        set('id', 'caseBox'),
                        select
                        (
                            set::name('case'),
                            set::items($cases),
                            set::value($bug->case)
                        )
                    )
                ),
            )
        )
    )
);
render();
