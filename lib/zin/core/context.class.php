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
require_once __DIR__ . DS . 'helper.func.php';
require_once __DIR__ . DS . 'render.class.php';
require_once __DIR__ . DS . 'js.class.php';

class context extends \zin\utils\dataset
{
    public string $name;

    public array $globalRenderList = array();

    public int $globalRenderLevel = 0;

    public array $data = array();

    public bool $rendered = false;

    public bool $rawContentCalled = false;

    public bool $hookContentCalled = false;

    public array $beforeBuildNodeCallbacks = array();

    public array $onBuildNodeCallbacks = array();

    public array $onRenderNodeCallbacks = array();

    public array $onRenderCallbacks = array();

    public ?render $renderer = null;

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
        return $this->addToList('jsVar', js::defineVar($name, $value));
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

    public function getDebugData() : ?array
    {
        global $app, $config;
        $zinDebug = null;
        if(isDebug() && (!isAjaxRequest() || isAjaxRequest('zin')))
        {
            $zinDebug = data('zinDebug');
            if(is_array($zinDebug))
            {
                $zinDebug['basePath'] = $app->getBasePath();
                if(isset($app->zinErrors)) $zinDebug['errors'] = $app->zinErrors;
            }
        }
        return $zinDebug;
    }

    public function getRawContent(): string
    {
        $rawContent = ob_get_contents();
        if(!is_string($rawContent)) $rawContent = '';
        ob_end_clean();
        return $rawContent;
    }

    public function render(node $node, null|object|string|array $selectors = null, string $renderType = 'html', null|string|array $dataCommands = null, bool $renderInner = false): string|array|object
    {
        $renderer = new render($node, $selectors, $renderType, $dataCommands, $renderInner);
        $this->rendered = true;
        $this->renderer = $renderer;

        $rawContent = $this->getRawContent();
        $zinDebug   = $this->getDebugData();
        $result     = $renderer->render();
        $js         = $this->getJS();
        $css        = $this->getCSS();

        if(is_object($result)) // renderType = json
        {
            if($zinDebug && isset($result->zinDebug)) $result->zinDebug = $zinDebug;
            $result = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        elseif(is_array($result)) // renderType = list
        {
            foreach($result as $item)
            {
                if($item->name === 'zinDebug' && $zinDebug)
                {
                    $item->data = $zinDebug;
                    continue;
                }
                if(!isset($item->type) || $item->type !== 'html') continue;

                $replace = array('<!-- {{RAW_CONTENT}} -->' => $rawContent, '<!-- {{HOOK_CONTENT}} -->' => $hookHtml, '/*{{ZIN_PAGE_CSS}}*/' => $css, '/*{{ZIN_PAGE_JS}}*/' => $js);
                $item->data    = str_replace(array_keys($replace), array_values($replace), $item->data);
            }

            $result = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        else // renderType = html
        {
            if($zinDebug) $js .= js::defineVar('window.zinDebug', $zinDebug);

            $replace = array('<!-- {{RAW_CONTENT}} -->' => $rawContent, '<!-- {{HOOK_CONTENT}} -->' => $hookHtml, '/*{{ZIN_PAGE_CSS}}*/' => $css, '/*{{ZIN_PAGE_JS}}*/' => $js);
            $result  = str_replace(array_keys($replace), array_values($replace), $result);
        }

        $data = new stdClass();
        $data->type   = $renderType;
        $data->output = $result;

        foreach($this->onRenderCallbacks as $callback)
        {
            call_user_func($callback, $data);
        }

        return $data->output;
    }

    public function handleBeforeBuildNode($node)
    {
        foreach($this->beforeBuildNodeCallbacks as $callback)
        {
            call_user_func($callback, $node);
        }
    }

    public function handleBuildNode(stdClass &$data, node $node)
    {
        foreach($this->onBuildNodeCallbacks as $callback)
        {
            call_user_func($callback, $data, $node);
        }

        if($this->renderer) $this->renderer->handleBuildNode($data, $node);
    }

    public function handleRenderNode(stdClass &$data, node $node)
    {
        foreach($this->onRenderNodeCallbacks as $callback)
        {
            call_user_func($callback, $data, $node);
        }
    }

    public function onBuildNode(callable $callback)
    {
        $this->onBuildNodeCallbacks[] = $callback;
    }

    public function onRenderNode(callable $callback)
    {
        $this->onRenderNodeCallbacks[] = $callback;
    }

    public static array $stack = array();

    public static function js(string ...$code)
    {
        static::current()->addJS(...flat($code));
    }

    public static function jsCall(string ...$code)
    {
        static::current()->addJSCall(...flat($code));
    }

    public static function jsVar($name, $value)
    {
        static::current()->addJSVar($name, $value);
    }

    public static function css(string ...$code)
    {
        static::current()->addCSS(...flat($code));
    }

    public static function import(string ...$files)
    {
        static::current()->addImports(...$files);
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
        if(isset(static::$stack[$name]))
        {
            triggerError("Context name \"$name\" already exists.");
        }
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
