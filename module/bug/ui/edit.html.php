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

jsVar('bug',                  $bug);
jsVar('confirmChangeProduct', $lang->bug->notice->confirmChangeProduct);
jsVar('moduleID',             $bug->module);
jsVar('tab',                  $this->app->tab);
jsVar('released',             $lang->build->released);
jsVar('confirmUnlinkBuild',   sprintf($lang->bug->notice->confirmUnlinkBuild, zget($resolvedBuilds, $bug->resolvedBuild)));

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($bug->id),
            set::level(1),
            set::text($lang->bug->edit),
            set::reverse(true),
            to::suffix($bug->title)
        )
    ),
);

detailBody
(
    on::change('[name="product"]',       'changeProduct'),
    on::change('[name="branch"]',        'changeBranch'),
    on::change('[name="project"]',       'changeProject'),
    on::change('[name="execution"]',     'changeExecution'),
    on::change('[name="module"]',        'changeModule'),
    on::change('[name="resolvedBuild"]', 'changeResolvedBuild'),
    on::change('[name="resolution"]',    'changeResolution'),
    on::click('#linkBug',        'linkBug'),
    on::click('#refresh',        'clickRefresh'),
    on::click('#allBuilds',      'loadAllBuilds'),
    on::click('#allUsers',       'loadAllUsers'),
    on::click('#refreshMailto',  'refreshContact'),
    set::isForm(true),
    sectionList
    (
        section
        (
            set::title($lang->bug->title),
            inputControl
            (
                input
                (
                    set::name('title'),
                    set::value($bug->title)
                ),
                set::suffixWidth('icon'),
                to::suffix
                (
                    colorPicker
                    (
                        set::name('color'),
                        set::value($bug->color),
                        set::syncColor('#title')
                    )
                )
            )
        ),
        section
        (
            set::title($lang->bug->legendSteps),
            editor
            (
                set::name('steps'),
                html($bug->steps)
            )
        ),
        section
        (
            set::title($lang->files),
            upload()
        ),
        section
        (
            set::title($lang->bug->legendComment),
            editor
            (
                set::name('comment'),
            )
        )
    ),
    history(),
    detailSide
    (
        tableData
        (
            set::title($lang->bug->legendBasicInfo),
            set::tdClass('w-64'),
            item
            (
                set::trClass($product->shadow ? 'hidden' : ''),
                set::name($lang->bug->product),
                inputGroup
                (
                    picker
                    (
                        set::name('product'),
                        set::items($products),
                        set::value($product->id)
                    ),
                    $product->type != 'normal' ? picker
                    (
                        set::width('100px'),
                        set::name('branch'),
                        set::items($branchTagOption),
                        set::value($bug->branch)
                    ) : null
                )
            ),
            item
            (
                    set::name($lang->bug->module),
                    inputGroup
                    (
                        set('id', 'moduleBox'),
                        picker
                        (
                            set::name('module'),
                            set::items($moduleOptionMenu),
                            set::value($bug->module),
                            set::required('true')
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
            item
            (
                set::trClass($product->shadow && isset($project) && empty($project->multiple) ? 'hidden' : ''),
                set::name($lang->bug->plan),
                inputGroup
                (
                    set('id', 'planBox'),
                    picker
                    (
                        set::name('plan'),
                        set::items($plans),
                        set::value($bug->plan)
                    )
                )
            ),
            item
            (
                set::name($lang->bug->fromCase),
                inputGroup
                (
                    set('id', 'caseBox'),
                    picker
                    (
                        set::name('case'),
                        set::items($cases),
                        set::value($bug->case)
                    )
                )
            ),
            item
            (
                set::name($lang->bug->type),
                picker
                (
                    set::items($lang->bug->typeList),
                    set::name('type'),
                    set::value($bug->type)
                )
            ),
            item
            (
                set::name($lang->bug->severity),
                severityPicker
                (
                    set::items($lang->bug->severityList),
                    set::name('severity'),
                    set::value($bug->severity)
                )
            ),
            item
            (
                set::name($lang->bug->pri),
                priPicker
                (
                    set::items($lang->bug->priList),
                    set::name('pri'),
                    set::value($bug->pri)
                )
            ),
            item
            (
                set::name($lang->bug->status),
                picker
                (
                    set::disabled(true),
                    set::items($lang->bug->statusList),
                    set::value($bug->status)
                )
            ),
            item
            (
                set::name($lang->bug->confirmed),
                picker
                (
                    set::disabled(true),
                    set::items($lang->bug->confirmedList),
                    set::value($bug->confirmed)
                )
            ),
            item
            (
                set::name($lang->bug->assignedTo),
                inputGroup
                (
                    picker
                    (
                        set::disabled($bug->status == 'closed' ? true : false),
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
                            $lang->bug->loadAll
                        )
                    )
                )
            ),
            item
            (
                set::name($lang->bug->deadline),
                datePicker
                (
                    set::name('deadline'),
                    set::value($bug->deadline)
                )
            ),
            item
            (
                set::name($lang->bug->feedbackBy),
                input
                (
                    set::name('feedbackBy'),
                    set::value($bug->feedbackBy)
                )
            ),
            item
            (
                set::name($lang->bug->notifyEmail),
                input
                (
                    set::name('notifyEmail'),
                    set::value($bug->notifyEmail)
                )
            ),
            item
            (
                set::name($lang->bug->os),
                picker
                (
                    set::items($lang->bug->osList),
                    set::multiple(true),
                    set::name('os[]'),
                    set::value($bug->os)
                )
            ),
            item
            (
                set::name($lang->bug->browser),
                picker
                (
                    set::items($lang->bug->browserList),
                    set::name('browser'),
                    set::value($bug->browser)
                )
            ),
            item
            (
                set::name($lang->bug->keywords),
                input
                (
                    set::name('keywords'),
                    set::value($bug->keywords)
                )
            ),
            item
            (
                set::name($lang->bug->mailto),
                inputGroup
                (
                    picker
                    (
                        set::items($users),
                        set::multiple(true),
                        set::name('mailto[]'),
                        set::value($bug->mailto)
                    ),
                    span
                    (
                        set('id', 'contactBox'),
                        set('class', 'input-group-addon'),
                        $contactList ? picker
                        (
                            set::className('width', 'w-20'),
                            set::name('contactListMenu'),
                            set::items($contactList),
                            set::value()
                        ) : a
                        (
                            set('href', createLink('my', 'managecontacts', 'listID=0&mode=new')),
                            set('title', $lang->user->contacts->manage),
                            set('data-toggle', 'modal'),
                            icon('cog'),
                        )
                    ),
                    span
                    (
                        set('class', 'input-group-addon'),
                        a
                        (
                            set('id', 'refreshMailto'),
                            set('class', 'text-black'),
                            set('href', 'javascript:void(0)'),
                            icon('refresh')
                        )
                    )
                )
            )
        ),
        tableData
        (
            set::title(!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask),
            set::tdClass('w-64'),
            item
            (
                set::name($lang->bug->project),
                inputGroup
                (
                    set('id', 'projectBox'),
                    picker
                    (
                        set::name('project'),
                        set::items($projects),
                        set::value($bug->project)
                    )
                )
            ),
            item
            (
                set::trClass($execution && !$execution->multiple ? 'hidden' : ''),
                set::name($lang->bug->execution),
                inputGroup
                (
                    set('id', 'executionBox'),
                    picker
                    (
                        set::name('execution'),
                        set::items($executions),
                        set::value($bug->execution)
                    )
                )
            ),
            item
            (
                set::name($lang->bug->story),
                inputGroup
                (
                    set('id', 'storyBox'),
                    picker
                    (
                        set::name('story'),
                        set::items($stories),
                        set::value($bug->story)
                    )
                )
            ),
            item
            (
                set::name($lang->bug->task),
                inputGroup
                (
                    set('id', 'taskBox'),
                    picker
                    (
                        set::name('task'),
                        set::items($tasks),
                        set::value($bug->task)
                    )
                )
            )
        ),
        tableData
        (
            set::title($lang->bug->legendLife),
            set::tdClass('w-64'),
            item
            (
                set::name($lang->bug->openedBy),
                picker
                (
                    set::disabled(true),
                    set::items($users),
                    set::value($bug->openedBy)
                )
            ),
            item
            (
                set::name($lang->bug->openedBuild),
                inputGroup
                (
                    set('id', 'openedBuildBox'),
                    picker
                    (
                        set::name('openedBuild[]'),
                        set::items($openedBuilds),
                        set::value($bug->openedBuild),
                        set::multiple(true)
                    ),
                    span
                    (
                        set('class', 'input-group-addon'),
                        a
                        (
                            set('id', 'allBuilds'),
                            set('href', 'javascript:;'),
                            $lang->bug->loadAll
                        )
                    )
                )
            ),
            item
            (
                set::name($lang->bug->resolvedBy),
                picker
                (
                    set::items($users),
                    set::name('resolvedBy'),
                    set::value($bug->resolvedBy)
                )
            ),
            item
            (
                set::name($lang->bug->resolvedDate),
                datePicker
                (
                    set::name('resolvedDate'),
                    set::value($bug->resolvedDate)
                )
            ),
            item
            (
                set::name($lang->bug->resolvedBuild),
                inputGroup
                (
                    set('id', 'resolvedBuildBox'),
                    picker
                    (
                        set::name('resolvedBuild'),
                        set::items($resolvedBuilds),
                        set::value($bug->resolvedBuild),
                    ),
                    span
                    (
                        set('class', 'input-group-addon'),
                        a
                        (
                            set('id', 'allBuilds'),
                            set('href', 'javascript:;'),
                            $lang->bug->loadAll
                        )
                    )
                )
            ),
            item
            (
                set::name($lang->bug->resolution),
                picker
                (
                    set::items($lang->bug->resolutionList),
                    set::name('resolution'),
                    set::value($bug->resolution)
                )
            ),
            item
            (
                set::trClass($bug->resolution != 'duplicate' ? 'hidden' : ''),
                set::name($lang->bug->duplicateBug),
                picker
                (
                    set::items($productBugs),
                    set::name('duplicateBug'),
                    set::value($bug->duplicateBug)
                )
            ),
            item
            (
                set::name($lang->bug->closedBy),
                picker
                (
                    set::items($users),
                    set::name('closedBy'),
                    set::value($bug->closedBy)
                )
            ),
            item
            (
                set::name($lang->bug->closedDate),
                datePicker
                (
                    set::name('closedDate'),
                    set::value($bug->closedDate)
                )
            ),
        ),
        tableData
        (
            set::title($lang->bug->legendMisc),
            set::tdClass('w-64'),
            item
            (
                set::name($lang->bug->relatedBug),
                inputGroup
                (
                    set('id', 'linkBugsBox'),
                    picker
                    (
                        set::multiple(true),
                        set::name('relatedBug[]'),
                        set::items(isset($bug->relatedBugTitles) ? $bug->relatedBugTitles : array()),
                    ),
                    common::hasPriv('bug', 'linkBugs') ? span
                    (
                        set('class', 'input-group-addon'),
                        a
                        (
                            set('id', 'linkBug'),
                            set('href', 'javascript:;'),
                            $lang->bug->linkBugs
                        )
                    ) : null
                )
            ),
            item
            (
                set::name($lang->bug->testtask),
                inputGroup
                (
                    set('id', 'testtaskBox'),
                    picker
                    (
                        set::name('testtask'),
                        set::items($testtasks),
                        set::value($bug->testtask)
                    )
                )
            )
        )
    )
);

render();
