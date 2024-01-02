<?php
declare(strict_types=1);
/**
 * The createcase view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    // on::click('#refresh', 'clickRefresh'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->lib),
            inputGroup
            (
                picker
                (
                    set::name('lib'),
                    set::items($libraries),
                    set::required(true),
                    set::value($libID)
                )
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->module),
            inputGroup
            (
                set('id', 'moduleBox'),
                picker
                (
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::required(true),
                    set::value($currentModuleID)
                ),
                count($moduleOptionMenu) == 1 ? span
                (
                    set('class', 'input-group-addon'),
                    a
                    (
                        set('class', 'mr-2'),
                        set('href', $this->createLink('tree', 'browse', "rootID={$libID}&view=caselib&currentModuleID=0")),
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
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->type),
            set::required(true),
            picker
            (
                set::name('type'),
                set::items($lang->testcase->typeList),
                set::value($type),
                set::required(true)
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->stage),
            inputGroup
            (
                set('id', 'stageBox'),
                picker
                (
                    set::name('stage[]'),
                    set::multiple(true),
                    set::items($lang->testcase->stageList),
                    set::value($stage)
                )
            )
        )
    ),
    formRow
    (
        setClass('title-row'),
        formGroup
        (
            setClass('grow'),
            set::label($lang->testcase->title),
            set::required(true),
            inputControl
            (
                input
                (
                    set::name('title'),
                    set::value($caseTitle),
                    set::required(true)
                ),
                set::suffixWidth('icon'),
                to::suffix
                (
                    colorPicker
                    (
                        set::name('color'),
                        set::value(''),
                        set::syncColor('#title')
                    )
                )
            )
        ),
        formGroup
        (
            setClass('grow-0'),
            set::label($lang->testcase->pri),
            set::control(array('type' => 'priPicker', 'items' => array_filter($lang->testcase->priList))),
            set::name('pri'),
            set::value('3')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->precondition),
            set::control(array('type' => 'textarea', 'rows' => 2)),
            set::name('precondition'),
            set::value($precondition)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->steps),
            stepsEditor(set::data($steps))
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->keywords),
            set::name('keywords'),
            set::value($keywords)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->files),
            upload()
        )
    )
);

render();
