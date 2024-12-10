<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
{
    $executions = $this->loadModel('execution')->getPairs();

    $searchConditions = array();
    foreach($lang->execution->featureBar['story'] as $key => $label) $searchConditions[$key] = $label;

    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('execution'),
                set::label($lang->doc->execution),
                set::items($executions),
                set::value(isset($settings['execution']) ? $settings['execution'] : null)
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('condition'),
                set::label($lang->doc->searchCondition),
                set::items($searchConditions),
                set::value(isset($settings['condition']) ? $settings['condition'] : null)
            )
        )
    );
};
