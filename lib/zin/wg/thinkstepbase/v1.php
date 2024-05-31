<?php
declare(strict_types=1);
/**
 * The thinkStepBase widget class file of zin module of ZenTaoPMS.
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

class thinkStepBase extends wg
{
    protected static array $defineProps = array(
        'title?: string',         // 标题
        'desc?: string',          // 描述
        'isRun?: bool=false',     // 是否是分析活动
        'step?: object',          // 整个步骤的对象
        'mode?: string="detail"', // detail|create|edit
        'type?: string="node"',   // node|transition/question
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildDetail(): array
    {
        global $lang, $app;
        $app->loadLang('thinkrun');
        $app->loadLang('thinkstep');

        $step    = $this->prop('step');
        $options = $step->options;
        if($options)
        {
            $questionType = $options->questionType;
            $tips         = $lang->thinkstep->$questionType;
            if($options->required && $questionType == 'checkbox')
            {
                $tips = $lang->thinkrun->requiredTitle[$questionType];
                $tips = str_replace(array('%min%', '%max%'), array($options->minCount, $options->maxCount), $tips);
            }
            if($options->required && $questionType == 'tableInput')
            {
                if($options->supportAdd)  $tips = sprintf($lang->thinkrun->tableInputTitle->notSupportAdd, count($options->fields), $options->requiredRows);
                if(!$options->supportAdd) $tips = sprintf($lang->thinkrun->tableInputTitle->supportAdd, $options->requiredRows);
            }
        }

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
                        setClass('h-full flex items-center text-fore'),
                        !empty($options->required) ? div(setClass('text-danger mr-0.5 h-5'), '*') : null,
                        $step->title,
                        !empty($tips) ? span(setClass('text-gray mx-1'), '(' . $tips . ')') : null,
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
        global $lang, $app;
        $app->loadLang('thinkstep');
        list($step, $title, $desc, $type, $mode) = $this->prop(array('step', 'title', 'desc', 'type', 'mode'));
        if($mode == 'create') $title = $lang->thinkstep->untitled . $lang->thinkstep->$type;
        if($step)
        {
            $title = $step->title;
            $desc  = htmlspecialchars_decode($step->desc);
        }

        return array(
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkstep->label->title),
                set::labelClass('required'),
                input
                (
                    setClass('is-required'),
                    set::value($title ?? ''),
                    set::name('title'),
                    set::placeholder($lang->thinkstep->placeholder->inputContent)
                )
            ),
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkstep->label->desc),
                editor
                (
                    setClass('desc'),
                    set::name('desc'),
                    set::placeholder($lang->thinkstep->placeholder->pleaseInput),
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
            'text'    => $lang->save,
            'btnType' => 'submit',
            'class'   => 'primary h-7 ring-0 submit-btn px-4',
            'style'   => array('min-width' => '0')
        ));

        return formPanel
        (
            setClass('think-step-panel py-4 px-0'),
            set::formClass('form-watched gap-3'),
            set::bodyClass('think-step-form'),
            set::actions($actions),
            formHidden('type', $this->prop('type')),
            $this->buildFormItem()
        );
    }

    protected function build(): wg|node|array
    {
        return $this->prop('mode') == 'detail' ? ($this->prop('isRun') ? div
        (
            setClass('w-full col bg-white items-center py-10 px-8 mb-4'),
            div
            (
                setStyle(array('max-width' => '878px')),
                setClass('w-full'),
                $this->buildDetail()
            )
        ) : $this->buildDetail()): $this->buildForm();
    }
}
