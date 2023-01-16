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

require_once 'dataset.class.php';
require_once 'classlist.class.php';
require_once 'style.class.php';
require_once 'hx.class.php';

/**
 * Manage properties for html element and widgets
 */
class props extends dataset
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
     * Hx property
     *
     * @access public
     * @var    hx
     */
    public $hx;

    public $customProps;

    /**
     * Create properties instance
     *
     * @access public
     * @param array $props - Properties list array
     */
    public function __construct($props = NULL, $customProps = NULL)
    {
        $this->style       = new style();
        $this->class       = new classlist();
        $this->hx          = new hx();
        $this->customProps = is_string($customProps) ? explode(',', $customProps) : $customProps;

        parent::__construct($props);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @param bool           $removeEmpty - Whether to remove empty value
     * @return hx
     */
    protected function setVal($prop, $value, $removeEmpty = false)
    {
        if($prop === 'style')          return $this->style->set($value);
        if($prop === 'class')          return $this->class->set($value);
        if($prop === 'hx')             return $this->hx->set($value);
        if(strpos($prop, 'hx-') === 0) return $this->hx->set($prop, $value);

        return parent::setVal($prop, $value);
    }

    public function setCustom($props)
    {
        if(is_string($props)) $props = explode(',', $props);

    }

    /**
     * Get data properties name
     *
     * @access public
     * @param array|string $name - Data properties name, a string or an list
     * @return mixed
     */
    public function getData($name = NULL)
    {
        if($name === NULL)
        {
            $data = array();
            foreach($this->data as $prop => $value)
            {
                if(strpos($prop, 'data-') !== 0) continue;
                $data[substr($prop, 5)] = $value;
            }
            return $data;
        }
        return $this->get("data-$name");
    }

    /**
     * Set data properties
     *
     * @access public
     * @param array|string   $name  - Data properties name, a string or an list
     * @param mixed          $value - Data property value
     * @return props
     */
    public function setData($name, $value = NULL)
    {
        if(is_array($name))
        {
            foreach($name as $n => $v)
            {
                if(strpos($n, 'data-') !== 0) $n = "data-$n";
                $this->set($n, $v);
            }
            return $this;
        }
        if(strpos($name, 'data-') !== 0) $name = "data-$name";
        $this->set($name, $value);
        return $this;
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
    public function toStr($skipProps = true)
    {
        if($skipProps === true) $skipProps = $this->customProps;

        $pairs = array();

        if($this->class->count())     $pairs[] = 'class="' . $this->class->toStr() . '"';
        if($this->style->count(true)) $pairs[] = 'style="' . $this->style->toStr() . '"';
        if($this->hx->count(true))    $pairs[] = $this->hx->toStr();

        foreach($this->data as $name => $value)
        {
            /* Skip any null value */
            if($value === NULL || (is_array($skipProps) && in_array($name, $skipProps))) continue;

            /* Convert non-string to json */
            if(!is_string($value)) $value = json_encode($value);

            $pairs[] = $name . '="' . htmlspecialchars($value) . '"';
        }

        return implode(' ', $pairs);
    }

    /**
     * Create an properties instance
     *
     * @param array $props - Properties list array
     * @return props
     */
    static public function new($props)
    {
        return new props($props);
    }

    /**
     * Stringify properties
     *
     * @access public
     * @param array $props - Properties list array
     * @return string
     */
    static public function str($props, $skipCustomProps = NULL)
    {
        return (new props($props))->toStr($skipCustomProps);
    }
}
