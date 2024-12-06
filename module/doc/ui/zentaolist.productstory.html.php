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
};
