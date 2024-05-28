<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class demandBasicInfo extends wg
{
    protected static array $defineProps = array
    (
        'demand' => '?object', // 当前需求。
    );

    protected function getItems(): array
    {
        global $lang;

        $demand = $this->prop('demand', data('demand'));
        if(!$demand) return array();

        $demandpools = data('demandpools');
        $products    = data('products');
        $users       = data('users');

        $productList = '';
        $mailtoList  = '';
        foreach(explode(',', $demand->product) as $product) $productList .= zget($products, $product) . ', ';
        foreach(explode(',', $demand->mailto)  as $user)    $mailtoList  .= zget($users,    $user)    . ', ';

        $items = array();
        $items[$lang->demand->pool]         = zget($demandpools, $demand->pool, '');
        $items[$lang->demand->status]       = zget($lang->demand->statusList, $demand->status);
        $items[$lang->demand->product]      = trim($productList, ', ') ? trim($productList, ', ') : $lang->demand->undetermined;
        $items[$lang->demand->pri]          = array('control' => 'pri', 'text' => $lang->demand->priList, 'pri' => $demand->pri);
        $items[$lang->demand->category]     = zget($lang->demand->categoryList, $demand->category);
        $items[$lang->demand->source]       = zget($lang->demand->sourceList, $demand->source);
        $items[$lang->demand->sourceNote]   = $demand->sourceNote;
        $items[$lang->demand->BSA]          = zget($lang->demand->bsaList, $demand->BSA);
        $items[$lang->demand->duration]     = zget($lang->demand->durationList, $demand->duration);
        $items[$lang->demand->feedbackedBy] = $demand->feedbackedBy;
        $items[$lang->demand->email]        = $demand->email;
        $items[$lang->demand->keywords]     = $demand->keywords;
        $items[$lang->demand->mailto]       = trim($mailtoList, ', ');

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('demand-basec-info'),
            set::items($this->getItems())
        );
    }
}
