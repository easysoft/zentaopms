<?php
namespace zin\wg;

use \zin\core\h5;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

class avatar extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array(
        'class'          => 'userMenu dropdown-toggle',
        'data-arrow'     => true,
        'data-toggle'    => 'dropdown',
        'data-trigger'   => 'hover',
        'data-placement' => 'bottom',
        'href'           => '#userMenu',
        'theme'          => 'success',
        'radius'         => 'circle',
        'size'           => '',
        'outline'        => '',
        'showName'       => false,
    );

    static $customProps = 'text,theme,radius,size,outline,name,role';

    static function create($props)
    {
        $avatar = new avatar();
        foreach($props as $key => $value) $avatar->prop($key, $value);
        return $avatar;
    }

    protected function acceptChild($child, $strAsHtml = false)
    {
        $child = parent::acceptChild($child, $strAsHtml);

        if(!$strAsHtml && is_string($child) && !$this->props->has('name'))
        {
            $this->prop('name', $child);
            return NULL;
        }
        return $child;
    }

    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $name   = $this->prop('name');
        $avatar = $this->prop('avatar');

        if(empty($name) and empty($avatar)) return $builder;

        /* Strig avatar. */
        if(!empty($avatar))
        {
            /* Image avatar. */
            $div = h5::div(h5::img(\zin\set('src', $this->prop('avatar'))))->addClass('avatar');
            if(!empty($this->prop('radius')))  $div->addClass($this->prop('radius'));
            if(!empty($this->prop('theme')))   $div->addClass($this->prop('theme'));
            if(!empty($this->prop('outline'))) $div->addClass($this->prop('outline') . '-outline');
            if(!empty($this->prop('size')))    $div->addClass($this->prop('size'));
            $builder->append($div);

            /* Show name and role. */
            if(!empty($this->prop('showName')))
            {
                $builder->append(h5::span($this->prop('name')));
                if(!empty($this->prop('role'))) $builder->append(h5::span($this->prop('role')));
            }

            return $builder;
        }

        $div = h5::div($this->prop('name'))->addClass('avatar');
        if(!empty($this->prop('radius')))  $div->addClass($this->prop('radius'));
        if(!empty($this->prop('theme')))   $div->addClass($this->prop('theme'));
        if(!empty($this->prop('outline'))) $div->addClass($this->prop('outline') . '-outline');
        if(!empty($this->prop('size')))    $div->addClass($this->prop('size'));
        $builder->append($div);

        if(!empty($this->prop('showName')))
        {
            $builder->append(h5::span($this->prop('name')));
            if(!empty($this->prop('role'))) $builder->append(h5::span($this->prop('role')));
        }

        return $builder;
    }
}

