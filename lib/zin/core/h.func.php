<?php
declare(strict_types=1);
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

require_once __DIR__ . DS . 'h.class.php';
require_once __DIR__ . DS . 'item.class.php';
require_once __DIR__ . DS . 'wg.func.php';
require_once __DIR__ . DS . 'set.class.php';
require_once __DIR__ . DS . 'to.class.php';
require_once __DIR__ . DS . 'data.func.php';
require_once __DIR__ . DS . 'on.class.php';

function h(): h          {return call_user_func_array('\zin\h::create', func_get_args());}

function div(): h        {return call_user_func_array('\zin\h::div', func_get_args());}
function span(): h       {return call_user_func_array('\zin\h::span', func_get_args());}
function code(): h       {return call_user_func_array('\zin\h::code', func_get_args());}
function canvas(): h     {return call_user_func_array('\zin\h::canvas', func_get_args());}
function br(): h         {return call_user_func_array('\zin\h::br', func_get_args());}
function a(): h          {return call_user_func_array('\zin\h::a', func_get_args());}
function p(): h          {return call_user_func_array('\zin\h::p', func_get_args());}
function img(): h        {return call_user_func_array('\zin\h::img', func_get_args());}
function button(): h     {return call_user_func_array('\zin\h::button', func_get_args());}
function h1(): h         {return call_user_func_array('\zin\h::h1', func_get_args());}
function h2(): h         {return call_user_func_array('\zin\h::h2', func_get_args());}
function h3(): h         {return call_user_func_array('\zin\h::h3', func_get_args());}
function h4(): h         {return call_user_func_array('\zin\h::h4', func_get_args());}
function h5(): h         {return call_user_func_array('\zin\h::h5', func_get_args());}
function h6(): h         {return call_user_func_array('\zin\h::h6', func_get_args());}
function ul(): h         {return call_user_func_array('\zin\h::ul', func_get_args());}
function li(): h         {return call_user_func_array('\zin\h::li', func_get_args());}
function template(): h   {return call_user_func_array('\zin\h::template', func_get_args());}
function formHidden(): h {return call_user_func_array('\zin\h::formHidden', func_get_args());}
function fieldset(): h   {return call_user_func_array('\zin\h::fieldset', func_get_args());}
function legend(): h     {return call_user_func_array('\zin\h::legend', func_get_args());}

function jsRaw(): string    {return call_user_func_array('\zin\h::jsRaw', func_get_args());}
