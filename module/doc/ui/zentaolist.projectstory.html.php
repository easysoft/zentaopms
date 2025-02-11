<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings, $fnGenerateCustomSearch)
{
    $projects = $this->loadModel('project')->getPairsByProgram();
    $project  = isset($settings['project']) ? $settings['project'] : 0;

    $this->loadModel('projectstory');

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
    $searchConditions['customSearch'] = $lang->doc->customSearch;
    $searchConfig = $this->projectstory->buildSearchConfig((int)$project);

    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('project'),
                set::label($lang->doc->project),
                set::items($projects),
                set::value($project)
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
        ),
        $fnGenerateCustomSearch($searchConfig)
    );
};
