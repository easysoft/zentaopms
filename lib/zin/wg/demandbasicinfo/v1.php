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
        global $lang, $config;

        $demand = $this->prop('demand', data('demand'));
        if(!$demand) return array();

        $demandpools = data('demandpools');
        $products    = data('products');
        $users       = data('users');
        $demands     = data('demands');

        $productList = '';
        $mailtoList  = '';
        foreach(explode(',', $demand->product) as $product) $productList .= zget($products, $product) . ', ';
        foreach(explode(',', $demand->mailto)  as $user)    $mailtoList  .= zget($users,    $user)    . ', ';

        $items = array();
        if($config->vision == 'or') $items[$lang->demand->pool] = zget($demandpools, $demand->pool, '');

        if(!empty($demand->parent) && $demand->parent > 0)
        {
            $demandHtml = hasPriv('demand', 'view') ? div
            (
                setClass('flex'),
                a
                (
                    zget($demands, $demand->parent),
                    set::href(helper::createLink('demand', 'view', "demandID=$demand->parent")),
                    set::title(zget($demands, $demand->parent)),
                    setClass('w-1/5 text-clip mr-2.5'),
                    setData('toggle', 'modal'),
                    setData('size', 'lg')
                ),
                $demand->parentVersion < $demand->parentInfo->version && common::hasPriv('demand', 'processDemandChange') ? span
                (
                    ' (',
                    $lang->story->storyChange . ' ',
                    a
                    (
                        setClass('btn primary-pale border-primary size-xs ajax-submit'),
                        set::href(createLink('demand', 'processDemandChange', "demandID={$demand->id}")),
                        $lang->confirm,
                    ),
                    ')'
                ) : null,

            ) : zget($demands, $demand->parent);
            $items[$lang->demand->parent] = array
            (
                'control' => 'div',
                'content' => $demandHtml
            );
        }

        $items[$lang->demand->status]       = array('control' => 'status', 'class' => 'status-story', 'status' => $demand->status, 'text' => zget($lang->demand->statusList, $demand->status));
        $items[$lang->demand->stage]        = zget($lang->demand->stageList, $demand->stage);
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
