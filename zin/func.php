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
require_once __DIR__ . DS . 'core' . DS . 'render.func.php';
require_once __DIR__ . DS . 'zui' . DS . 'zui.class.php';
require_once __DIR__ . DS . 'zentao' . DS . 'zentao.func.php';

function icon()        {return createWg('icon', func_get_args());}
function btn()         {return createWg('btn', func_get_args());}
function pagebase()    {return createWg('pagebase', func_get_args());}
function page()        {return createWg('page',    func_get_args());}
function btnGroup()    {return createWg('btngroup', func_get_args());}
function checkbox()    {return createWg('checkbox', func_get_args());}
function pagemain()    {return createWg('pagemain', func_get_args());}
function mainmenu()    {return createWg('mainmenu', func_get_args());}
function row()         {return createWg('row', func_get_args());}
function column()      {return createWg('column', func_get_args());}
function center()      {return createWg('center', func_get_args());}
function cell()        {return createWg('cell', func_get_args());}
function actionItem()  {return createWg('actionitem', func_get_args());}
function nav()         {return createWg('nav', func_get_args());}
function label()       {return createWg('label', func_get_args());}
function dtable()      {return createWg('dtable', func_get_args());}
function menu()        {return createWg('menu', func_get_args());}
function radio()       {return createWg('radio', func_get_args());}
function switcher()    {return createWg('switcher', func_get_args());}
function select()      {return createWg('select', func_get_args());}
function formlabel()   {return createWg('formlabel', func_get_args());}
function formgroup()   {return createWg('formgroup', func_get_args());}
function formcell()    {return createWg('formcell', func_get_args());}
function formgrid()    {return createWg('formgrid', func_get_args());}
function formrow()     {return createWg('formrow', func_get_args());}
function forminput()   {return createWg('forminput', func_get_args());}
function dropdown()    {return createWg('dropdown', func_get_args());}
function header()      {return createWg('header', func_get_args());}
function heading()     {return createWg('heading', func_get_args());}
function navbar()      {return createWg('navbar', func_get_args());}
function main()        {return createWg('main', func_get_args());}
function sidebar()     {return createWg('sidebar', func_get_args());}
function featureBar()  {return createWg('featurebar', func_get_args());}
function pageheader()  {return createWg('pageheader', func_get_args());}
function pageheading() {return createWg('pageheading', func_get_args());}
function pagenavbar()  {return createWg('pagenavbar', func_get_args());}
function pagetoolbar() {return createWg('pagetoolbar', func_get_args());}
function avatar()      {return createWg('avatar', func_get_args());}
function pager()       {return createWg('pager', func_get_args());}
function modal()       {return createWg('modal', func_get_args());}
function tabs()        {return createWg('tabs', func_get_args());}
function panel()       {return createWg('panel', func_get_args());}
function tooltip()     {return createWg('tooltip', func_get_args());}
function inputaddon()  {return createWg('inputaddon', func_get_args());}
function inputgroup()  {return createWg('inputgroup', func_get_args());}
function inputbtn()    {return createWg('inputbtn', func_get_args());}
function toolbar()     {return createWg('toolbar', func_get_args());}
function searchform()  {return createWg('searchform', func_get_args());}
function searchToggle(){return createWg('searchtoggle', func_get_args());}
function programmenu() {return createWg('programmenu', func_get_args());}
function modulemenu()  {return createWg('modulemenu', func_get_args());}
