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

class context extends \zin\utils\dataset
{
    public function addHookFiles(string|array ...$files)
    {
        $files = flat($files);
        return $this->addToList('hookFiles', ...$files);
    }

    public function getHookFiles(): array
    {
        return $this->getList('hookFiles');
    }

    public function addImport(string ...$files)
    {
        return $this->addToList('import', ...$files);
    }

    public function getImportList(): array
    {
        return $this->getList('import');
    }

    public function addCSS(string ...$cssList)
    {
        return $this->addToList('css', ...$cssList);
    }

    public function getCSS(): string
    {
        return trim(implode("\n", $this->getList('css')));
    }

    public function addJS(string ...$jsList)
    {
        return $this->addToList('js', ...$jsList);
    }

    public function addJSVar(string $name, mixed $value)
    {
        return $this->addToList('jsVar', h::createJsVarCode($name, $value));
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

    public static $map = array();

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
        call_user_func_array(array($context, 'addImport'), func_get_args());
    }

    /**
     * Get current context.
     *
     * @access public
     * @return context
     */
    public static function current(): context
    {
        if(empty(static::$map)) static::$map['current'] = new context();
        return static::$map['current'];
    }

    /**
     * Create widget context.
     *
     * @access public
     * @param string $gid  The widget gid.
     * @return context
     */
    public static function create(string $gid): context
    {
        if(isset(static::$map[$gid])) return static::$map[$gid];
        $context = new context();
        static::$map[$gid] = $context;
        return $context;
    }

    /**
     * Destroy widget context.
     *
     * @access public
     * @param string $gid  The widget gid.
     * @return void
     */
    public static function destroy(string $gid = null): void
    {
        if($gid === null) unset(static::$map['current']);
        elseif(isset(static::$map[$gid])) unset(static::$map[$gid]);
    }
}
