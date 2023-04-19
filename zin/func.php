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
require_once __DIR__ . DS . 'zui' . DS . 'zui.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'zentao.func.php';

/* Form */
function input()          {return createWg('input', func_get_args());}
function textarea()       {return createWg('textarea', func_get_args());}
function radio()          {return createWg('radio', func_get_args());}
function switcher()       {return createWg('switcher', func_get_args());}
function checkbox()       {return createWg('checkbox', func_get_args());}
function form()           {return createWg('form',  func_get_args());}
function formPanel()      {return createWg('formPanel', func_get_args());}
function control()        {return createWg('control', func_get_args());}
function select()         {return createWg('select', func_get_args());}
function formLabel()      {return createWg('formLabel', func_get_args());}
function formGroup()      {return createWg('formGroup', func_get_args());}
function formRow()        {return createWg('formRow', func_get_args());}
function inputControl()   {return createWg('inputControl', func_get_args());}
function inputGroup()     {return createWg('inputGroup', func_get_args());}
function checkList()      {return createWg('checkList', func_get_args());}
function radioList()      {return createWg('radioList', func_get_args());}
function colorPicker()    {return createWg('colorPicker', func_get_args());}
function datePicker()     {return createWg('datePicker', func_get_args());}
function datetimePicker() {return createWg('datetimePicker', func_get_args());}
function timePicker()     {return createWg('timePicker', func_get_args());}
function fileInput()      {return createWg('fileInput', func_get_args());}

function icon()        {return createWg('icon', func_get_args());}
function btn()         {return createWg('btn', func_get_args());}
function pageBase()    {return createWg('pageBase', func_get_args());}
function page()        {return createWg('page',    func_get_args());}
function fragment()    {return createWg('fragment',    func_get_args());}
function btnGroup()    {return createWg('btnGroup', func_get_args());}
function mainMenu()    {return createWg('mainMenu', func_get_args());}
function row()         {return createWg('row', func_get_args());}
function col()         {return createWg('col', func_get_args());}
function column()      {return createWg('column', func_get_args());}
function center()      {return createWg('center', func_get_args());}
function cell()        {return createWg('cell', func_get_args());}
function actionItem()  {return createWg('actionItem', func_get_args());}
function nav()         {return createWg('nav', func_get_args());}
function label()       {return createWg('label', func_get_args());}
function dtable()      {return createWg('dtable', func_get_args());}
function menu()        {return createWg('menu', func_get_args());}
function dropdown()    {return createWg('dropdown', func_get_args());}
function header()      {return createWg('header', func_get_args());}
function heading()     {return createWg('heading', func_get_args());}
function navbar()      {return createWg('navbar', func_get_args());}
function main()        {return createWg('main', func_get_args());}
function sidebar()     {return createWg('sidebar', func_get_args());}
function featureBar()  {return createWg('featureBar', func_get_args());}
function pageHeading() {return createWg('pageHeading', func_get_args());}
function pageNavbar()  {return createWg('pageNavbar', func_get_args());}
function pageToolbar() {return createWg('pageToolbar', func_get_args());}
function avatar()      {return createWg('avatar', func_get_args());}
function userAvatar()  {return createWg('userAvatar', func_get_args());}
function pager()       {return createWg('pager', func_get_args());}
function modal()       {return createWg('modal', func_get_args());}
function modalTrigger(){return createWg('modalTrigger', func_get_args());}
function modalDialog() {return createWg('modalDialog', func_get_args());}
function tabs()        {return createWg('tabs', func_get_args());}
function panel()       {return createWg('panel', func_get_args());}
function tooltip()     {return createWg('tooltip', func_get_args());}
function toolbar()     {return createWg('toolbar', func_get_args());}
function searchForm()  {return createWg('searchForm', func_get_args());}
function searchToggle(){return createWg('searchToggle', func_get_args());}
function programMenu() {return createWg('programMenu', func_get_args());}
function moduleMenu()  {return createWg('moduleMenu', func_get_args());}
function assigntoDialog() {return createWg('assigntoDialog', func_get_args());}
function historyRecord()  {return createWg('historyRecord', func_get_args());}
