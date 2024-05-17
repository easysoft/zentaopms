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
    protected static array $defineProps = array(
        'isRequiredValue?: string'              // 是否必填的值
    );

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $required, $isRequiredValue) = $this->prop(array('step', 'required', 'isRequiredValue'));
        if($step)
        {
            $required        = $step->options->required;
            $isRequiredValue = $step->answer->result;
        }

        $detailWg[] = textarea
        (
            set::rows('3'),
            set::name('result'),
            set::required($required),
            set::value($isRequiredValue),
            set::placeholder($lang->thinkwizard->step->pleaseInput)
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $required) = $this->prop(array('step', 'required', 'requiredRows', 'requiredRowsName'));
        if($step) $required = $step->required;
        $formItems[] = array(
            formHidden('options[questionType]', 'input'),
            formGroup
            (
                setClass('w-1/2'),
                setStyle(array('display' => 'flex')),
                set::label($lang->thinkstep->label->required),
                radioList
                (
                    set::name('options[required]'),
                    set::inline(true),
                    set::items($lang->thinkstep->requiredList),
                    set::value($required),
                )
            ),
            $this->children()
        );
        return $formItems;
    }
}
