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

function div()      {return call_user_func_array('\zin\core\h::div', func_get_args());}
function span()     {return call_user_func_array('\zin\core\h::span', func_get_args());}
function a()        {return call_user_func_array('\zin\core\h::a', func_get_args());}
function p()        {return call_user_func_array('\zin\core\h::p', func_get_args());}
function img()      {return call_user_func_array('\zin\core\h::img', func_get_args());}
function button()   {return call_user_func_array('\zin\core\h::button', func_get_args());}
function ol()       {return call_user_func_array('\zin\core\h::ol', func_get_args());}
function ul()       {return call_user_func_array('\zin\core\h::ul', func_get_args());}
function li()       {return call_user_func_array('\zin\core\h::li', func_get_args());}
function input()    {return call_user_func_array('\zin\core\h::input', func_get_args());}
function textarea() {return call_user_func_array('\zin\core\h::textarea', func_get_args());}

function js()       {return call_user_func_array('\zin\core\h::js', func_get_args());}
function css()      {return call_user_func_array('\zin\core\h::css', func_get_args());}
function import()   {return call_user_func_array('\zin\core\h::import', func_get_args());}
