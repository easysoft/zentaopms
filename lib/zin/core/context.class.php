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
require_once __DIR__ . DS . 'command.class.php';
require_once __DIR__ . DS . 'js.class.php';

class context extends \zin\utils\dataset
{
    public string $name;

    public array $globalRenderList = array();

    public int $globalRenderLevel = 0;

    public array $data = array();

    public array $debugData = array();

    public ?\control $control = null;

    public bool $rendered = false;

    public bool $rawContentCalled = false;

    public bool $hookContentCalled = false;

    public array $beforeBuildNodeCallbacks = array();

    public array $onBuildNodeCallbacks = array();

    public array $onRenderNodeCallbacks = array();

    public array $onRenderCallbacks = array();

    public array $queries = array();

    public ?node $rootNode = null;

    public ?render $renderer = null;

    public array $pageJS = array();

    public array $pageCSS = array();

    public array $jsVars = array();

    public array $jsCalls = array();

    public array $wgRes = array();

    public array $eventBindings = array();

    public array $renderWgMap = array('page' => 'page', 'modal' => 'modalDialog', 'fragment' => 'fragment');

    public array $rawContentNames = array();

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
            'rawContentNames'     => $this->rawContentNames
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
            if($item->parent) return false;

            $type = $item->type();
            if($type === 'wg' || $type === 'node') return false;

