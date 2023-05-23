<?php
declare(strict_types=1);
namespace zin;

class detailHeader extends wg
{
    protected static $defineBlocks = array(
        'prefix' => array(),
        'title'  => array(),
        'suffix' => array(),
    );

    private function backBtn(): wg
    {
        global $lang;

        return btn
        (
            set::icon('back'),
            set::type('secondary'),
            setClass('mr-4'),
            $lang->goback
        );
    }

    protected function build(): wg
    {
        $prefix = $this->block('prefix');
        $title  = $this->block('title');
        $suffix = $this->block('suffix');

        if(empty($prefix)) $prefix = $this->backBtn();

        return div
        (
            setClass('detail-header flex justify-between mb-3'),
            div
            (
                setClass('flex', 'items-center'),
                $prefix,
                $title,
            ),
            $suffix
        );
    }
}
