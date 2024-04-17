<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkradio' . DS . 'v1.php';

/**
 * 多选题型（thinkCheckbox）部件类
 * The thinkCheckbox widget class
 */
class thinkCheckbox extends thinkRadio
{
    protected static array $defineProps = array
    (
        'minCountName?: string',
        'maxCountName?: string',
        'minCount?: string',
        'maxCount?: string',
    );

    protected static array $defaultProps = array
    (
        'type' => 'checkbox'
    );

    protected function buildBody(): array
    {
        $items = parent::buildBody();

        list($minCount, $maxCount, $maxCountName, $minCountName) = $this->prop(array('minCountName', 'minCount', 'maxCountName', 'maxCount'));
        $items[] = formRow
        (
            setClass('gap-4'),
            formGroup
            (
                set::label(data('lang.thinkwizard.step.label.minCount')),
                set::placeholder(data('lang.thinkwizard.step.inputContent')),
                set::control('input'),
                set::name($minCountName),
                set::value($minCount),
            ),
            formGroup
            (
                set::label(data('lang.thinkwizard.step.label.maxCount')),
                set::placeholder(data('lang.thinkwizard.step.inputContent')),
                set::control('input'),
                set::name($maxCountName),
                set::value($maxCount)
            )
        );
        $items[] = $this->children();
        return $items;
    }
}
