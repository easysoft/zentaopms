<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbtngroup' . DS . 'v1.php';

class checkColorGroup extends checkBtnGroup
{
    public static function getPageCSS(): string|false
    {
        return <<<CSS
        .check-btn-group {gap: 1px; padding: 0; border-radius: var(--radius)}
        .check-btn-group > .check-btn {flex: auto; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;}
        .check-btn-group > .check-btn + .check-btn::before {content: ' '; display: block; position: absolute; left: -1px; top: 8px; bottom: 8px; width: 1px; background: var(--color-border)}
        .check-btn-group > .check-btn > label {width: 24px; height: 24px; margin-right: 2px; --tw-ring-color: transparent; border-radius: var(--radius-full);}
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
