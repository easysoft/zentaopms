<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbtngroup' . DS . 'v1.php';

class checkColorGroup extends checkBtnGroup
{
    public static function getPageCSS(): string|false
    {
        return <<<CSS
        .check-btn-group {gap: 1px; padding: 0; border-radius: var(--radius); margin-top: 4px;}
        .check-btn-group > .check-btn {flex: auto; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;}
        .check-btn-group > .check-btn > label {width: 20px; height: 20px; padding: 0; margin-right: 6px; --tw-ring-color: transparent; border-radius: var(--radius-full);}
        .check-btn-group > .check-btn > input:checked + label {font-family: ZentaoIcon; text-align: center; color: #fff;}
        .check-btn-group > .check-btn > input:checked + label.btn::after {font-size: 14px; content: '\\e5ca';}
        .check-btn-group > .check-btn > label > svg {display: none}
        CSS;
    }

    public function buildItem(array $props)
    {
        $props['text']       = '';
        $props['labelStyle'] = array('background-color' => $props['value']);
        return parent::buildItem($props);
    }

    protected function build(): wg
    {
        return parent::build();
    }
}
