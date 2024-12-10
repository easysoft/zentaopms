<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings, $fnGenerateCustomSearch)
{
    $this->loadModel('caselib');
    $libList = $this->caselib->getLibraries();
    $lib     = isset($settings['caselib']) ? $settings['caselib'] : 0;
    $conditions = array_filter($lang->caselib->featureBar['browse'], fn($value) => $value !== '-');
    $conditions['customSearch'] = $lang->doc->customSearch;

    $searchConfig = $this->caselib->buildSearchConfig((int)$lib);
    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('caselib'),
                set::label($lang->doc->caselib),
                set::required(),
                set::control(array('contorl' => 'picker', 'required' => false, 'maxItemsCount' => 50)),
                set::items($libList),
                set::value($lib),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('condition'),
                set::label($lang->doc->searchCondition),
                set::required(),
                set::control(array('contorl' => 'picker', 'required' => false, 'maxItemsCount' => 50)),
                set::items($conditions),
                set::value(isset($settings['condition']) ? $settings['condition'] : ''),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        ),
        $fnGenerateCustomSearch($searchConfig)
    );
};
