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

foreach(explode(',', $config->bug->create->requiredFields) as $field)
{
    if($field and strpos($showFields, $field) === false) $showFields .= ',' . $field;
}

$showExecution        = strpos(",$showFields,", ',execution,')        !== false;
$showDeadline         = strpos(",$showFields,", ',deadline,')         !== false;
$showNoticefeedbackBy = strpos(",$showFields,", ',noticefeedbackBy,') !== false;
$showOS               = strpos(",$showFields,", ',os,')               !== false;
$showBrowser          = strpos(",$showFields,", ',browser,')          !== false;
$showSeverity         = strpos(",$showFields,", ',severity,')         !== false;
$showPri              = strpos(",$showFields,", ',pri,')              !== false;
$showStory            = strpos(",$showFields,", ',story,')            !== false;
$showTask             = strpos(",$showFields,", ',task,')             !== false;
$showMailto           = strpos(",$showFields,", ',mailto,')           !== false;
$showKeywords         = strpos(",$showFields,", ',keywords,')         !== false;

formPanel
(
    on::change('#product',              'changeProduct'),
    on::change('#branch',               'changeBranch'),
    on::change('#project',              'changeProject'),
    on::change('#execution',            'changeExecution'),
    on::change('#module',               'changeModule'),
    on::change('#region',               'changeRegion'),
    on::change('#contactListMenu',      'changeContact'),
    on::click('#allBuilds',             'loadAllBuilds'),
    on::click('#allUsers',              'loadAllUsers'),
    on::click('#refreshModule',         'refreshModule'),
    on::click('#refreshMailto',         'refreshContact'),
    on::click('#refreshExecutionBuild', 'refreshExecutionBuild'),
    on::click('#refreshProductBuild',   'refreshProductBuild'),
    set::title($lang->bug->create),
    to::headingActions(icon('cog-outline')),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::class($product->shadow ? 'hidden' : ''),
            set::label($lang->bug->product),
            inputGroup
            (
                select
                (
                    set::name('product'),
                    set::items($products),
                    set::value($bug->productID)
                ),
                $product->type != 'normal' && isset($products[$bug->productID]) ? select
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
                select
                (
                    set::name('project'),
                    set::items($projects),
                    set::value($bug->projectID)
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
                select
                (
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::value($bug->moduleID)
                ),
                count($moduleOptionMenu) == 1 ? span
                (
                    set('class', 'input-group-addon'),
                    a
                    (
                        set('class', 'mr-2'),
                        set('href', $this->createLink('tree', 'browse', "rootID=$bug->productID&view=bug&currentModuleID=0&branch={$bug->branch}")),
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
            set::class($showExecution ? '' : 'hidden'),
            set::width('1/2'),
            set::label($bug->projectModel == 'kanban' ? $lang->bug->kanban : $lang->bug->execution),
            inputGroup
            (
                set('id', 'executionBox'),
                select
                (
                    set::name('execution'),
                    set::items($executions),
                    set::value(zget($bug->execution, 'id', ''))
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->openedBuild),
            inputGroup
            (
                select
                (
                    set::multiple(true),
                    set::name('openedBuild[]'),
                    set('data-items', count($builds)),
                    set::items($builds),
                    set::value(empty($bug->buildID) ? '' : $bug->buildID)
                ),
                span
                (
                    set('id', 'buildBoxActions'),
                    set('class', 'input-group-addon'),
                    set('style', array('display' => 'none'))
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
                select
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
            set::control('select'),
            set::name('region'),
            set::items($regionPairs),
            set::value($regionID)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanbancard->lane),
            set::control('select'),
            set::name('lane'),
            set::items($lanePairs),
            set::value($laneID)
        ),
    ) : null,
    ($showDeadline || $showNoticefeedbackBy) ? formRow
    (
        $showDeadline ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->deadline),
            datePicker
            (
                set::name('deadline'),
                set::value($bug->deadline)
            )
        ) : null,
        $showNoticefeedbackBy ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->feedbackBy),
            set::name('feedbackBy'),
            set::value(isset($bug->feedbackBy) ? $bug->feedbackBy : '')
        ) : null,
    ) : null,
    formRow
    (
        $showNoticefeedbackBy ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->notifyEmail),
            set::name('notifyEmail'),
            set::value($bug->notifyEmail)
        ) : null,
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->type),
            set::control(array('type' => 'select', 'items' => $lang->bug->typeList)),
            set::name('type'),
            set::value($bug->type)
        )
    ),
    formRow
    (
        $showOS ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->os),
            set::control(array('type' => 'select', 'items' => $lang->bug->osList, 'multiple' => true)),
            set::name('os[]'),
            set::value($bug->os)
        ) : null,
        $showBrowser ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->browser),
            set::control(array('type' => 'select', 'items' => $lang->bug->browserList)),
            set::name('browser'),
            set::value($bug->browser)
        ) : null
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->bug->title),
            set::name('title'),
            set::value($bug->title)
        ),
        formGroup
        (
            set::width('180px'),
            set::label($lang->bug->severity),
            set::control(array('type' => 'select', 'items' => $lang->bug->severityList)),
            set::name('severity'),
            set::value($bug->severity)
        ),
        $showPri ? formGroup
        (
            set::width('180px'),
            set::label($lang->bug->pri),
            set::control(array('type' => 'select', 'items' => $lang->bug->priList)),
            set::name('pri'),
            set::value($bug->pri)
        ) : null
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
    ($showStory || $showTask) ? formRow
    (
        $showStory ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->story),
            inputGroup
            (
                set('id', 'storyBox'),
                select
                (
                    set::name('story'),
                    set::items((empty($bug->stories) ? '' : $bug->stories)),
                    set::value($bug->storyID)
                )
            )
        ) : null,
        $showTask ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->task),
            set::control(array('type' => 'select', 'items' => '')),
            set::name('task'),
            set::value($bug->taskID)
        ) : null
    ) : null,
    ($showMailto || $showKeywords) ? formRow
    (
        $showMailto ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->lblMailto),
            inputGroup
            (
                select
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
                    $contactList ? select
                    (
                        set::class('width', 'w-20'),
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
        ) : null,
        $showKeywords ? formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->keywords),
            set::name('keywords'),
            set::value($bug->keywords)
        ) : null
    ) : null,
    formRow
    (
        formGroup
        (
            set::label($lang->bug->files),
            upload()
        )
    )
);

render();
