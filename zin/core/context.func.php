<?php
/**
 * The context function file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'context.class.php';

function portal(/* string $name, mixed ...$children */)
{
    call_user_func_array('\zin\context::portal', func_get_args());
}

function js()
{
    call_user_func_array('\zin\context::js', func_get_args());
}

function jsCall()
{
    call_user_func_array('\zin\context::jsCall', func_get_args());
}

function jsVar()
{
    call_user_func_array('\zin\context::jsVar', func_get_args());
}

function css()
{
    call_user_func_array('\zin\context::css', func_get_args());
}

function import()
{
    call_user_func_array('\zin\context::import', func_get_args());
}
