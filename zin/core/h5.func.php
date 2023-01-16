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

require_once 'h5.class.php';

function h()      {return new h5(func_get_args());}
function button() {return call_user_func_array('h5::button', func_get_args());}
function div()    {return call_user_func_array('h5::div', func_get_args());}
function span()   {return call_user_func_array('h5::span', func_get_args());}
function ul()     {return call_user_func_array('h5::ul', func_get_args());}
function li()     {return call_user_func_array('h5::li', func_get_args());}
function h1()     {return call_user_func_array('h5::h1', func_get_args());}
function h2()     {return call_user_func_array('h5::h2', func_get_args());}
function h3()     {return call_user_func_array('h5::h3', func_get_args());}
function h4()     {return call_user_func_array('h5::h4', func_get_args());}
function h5()     {return call_user_func_array('h5::h5', func_get_args());}
function h6()     {return call_user_func_array('h5::h6', func_get_args());}
