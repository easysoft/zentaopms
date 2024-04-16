<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstep' . DS . 'v1.php';

/**
 * 单选题型（thinkRadio）部件类
 * The thinkRadio widget class
 */
class thinkRadio extends thinkStep
{
    protected static array $defineProps = array
    (
        'requiredLabel?: string',
        'requiredItems?: array',
        'optionLabel?: string',
    );

    protected function buildBody(): array
    {
        $items = parent::buildBody();

        list($requiredItems, $requiredLabel, $optionLabel) = $this->prop(array('requiredItems', 'requiredLabel', 'optionLabel'));
        if(!empty($requiredItems)) $items[] = formGroup
        (
            set::label($requiredLabel),
            radioList
            (
                set::name('contact'),
                set::inline(true),
                set::items($requiredItems),
            )
        );
        $items[] = formGroup
        (
            set::label($optionLabel),
            stepsEditor(),
            div
            (
                setClass('w-full flex justify-between items-center h-8 border px-2.5 rounded mt-1'),
                setStyle(array('background' => 'rgba(242, 244, 247, .7)')),
                div
                (
                    setClass('flex items-center'),
                    div(setStyle(array('width' => '44px', 'color' => '#5E626D')), '其他'),
                    div(setStyle(array('color' => '#B2B9C5')), '请输入'),
                ),
                checkbox(set::text('启用')),
            )
        );
        return $items;
    }
}
