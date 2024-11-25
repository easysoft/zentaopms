<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

/**
 * 思引师填空部件类。
 * thinmory Input widget class.
 */
class thinkInput extends thinkQuestion
{
    public static function getPageCSS(): ?string
    {
        $baseCss = file_get_contents(dirname(__FILE__, 2) . DS . 'thinkstepbase' . DS . 'css' . DS . 'v1.css');
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css') . $baseCss;
    }
    protected function buildDetail(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $detailWg = parent::buildDetail();
        list($step, $required, $value, $isRun, $preViewModel) = $this->prop(array('step', 'required', 'value', 'isRun', 'preViewModel'));
        if($step)
        {
            $required = $step->options->required;
            $value    = !empty($step->answer->result) ? $step->answer->result[0] : '';
            $disabled = !empty($value) && $preViewModel;
        }

        $detailWg[] = div(
            set::title($value),
            textarea
            (
                set::rows('3'),
                set::name('result'),
                set::required($required),
                set::value($value),
                set::disabled($disabled),
                set::placeholder($lang->thinkstep->placeholder->pleaseInput)
            ),
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $required) = $this->prop(array('step', 'required'));
        if($step) $required = $step->options->required;
        $formItems[] = array(
            formHidden('options[questionType]', 'input'),
            formGroup
            (
                setClass('w-1/2 step-required'),
                setStyle(array('display' => 'flex')),
                set::label($lang->thinkstep->label->required),
                radioList
                (
                    set::name('options[required]'),
                    set::inline(true),
                    set::items($lang->thinkstep->requiredList),
                    set::value($required),
                )
            )
        );
        return $formItems;
    }
}
