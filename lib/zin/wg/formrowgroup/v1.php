<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

class formRowGroup extends formRow
{
    protected static array $defineProps = array(
        'title: array',
        'items: array',
    );

    protected static array $defineBlocks = array(
        'prefix' => array(),
        'suffix' => array(),
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build(): wg
    {
        return formRow
        (
            setClass('form-row-group border-b border-b-1'),
            div
            (
                $this->block('prefix'),
                setClass('row-group-title font-black px-3 py-1'),
                $this->prop('title'),
                $this->block('suffix'),
            ),
            set($this->getRestProps()),
            $this->prop('items'),
        );
    }
}
