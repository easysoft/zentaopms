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
    );
};
