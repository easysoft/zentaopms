<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
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
                set::items($projects),
                set::value(isset($settings['project']) ? $settings['project'] : null)
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
