<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstepdetail' . DS . 'v1.php';
/**
 * 思引师填空详情部件类。
 * thinmory input detail widget class.
 */

class thinkInputDetail extends wg
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
                setClass('text-xl absolute top-6 text-danger left-5'),
                '*'
            ) : null,
            setClass('mt-3 mb-2'),
            textarea
            (
                set::rows('3'),
                set::name($isRequiredName),
                set::required($required),
                set::placeholder($lang->thinkwizard->step->pleaseInput)
            )
        );
    }

    protected function build()
    {
        return $this->detailInputControl();
    }
}
