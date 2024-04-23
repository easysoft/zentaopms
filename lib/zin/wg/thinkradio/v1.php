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
        'optionName?: string',
        'otherName?: string',
        'required?: int=1',
        'enableOther?: bool',
        'data?: array',
    );

    protected static array $defaultProps = array
    (
        'type' => 'question'
    );

    protected function buildBody(): array
    {
        global $lang;
        $items = parent::buildBody();

        list($requiredName, $optionName, $otherName, $required, $enableOther, $data) = $this->prop(array('requiredName', 'optionName', 'otherName', 'required', 'enableOther', 'data'));
        $requiredItems = $lang->thinkwizard->step->requiredList;

        $items[] = formGroup
        (
            set::label(data('lang.thinkwizard.step.label.required')),
            radioList
            (
                set::name($requiredName),
                set::inline(true),
                set::value($required),
                set::items($requiredItems),
            )
        );
        $items[] = formGroup
        (
            set::label(data('lang.thinkwizard.step.label.option')),
            thinkOptions
            (
                set::name($optionName),
                set::data($data),
                set::otherName($otherName),
                set::enableOther($enableOther),
                set::otherName('enableOther')
            ),
        );
        $items[] = $this->children();
        return $items;
    }
}
