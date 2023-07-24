<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';

class formRowGroup extends formRow
{
    protected static array $defineProps = array(
        'title: array',
        'items: array',
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
                setClass('row-group-title font-black px-3 py-1'),
                $this->prop('title')
            ),
            $this->prop('items'),
        );
    }
}
