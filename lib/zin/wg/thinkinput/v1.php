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
        'isRequiredName?: string="required"',   // 是否必填对应的name
        'isRequiredValue?: string'              // 是否必填的值
    );

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $required, $isRequiredName, $isRequiredValue) = $this->prop(array('step', 'required', 'isRequiredName', 'isRequiredValue'));
        
        $detailWg[] = textarea
        (
            set::rows('3'),
            set::name($isRequiredName),
            set::required($required),
            set::value($isRequiredValue),
            set::placeholder($lang->thinkwizard->step->pleaseInput)
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang;
        $formItems = parent::buildFormItem();

        list($step, $required, $isRequiredName) = $this->prop(array('step', 'required', 'isRequiredName', 'requiredRows', 'requiredRowsName'));
        if($step) $required = $step->required;
        $formItems[] = array(
            formGroup
            (
                setClass('w-1/2'),
                setStyle(array('display' => 'flex')),
                set::label($lang->thinkwizard->step->label->required),
                radioList
                (
                    set::name($isRequiredName),
                    set::inline(true),
                    set::items($lang->thinkwizard->step->requiredList),
                    set::value($required),
                )
            ),
            $this->children()
        );
        return $formItems;
    }
}
