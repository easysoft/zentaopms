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

class context
{
    public $root;

    public $portals = array();

    public function __construct($root)
    {
        $this->root = $root;
    }

    public function isRoot($root)
    {
        if(is_string($root)) return $root === $this->root->gid;
        return $root->gid === $this->root->gid;
    }

    public static $map = array();

    public static function addPortal($portal)
    {
        $context = static::current();
        if($context)
        {
            $context->portals[] = $portal;
            return true;
        }
        return false;
    }

    public static function current()
    {
        return current(static::$map);
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
