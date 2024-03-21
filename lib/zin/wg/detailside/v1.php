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

    protected function buildExtraSide()
    {
        global $app, $lang;

        $fields    = $app->control->appendExtendForm('basic', data($app->getModuleName()));
        $extraSide = array();
        foreach($fields as $field)
        {
            $extraSide[] = item
            (
                set::name($field->name),
                formGroup
                (
                    set::id($field->field),
                    set::name($field->field),
                    set::required($field->required),
                    set::control($field->control),
                    set::items($field->items),
                    set::value($field->value)
                )
            );
        }
        return tableData(set::title($lang->other), $extraSide);
    }

    protected function build()
    {
        $extraSide = $this->buildExtraSide();
        return div
        (
            setClass('detail-side canvas flex-none px-6 h-min'),
            set($this->getRestProps()),
            $this->children(),
            $extraSide
        );
    }
}
