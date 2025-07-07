<?php
declare(strict_types=1);
/**
 * The common item element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'zin.func.php';

class item extends node
{
    public function build(): array|node|directive
    {
        if($this->parent instanceof node && method_exists($this->parent, 'onBuildItem'))
        {
            return call_user_func(array($this->parent, 'onBuildItem'), $this);
        }
        return parent::build();
    }
}

function item()
{
    return new item(func_get_args());
}
