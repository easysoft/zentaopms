<?php
declare(strict_types=1);
/**
 * The context class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

use function zin\utils\flat;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'deep.func.php';

class context extends \zin\utils\dataset
{
    public string $name;

    public array $globalRenderList = array();

    public int $globalRenderLevel = 0;

    public array $data = array();

    public bool $rendered = false;

    public bool $rawContentCalled = false;

    public function __construct(string $name)
    {
        parent::__construct();
        $this->name = $name;
    }

    public function __debugInfo(): array
    {
        return array_merge(array
        (
            'name'                => $this->name,
            'globalRenderListLen' => count($this->globalRenderList),
            'globalRenderList'    => $this->globalRenderList,
            'globalRenderLevel'   => $this->globalRenderLevel,
            'rendered'            => $this->rendered,
            'rawContentCalled'    => $this->rawContentCalled,
        ), $this->storedData);
    }

    public function getData(string $namePath, mixed $defaultValue = null): mixed
    {
        return \zin\utils\deepGet($this->data, $namePath, $defaultValue);
    }

    public function setData(string $namePath, mixed $value)
    {
        \zin\utils\deepSet($this->data, $namePath, $value);
    }

    public function enableGlobalRender()
    {
        $this->globalRenderLevel--;
    }

    public function disableGlobalRender()
    {
        $this->globalRenderLevel++;
    }

    public function enabledGlobalRender()
    {
        return $this->globalRenderLevel < 1;
    }

    public function renderInGlobal(node|iDirective $item): bool
    {
        if($this->globalRenderLevel > 0)
        {
            return false;
        }

        if($item instanceof node)
        {
            if($item->parent || $item->type() === 'wg') return false;

            if(!isset($this->globalRenderList[$item->gid])) $this->globalRenderList[$item->gid] = $item;
            return true;
        }

        if(in_array($item, $this->globalRenderList)) return false;

        $this->globalRenderList[] = $item;
        return true;
    }

    public function getGlobalRenderList(bool $clear = true): array
    {
        $globalItems = array();

        foreach($this->globalRenderList as $item)
        {
            if(is_object($item) && ((isset($item->parent) && $item->parent) || (isset($item->notRenderInGlobal) && $item->notRenderInGlobal)))
            {
                continue;
            }
            $globalItems[] = $item;
        }

        /* Clear globalRenderList. */
        if($clear) $this->globalRenderList = array();

        return $globalItems;
    }

    public function addHookFiles(string|array ...$files)
    {
        $files = flat($files);
        return $this->mergeToList('hookFiles', array_filter(array_values($files)));
    }

    public function getHookFiles(): array
    {
        return $this->getList('hookFiles');
    }

    public function addImports(string ...$files)
    {
        return $this->mergeToList('import', $files);
    }

    public function getImports(): array
    {
        return $this->getList('import');
    }

    public function addCSS(string ...$cssList)
    {
        return $this->mergeToList('css', $cssList);
    }

    public function getCSS(): string
    {
        return trim(implode("\n", $this->getList('css')));
    }

    public function addJS(string ...$jsList)
    {
        return $this->mergeToList('js', $jsList);
    }

    public function addJSVar(string $name, mixed $value)
    {
        // return $this->addToList('jsVar', h::createJsVarCode($name, $value));
    }

    public function addWgWithEvents($wg)
    {
        $list = $this->getWgWithEventsList();
        if(in_array($wg, $list)) return $this;
        return $this->addToList('wgWithEvents', $wg);
    }

    public function getWgWithEventsList()
    {
        return $this->getList('wgWithEvents');
    }

    public function addJSCall($func, $args)
    {
        $code = call_user_func('\zin\h::createJsCallCode', $func, $args);
        return $this->addToList('jsCall', $code);
    }

    public function getEventsBindings()
    {
        $wgs   = $this->getList('wgWithEvents');
        $codes = array();
        foreach($wgs as $wg)
        {
            if(!method_exists($wg, 'buildEvents')) continue;
            $code = $wg->buildEvents();
            if(!empty($code)) $codes[] = $code;
        }
        return $codes;
    }

    public function getJS()
    {
        $js = trim(implode("\n", array_merge($this->getList('jsVar'), $this->getList('js'), $this->getEventsBindings(), $this->getList('jsCall'))));
        if(empty($js)) return '';

        if(strpos($js, 'setTimeout') !== false) $js = 'function setTimeout(callback, time){return typeof window.registerTimer === "function" ? window.registerTimer(callback, time) : window.setTimeout(callback, time);}' . $js;
        if(strpos($js, 'setInterval') !== false) $js = 'function setInterval(callback, time){return typeof window.registerTimer === "function" ? window.registerTimer(callback, time, "interval") : window.setInterval(callback, time);}' . $js;

        $methods = array('onPageUnmount', 'beforePageUpdate', 'afterPageUpdate', 'onPageRender');
        foreach($methods as $method)
        {
            if(strpos($js, $method) !== false) $js .= "if(typeof $method === 'function') window.$method = $method;";
        }
        return $js;
    }

    public static array $stack = array();

    public static function js(/* string ...$code */)
    {
        $context = static::current();
        call_user_func_array(array($context, 'addJS'), \zin\utils\flat(func_get_args()));
    }

    public static function jsCall(/* string ...$code */)
    {
        $context = static::current();
        call_user_func_array(array($context, 'addJSCall'), func_get_args());
    }

    public static function jsVar($name, $value)
    {
        $context = static::current();
        $context->addJSVar($name, $value);
    }

    public static function css(/* string ...$code */)
    {
        $context = static::current();
        call_user_func_array(array($context, 'addCSS'), \zin\utils\flat(func_get_args()));
    }

    public static function import(/* string ...$files */)
    {
        $context = static::current();
        call_user_func_array(array($context, 'addImports'), func_get_args());
    }

    /**
     * Get current context.
     *
     * @access public
     * @return context
     */
    public static function current(): context
    {
        if(empty(static::$stack))
        {
            $context = new context('default');
            static::$stack['default'] = $context;
            return $context;
        }
        return end(static::$stack);
    }

    /**
     * Create context.
     *
     * @access public
     * @param string $name  Context name.
     * @return context
     */
    public static function create(string $name): context
    {
        if(isset(static::$stack[$name])) return static::$stack[$name];
        $context = new context($name);
        static::$stack[$name] = $context;
        return $context;
    }

    /**
     * Pop last context.
     *
     * @access public
     * @return ?context
     */
    public static function pop(): ?context
    {
        return array_pop(static::$stack);
    }
}
