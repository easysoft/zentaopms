<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings, $lowerType, $fnGenerateCustomSearch)
{
    $products = $this->loadModel('product')->getPairs();
    $product  = isset($settings['product']) ? $settings['product'] : 0;

    $searchConditions = array();
    foreach($lang->product->featureBar['browse'] as $key => $label)
    {
        if(strpos($key, 'byme') !== false || strpos($key, 'tome') !== false) continue;
        if($key == 'more')
        {
            foreach($lang->product->moreSelects['browse']['more'] as $moreKey => $moreLabel)
            {
                if(strpos($moreKey, 'byme') !== false || strpos($moreKey, 'tome') !== false) continue;

                $searchConditions[$moreKey] = $moreLabel;
            }
        }
        else
        {
            $searchConditions[$key] = $label;
        }
    }
    $searchConditions['customSearch'] = $lang->doc->customSearch;

    $storyType = 'story';
    if($lowerType == 'er') $storyType = 'epic';
    if($lowerType == 'ur') $storyType = 'requirement';
    $searchConfig = $this->product->buildSearchConfig((int)$product, $storyType);

    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::items($products),
                set::value($product),
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
                set::items($searchConditions),
                set::value(isset($settings['condition']) ? $settings['condition'] : '')
            )
        ),
        $fnGenerateCustomSearch($searchConfig)
    );
};
