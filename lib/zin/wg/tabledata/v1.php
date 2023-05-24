<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'prilabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitylabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'risklabel' . DS . 'v1.php';

class tableData extends wg
{
    protected static $defineProps = array(
        'items: array'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function th(string $text): wg
    {
        return h::th
        (
            setClass('py-1.5 pr-2 text-right font-normal'),
            $text
        );
    }

    private function buildContent(string|array|null $value)
    {
        if(is_callable($value)) return $value();

        if(!is_array($value)) return $value;

        $wgName = "\\zin\\{$value['wg']}";
        unset($value['wg']);
        if(class_exists($wgName)) return new $wgName(set($value));

        return null;
    }

    private function td(string|array|null $value): ?wg
    {
        $content = $this->buildContent($value);
        if(is_null($content)) return null;

        return h::td
        (
            setClass('py-1.5 pl-2'),
            $content
        );

    }

    private function tr(string $name, string|array|null $value)
    {
        return h::tr
        (
            $this->th($name),
            $this->td($value)
        );
    }

    private function tbody()
    {
        $items = $this->prop('items');
        $trs   = array();

        foreach ($items as $item) $trs[] = $this->tr($item['name'], $item['value']);

        return h::tbody($trs);
    }

    protected function build(): wg
    {
        return h::table
        (
            setClass('table-data'),
            $this->tbody(),
        );
    }
}
