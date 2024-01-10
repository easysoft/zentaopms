<?php
declare(strict_types=1);
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

use zin\utils\classlist;
use zin\utils\style;

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
    public style $style;

    /**
     * Class property
     *
     * @access public
     * @var    classlist
     */
    public classlist $class;

    public static array $booleanAttrs = array('allowfullscreen', 'async', 'autofocus', 'autoplay', 'checked', 'controls', 'default', 'defer', 'disabled', 'formnovalidate', 'inert', 'ismap', 'itemscope', 'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline', 'readonly', 'required', 'reversed', 'selected');

    /**
     * Create properties instance
     *
     * @access public
     * @param array $props - Properties list array
     */
    public function __construct(array $props = array())
    {
        $this->style = new style();
        $this->class = new classlist();

        parent::__construct($props);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access public
     * @param string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     */
    protected function setVal(string $prop, mixed $value): props
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

    protected function getVal(string $prop): mixed
    {
        if($prop === 'class' || $prop === '.')
        {
            if(!$this->class->count()) return null;
            return $this->class->toStr();
        }
        if($prop === 'style' || $prop === '~')
        {
            if(!$this->style->getCount(true)) return null;
            return $this->style->toStr();
        }
        return parent::getVal($prop);
    }

    /**
     * @param string|string[] $name
     * @param mixed $value
     */
    public function reset(array|string $name, mixed $value = null)
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

    public function bindEvent($name, $callback = null)
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

    public function events(): array
    {
        $events = array();
        foreach($this->_data as $name => $value)
        {
            if(str_starts_with($name, '@')) $events[substr($name, 1)] = $value;
        }

        return $events;
    }

    public function hasEvent(): bool
    {
        foreach($this->_data as $name => $value)
        {
            if(str_starts_with($name, '@')) return true;
        }

        return false;
    }

    public function hx(array|string $name, ?string $value = null)
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
     *         'data-content' => null,
     *         'data-show' => true,
     *     );
     *     // Output string: id="sayHelloBtn" data-title="Say &quot;Hello&quot;!" data-show="true"
     *
     * @access public
     */
    public function toStr(array|string $skipProps = array()): string
    {
        if(is_string($skipProps)) $skipProps = explode(',', $skipProps);

        $pairs = array();

        if($this->class->count())     $pairs[] = 'class="' . $this->class->toStr() . '"';
        if($this->style->getCount(true)) $pairs[] = 'style="' . $this->style->toStr() . '"';

        foreach($this->_data as $name => $value)
        {
            /* Handle boolean attributes */
            if(in_array($name, static::$booleanAttrs)) $value = $value ? true : null;

            /* Skip any null value or events setting */
            if($value === null || in_array($name, $skipProps) || $name[0] === '@') continue;

            /* Convert non-string to json */
            if($value === true && !str_starts_with($name, 'data-'))
            {
                $pairs[] = $name;
            }
            else
            {
                if(!is_string($value)) $value = json_encode($value);

                $pairs[] = $name . '="' . htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false) . '"';
            }
        }

        return implode(' ', $pairs);
    }

    public function toJSON(bool $skipEvents = false): array
    {
        $data      = $this->_data;
        $styleData = $this->style->get();

        if(!empty($styleData)) $data['style'] = $styleData;
        if(!empty($this->class->toJSON())) $data['class'] = $this->class->toStr();

        if($skipEvents)
        {
            foreach($data as $name => $value)
            {
                if(str_starts_with($name, '@')) unset($data[$name]);
            }
        }
        return $data;
    }

    public function skip(array|string $skipProps = array(), bool $skipFalse = false): array
    {
        if(is_string($skipProps)) $skipProps = explode(',', $skipProps);

        $data = $this->toJSON();
        foreach($data as $name => $value)
        {
            if($value === null || $name[0] === '@' || in_array($name, $skipProps)) unset($data[$name]);
            if($skipFalse && $value === false) unset($data[$name]);
        }

        return $data;
    }

    public function split(array|string $firstListProps = array()): array
    {
        if(is_string($firstListProps)) $firstListProps = explode(',', $firstListProps);

        $data       = $this->toJSON();
        $firstList  = array();
        $restList   = array();
        foreach($data as $name => $value)
        {
            if($value === null ||  $name[0] === '@') continue;
            if(in_array($name, $firstListProps)) $firstList[$name] = $value;
            else                                 $restList[$name]  = $value;
        }

        return array($firstList, $restList);
    }

    public function pick(array|string $pickProps = array()): array
    {
        if(is_string($pickProps)) $pickProps = explode(',', $pickProps);

        $data = $this->toJSON();
        foreach($data as $name => $value)
        {
            if($value === null || !in_array($name, $pickProps)) unset($data[$name]);
        }

        return $data;
    }

    /**
     * Clone a new instance
     *
     * @access public
     * @return props
     */
    public function copy(): props
    {
        $props = new props($this->_data);
        $props->style = clone $this->style;
        $props->class = clone $this->class;
        return $props;
    }
}
