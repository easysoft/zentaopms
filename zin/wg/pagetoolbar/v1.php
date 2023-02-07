<?php
namespace zin\wg;

use zin\core\h5;
use function zin\Icon;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'avatar' . DS . 'v1.php';

class pagetoolbar extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'toolbar');

    static $customProps = 'globalGreate,avatar,switcher';

    private function buildGlobarCreate()
    {
        return h5::div(
            h5::div(Icon('plus'))->addClass('rounded-sm btn square size-sm secondary')
        )->addClass('globalGreate');
    }

    private function buildAvatar($props)
    {
        return Avatar::create($props);
    }

    private function buildSwitcher($props)
    {

        return h5::div(
            h5::div($props['text'])->addClass('switcher-text')
        )->addClass('vision-switcher');
    }

    protected function build($isPrint = false, $parent = null)
    {
        $builder = parent::build($isPrint, $parent);
        $builder->append($this->buildGlobarCreate());
        $builder->append($this->buildAvatar($this->prop('avatar')));
        $builder->append($this->buildSwitcher($this->prop('switcher')));
        return $builder;
    }
}
