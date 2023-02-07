<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

use \zin\core\h5;

class btn extends \zin\core\wg
{
    static $tag = 'button';

    static $defaultProps = array('class' => 'btn', 'btnType' => 'button');

    static $customProps = 'type,icon,text,square,disabled,active,url,target,size,trailingIcon,caret,hint,btnType';

    static function create($props)
    {
        $btn = new btn();
        foreach($props as $key => $value) $btn->prop($key, $value);
        return $btn;
    }

    protected function acceptChild($child, $strAsHtml = false)
    {
        $child = parent::acceptChild($child, $strAsHtml);

        if(!$strAsHtml && is_string($child) && !$this->props->has('text'))
        {
            $this->prop('text', $child);
            return NULL;
        }
        return $child;
    }

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);
        if($this->hasProp('url'))
        {
            $builder->setTag('a');
            $builder->props->set('href',   $this->prop('url'));
            $builder->props->set('target', $this->prop('target'));
        }
        else
        {
            $builder->props->set('type',        $this->prop('btnType'));
            $builder->props->set('data-url',    $this->prop('url'));
            $builder->props->set('data-target', $this->prop('target'));
        }

        $builder->props->set('title', $this->prop('hint'));

        $caret         = $this->prop('caret');
        $text          = $this->prop('text');
        $icon          = $this->prop('icon');
        $size          = $this->prop('size');
        $trailingIcon  = $this->prop('trailingIcon');
        $square        = $this->prop('square');
        $isEmptyText   = empty($text);
        $onlyCaret     = $isEmptyText && empty($icon) && empty($trailingIcon);

        if($square === NULL) $square = $isEmptyText && !$onlyCaret;

        $classList = array($this->prop('type'), 'disabled' => $this->prop('disabled'), 'active' => $this->prop('active'), 'btn-caret' => $onlyCaret, 'square' => $square);

        if(!empty($size)) $classList[] = "size-$size";

        $builder->props->set('class', $classList);

        if(!empty($icon))         $builder->append(new icon($icon));
        if(!empty($text))         $builder->append(h5::span($text)->addClass('text'));
        if(!empty($trailingIcon)) $builder->append(new icon($trailingIcon));
        if(!empty($caret))        $builder->append(h5::span()->addClass(is_string($caret) ? "caret-$caret" : 'caret'));

        return $builder;
    }
}
