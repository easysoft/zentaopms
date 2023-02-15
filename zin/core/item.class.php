<?php
/**
 * The html element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once 'wg.class.php';
require_once 'directive.func.php';

class itemWg extends wg
{
    public function build()
    {
        if(!($this->parent instanceof wg) || method_exists($this->parent, 'onBuildItem')) return parent::build();
        return $this->parent->onBuildItem($this);
    }
}

function item()
{
    return new itemWg(func_num_args());
}
