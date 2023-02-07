<?php

namespace zin\wg;

use zin\core\h5;

use function zin\setId;

use function zin\toolbar;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btngroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class mainmenu extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'mainMenu', 'class' => 'flex justify-between');

    static $customProps = 'statuses,btnGroup';

    protected function buildOther($props)
    {
        if ($props['type'] === 'checkbox')
        {
            unset($props['type']);
            return checkbox::create($props);
        }

        if ($props['type'] === 'button')
        {
            unset($prop['type']);
            return btn::create($props);
        }
    }

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $others = $this->prop('others');
        $this->props->remove('others');

        $flexDom = h5::div(
            h5::div(toolbar::create($this->prop('statuses'))),
        )->addClass('flex');

        foreach($others as $props) $flexDom->append($this->buildOther($props));

        $this->buildOthers(($others));

        $builder->append($flexDom);

        $builder->append(
            h5::div(
                setId('featureBarBtns'),
                toolbar(btngroup::create($this->prop('btnGroup'))->addClass('toolbar-btn-group')->setStyle('gap', '0.625rem'))
            )
        );
        $this->props->remove('btnGroup');

        return $builder;
    }
}
