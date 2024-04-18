<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstep' . DS . 'v1.php';

/**
 * 思引师填空部件类。
 * thinmory Input widget class.
 */
class thinkInput extends thinkStep
{
    protected static array $defineProps = array(
        'isRequired?: bool',                    // 是否必填
        'isRequiredName?: string="required"',   // 是否必填对应的name
    );

    private function buildRequiredControl(): wg
    {
        global $lang;
        list($isRequired, $isRequiredName) = $this->prop(array('isRequired', 'isRequiredName', 'requiredRows', 'requiredRowsName'));
        return formRow
        (
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkwizard->step->label->required),
                radioList
                (
                    set::name($isRequiredName),
                    set::inline(true),
                    set::items($lang->thinkwizard->step->requiredList),
                    set::value($isRequired ? $isRequired : 0),
                )
            ),
        );
    }

    protected function buildBody(): array
    {
        $items = parent::buildBody();
        $items[] = $this->buildRequiredControl();
        return $items;
    }
}
