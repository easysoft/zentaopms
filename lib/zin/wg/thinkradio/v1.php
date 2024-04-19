<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstep' . DS . 'v1.php';

/**
 * 单选题型部件类
 * The thinkRadio widget class
 */
class thinkRadio extends thinkStep
{
    protected static array $defineProps = array
    (
        'requiredName?: string="required"',
        'requiredItems?: array',
        'optionName?: string',
        'otherName?: string',
    );

    protected function buildBody(): array
    {
        $items = parent::buildBody();

        list($requiredItems, $requiredName, $optionName, $otherName, $type) = $this->prop(array('requiredItems', 'requiredName', 'optionName', 'otherName', 'type'));
        if(!empty($requiredItems)) $items[] = formGroup
        (
            set::label(data('lang.thinkwizard.step.label.required')),
            radioList
            (
                set::name($requiredName),
                set::inline(true),
                set::value(1),
                set::items($requiredItems),
            )
        );
        $items[] = formGroup
        (
            set::label(data('lang.thinkwizard.step.label.option')),
            thinkOptions(set::name($optionName), set::otherName($otherName)),
        );
        $items[] = $this->children();
        return $items;
    }
}
