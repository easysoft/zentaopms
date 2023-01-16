<?php
/**
 * The wg class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

require_once 'ele.class.php';

class wg extends ele
{
    public static $tag = __CLASS__;

    public static $imports = NULL;

    public $slots = array();

    public $jsList = array();

    public $cssList = array();

    protected function getCssCode()
    {
        if(empty($this->cssList)) return '';
        return implode('', $this->cssList);
    }

    protected function getJsCode()
    {
        if(empty($this->jsList)) return '';
        return '(function(){'. implode('', $this->jsList) . '}());';
    }

    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $cssCode = $this->getCssCode();
        $jsCode  = $this->getJsCode();
        $suffix  = array();

        if(!empty($cssCode)) $suffix[] = "<style>$cssCode</style>";
        if(!empty($jsCode))  $suffix[] = "<script>$jsCode</script>";

        if(!empty($suffix))
        {
            $suffix = implode('\n', $suffix);
            if(isset($builder->suffixCode)) $builder->suffixCode .= $suffix;
            else $builder->suffixCode = $suffix;
        }

        return $builder;
    }

    /**
     * @param mixed $children
     */
    public function append($children, $slot = NULL)
    {
        if(!empty($slot)) return $this->appendToSlot($slot, $children);

        return parent::append($children);
    }

    public function appendToSlot($slot, $children)
    {
        if(!isset($this->slots[$slot])) $this->slots[$slot] = array();

        if(is_array($children)) $this->slots[$slot]   = array_merge($this->slots[$slot], $children);
        else                    $this->slots[$slot][] = $children;

        return $this;
    }

    public function prepend($children, $slot = NULL)
    {
        if(!empty($slot)) return $this->prependToSlot($slot, $children);

        return parent::prepend($children);
    }

    public function prependToSlot($slot, $children)
    {
        if(!isset($this->slots[$slot])) $this->slots[$slot] = array();

        if(is_array($children)) $this->slots[$slot]   = array_merge($children, $this->slots[$slot]);
        else                    array_unshift($this->slots[$slot], $children);

        return $this;
    }

    /**
     * Get or set css
     *
     * @param string $css
     * @param boolean $reset
     * @return mixed
     */
    public function css($css = NULL, $reset = false)
    {
        if($css === NULL) return $this->getCssCode();

        if($reset) $this->cssList = array($css);
        else       $this->cssList = array_merge($this->cssList, $css);

        return $this;
    }

    /**
     * Get or set js
     *
     * @param string $js
     * @param boolean $reset
     * @return mixed
     */
    public function js($js = NULL, $reset = false)
    {
        if($js === NULL) return implode("\n", $this->jsList);

        if($reset) $this->jsList = array($js);
        else       $this->jsList = array_merge($this->jsList, $js);

        return $this;
    }

    /**
     * Create an instance of wg
     *
     * @return wg
     */
    static public function new()
    {
        return new wg(func_get_args());
    }

    /**
     * Stringify wg to html
     *
     * @access public
     * @return string
     */
    static public function str($tagName, $props = NULL, $children = NULL)
    {
        return (new wg($tagName, $props, $children))->toStr();
    }
}
