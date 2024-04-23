<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstepdetail' . DS . 'v1.php';
/**
 * 思引师选择题详情部件类。
 * thinmory option detail widget class.
 */

class thinkOptionsDetail extends thinkStepDetail
{
    protected static array $defineProps = array(
        'required?: bool',           // 是否必填
        'data?: array',              // 选项
        'enableOther?: bool=false',  // 是否启用其他
    );

    protected function detailOptionsControl()
    {
        global $lang;
        list($required, $data, $enableOther) = $this->prop(array('required', 'data', 'enableOther'));
        $optionsItems = array();
        $letters      = range('A', 'Z');

        if($enableOther) $data = array(...$data, $lang->thinkwizard->step->other);

        foreach($data as $key => $value)
        {
            $letter = '';

            while ($key >= 0) {
                $letter .= $letters[$key % 26];
                $key     = (int)($key / 26) - 1;
            }

            $optionsItems[] = div
            (
                setClass('px-6 py-2 mt-2 leading-6 flex items-center'),
                setStyle(array('border' => '1px solid rgba(46, 127, 255, 0.4)', 'background' => '#E6F0FF', 'font-size' => '13px', 'color' => '#313C52', 'border-radius' => '2px')),
                div
                (
                    $letter . '.',
                    setClass('mr-2')
                ),
                div
                (
                    $value,
                    setStyle(array('min-width' => '30px'))
                ),
                ($enableOther && $value === end($data)) ? input
                (
                    setClass('ml-4')
                ) : null
            );
        };

        return div(
            $required ? span(
                setClass('text-xl absolute top-6'),
                setStyle(array('color' => 'rgba(var(--color-danger-500-rgb),var(--tw-text-opacity))', 'left' => '36px')),
                '*'
            ) : null,
            setStyle(array('margin' => '13px 48px 8px')),
            $optionsItems,
        );
    }

    protected function buildBody(): array
    {
        $items   = parent::buildBody();
        $items[] = $this->detailOptionsControl();
        return $items;
    }
}
