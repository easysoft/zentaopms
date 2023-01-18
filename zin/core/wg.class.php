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

    public $jsImports = array();

    public $cssImports = array();

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent)
            ->js($this->jsList)
            ->css($this->cssList);

        if(is_array(static::$imports) && (!$isPrint || isset(static::$imports['imported'])))
        {
            if(isset(static::$imports['css'])) $builder->importCss(static::$imports['css']);
            if(isset(static::$imports['js'])) $builder->importJs(static::$imports['js']);
            static::$imports['imported'] = true;
        }

        $builder->importJs($this->jsImports)
            ->importCss($this->cssImports);

        return $builder;
    }

    public function appendTo()
    {
        $args = func_get_args();
        $slot = array_shift($args);

        if(!isset($this->slots[$slot])) $this->slots[$slot] = array();

        $this->slots[$slot]= array_merge($this->slots[$slot], $args);

        return $this;
    }

    public function prependTo()
    {
        $args = func_get_args();
        $slot = array_shift($args);

        if(!isset($this->slots[$slot])) $this->slots[$slot] = array();

        $this->slots[$slot]   = array_merge($args, $this->slots[$slot]);

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

    public function importJs($jsFile)
    {
        if(is_array($jsFile)) $this->jsImports = array_merge($this->jsImports, $jsFile);
        else $this->jsImports[] = $jsFile;
        return $this;
    }

    public function importCss($cssFile)
    {
        if(is_array($cssFile)) $this->cssImports = array_merge($this->cssImports, $cssFile);
        else $this->cssImports[] = $cssFile;
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
