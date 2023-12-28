<?php
declare(strict_types=1);
/**
 * The create file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('bug',                   $bug);
jsVar('moduleID',              $bug->moduleID);
jsVar('tab',                   $this->app->tab);
jsVar('createRelease',         $lang->release->create);
jsVar('refresh',               $lang->refreshIcon);
jsVar('projectExecutionPairs', $projectExecutionPairs);

formPanel
(
    on::change('[name="product"]',      'changeProduct'),
    on::change('[name="branch"]',       'changeBranch'),
    on::change('[name="project"]',      'changeProject'),
    on::change('[name="execution"]',    'changeExecution'),
    on::change('[name="module"]',       'changeModule'),
    on::change('[name="region"]',       'changeRegion'),
    on::click('#allBuilds',             'loadAllBuilds'),
    on::click('#allUsers',              'loadAllUsers'),
    on::click('#refreshModule',         'refreshModule'),
    on::click('#refreshExecutionBuild', 'refreshExecutionBuild'),
    on::click('#refreshProductBuild',   'refreshProductBuild'),
    set::title($lang->bug->create),
    set::customFields(true),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            setClass($product->shadow ? 'hidden' : ''),
            set::label($lang->bug->product),
            inputGroup
            (
                picker
                (
                    set::name('product'),
                    set::items($products),
                    set::value($bug->productID),
                    set::required(true)
                ),
                $product->type != 'normal' && isset($products[$bug->productID]) ? picker
                (
                    setID('branchPicker'),
                    set::boxClass('flex-none'),
                    set::width('100px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($bug->branch),
                    set::required(true)
                ) : null
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->project),
            set::required(strpos(",{$config->bug->create->requiredFields},", ',project,') !== false),
            inputGroup
            (
                setID('projectBox'),
                picker
                (
                    set::name('project'),
                    set::items($projects),
                    set::value($projectID)
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
            set::required(strpos(",{$config->bug->create->requiredFields},", ',module,') !== false),
            inputGroup
            (
                setID('moduleBox'),
                picker
                (
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::value($bug->moduleID),
                    set::required('true')
                ),
                count($moduleOptionMenu) == 1 ? span
                (
                    setClass('input-group-addon'),
                    a
                    (
                        setClass('mr-2'),
                        set('href', $this->createLink('tree', 'browse', "rootID=$bug->productID&view=bug&currentModuleID=0&branch={$bug->branch}")),
                        setData
                        (
                            array
                            (
                                'toggle' => 'modal',
                                'size'   => 'lg'
                            )
                        ),
                        $lang->tree->manage
                    ),
                    a
                    (
                        setID('refreshModule'),
                        setClass('text-black'),
                        set('href', 'javascript:void(0)'),
                        icon('refresh')
                    )
                ) : null
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setID('executionBox'),
            set::label($bug->projectModel == 'kanban' ? $lang->bug->kanban : $lang->bug->execution),
            set::required(strpos(",{$config->bug->create->requiredFields},", ',execution,') !== false),
            picker
            (
                set::name('execution'),
                set::items($executions),
                set::value($executionID)
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
                    set::items($builds),
                    set::value(empty($bug->buildID) ? 'trunk' : $bug->buildID),
                    setData(array('items' => count($builds)))
                ),
                span
                (
                    setID('buildBoxActions'),
                    setClass('btn-group'),
                    setStyle(array('display' => 'none'))
                ),
                span
                (
                    setClass('input-group-btn'),
                    a
                    (
                        setClass('btn'),
                        setID('allBuilds'),
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
                    setClass('input-group-btn'),
                    a
                    (
                        setClass('btn'),
                        setID('allUsers'),
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
            set::required(strpos(",{$config->bug->create->requiredFields},", ',deadline,') !== false),
            datePicker
            (
                setID('deadline'),
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
        )
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
            set::required(strpos(",{$config->bug->create->requiredFields},", ',deadline,') !== false),
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
            set::required(strpos(",{$config->bug->create->requiredFields},", ',deadline,') !== false),
            set::control('picker'),
            set::items($lang->bug->browserList),
            set::name('browser[]'),
            set::value($bug->browser),
            set::multiple(true)
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
            set::items($lang->bug->severityList),
            set::name('severity'),
            set::value($bug->severity),
            set::required(true)
        ),
        formGroup
        (
            set::width('180px'),
            set::label($lang->bug->pri),
            set::control('priPicker'),
            set::items($lang->bug->priList),
            set::name('pri'),
            set::value($bug->pri),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->bug->steps),
            set::required(strpos(",{$this->config->bug->create->requiredFields},", ",steps,") !== false),
            editor
            (
                set::name('steps'),
                html($bug->steps)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->story),
            inputGroup
            (
                setID('storyBox'),
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
            mailto(set::items($users), set::value($bug->mailto))
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
            set::value($bug->caseID)
        ),
        input
        (
            setClass('hidden'),
            set::name('caseVersion'),
            set::value($bug->version)
        ),
        input
        (
            setClass('hidden'),
            set::name('result'),
            set::value($bug->runID)
        ),
        input
        (
            setClass('hidden'),
            set::name('testtask'),
            set::value($bug->testtask)
        )
    )
);

render();
