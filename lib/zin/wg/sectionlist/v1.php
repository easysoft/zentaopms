<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'section' . DS . 'v1.php';

class sectionList extends wg
{
    protected static $defineProps = array(
        'items: array'
    );

    private function buildSection(array $item): wg
    {
        return new section(set($item));
    }

    protected function build(): wg
    {
        $items = $this->prop('items');

        return div
        (
            setClass('section-list', 'grow'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            array_map(array($this, 'buildSection'), $items),
            $this->children()
        );
    }
}
