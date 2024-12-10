<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
{
    $executions = $this->loadModel('execution')->getPairs();
    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('execution'),
                set::label($lang->doc->execution),
                set::required(),
                set::control(array('contorl' => 'picker', 'required' => false, 'maxItemsCount' => 50)),
                set::items($executions),
                set::value(isset($settings['execution']) ? $settings['execution'] : null),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        )
    );
};
