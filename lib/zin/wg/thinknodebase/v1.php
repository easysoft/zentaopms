<?php
declare(strict_types=1);
/**
 * The thinkNodeBase widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * 思引师基础节点内容。
 * thinmory basic node content.
 */

class thinkNodeBase extends wg
{
    protected static array $defineProps = array(
        'title?: string',             // 标题
        'titleName?: string="title"', // 标题对应的name
        'desc?: string',              // 描述
        'descName?: string="desc"',   // 描述对应的name
        'isRun?: bool=false',         // 是否是分析活动
        'step?: object',              // 整个步骤的对象
        'mode?: string="detail"',     // detail|create|edit
        'type?: string="node"',       // node|transition/question
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildDetail(): array
    {
        global $lang, $app;
        $app->loadLang('thinkrun');

        $step    = $this->prop('step');
        $options = $step->options;
        return array
        (
            div
            (
                setClass('h-10 flex items-start justify-between mb-2'),
                $step->type == 'question' ? array
                (
                    div
                    (
                        setStyle(array('font-size' => '1.25rem')),
                        setClass('mb-3 flex items-center'),
                        !empty($options->required) ? div(setClass('text-danger mr-0.5 h-5'), '*') : null,
                        $step->title,
                        !empty($lang->thinkrun->questionType[$options->questionType]) ? span(setClass('text-gray mx-1'), '(' . $lang->thinkrun->questionType[$options->questionType] . ')') : null,
                        !empty($lang->thinkrun->error->requiredType[$options->questionType]) ? span
                        (
                            setClass('run-error-msg h-5 inline-block text-canvas text-md px-2 ml-0.5 rounded-md hidden'),
                            setStyle('background', 'var(--color-danger-600)'),
                            $lang->thinkrun->error->requiredType[$options->questionType]
                        ) : null,
                    ),
                ) : div
                (
                    setClass('text-2xl'),
                    $step->title
                ),
                $this->prop('isRun') ? null : div
                (
                    setClass('ml-2'),
                    setStyle(array('min-width' => '48px')),
                    btnGroup
                    (
                        btn
                        (
                            setClass('btn ghost text-gray w-5 h-5'),
                            set::icon('edit'),
                            set::url(createLink('thinkwizard', 'design', "wizardID={$step->wizard}&stepID={$step->id}&status=edit")),
                        ),
                        !$step->existNotNode ? btn
                        (
                            setClass('btn ghost text-gray w-5 h-5 ml-1 ajax-submit'),
                            set::icon('trash'),
                            setData('url', createLink('thinkstep', 'ajaxDelete', "stepID={$step->id}")),
                            setData('confirm',  $lang->thinkwizard->step->deleteTips[$step->type])
                        ) : btn
                        (
                            set(array(
                                'class'          => 'ghost w-5 h-5 text-gray opacity-50 ml-1',
                                'icon'           => 'trash',
                                'data-toggle'    => 'tooltip',
                                'data-title'     => $lang->thinkwizard->step->cannotDeleteNode,
                                'data-placement' => 'bottom-start',
                            ))
                        )
                    )
                )
            ),
            div
            (
                setClass('run-desc mb-2.5'),
                setStyle(array('margin-top' => '-28px')),
                section
                (
                    setClass('break-words'),
                    set::content(!empty($step->desc) ? htmlspecialchars_decode($step->desc) : ''),
                    set::useHtml(true)
                )
            )
        );
    }

    protected function buildFormItem(): array
    {
        global $lang;
        list($step, $title, $titleName, $desc, $descName) = $this->prop(array('step', 'title', 'titleName', 'desc', 'descName'));
        if($step)
        {
            $title = $step->title;
            $desc  = htmlspecialchars_decode($step->desc);
        }

        return array(
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkwizard->step->label->title),
                set::labelClass('required'),
                input
                (
                    setClass('is-required'),
                    set::value($title ?? ''),
                    set::name($titleName),
                    set::placeholder($lang->thinkwizard->step->inputContent)
                )
            ),
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkwizard->step->label->desc),
                editor
                (
                    setClass('desc'),
                    set::name($descName),
                    set::placeholder($lang->thinkwizard->step->pleaseInput),
                    html($desc ?? ''),
                    set::rows(3)
                )
            )
        );
    }

    protected function buildForm(): wg
    {
        global $lang;
        $actions = array(array(
            'text'    => $lang->thinkmodel->save,
            'btnType' => 'submit',
            'class'   => 'primary h-7 ring-0 submit-btn px-4',
            'style'   => array('min-width' => '0')
        ));

        return formPanel
        (
            setClass('think-step-panel py-4 px-8 mx-4'),
            set::formClass('form-watched gap-3'),
            set::bodyClass('think-step-form'),
            set::actions($actions),
            formHidden('type', $this->prop('type')),
            $this->buildFormItem()
        );
    }

    protected function build(): wg|node|array
    {
        $step = $this->prop('step');

        return $this->prop('mode') == 'detail' ? ($this->prop('isRun') ? div
        (
            setClass('w-full col bg-white items-center py-10 px-8 mt-6 mb-4'),
            div
            (
                setStyle($step->type == 'question' ? array('max-width' => '878px', 'min-width' => '643px') : array('max-width' => '878px')),
                $this->buildDetail()
            )
        ) : $this->buildDetail()): $this->buildForm();
    }
}
