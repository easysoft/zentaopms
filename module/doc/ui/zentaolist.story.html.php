<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
{
    $products = $this->loadModel('product')->getPairs();
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
                set::value(isset($settings['product']) ? $settings['product'] : '')
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
        )
    );
};
