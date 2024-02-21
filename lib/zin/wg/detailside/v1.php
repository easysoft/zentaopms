<?php
declare(strict_types=1);
namespace zin;

class detailSide extends wg
{
    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .detail-side {width: 370px;}
        .detail-side .tab-content>.tab-pane {padding-left: 0 !important;}
        .detail-side .tabs:not(:first-child) {border-top: 1px solid #E6EAF1;}
        .detail-side .tabs {padding-top: 12px; padding-bottom: 20px;}
        .detail-side > .table-data {width: 100%;}
        CSS;
    }

    protected function build()
    {
        return div
        (
            setClass('detail-side canvas flex-none px-6 h-min'),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
