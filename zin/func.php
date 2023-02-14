<?php
/**
 * The functions of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'core' . DS . 'directive.func.php';
require_once __DIR__ . DS . 'core' . DS . 'wg.class.php';
require_once __DIR__ . DS . 'core' . DS . 'h.class.php';
require_once __DIR__ . DS . 'core' . DS . 'h.func.php';

class h  extends \zin\core\h {}
class wg extends \zin\core\wg {}

function prop()      {return call_user_func_array('\zin\core\prop', func_get_args());}
function set()       {return call_user_func_array('\zin\core\set', func_get_args());}
function setClass()  {return call_user_func_array('\zin\core\setClass', func_get_args());}
function setStyle()  {return call_user_func_array('\zin\core\setStyle', func_get_args());}
function setCssVar() {return call_user_func_array('\zin\core\setCssVar', func_get_args());}
function setId()     {return call_user_func_array('\zin\core\setId', func_get_args());}
function html()      {return call_user_func_array('\zin\core\html', func_get_args());}
function text()      {return call_user_func_array('\zin\core\text', func_get_args());}
function block()     {return call_user_func_array('\zin\core\block', func_get_args());}
function div()       {return call_user_func_array('\zin\core\div', func_get_args());}
function span()      {return call_user_func_array('\zin\core\span', func_get_args());}
function a()         {return call_user_func_array('\zin\core\a', func_get_args());}
function p()         {return call_user_func_array('\zin\core\p', func_get_args());}
function img()       {return call_user_func_array('\zin\core\img', func_get_args());}
function button()    {return call_user_func_array('\zin\core\button', func_get_args());}
function ol()        {return call_user_func_array('\zin\core\ol', func_get_args());}
function ul()        {return call_user_func_array('\zin\core\ul', func_get_args());}
function li()        {return call_user_func_array('\zin\core\li', func_get_args());}
function input()     {return call_user_func_array('\zin\core\input', func_get_args());}
function textarea()  {return call_user_func_array('\zin\core\textarea', func_get_args());}
function js()        {return call_user_func_array('\zin\core\js', func_get_args());}
function css()       {return call_user_func_array('\zin\core\css', func_get_args());}
function import()    {return call_user_func_array('\zin\core\import', func_get_args());}

function icon()      {return createWg('icon',    func_get_args());}
