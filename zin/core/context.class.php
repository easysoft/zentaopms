<?php
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

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once 'portal.class.php';

class context extends \zin\utils\dataset
{
    /**
     * @var object
     */
    public $root;

    public function __construct($root)
    {
        $this->root = $root;
    }

    public function isRoot($root)
    {
        if(is_string($root)) return $root === $this->root->gid;
        return $root->gid === $this->root->gid;
    }

    public function getPortals()
    {
        return $this->getList('portals');
    }

    public function addPortal($portal)
    {
        return $this->addToList('portals', $portal);
    }

    public function addImport()
    {
        return $this->addToList('import', func_get_args());
    }

    public function getImportList()
    {
        return $this->getList('import');
    }

    public function addCSS()
    {
        return $this->addToList('css', func_get_args());
    }

    public function getCssList()
    {
        return $this->getList('css');
    }

    public function addJS()
    {
        return $this->addToList('js', func_get_args());
    }

    public function addJSVar($name, $value)
    {
        return $this->addToList('jsVar', h::createJsVarCode($name, $value));
    }

    public function addJSCall()
    {
        $code = call_user_func_array('\zin\h::createJsCallCode', func_get_args());
        \a(array('code', $code, $this->getJsList()));
        return $this->addToList('jsCall', $code);
    }

    public function getJsList()
    {
        return array_merge($this->getList('jsVar'), $this->getList('js'), $this->getList('jsCall'));
    }

    public static $map = array();

    public static function portal(/* string $name, mixed ...$children */)
    {
        $args    = func_get_args();
        $name    = array_shift($args);
        $context = static::current();
        $portal  = new portal(set::target($name), $args);
        $context->addPortal($portal);
    }

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
     * Get current context
     * @return context
     */
    public static function current()
    {
        if(empty(static::$map)) static::$map['current'] = new context(NULL);
        return static::$map['current'];
    }

    public static function create($wg)
    {
        $gid = $wg->gid;
        if(isset(static::$map[$gid])) return static::$map[$gid];
        $context = new context($wg);
        static::$map[$gid] = $context;
        return $context;
    }

    public static function destroy($gid)
    {
        if($gid instanceof wg) $gid = $gid->gid;
        if(isset(static::$map[$gid])) unset(static::$map[$gid]);
    }
}
