<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'prilabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitylabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'risklabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'collapsebtn' . DS . 'v1.php';

class tableData extends wg
{
    protected static $defineProps = array(
        'title?: string',
        'useTable?: bool=true'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildItemWithTr($item)
    {
        return h::tr
        (
            h::th
            (
                setClass('py-1.5 pr-2 font-normal nowrap text-right'),
                $item->prop('name'),
            ),
            h::td
            (
                setClass('py-1.5 pl-2'),
                $item->children()
            )
        );
    }

    private function buildItemWithDiv($item)
    {
        $collapse = $item->prop('collapse');
        if(!$collapse)
        return div
        (
            setClass('col', 'table-data-tr'),
            div
            (
                setClass('py-1.5 pr-2 font-normal nowrap table-data-th'),
                $item->prop('name'),
                new collapseBtn
                (
                    set::target('.table-data-td'),
                    set::parent('.table-data-tr')
                ),
            ),
            div
            (
                setClass('py-1.5 pl-2 table-data-td'),
                $item->children()
            )
        );
    }

    public function onBuildItem($item)
    {
        $useTable = $this->prop('useTable');
        if($useTable) return $this->buildItemWithTr($item);

        return $this->buildItemWithDiv($item);
    }

    private function caption()
    {
        $title = $this->prop('title');
        if(empty($title)) return null;

        return h::caption
        (
            setClass('font-normal article-h2 text-left mb-2'),
            $title
        );
    }

    protected function build(): wg
    {
        $useTable = $this->prop('useTable');
        if($useTable)
        {
            return h::table
            (
                setClass('table-data'),
                $this->caption(),
                h::tbody($this->children())
            );
        }

        return div
        (
            setClass('table-data'),
            div
            (
                setClass('table-data-body'),
                $this->children()
            )
        );
    }
}
