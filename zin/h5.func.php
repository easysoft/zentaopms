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

function custom($name, $data)
{
    $info = new \stdClass();
    $info->$name = $data;
    return $info;
}

function set($prop, $value = NULL)          {return custom('set', is_array($prop) ? $prop : array($prop => $value));}
function hx($prop, $value = NULL)           {return (new core\hx())->set($prop, $value);}
function style($prop, $value = NULL)        {return (new core\style())->set($prop, $value);}
function cssVar($name = '', $value = NULL)  {return (new core\style())->var($name, $value);}
function setClass()                         {return (new core\classlist(func_get_args()));}
function html()                             {return custom('html', implode("\n", func_get_args()));}
function id($id)                            {return set('id', $id);}
function tag($tag)                          {return custom('tag', $tag);}

function h()
{
    $args = func_get_args();
    $tagName = array_shift($args);

    return core\h5::create($tagName, $args);
}

function button()   {return call_user_func_array('\zin\core\h5::button', func_get_args());}
function div()      {return call_user_func_array('\zin\core\h5::div', func_get_args());}
function span()     {return call_user_func_array('\zin\core\h5::span', func_get_args());}
function ol()       {return call_user_func_array('\zin\core\h5::ol', func_get_args());}
function ul()       {return call_user_func_array('\zin\core\h5::ul', func_get_args());}
function li()       {return call_user_func_array('\zin\core\h5::li', func_get_args());}
function h1()       {return call_user_func_array('\zin\core\h5::h1', func_get_args());}
function h2()       {return call_user_func_array('\zin\core\h5::h2', func_get_args());}
function h3()       {return call_user_func_array('\zin\core\h5::h3', func_get_args());}
function h4()       {return call_user_func_array('\zin\core\h5::h4', func_get_args());}
function h5()       {return call_user_func_array('\zin\core\h5::h5', func_get_args());}
function h6()       {return call_user_func_array('\zin\core\h5::h6', func_get_args());}
function a()        {return call_user_func_array('\zin\core\h5::a', func_get_args());}
function strong()   {return call_user_func_array('\zin\core\h5::strong', func_get_args());}
function small()    {return call_user_func_array('\zin\core\h5::small', func_get_args());}
function em()       {return call_user_func_array('\zin\core\h5::em', func_get_args());}
function sub()      {return call_user_func_array('\zin\core\h5::sub', func_get_args());}
function sup()      {return call_user_func_array('\zin\core\h5::sup', func_get_args());}
function code()     {return call_user_func_array('\zin\core\h5::code', func_get_args());}
function pre()      {return call_user_func_array('\zin\core\h5::pre', func_get_args());}
function br()       {return call_user_func_array('\zin\core\h5::br', func_get_args());}
function canvas()   {return call_user_func_array('\zin\core\h5::canvas', func_get_args());}
function iframe()   {return call_user_func_array('\zin\core\h5::iframe', func_get_args());}
function audio()    {return call_user_func_array('\zin\core\h5::audio', func_get_args());}
function video()    {return call_user_func_array('\zin\core\h5::video', func_get_args());}
function track()    {return call_user_func_array('\zin\core\h5::track', func_get_args());}
function picture()  {return call_user_func_array('\zin\core\h5::picture', func_get_args());}
function source()   {return call_user_func_array('\zin\core\h5::source', func_get_args());}
function svg()      {return call_user_func_array('\zin\core\h5::svg', func_get_args());}
function del()      {return call_user_func_array('\zin\core\h5::del', func_get_args());}
function ins()      {return call_user_func_array('\zin\core\h5::ins', func_get_args());}
function caption()  {return call_user_func_array('\zin\core\h5::caption', func_get_args());}
function details()  {return call_user_func_array('\zin\core\h5::details', func_get_args());}
function summary()  {return call_user_func_array('\zin\core\h5::summary', func_get_args());}
function img()      {return call_user_func_array('\zin\core\h5::img', func_get_args());}
function input()    {return call_user_func_array('\zin\core\h5::input', func_get_args());}
function label()    {return call_user_func_array('\zin\core\h5::label', func_get_args());}
function p()        {return call_user_func_array('\zin\core\h5::p', func_get_args());}
function main()     {return call_user_func_array('\zin\core\h5::main', func_get_args());}
function side()     {return call_user_func_array('\zin\core\h5::side', func_get_args());}
function section()  {return call_user_func_array('\zin\core\h5::section', func_get_args());}
function nav()      {return call_user_func_array('\zin\core\h5::nav', func_get_args());}
function table()    {return call_user_func_array('\zin\core\h5::table', func_get_args());}
function tbody()    {return call_user_func_array('\zin\core\h5::tbody', func_get_args());}
function thead()    {return call_user_func_array('\zin\core\h5::thead', func_get_args());}
function tfoot()    {return call_user_func_array('\zin\core\h5::tfoot', func_get_args());}
function td()       {return call_user_func_array('\zin\core\h5::td', func_get_args());}
function th()       {return call_user_func_array('\zin\core\h5::th', func_get_args());}
function tr()       {return call_user_func_array('\zin\core\h5::tr', func_get_args());}
function form()     {return call_user_func_array('\zin\core\h5::form', func_get_args());}
function select()   {return call_user_func_array('\zin\core\h5::select', func_get_args());}
function option()   {return call_user_func_array('\zin\core\h5::option', func_get_args());}
function textarea() {return call_user_func_array('\zin\core\h5::textarea', func_get_args());}
