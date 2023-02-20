<?php
namespace zin;

use zin\core\h5;
use function zin\Icon;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'avatar' . DS . 'v1.php';

class pagetoolbar extends wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'toolbar');

    static $customProps = 'globalCreate,avatar,switcher';

    private function buildGlobalCreate($props)
    {
        $div = h::create('div', NULL, $props);
        $div->append(h::div(Icon('plus'))->addClass('rounded-sm btn square size-sm secondary'));
        $div->addClass('globalGreate');

        return $div;
    }

    private function buildAvatar($props)
    {
        return Avatar::create($props);
    }

    private function buildSwitcher($props)
    {
        $div = h::create('div', NULL, $props);
        $div->append(h::div($props['text'])->addClass('switcher-text'));
        $div->addClass('vision-switcher');

        return $div;
    }

    protected function build($isPrint = false, $parent = null)
    {
        $builder = parent::build($isPrint, $parent);
        $builder->append($this->buildGlobalCreate($this->prop('globalCreate')));
        $builder->append($this->buildAvatar($this->prop('avatar')));
        $builder->append($this->buildSwitcher($this->prop('switcher')));
        return $builder;
    }
}
