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
jsVar('executionID', $executionID);
jsVar('tab', $this->app->tab);
if($app->tab == 'execution') jsVar('objectID', $executionID);
if($app->tab == 'project')   jsVar('objectID', $projectID);

$priList  = array_filter($lang->testcase->priList);
unset($lang->testcase->typeList['unit']);

formPanel
(
    on::change('#product', 'changeProduct'),
    on::change('#branch',  'changeBranch'),
    on::change('#story',   'changeStory'),
    on::click('#refresh',  'clickRefresh'),
    to::headingActions(icon('cog-outline')),
    set::title($lang->testcase->create),
    !empty($gobackLink) ? set::backUrl($gobackLink) : null,
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            setClass(!empty($product->shadow) ? 'hidden' : ''),
            set::label($lang->testcase->product),
            inputGroup
            (
                picker
                (
                    set::id('product'),
                    set::name('product'),
                    set::items($products),
                    set::value(!empty($case->product) ? $case->product :  $productID)
                ),
                picker
                (
                    set::className(!isset($product->type) || $product->type == 'normal' ? 'hidden' : ''),
                    set::width('100px'),
                    set::id('branch'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($branch),
                    set::emptyValue('')
                )
            )
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->module),
            inputGroup
            (
                set('id', 'moduleBox'),
                picker
                (
                    set::id('module'),
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::value($currentModuleID),
                    set::required(true),
                ),
                span
                (
                    set('class', 'input-group-addon' . (count($moduleOptionMenu) > 1 ? ' hidden' : '')),
                    a
                    (
                        set('class', 'mr-2'),
                        set('href', $this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch={$branch}")),
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
                ),
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->scene),
            inputGroup
            (
                set('id', 'sceneBox'),
                picker
                (
                    set::id('scene'),
                    set::name('scene'),
                    set::items($sceneOptionMenu),
                    set::value($currentSceneID),
                    set::required(true),
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->type),
            set::required(true),
            picker
            (
                set::id('type'),
                set::name('type'),
                set::items($lang->testcase->typeList),
                set::value($case->type),
                set::required(true)
            ),
        ),
        formGroup
        (
            setClass('ml-2 items-center'),
            checkbox
            (
                set::name('auto'),
                set::text($lang->testcase->automated),
                set::checked($case->auto == 'auto'),
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->stage),
            inputGroup
            (
                set('id', 'stageBox'),
                picker
                (
                    set::id('stage'),
                    set::name('stage[]'),
                    set::multiple(true),
                    set::items($lang->testcase->stageList),
                    set::value($case->stage)
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->lblStory),
            inputGroup
            (
                set('id', 'storyBox'),
                picker
                (
                    set::id('story'),
                    set::name('story'),
                    set::items($stories),
                    set::value($case->story)
                ),
                span
                (
                    set('class', 'input-group-addon'),
                    set('class' , !$case->story ? 'hidden' : ''),
                    a
                    (
                        set::id('preview'),
                        set::href(helper::createLink('story', 'view', "storyID={$case->story}")),
                        set('data-toggle', 'modal'),
                        $lang->preview
                    )
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
                    set::value($case->title),
                ),
                set::suffixWidth('icon'),
                to::suffix
                (
                    colorPicker
                    (
                        set::name('color'),
                        set::value($case->color),
                        set::syncColor('#title')
                    )
                )
            )
        ),
        formGroup
        (
            setClass('grow-0'),
            set::label($lang->testcase->pri),
            priPicker
            (
                width('80px'),
                set::name('pri'),
                set::items($priList),
                set::value($case->pri)
            ),
        ),
        $needReview ? formGroup
        (
            setClass('grow-0'),
            set::label($lang->testcase->isReviewed),
            picker
            (
                width('80px'),
                set::items($lang->testcase->reviewList),
                set::id('needReview'),
                set::name('needReview'),
                set::value('0'),
                set::required(true),
            ),
        ) : null
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->precondition),
            set::control(array('type' => 'textarea', 'rows' => 2)),
            set::name('precondition'),
            set::value($case->precondition)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->steps),
            stepsEditor(set::data($case->steps))
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->keywords),
            set::name('keywords'),
            set::value($case->keywords)
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

function printStepsTable()
{
    global $lang;

    $stepsTR = array();
    for($i = 1; $i <= 3; $i ++)
    {
        $stepsTR[] = h::tr
        (
            h::td(set::className('center'), $i),
            h::td
            (
                inputGroup
                (
                    textarea
                    (
                        set::rows(1),
                        set::name("steps[$i]")
                    ),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox
                        (
                            set::name("stepType[$i]"),
                            set::text($lang->testcase->automated),
                        )
                    )
                )
            ),
            h::td
            (
                textarea
                (
                    set::rows(1),
                    set::name("expects[$i]")
                )
            ),
            h::td
            (
                set::className('center'),
                btnGroup
                (
                    set::items(array(
                        array('icon' => 'plus'),
                        array('icon' => 'trash'),
                        array('icon' => 'move')
                    ))
                )
            )
        );
    }
    return h::table
    (
        set::className('w-full'),
        h::thead
        (
            h::tr
            (
                h::th($lang->testcase->stepID),
                h::th($lang->testcase->stepDesc),
                h::th($lang->testcase->stepExpect),
                h::th($lang->actions)
            )
        ),
        h::tbody
        (
            $stepsTR
        )
    );
}
