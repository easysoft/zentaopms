<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang)
{
    $projects = $this->loadModel('project')->getPairsByProgram();

    $this->app->loadLang('projectstory');
    $searchConditions = array();
    foreach($lang->projectstory->featureBar['story'] as $key => $label)
    {
        if($key == 'more')
        {
            foreach($lang->projectstory->moreSelects['story']['more'] as $moreKey => $moreLabel) $searchConditions[$moreKey] = $moreLabel;
        }
        else
        {
            $searchConditions[$key] = $label;
        }
    }

    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('project'),
                set::label($lang->doc->project),
                set::items($projects)
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
