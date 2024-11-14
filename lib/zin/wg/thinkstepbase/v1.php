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
        'title?: string',          // 标题
        'desc?: string',           // 描述
        'isRun?: bool=false',      // 是否是分析活动
        'step?: object',           // 整个步骤的对象
        'mode?: string="detail"',  // detail|create|edit
        'type?: string="node"',    // node|transition/question
        'quoteQuestions?: array'.  // 引用的问题
        'quotedQuestions?: attay', // 被引用的问题
        'isResult?: bool=false',   // 是否是结果页
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
        list($step, $mode) = $this->prop(array('step', 'mode'));
        if($mode != 'detail') return array();

        $options = $step->options;
        if($step->type == 'questions')
        {
            $questionType = $options->questionType;
            $tips         = $lang->thinkstep->$questionType;
            $setOption    = empty($options->setOption) || $options->setOption == 0;
            if(!empty($options->required) && $questionType == 'checkbox' && $setOption)
            {
                $tips = $lang->thinkrun->requiredTitle[$questionType];
                $tips = str_replace(array('%min%', '%max%'), array($options->minCount, $options->maxCount), $tips);
            }
            if(!empty($options->required) && $questionType == 'tableInput')
            {
                if($options->supportAdd)  $tips = sprintf($lang->thinkrun->tableInputTitle->notSupportAdd, count($options->fields), $options->requiredRows);
                if(!$options->supportAdd) $tips = sprintf($lang->thinkrun->tableInputTitle->supportAdd, $options->requiredRows);
            }
        }

        $requiredSymbal = !empty($options->required) ? span(setClass('text-danger mr-0.5 h-5'), '*') : null;
        $questionTips   = !empty($tips) ? span(setClass('text-gray mx-1'), '(' . $tips . ')') : null;
        $errorText      = isset($options->questionType) && !empty($lang->thinkrun->error->requiredType[$options->questionType]) ? span
        (
            setClass('run-error-msg h-5 inline-block text-canvas text-md px-2 ml-0.5 rounded-md hidden'),
            setStyle('background', 'var(--color-danger-600)'),
            $lang->thinkrun->error->requiredType[$options->questionType]
        ) : null;

        return array
        (
            div
            (
                setClass('flex items-start justify-between mb-2 step-title'),
                $step->type == 'question' ? div
                (
                    setClass('h-full text-fore text-lg'),
                    $requiredSymbal,
                    isset($step->index) ? $step->index . '. ' : '',
                    $step->title,
                    $questionTips,
                    $errorText
                ) : div
                (
                    setClass('text-2xl'),
                    $step->title
                )
            ),
            div
            (
                setClass('run-desc mb-3.5'),
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
    protected function buildDetailTip(): array
    {
        global $lang, $app, $config;
        $app->loadLang('thinkstep');
        $app->loadLang('thinkrun');
        list($quoteQuestions, $quotedQuestions, $step, $isRun) = $this->prop(array('quoteQuestions', 'quotedQuestions', 'step', 'isRun'));

        if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);

        $questionType = !empty($step) && $step->type == 'questions' ? $step->options->questionType : '';
        $isCheckBox   = !empty($step) && $step->type == 'question' && in_array($questionType, $config->thinkstep->quoteQuestionType);
        $isQuoteItem  = $isCheckBox && !empty($step->options->setOption) && $step->options->setOption == 1;
        $detailTip    = array();
        $quotedItems  = array();
        if(!empty($quotedQuestions))
        {
            foreach($quotedQuestions as $item)
            {
                $quotedItems[] = a
               (
                   setClass('block text-primary-500 leading-relaxed'),
                   set::href(createLink('thinkstep', 'view', "marketID=0&&wizardID=$item->wizard&&stepID=$item->id&&from=detail")),
                   setData('toggle', 'modal'),
                   setData('dismiss', 'modal'),
                   setData('size', 'sm'),
                   $item->index . '. ' . $item->title
                );
            }
        }

        if($isQuoteItem && !empty($quoteQuestions))
        {
            foreach($quoteQuestions as $item)
            {
                if(!$isRun && $item->id == $step->options->quoteTitle) $sourceQuestion = $item;
                if($isRun && $item->origin == $step->options->quoteTitle) $sourceQuestion = $item;
            }
        }
        if($isRun && (!empty($quotedQuestions) || !empty($sourceQuestion)))
        {
            $tipType = $lang->thinkstep->label->option;
            if(!empty($sourceQuestion))
            {
                $sourceQuestionType = is_string($sourceQuestion->options) ? json_decode($sourceQuestion->options)->questionType : $sourceQuestion->options->questionType;
                if($sourceQuestionType == 'multicolumn') $tipType = sprintf($lang->thinkstep->entry, $step->options->selectColumn);
            }
            $detailTip[] = div
            (
                setClass('bg-primary-50 text-gray p-2 mt-3 leading-normal'),
                !empty($quotedQuestions) ? div
                (
                    setClass('flex items-center'),
                    icon(setClass('font text-warning mr-1'), 'about'),
                    $lang->thinkrun->tips->quotedTip
                ) : null,
                !empty($sourceQuestion) ? div
                (
                    setClass('ml-4 pl-0.5'),
                    sprintf($lang->thinkstep->tips->checkbox, $lang->thinkstep->tips->options[$questionType], ($sourceQuestion->index . '. ' . $sourceQuestion->title)),
                    $tipType
                ) : null
            );
        }
        if(!$isRun)
        {
            $detailTip[] = array
            (
                !empty($sourceQuestion) ? array(
                    div
                    (
                        setClass('bg-primary-50 leading-normal p-2 mt-3'),
                        div(sprintf($lang->thinkstep->tips->sourceofOptions, $lang->thinkstep->tips->options[$questionType])),
                        a
                        (
                            setClass('block text-primary-500 leading-relaxed'),
                            set::href(createLink('thinkstep', 'view', "marketID=0&&wizardID=$sourceQuestion->wizard&&stepID=$sourceQuestion->id&&from=detail")),
                            setData('toggle', 'modal'),
                            setData('dismiss', 'modal'),
                            setData('size', 'sm'),
                            $sourceQuestion->index . '. ' . $sourceQuestion->title
                        )
                    ),
                    (!empty($questionType) && $questionType == 'multicolumn') ? div(setClass('text-sm text-gray-400 leading-loose mt-2'), $lang->thinkstep->tips->multicolumn) : null
                ): null,
                !empty($quotedQuestions) ? div
                (
                    setClass('bg-primary-50 leading-normal p-2 mt-3'),
                    div(sprintf($lang->thinkstep->tips->optionsAreReferenced, $questionType == 'multicolumn' ? $lang->thinkstep->inputItem : $lang->thinkstep->label->option)),
                    $quotedItems
                ) : null
            );
        }
        return $detailTip;
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
        list($isResult, $step, $isRun) = $this->prop(array('isResult', 'step', 'isRun'));
        $questionType = '';
        if($isResult && isset($step->options->questionType)) $questionType = $step->options->questionType;

        $content = $isRun ? div
        (
            setClass('w-full col bg-white items-center pt-4 pb-10 px-8 mb-4'),
            div
            (
                setStyle(array('max-width' => '878px')),
                setClass('w-full'),
                $this->buildDetail(),
                $this->buildDetailTip()
            )
        ) : div
        (
            $questionType == 'tableInput' ? setStyle(array('min-width' => '220px')) : null,
            $this->buildDetail(),
            $this->buildDetailTip()
        );
        return $this->prop('mode') == 'detail' ? $content : $this->buildForm();
    }
}
