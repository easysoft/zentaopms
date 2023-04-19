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
require_once 'set.class.php';
require_once 'to.class.php';
require_once 'data.func.php';
require_once 'on.class.php';

function h()          {return call_user_func_array('\zin\h::create', func_get_args());}

function div()        {return call_user_func_array('\zin\h::div', func_get_args());}
function span()       {return call_user_func_array('\zin\h::span', func_get_args());}
function code()       {return call_user_func_array('\zin\h::code', func_get_args());}
function canvas()     {return call_user_func_array('\zin\h::canvas', func_get_args());}
function br()         {return call_user_func_array('\zin\h::br', func_get_args());}
function a()          {return call_user_func_array('\zin\h::a', func_get_args());}
function p()          {return call_user_func_array('\zin\h::p', func_get_args());}
function img()        {return call_user_func_array('\zin\h::img', func_get_args());}
function button()     {return call_user_func_array('\zin\h::button', func_get_args());}
function h1()         {return call_user_func_array('\zin\h::h1', func_get_args());}
function h2()         {return call_user_func_array('\zin\h::h2', func_get_args());}
function h3()         {return call_user_func_array('\zin\h::h3', func_get_args());}
function h4()         {return call_user_func_array('\zin\h::h4', func_get_args());}
function h5()         {return call_user_func_array('\zin\h::h5', func_get_args());}
function h6()         {return call_user_func_array('\zin\h::h6', func_get_args());}
function ul()         {return call_user_func_array('\zin\h::ul', func_get_args());}
function li()         {return call_user_func_array('\zin\h::li', func_get_args());}
function template()   {return call_user_func_array('\zin\h::template', func_get_args());}
function formHidden() {return call_user_func_array('\zin\h::formHidden', func_get_args());}
function fieldset()   {return call_user_func_array('\zin\h::fieldset', func_get_args());}
function legend()     {return call_user_func_array('\zin\h::legend', func_get_args());}

function jsRaw()    {return call_user_func_array('\zin\h::jsRaw', func_get_args());}
