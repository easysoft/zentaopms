<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'prilabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitylabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'risklabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'collapsebtn' . DS . 'v1.php';

class tableData extends wg
{
    protected static array $defineProps = array(
        'title?: string',
        'useTable?: bool=true'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildItemWithTr($item): wg
    {
        return h::tr
        (
            setClass($item->prop('trClass')),
            h::th
            (
                setClass('py-1.5 pr-2 font-normal nowrap text-right', $item->prop('thClass')),
                $item->prop('name'),
            ),
            h::td
            (
                setClass('py-1.5 pl-2 w-full', $item->prop('tdClass')),
                $item->children()
            )
        );
    }

    private function buildItemWithDiv($item): wg
    {
        if($item->prop('collapse'))
        {
            return div
            (
                setClass('col', 'table-data-tr', $item->prop('trClass')),
                div
                (
                    setClass('py-1.5 pr-2 font-normal nowrap table-data-th', $item->prop('thClass')),
                    $item->prop('name'),
                    new collapseBtn
                    (
                        setClass('w-5 h-5 ml-1'),
                        set::target('.table-data-td'),
                        set::parent('.table-data-tr')
                    ),
                ),
                div
                (
                    setClass('py-1.5 pl-2 table-data-td', $item->prop('tdClass')),
                    $item->children()
                )
            );
        }

        return div
        (
            setClass('flex table-data-tr', $item->prop('trClass')),
            div
            (
                setClass('py-1.5 pr-2 font-normal nowrap table-data-th', $item->prop('thClass')),
                $item->prop('name'),
            ),
            div
            (
                setClass('py-1.5 pl-2 table-data-td', $item->prop('tdClass')),
                $item->children()
            )
        );
    }

    public function onBuildItem($item): wg
    {
        $item->setProp(array('thClass' => $this->prop('thClass'), 'tdClass' => $this->prop('tdClass')));

        $useTable = $this->prop('useTable');
        if($useTable) return $this->buildItemWithTr($item);

        return $this->buildItemWithDiv($item);
    }

    private function caption(): ?wg
    {
        $title = $this->prop('title');
        if(empty($title)) return null;

        return h::caption
        (
            setClass('article-h1 text-left mb-2'),
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
