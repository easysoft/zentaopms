<?php
/**
 * The wg helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'helper.php';

function import()    {return setCustom('import', func_get_args());}
function js()        {return setCustom('js',     func_get_args());}
function css()       {return setCustom('css',    func_get_args());}
function to()        {return setCustom('blocks', func_get_args());}
function item($item) {return setCustom('item',   $item);}

function data()
{
    $args = func_get_args();
    foreach($args as $arg)
    {
        if(is_object($arg) && isset($arg->data)) core\data::setGlobal($arg->data);
        else if(is_array($arg)) core\data::setGlobal($arg);
    }
    return core\data::$global;
}

function btn()      {return createWg('btn',      func_get_args());}
function icon()     {return createWg('icon',    func_get_args());}
function pagebase() {return createWg('pagebase', func_get_args());}
function page()     {return createWg('page',     func_get_args());}
function dtable()   {return createWg('dtable',   func_get_args());}

function pageheader()  {return createWg('pageheader',  func_get_args());}
function pageheading() {return createWg('pageheading', func_get_args());}
function pagenavbar()  {return createWg('pagenavbar',  func_get_args());}
