<?php
declare(strict_types=1);
namespace zin;

class detailHeader extends wg
{
    protected static array $defineProps = array(
        'back?: string="APP"',
        'backUrl?: string'
    );

    protected static array $defineBlocks = array(
        'prefix' => array(),
        'title'  => array(),
        'suffix' => array()
    );

    private function backBtn(): wg
    {
        global $lang;
        return backBtn
        (
            set::icon('back'),
            set::type('primary-outline'),
            set::back($this->prop('back')),
            set::url($this->prop('backUrl')),
            $lang->goback
        );
    }

    protected function build(): wg
    {
        $prefix = $this->block('prefix');
        $title  = $this->block('title');
        $suffix = $this->block('suffix');

        if(empty($prefix) && !isAjaxRequest('modal')) $prefix = $this->backBtn();

        return div
        (
            setClass('detail-header flex justify-between mb-3 min-w-0 flex-nowrap'),
            div
            (
                setClass('flex flex-auto min-w-0 items-center gap-x-4 flex-nowrap pr-5'),
                $prefix,
                $title
            ),
            $suffix
        );
    }
}
