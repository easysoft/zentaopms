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
    protected function buildDetail(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $detailWg = parent::buildDetail();
        list($step, $required, $value) = $this->prop(array('step', 'required', 'value'));
        if($step)
        {
            $required = $step->options->required;
            $value    = !empty($step->answer->result) ? $step->answer->result[0] : '';
        }
        $detailWg[] = div(
            set::title($value),
            textarea
            (
                set::rows('3'),
                set::name('result'),
                set::required($required),
                set::value($value),
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
