<?php
declare(strict_types=1);
/**
 * The create file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        http://www.zentao.net
 */
namespace zin;

jsVar('bug',           $bug);
jsVar('moduleID',      $bug->moduleID);
jsVar('tab',           $this->app->tab);
jsVar('createRelease', $lang->release->create);
jsVar('refresh',       $lang->refreshIcon);

formPanel
(
    on::change('[name="product"]',         'changeProduct'),
    on::change('[name="branch"]',          'changeBranch'),
    on::change('[name="project"]',         'changeProject'),
    on::change('[name="execution"]',       'changeExecution'),
    on::change('[name="module"]',          'changeModule'),
    on::change('[name="region"]',          'changeRegion'),
    on::change('[name="contactListMenu"]', 'changeContact'),
    on::click('#allBuilds',                'loadAllBuilds'),
    on::click('#allUsers',                 'loadAllUsers'),
    on::click('#refreshModule',            'refreshModule'),
    on::click('#refreshMailto',            'refreshContact'),
    on::click('#refreshExecutionBuild',    'refreshExecutionBuild'),
    on::click('#refreshProductBuild',      'refreshProductBuild'),
    set::title($lang->bug->create),
    set::customFields(true),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::className($product->shadow ? 'hidden' : ''),
            set::label($lang->bug->product),
            inputGroup
            (
                picker
                (
                    set::name('product'),
                    set::items($products),
                    set::value($bug->productID)
                ),
                $product->type != 'normal' && isset($products[$bug->productID]) ? picker
                (
                    set::width('100px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($bug->branch)
                ) : null
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->project),
            inputGroup
            (
                set('id', 'projectBox'),
                picker
                (
                    set::name('project'),
                    set::items($projects),
                    set::value($defaultProject)
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->module),
            inputGroup
            (
                set('id', 'moduleBox'),
                picker
                (
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::value($bug->moduleID),
                    set::required('true')
                ),
                count($moduleOptionMenu) == 1 ? span
                (
                    set('class', 'input-group-addon'),
                    a
                    (
                        set('class', 'mr-2'),
                        set('href', $this->createLink('tree', 'browse', "rootID=$bug->productID&view=bug&currentModuleID=0&branch={$bug->branch}")),
                        set('data-toggle', 'modal'),
                        set('data-size', 'lg'),
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
            set::width('1/2'),
            set('id', 'executionBox'),
            set::label($bug->projectModel == 'kanban' ? $lang->bug->kanban : $lang->bug->execution),
            picker
            (
                set::name('execution'),
                set::items($executions),
                set::value($defaultExecution)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->openedBuild),
            set::required(true),
            inputGroup
            (
                picker
                (
                    set::multiple(true),
                    set::name('openedBuild[]'),
                    set('data-items', count($builds)),
                    set::items($builds),
                    set::value(empty($bug->buildID) ? 'trunk' : $bug->buildID)
                ),
                span
                (
                    set('id', 'buildBoxActions'),
                    set('class', 'input-group-addon'),
                    setStyle(array('display' => 'none'))
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
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->lblAssignedTo),
            inputGroup
            (
                picker
                (
                    set::name('assignedTo'),
                    set::items($productMembers),
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
        )
    ),
    (!empty($executionType) && $executionType == 'kanban') ? formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanbancard->region),
            set::control('picker'),
            set::name('region'),
            set::items($regionPairs),
            set::value($regionID)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanbancard->lane),
            set::control('picker'),
            set::name('lane'),
            set::items($lanePairs),
            set::value($laneID)
        ),
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->deadline),
            datePicker
            (
                set::id('deadline'),
                set::name('deadline'),
                set::value($bug->deadline)
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->feedbackBy),
            set::name('feedbackBy'),
            set::value(isset($bug->feedbackBy) ? $bug->feedbackBy : '')
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->notifyEmail),
            set::name('notifyEmail'),
            set::value($bug->notifyEmail)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->type),
            set::control('picker'),
            set::name('type'),
            set::items($lang->bug->typeList),
            set::value($bug->type)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->os),
            set::control('picker'),
            set::items($lang->bug->osList),
            set::name('os[]'),
            set::value($bug->os),
            set::multiple(true)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->browser),
            set::control('picker'),
            set::items($lang->bug->browserList),
            set::name('browser'),
            set::value($bug->browser)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->bug->title),
            set::required(true),
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
        formGroup
        (
            set::width('180px'),
            set::label($lang->bug->severity),
            set::control('severityPicker'),
            set::items(array_filter($lang->bug->severityList)),
            set::name('severity'),
            set::value($bug->severity)
        ),
        formGroup
        (
            set::width('180px'),
            set::label($lang->bug->pri),
            set::control('priPicker'),
            set::items(array_filter($lang->bug->priList)),
            set::name('pri'),
            set::value($bug->pri)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->bug->steps),
            editor
            (
                set::name('steps'),
                html($bug->steps)
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->story),
            inputGroup
            (
                set('id', 'storyBox'),
                picker
                (
                    set::name('story'),
                    set::items(empty($bug->stories) ? array() : $bug->stories),
                    set::value($bug->storyID)
                )
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->task),
            set::control('picker'),
            set::items(array()),
            set::name('task'),
            set::value($bug->taskID)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->lblMailto),
            inputGroup
            (
                picker
                (
                    set::multiple(true),
                    set::name('mailto[]'),
                    set::items($users),
                    set::value($bug->mailto ? str_replace(' ', '', $bug->mailto) : '')
                ),
                span
                (
                    set('id', 'contactBox'),
                    set('class', 'input-group-addon'),
                    $contactList ? setStyle(array('width' => '100px', 'padding' => '0')) : null,
                    $contactList ? picker
                    (
                        set::className('width', 'w-20'),
                        set::name('contactListMenu'),
                        set::items($contactList),
                        set::value(),
                        set::placeholder($lang->contact->common)
                    ) :
                    span
                    (
                        set('class', 'input-group-addon'),
                        a
                        (
                            set('class', 'mr-2'),
                            set('href', createLink('my', 'managecontacts', 'listID=0&mode=new')),
                            set('title', $lang->user->contacts->manage),
                            set('data-toggle', 'modal'),
                            icon('cog')
                        ),
                        a
                        (
                            set('id', 'refreshMailto'),
                            set('class', 'text-black'),
                            set('href', 'javascript:void(0)'),
                            icon('refresh')
                        )
                    )
                ),
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->keywords),
            set::name('keywords'),
            set::value($bug->keywords)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->bug->files),
            upload()
        )
    ),
    formRow
    (
        setClass('hidden'),
        input
        (
            setClass('hidden'),
            set::name('case'),
            set::value($bug->caseID),
        ),
        input
        (
            setClass('hidden'),
            set::name('caseVersion'),
            set::value($bug->version),
        ),
        input
        (
            setClass('hidden'),
            set::name('result'),
            set::value($bug->runID),
        ),
        input
        (
            setClass('hidden'),
            set::name('testtask'),
            set::value($bug->testtask),
        ),
    )
);

render();
