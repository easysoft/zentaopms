<?php
/**
 * The hx class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\utils;

require_once 'dataset.class.php';

/**
 * Manage hx for html element and widgets
 *
 * Example:
 *
 *     // Create a hx object an convert to str string
 *     $hx = hx::new()->boost();
 *
 *     echo $hx(); // Output 'hx-boost="true"'
 *
 * @see https://htmx.org/
 * @todo @sunhao: Validate hx properties on modifying
 */
class hx extends dataset
{
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
        if(str_starts_with($prop, 'hx-')) $prop = substr($prop, 3);
        return parent::setVal($prop, $value);
    }

    /**
     * Set ajax request
     *
     * @access public
     * @param string $url     - The request url
     * @param string $trigger - The trigget
     * @param string $target  - A css selector to specific a element to load remote content
     * @param string $method  - The request method, default value is "get"
     * @return hx
     * @see https://htmx.org/docs/#ajax
     */
    public function ajax($url, $trigger = '', $target = '', $method = 'get')
    {
        if(is_array($url)) return $this->set($url);

        return $this->set(array('url' => $url, 'trigger' => $trigger, 'target' => $target, 'method' => $method));
    }

    /**
     * Set ajax post request
     *
     * @access public
     * @param string $url     - The request url
     * @param string $trigger - The trigget
     * @param string $target  - A css selector to specific a element to load remote content
     * @return hx
     * @see https://htmx.org/docs/#ajax
     */
    public function post($url, $trigger = '', $target = '')
    {
        return $this->ajax($url, $trigger, $target, 'post');
    }

    /**
     * Convert hx properties to str string
     *
     * @access public
     * @return string
     */
    public function toStr()
    {
        $pairs = array();

        foreach($this->data as $name => $value)
        {
            /* Skip any null value */
            if($value === NULL) continue;

            /* Convert non-string to json */
            if(!is_string($value)) $value = json_encode($value);

            $pairs[] = 'hx-' . $name . '="' . htmlspecialchars($value) . '"';
        }

        return implode(' ', $pairs);
    }

    /**
     * Create an instance
     *
     * @param string $hx - CSS hx list
     * @return hx
     */
    static public function new($hx)
    {
        return new hx($hx);
    }

    /**
     * Create properties string from hx list
     *
     * @access public
     * @param string $hx - CSS hx list
     * @return string
     */
    static public function str($props)
    {
        return (new hx($props))->toStr();
    }
}
