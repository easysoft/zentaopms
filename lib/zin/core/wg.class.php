<?php
declare(strict_types=1);
/**
 * The base widget class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'node.class.php';

class wg extends node
{
    // public function buildEvents(): ?string
    // {
    //     $events = $this->props->events();
    //     if(empty($events)) return null;

    //     $id   = $this->id();
    //     $code = array($this->shortType() === 'html' ? 'const ele = document;' : 'const ele = document.getElementById("' . (empty($id) ? $this->gid : $id) . '");if(!ele)return;const $ele = $(ele); const events = new Set(($ele.attr("data-zin-events") || "").split(" ").filter(Boolean));');
    //     foreach($events as $event => $bindingList)
    //     {
    //         $code[]   = "\$ele.on('$event.on.zin', function(e){";
    //         foreach($bindingList as $binding)
    //         {
    //             if(is_string($binding)) $binding = (object)array('handler' => $binding);
    //             $selector = isset($binding->selector) ? $binding->selector : null;
    //             $handler  = isset($binding->handler) ? trim($binding->handler) : '';
    //             $stop     = isset($binding->stop) ? $binding->stop : null;
    //             $prevent  = isset($binding->prevent) ? $binding->prevent : null;
    //             $self     = isset($binding->self) ? $binding->self : null;

    //             $code[]   = '(function(){';
    //             if($selector) $code[] = "const target = e.target.closest('$selector');if(!target) return;";
    //             else          $code[] = "const target = ele;";
    //             if($self)     $code[] = "if(ele !== e.target) return;";
    //             if($stop)     $code[] = "e.stopPropagation();";
    //             if($prevent)  $code[] = "e.preventDefault();";

    //             if(preg_match('/^[$A-Z_][0-9A-Z_$\[\]."\']*$/i', $handler)) $code[] = "($handler).call(target,e);";
    //             else $code[] = $handler;

    //             $code[] = '})();';
    //         }
    //         $code[] = "});events.add('$event');";
    //     }
    //     $code[] = '$ele.attr("data-zin-events", Array.from(events).join(" "));';
    //     return h::createJsScopeCode($code);
    // }

    public static function getPageCSS(): ?string
    {
        return null; // No css
    }

    public static function getPageJS(): ?string
    {
        return null; // No js
    }

    protected static function checkPageResources()
    {
        $name = get_called_class();
        if(isset(static::$pageResources[$name])) return;

        static::$pageResources[$name] = true;

        $pageCSS = static::getPageCSS();
        $pageJS  = static::getPageJS();

        if(!empty($pageCSS)) context::css($pageCSS);
        if(!empty($pageJS))  context::js($pageJS);
    }

    protected static array $pageResources = array();
}

/**
 * Create an new widget.
 *
 * @return wg
 */
function wg(mixed ...$args): wg
{
    return new wg(...$args);
}
