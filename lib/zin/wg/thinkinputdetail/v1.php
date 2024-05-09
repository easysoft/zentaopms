<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstepdetail' . DS . 'v1.php';
/**
 * 思引师填空详情部件类。
 * thinmory input detail widget class.
 */

class thinkInputDetail extends thinkStepDetail
{
    protected static array $defineProps = array(
        'required?: bool',                      // 是否必填
        'isRequiredName?: string="required"',   // 是否必填对应的name
    );
    protected function detailInputControl()
    {
        global $lang;
        list($required, $isRequiredName) = $this->prop(array('required', 'isRequiredName'));

        return div
        (
            $required ? span(
                setClass('text-xl absolute top-6 text-danger'),
                setStyle(array('left' => '36px')),
                '*'
            ) : null,
            setStyle(array('margin' => '13px 48px 8px')),
            textarea
            (
                set::rows('3'),
                set::name($isRequiredName),
                set::required($required),
                set::placeholder($lang->thinkwizard->step->pleaseInput)
            )
        );
    }

    protected function buildBody(): array
    {
        $items   = parent::buildBody();
        $items[] = $this->detailInputControl();
        return $items;
    }
}
