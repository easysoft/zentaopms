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

require_once __DIR__ . DS . 'core' . DS . 'h.func.php';

function icon()       {return createWg('icon',    func_get_args());}
function btn()        {return createWg('btn',    func_get_args());}
function pagebase()   {return createWg('pagebase',    func_get_args());}
function page()       {return createWg('page',    func_get_args());}
function btngroup()   {return createWg('btngroup', func_get_args());}
function checkbox()   {return createWg('checkbox', func_get_args());}
function pagemain()   {return createWg('pagemain', func_get_args());}
function row()        {return createWg('row', func_get_args());}
function column()     {return createWg('column', func_get_args());}
function center()     {return createWg('center', func_get_args());}
function cell()       {return createWg('cell', func_get_args());}
function actionItem() {return createWg('actionitem', func_get_args());}
function nav()        {return createWg('nav', func_get_args());}
function label()      {return createWg('label', func_get_args());}
function dtable()     {return createWg('dtable', func_get_args());}
function menu()       {return createWg('menu', func_get_args());}
function radio()      {return createWg('radio', func_get_args());}
function select()     {return createWg('select', func_get_args());}
