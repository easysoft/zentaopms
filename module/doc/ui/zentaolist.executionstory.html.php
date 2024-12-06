<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang)
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
                set::items($executions)
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('search'),
                set::label($lang->doc->searchCondition),
                set::items($searchConditions)
            )
        )
    );
};
