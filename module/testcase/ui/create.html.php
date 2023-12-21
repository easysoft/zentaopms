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
jsVar('tab', $this->app->tab);
if($app->tab == 'execution') jsVar('objectID', $executionID);
if($app->tab == 'project')   jsVar('objectID', $projectID);
if($app->tab == 'qa')        jsVar('objectID', 0);

$priList  = array_filter($lang->testcase->priList);
unset($lang->testcase->typeList['unit']);

formPanel
(
    on::change('#product', 'changeProduct'),
    on::change('#branch',  'changeBranch'),
    on::change('#module',  'changeModule'),
    on::change('#story',   'changeStory'),
    on::change('#scriptFile', 'readScriptContent'),
    on::change('#scriptFile', 'hideUploadScriptBtn'),
    on::click('#refresh',  'clickRefresh'),
    on::click('#auto', 'checkScript'),
    on::click('.autoScript .file-delete', 'showUploadScriptBtn'),
    set::title($lang->testcase->create),
    set::customFields(true),
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
                    setID('product'),
                    set::name('product'),
                    set::items($products),
                    set::value(!empty($case->product) ? $case->product :  $productID)
                ),
                picker
                (
                    setID('branch'),
                    setClass(!isset($product->type) || $product->type == 'normal' ? 'hidden' : ''),
                    set::width('100px'),
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
            set::required(strpos(",{$config->testcase->create->requiredFields},", ',module,') !== false),
            inputGroup
            (
                setID('moduleBox'),
                picker
                (
                    setID('module'),
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::value($currentModuleID),
                    set::required(true)
                ),
                span
                (
                    setClass('input-group-addon' . (count($moduleOptionMenu) > 1 ? ' hidden' : '')),
                    a
                    (
                        setClass('mr-2'),
                        set('href', $this->createLink('tree', 'browse', "rootID=$productID&view=case&currentModuleID=0&branch={$branch}")),
                        setData
                        (
                            array(
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
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->scene),
            inputGroup
            (
                setID('sceneBox'),
                picker
                (
                    setID('scene'),
                    set::name('scene'),
                    set::items($sceneOptionMenu),
                    set::value($currentSceneID),
                    set::required(true)
                )
            )
        )
    ),
    formRow
    (
        setClass('items-center'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->type),
            set::required(strpos(",{$config->testcase->create->requiredFields},", ',type,') !== false),
            picker
            (
                setID('type'),
                set::name('type'),
                set::items($lang->testcase->typeList),
                set::value($case->type),
                set::required(true)
            )
        ),
        div
        (
            setClass('flex ml-2'),
            checkbox
            (
                setID('auto'),
                set::name('auto'),
                set::value('auto'),
                set::text($lang->testcase->automated)
            )
        )
    ),
    formRow
    (
        setClass('hidden autoScript'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->autoScript),
            upload
            (
                set::name('scriptFile'),
                set::accept($config->testcase->scriptAcceptFileTypes),
                set::limitCount(1)
            ),
            input
            (
                set::type('hidden'),
                set::name('script')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->testcase->stage),
            set::required(strpos(",{$config->testcase->create->requiredFields},", ',stage,') !== false),
            inputGroup
            (
                setID('stageBox'),
                picker
                (
                    setID('stage'),
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
            set::required(strpos(",{$config->testcase->create->requiredFields},", ',story,') !== false),
            inputGroup
            (
                setID('storyBox'),
                picker
                (
                    setID('story'),
                    set::name('story'),
                    set::items($stories),
                    set::value($case->story)
                ),
                span
                (
                    setClass('input-group-addon' . (!$case->story ? ' hidden' : '')),
                    a
                    (
                        setID('preview'),
                        set::href(helper::createLink('story', 'view', "storyID={$case->story}")),
                        setData(array('toggle' => 'modal')),
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
                    set::value($case->title)
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
            set::required(strpos(",{$config->testcase->create->requiredFields},", ',pri,') !== false),
            priPicker
            (
                width('80px'),
                set::name('pri'),
                set::items($priList),
                set::value($case->pri)
            )
        ),
        $needReview ? formGroup
        (
            setClass('grow-0'),
            set::label($lang->testcase->isReviewed),
            picker
            (
                width('80px'),
                set::items($lang->testcase->reviewList),
                setID('needReview'),
                set::name('needReview'),
                set::value('0'),
                set::required(true)
            )
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
            h::td(setClass('center'), $i),
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
                        setClass('input-group-addon'),
                        checkbox
                        (
                            set::name("stepType[$i]"),
                            set::text($lang->testcase->automated)
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
                setClass('center'),
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
        setClass('w-full'),
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
