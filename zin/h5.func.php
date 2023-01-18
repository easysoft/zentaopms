<?php
/**
 * The h5 helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'core/h5.class.php';

function h()      {return new core\h5(func_get_args());}
function button() {return call_user_func_array('h5::button', func_get_args());}
function div()    {return call_user_func_array('h5::div', func_get_args());}
function span()   {return call_user_func_array('h5::span', func_get_args());}
function ol()     {return call_user_func_array('h5::ol', func_get_args());}
function ul()     {return call_user_func_array('h5::ul', func_get_args());}
function li()     {return call_user_func_array('h5::li', func_get_args());}
function h1()     {return call_user_func_array('h5::h1', func_get_args());}
function h2()     {return call_user_func_array('h5::h2', func_get_args());}
function h3()     {return call_user_func_array('h5::h3', func_get_args());}
function h4()     {return call_user_func_array('h5::h4', func_get_args());}
function alink()  {return call_user_func_array('h5::a', func_get_args());}
function strong() {return call_user_func_array('h5::strong', func_get_args());}
function small()  {return call_user_func_array('h5::small', func_get_args());}
function code()   {return call_user_func_array('h5::code', func_get_args());}
function pre()    {return call_user_func_array('h5::pre', func_get_args());}
function br()     {return call_user_func_array('h5::br', func_get_args());}
function canvas() {return call_user_func_array('h5::canvas', func_get_args());}
function iframe() {return call_user_func_array('h5::iframe', func_get_args());}
function img()    {return call_user_func_array('h5::img', func_get_args());}
function input()  {return call_user_func_array('h5::input', func_get_args());}
function label()  {return call_user_func_array('h5::label', func_get_args());}
function p()      {return call_user_func_array('h5::p', func_get_args());}
