<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'prilabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitylabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'risklabel' . DS . 'v1.php';

class tableData extends wg
{
    protected static $defineProps = array(
        'title?: string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public function onBuildItem($item)
    {
        return h::tr
        (
            h::th
            (
                setClass('py-1.5 pr-2 text-right font-normal nowrap'),
                $item->prop('name'),
            ),
            h::td
            (
                setClass('py-1.5 pl-2'),
                $item->children()
            )
        );
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
        return h::table
        (
            setClass('table-data'),
            $this->caption(),
            h::tbody($this->children())
        );
    }
}
