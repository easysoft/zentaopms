<?php
/**
 * The html helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'h.class.php';
require_once 'item.class.php';
require_once 'wg.func.php';

function div()      {return call_user_func_array('\zin\h::div', func_get_args());}
function span()     {return call_user_func_array('\zin\h::span', func_get_args());}
function a()        {return call_user_func_array('\zin\h::a', func_get_args());}
function p()        {return call_user_func_array('\zin\h::p', func_get_args());}
function img()      {return call_user_func_array('\zin\h::img', func_get_args());}
function button()   {return call_user_func_array('\zin\h::button', func_get_args());}
function ol()       {return call_user_func_array('\zin\h::ol', func_get_args());}
function ul()       {return call_user_func_array('\zin\h::ul', func_get_args());}
function li()       {return call_user_func_array('\zin\h::li', func_get_args());}
function input()    {return call_user_func_array('\zin\h::input', func_get_args());}
function textarea() {return call_user_func_array('\zin\h::textarea', func_get_args());}

function js()       {return call_user_func_array('\zin\h::js', func_get_args());}
function jsVar()    {return call_user_func_array('\zin\h::jsVar', func_get_args());}
function css()      {return call_user_func_array('\zin\h::css', func_get_args());}
function import()   {return call_user_func_array('\zin\h::import', func_get_args());}
