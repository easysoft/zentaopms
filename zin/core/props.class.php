<?php
/**
 * The props class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'classlist.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'style.class.php';

/**
 * Manage properties for html element and widgets
 */
class props extends \zin\utils\dataset
{
    /**
     * Style property
     *
     * @access public
     * @var    style
     */
    public $style;

    /**
     * Class property
     *
     * @access public
     * @var    classlist
     */
    public $class;

    /**
     * Create properties instance
     *
     * @access public
     * @param array $props - Properties list array
     */
    public function __construct($props = NULL)
    {
        $this->style       = new \zin\utils\style();
        $this->class       = new \zin\utils\classlist();

        parent::__construct($props);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     */
    protected function setVal($prop, $value)
    {
        if($prop === 'class' || $prop === '.')     $this->class->set($value);
        elseif($prop === 'style' || $prop === '~') $this->style->set($value);
        elseif(str_starts_with($prop, '~'))        $this->style->set(substr($prop, 1), $value);
        elseif($prop === '--')                     $this->style->cssVar($value);
        elseif(str_starts_with($prop, '--'))       $this->style->cssVar(substr($prop, 2), $value);
        elseif($prop === '!')                      $this->hx($value);
        elseif(str_starts_with($prop, '!'))        $this->hx(substr($prop, 1), $value);
        elseif(str_starts_with($prop, ':'))        $this->set('data-' . substr($prop, 1), $value);
        elseif($prop === '@')                      $this->bindEvent($value);
        elseif(str_starts_with($prop, '@'))        $this->bindEvent(substr($prop, 1), $value);
        else                                       parent::setVal($prop, $value);
        return $this;
    }

    protected function getVal($prop)
    {
        if($prop === 'class' || $prop === '.')
        {
            if(!$this->class->count()) return NULL;
            return $this->class->toStr();
        }
        if($prop === 'style' || $prop === '~')
        {
            if(!$this->style->count(true)) return NULL;
            return $this->style->toStr();
        }
        return parent::getVal($prop);
    }

    public function reset($name, $value = NULL)
    {
        if(is_array($name))
        {
            foreach($name as $n) $this->reset($n);
            return;
        }
        if($name === 'class') return $this->class->clear();
        if($name === 'style') return $this->style->clear();

        $this->remove($name);
        if($value) $this->setVal($name, $value);
    }

    public function bindEvent($name, $callback = NULL)
    {
        if(is_array($name))
        {
            foreach($name as $key => $value) $this->bindEvent($key, $value);
            return;
        }

        $events = parent::getVal("@$name") ?? [];
        if(is_array($callback)) $events   = array_merge($events, $callback);
        else                    $events[] = $callback;

        parent::setVal("@$name", $events);
    }

    public function events()
    {
        $events = array();
        foreach($this->data as $name => $value)
        {
            if(str_starts_with($name, '@')) $events[substr($name, 1)] = $value;
        }

        return $events;
    }

    public function hasEvent()
    {
        foreach($this->data as $name => $value)
        {
            if(str_starts_with($name, '@')) return true;
        }

        return false;
    }

    public function hx($name, $value = NULL)
    {
        if(is_array($name))
        {
            foreach($name as $key => $val) $this->set("hx-$key", $val);
            return;
        }

        $this->set("hx-$name", $value);
    }

    /**
     * Convert props to html string
     *
     * Example:
     *
     *     // Properties data map:
     *     $map = array(
     *         'id' => 'sayHelloBtn',
     *         'data-title' => 'Say "Hello"!',
     *         'data-content' => NULL,
     *         'data-show' => true,
     *     );
     *     // Output string: id="sayHelloBtn" data-title="Say &quot;Hello&quot;!" data-show="true"
     *
     * @access public
     */
    public function toStr($skipProps = array())
    {
        if(is_string($skipProps)) $skipProps = explode(',', $skipProps);

        $pairs = array();

        if($this->class->count())     $pairs[] = 'class="' . $this->class->toStr() . '"';
        if($this->style->count(true)) $pairs[] = 'style="' . $this->style->toStr() . '"';

        foreach($this->data as $name => $value)
        {
            /* Handle boolean attributes */
            if(in_array($name, static::$booleanAttrs)) $value = $value ? true : NULL;

            /* Skip any null value or events setting */
            if($value === NULL || in_array($name, $skipProps) || $name[0] === '@') continue;

            /* Convert non-string to json */
            if($value === true && !str_starts_with($name, 'data-'))
            {
                $pairs[] = $name;
            }
            else
            {
                if(!is_string($value)) $value = json_encode($value);

                $pairs[] = $name . '="' . htmlspecialchars($value) . '"';
            }
        }

        return implode(' ', $pairs);
    }

    public function toJsonData()
    {
        $data = $this->data;
        if(!empty($this->style->data)) $data['style'] = $this->style->data;
        if(!empty($this->class->list)) $data['class'] = $this->class->toStr();
        return $data;
    }

    public function skip($skipProps = array(), $skipFalse = false)
    {
        if(is_string($skipProps)) $skipProps = explode(',', $skipProps);

        $data = $this->toJsonData();
        if($skipFalse) $data = array_filter($data, function($v) {return $v !== false;});
        foreach($data as $name => $value)
        {
            if($value === NULL || in_array($name, $skipProps)) unset($data[$name]);
        }

        return $data;
    }

    public function pick($pickProps = array())
    {
        if(is_string($pickProps)) $pickProps = explode(',', $pickProps);

        $data = $this->toJsonData();
        foreach($data as $name => $value)
        {
            if($value === NULL || !in_array($name, $pickProps)) unset($data[$name]);
        }

        return $data;
    }

    /**
     * Clone a new instance
     *
     * @access public
     * @return object
     */
    public function clone()
    {
        $props = new props($this->data);
        $props->style  = clone $this->style;
        $props->class  = clone $this->class;
        return $props;
    }

    public static $booleanAttrs = ['allowfullscreen', 'async', 'autofocus', 'autoplay', 'checked', 'controls', 'default', 'defer', 'disabled', 'formnovalidate', 'inert', 'ismap', 'itemscope', 'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline', 'readonly', 'required', 'reversed', 'selected'];
}