            if(!isset($this->globalRenderList[$item->gid])) $this->globalRenderList[$item->gid] = $item;
            return true;
        }

        if(in_array($item, $this->globalRenderList)) return false;

        $this->globalRenderList[] = $item;
        return true;
    }

    function skipRenderInGlobal(mixed $data)
    {
        if(is_array($data))
        {
            foreach($data as $item) skipRenderInGlobal($item);
            return;
        }

        if($data instanceof node || $data instanceof iDirective)
        {
            if(isset($data->gid)) unset($this->globalRenderList[$data->gid]);
            $data->notRenderInGlobal = true;
        }
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

    public function addCSS(string|array $css, ?string $name = null)
    {
        if(is_array($css)) $css = implode("\n", $css);

        if($name)
        {
            if(isset($this->pageCSS[$name]))
            {
                if(isDebug()) triggerError("Page CSS name \"$name\" already exists.");
                return;
            }
            $this->pageCSS[$name] = $css;
        }
        else
        {
            $this->pageCSS[] = $css;
        }
    }

    public function getCSS(): string
    {
        $css   = array();
        $wgRes = $this->wgRes;

        if($wgRes) foreach ($wgRes as $res) if($res['css']) $css[] = $res['css'];

        if($this->pageCSS) $css = array_merge($css, $this->pageCSS);
        return trim(implode("\n", $css));
    }

    public function addJS(string|array $js, ?string $name = null)
    {
        if(is_array($js)) $js = implode("\n", $js);

        if($name)
        {
            if(isset($this->pageJS[$name]))
            {
                if(isDebug()) triggerError("Page JS name \"$name\" already exists.");
                return;
            }
            $this->pageJS[$name] = $js;
        }
        else
        {
            $this->pageJS[] = $js;
        }
    }

    public function addJSVar(string $name, mixed $value)
    {
        $this->jsVars[$name] = $value;
    }

    public function addJSCall($func, $args)
    {
        $this->jsCalls[] = array($func, $args);
    }

    public function getJS()
    {
        $jsVars        = $this->jsVars;
        $pageJS        = $this->pageJS;
        $jsCalls       = $this->jsCalls;
        $wgRes         = $this->wgRes;
        $eventBindings = $this->eventBindings;
        $js            = array();

        if($wgRes)         foreach ($wgRes as $res) if($res['js']) $js[] = js::scope($res['js']);
        if($jsVars)        foreach($jsVars as $name => $value) $js[] = js::defineVar($name, $value);
        if($pageJS)        $js = array_merge($js, $pageJS);
        if($eventBindings) $js = array_merge($js, $eventBindings);
        if($jsCalls)       foreach($jsCalls as $call) $js[] = js::defineJSCall($call[0], $call[1]);

        if(empty($js)) return '';

        $js = trim(implode("\n", $js));
        if(strpos($js, 'setTimeout') !== false)  $js = 'function setTimeout(callback, time){return typeof window.registerTimer === "function" ? window.registerTimer(callback, time) : window.setTimeout(callback, time);}' . $js;
        if(strpos($js, 'setInterval') !== false) $js = 'function setInterval(callback, time){return typeof window.registerTimer === "function" ? window.registerTimer(callback, time, "interval") : window.setInterval(callback, time);}' . $js;

        $methods = array('onPageUnmount', 'beforePageUpdate', 'afterPageUpdate', 'onPageRender');
        foreach($methods as $method)
        {
            if(strpos($js, $method) === false) continue;
            $js .= "if(typeof $method === 'function') window.$method = $method;";
        }
        return $js;
    }

    public function addWgRes(string $wgClass)
    {
        if(isset($this->wgRes[$wgClass])) return;

        $res = array();
        $res['css'] = $wgClass::getPageCSS();
        $res['js']  = $wgClass::getPageJS();
        $this->wgRes[$wgClass] = $res;
    }

    public function addDebugData(string $name, mixed ...$values)
    {
        $e         = new \Exception();
        $trace     = $e->getTraceAsString();
        $trace     = str_replace($this->control->app->basePath, '', $trace);
        $stack     = explode("\n", $trace);
        if(str_contains($stack[0], 'lib/zin/core/context.func.php')) array_shift($stack);

        $finalName = $name;
        if(empty($finalName))
        {
            $statement = $stack[0];
            if(str_contains($stack[0], '): zin\d('))
            {
                $statement = explode('): zin\d(', $statement)[1];
                if(str_contains($statement, ',')) $finalName = explode(',', $statement)[0];
                else                              $finalName = explode(')', $statement)[0];
            }
            else
            {
                $finalName = 'dump';
            }
        }

        $isJson = !str_starts_with($name, '$');
        $data   = $values;
        if($isJson)
        {
            $data = json_encode($values);
            if($data === false) $isJson = false;
            else                $data = jsRaw($data);
        }
        if(!$isJson) $data = array_map(function($value) {return var_export($value, true);}, $values);
        $this->debugData[] = array('name' => $finalName, 'data' => $data, 'type' => $isJson ? 'json' : 'var', 'trace' => $stack);
    }

    public function getDebugData() : ?array
    {
        global $app;
        $zinDebug = null;
        if(isDebug() && (!isAjaxRequest() || isAjaxRequest('zin')))
        {
            $zinDebug = data('zinDebug');
            if(is_array($zinDebug))
            {
                $zinDebug['basePath'] = $app->getBasePath();
                $zinDebug['debug']    = $this->debugData;
                if(isset($app->zinErrors)) $zinDebug['errors'] = $app->zinErrors;
            }
        }
        return $zinDebug;
    }

    public function getRawContent(): string
    {
        $rawContent = ob_get_contents();
        if(!is_string($rawContent)) $rawContent = '';
        if(!empty(ob_get_status(true))) ob_end_clean();
        return $rawContent;
    }

    public function addQuery(query $query)
    {
        $this->queries[] = $query;
    }

    /**
     * Include hooks files.
     */
    public function includeHooks(): string
    {
        $hookFiles = $this->getHookFiles();
        ob_start();
        foreach($hookFiles as $hookFile)
        {
            if(!empty($hookFile) && file_exists($hookFile)) include $hookFile;
        }
        $hookCode = ob_get_clean();
        ob_end_flush();
        if($hookCode) return $hookCode;
        return '';
    }

    public function render(node $node, null|object|string|array $selectors = null, string $renderType = 'html', bool $renderInner = false): string|array|object
    {
        $this->disableGlobalRender();

        $renderer = new render($node, $selectors, $renderType, $renderInner);
        $this->rendered = true;
        $this->renderer = $renderer;
        $this->rootNode = $node;

        $hookCode   = $this->includeHooks();
        $rawContent = $this->getRawContent();

        $node->prebuild(true);
        $this->applyQueries($node);

        $result = $renderer->render();
        if(is_object($result)) // renderType = json
        {
            $zinDebug = $this->getDebugData();
            if($zinDebug && isset($result->zinDebug)) $result->zinDebug = $zinDebug;
            $result = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        else
        {
            $js      = $this->getJS();
            $css     = $this->getCSS();
            $replace = array('<!-- {{HOOK_CONTENT}} -->' => $hookCode, '/*{{ZIN_PAGE_CSS}}*/' => $css, '/*{{ZIN_PAGE_JS}}*/' => $js);

            /* 如果存在 rawContentNames，则将 rawContent 中的内容块替换为对应名称的内容块。 */
            /* If rawContentNames exists, replace the content block in rawContent with the content block of the corresponding name. */
            if($this->rawContentNames)
            {
                $rawContentMap = parseRawContent($rawContent);
                $replace['<!-- {{RAW_CONTENT}} -->']     = $rawContentMap['GLOBAL'];
                $replace['<!-- {{RAW_CONTENT:ALL}} -->'] = $rawContent;
                foreach ($this->rawContentNames as $name => $_)
                {
                    if(!isset($rawContentMap[$name]))
                    {
                        if(isDebug()) triggerError("The content of rawContent(\"$name\") not found.");
                        continue;
                    }
                    $replace["<!-- {{RAW_CONTENT:{$name}}} -->"] = $rawContentMap[$name];
                }
            }
            else
            {
                $replace['<!-- {{RAW_CONTENT}} -->'] = $rawContent;
            }

            $zinDebug = $this->getDebugData();
            if(is_array($result)) // renderType = list
            {
                foreach($result as $index => $item)
                {
                    if($item->name === 'zinDebug' && $zinDebug)
                    {
                        $result[$index] = array('zinDebug:<BEGIN>', $zinDebug);
                        continue;
                    }
                    if($item->name === 'hookCode')
                    {
                        $item->data = $hookCode;
                        continue;
                    }
                    if(!isset($item->type) || $item->type !== 'html') continue;

                    $item->data = str_replace(array_keys($replace), array_values($replace), $item->data);
                }
                $result = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
            }
            else // renderType = html
            {
                if($zinDebug) $js .= js::defineVar('window.zinDebug', $zinDebug);
                $result  = str_replace(array_keys($replace), array_values($replace), $result);
            }
        }

        $data = new stdClass();
        $data->type   = $renderType;
        $data->output = $result;

        foreach($this->onRenderCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($data, $node);
            else call_user_func($callback, $data, $node);
        }

        $this->enabledGlobalRender();
        return $data->output;
    }

    protected function applyQueries(node $rootNode)
    {
        if(!$this->queries) return;

        foreach($this->queries as $query)
        {
            $this->applyQuery($rootNode, $query);
        }
    }

    protected function applyQuery(node $rootNode, query $query)
    {
        $nodes = $query->isRoot() ? array($rootNode) : findInNode($query->selectors, $rootNode, false, false, false);
        if(!$nodes) return;

        $queryNodes = $nodes;
        foreach($query->commands as $command)
        {
            list($method, $args) = $command;
            $result = call_user_func("\zin\command::{$method}", $queryNodes, ...$args);
            if(is_array($result))  $queryNodes = $result;
            if(empty($queryNodes)) break;
            prebuild($queryNodes);
        }
    }

    public function handleBeforeBuildNode(node $node)
    {
        foreach($this->beforeBuildNodeCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($node);
            else call_user_func($callback, $node);
        }
    }

    public function handleBuildNode(stdClass &$data, node $node)
    {
        foreach($this->onBuildNodeCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($data, $node);
            else call_user_func($callback, $data, $node);
        }

        if($node instanceof wg)
        {
            $class = get_class($node);
            if(!isset($this->wgRes[$class]))
            {
                $res = array();
                $res['css'] = $class::getPageCSS();
                $res['js']  = $class::getPageJS();
                $this->wgRes[$class] = $res;
            }
        }

        if($this->renderer) $this->renderer->handleBuildNode($data, $node);

        $eventBinding = $node->buildEvents();
        if($eventBinding) $this->eventBindings[] = $eventBinding;
    }

    public function handleRenderNode(stdClass &$data, node $node)
    {
        foreach($this->onRenderNodeCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($data, $node);
            else call_user_func($callback, $data, $node);
        }
    }

    public function onBuildNode(callable|\Closure $callback)
    {
        $this->onBuildNodeCallbacks[] = $callback;
    }

    public function onRenderNode(callable|\Closure $callback)
    {
        $this->onRenderNodeCallbacks[] = $callback;
    }

    public function onBeforeBuildNode(callable|\Closure $callback)
    {
        $this->beforeBuildNodeCallbacks[] = $callback;
    }

    public function onRender(callable|\Closure $callback)
    {
        $this->onRenderCallbacks[] = $callback;
    }

    public function setRenderWgMap(string|array $mapOrName, ?string $wgName = null)
    {
        if(is_array($mapOrName))
        {
            $this->renderWgMap = array_merge($this->renderWgMap, $mapOrName);
        }
        else
        {
            $this->renderWgMap[$mapOrName] = $wgName;
        }
    }

    public function getRenderWgName()
    {
        if(isset($this->renderWgMap['all']))         return $this->renderWgMap['all'];
        if(isAjaxRequest('modal'))                   return $this->renderWgMap['modal'];
        if(isAjaxRequest() && !isAjaxRequest('zin')) return $this->renderWgMap['fragment'];
        return 'page';
    }

    public static array $stack = array();

    public static function js(string ...$code)
    {
        static::current()->addJS(flat($code));
    }

    public static function jsCall(string $func, mixed ...$args)
    {
        static::current()->addJSCall($func, $args);
    }

    public static function jsVar($name, $value)
    {
        static::current()->addJSVar($name, $value);
    }

    public static function css(string ...$code)
    {
        static::current()->addCSS(flat($code));
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
