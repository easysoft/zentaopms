<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbtn' . DS . 'v1.php';

class checkBtnGroup extends checkList
{
    protected static array $defaultProps = array
    (
        'inline' => true,
        'type'   => 'radio'
    );

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .check-btn-group {gap: 1px; border: 1px solid var(--form-control-border); padding: 0; border-radius: var(--radius)}
        .check-btn-group > .check-btn {flex: auto; position: relative}
        .check-btn-group > .check-btn + .check-btn::before {content: ' '; display: block; position: absolute; left: -1px; top: 8px; bottom: 8px; width: 1px; background: var(--color-border)}
        .check-btn-group > .check-btn > label {width: 100%;--tw-ring-color: transparent}
        .check-btn-group > .check-btn:not(:first-child) > label {border-top-left-radius: 0; border-bottom-left-radius: 0}
        .check-btn-group > .check-btn:not(:last-child) > label {border-top-right-radius: 0; border-bottom-right-radius: 0}
        CSS;
    }

    public function buildItem(array $props)
    {
        return new checkBtn(set($props));
    }

    protected function build()
    {
        $div = parent::build();
        $div->add(setClass('check-btn-group'));
        return $div;
    }
}
