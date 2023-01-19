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

namespace zin\core;

require_once 'ele.class.php';

class wg extends ele
{
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

    protected function acceptChild($child, $strAsHtml = false)
    {
        $child = parent::acceptChild($child, $strAsHtml);
        if(is_object($child) && isset($child->custom) && $child->custom)
        {
            if(isset($child->slots))
            {
                call_user_func_array(array($this, 'appendTo'), $child->slots);
                unset($child->slots);
            }
            if(isset($child->css))
            {
                $this->css($child->css);
                unset($child->css);
            }
            if(isset($child->js))
            {
                $this->js($child->js);
                unset($child->js);
            }
            if(isset($child->js))
            {
                $this->js($child->js);
                unset($child->js);
            }
            if(isset($child->js))
            {
                $this->js($child->js);
                unset($child->js);
            }
            if(isset($child->import))
            {
                $this->import($child->import);
                unset($child->import);
            }
        }
        return $child;
    }

    public function appendTo()
    {
        $args = func_get_args();
        $slot = array_shift($args);

        if(!isset($this->slots[$slot])) $this->slots[$slot] = array();

        foreach($args as $child)
        {
            if(is_array($child)) $this->slots[$slot] = array_merge($this->slots[$slot], $child);
            else $this->slots[$slot][] = $child;
        }

        return $this;
    }

    public function prependTo()
    {
        $args = func_get_args();
        $slot = array_shift($args);

        if(!isset($this->slots[$slot])) $this->slots[$slot] = array();

        foreach($args as $child)
        {
            if(is_array($child)) $this->slots[$slot] = array_merge($child, $this->slots[$slot]);
            else array_unshift($this->slots[$slot], $child);
        }

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

        if($reset)             $this->cssList   = array($css);
        elseif(is_array($css)) $this->cssList   = array_merge($this->cssList, $css);
        else                   $this->cssList[] = $css;

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

        if($reset)            $this->jsList   = array($js);
        elseif(is_array($js)) $this->jsList   = array_merge($this->jsList, $js);
        else                  $this->jsList[] = $js;

        return $this;
    }

    public function import($file, $type = '')
    {
        if(is_array($file))
        {
            foreach($file as $f) $this->import($f, $type);
            return $this;
        }

        if(empty($type)) $type = substr($file, -strlen('.css')) === '.css' ? 'css' : 'js';

        if($type === 'css') return $this->importCss($file);
        return $this->importJs($file);
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
    static public function str()
    {
        return (new wg(func_get_args()))->toStr();
    }
}
