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

/**
 * html input.
 *
 * string type='text'
 * string name
 * string id?
 * string class='form-control'
 * string value?
 * bool   required?
 * string placeholder?
 * bool   autofocus?
 * bool   autocomplete=false
 * bool   disabled
 */
function input()
{
    return createWg('input', func_get_args());
}

/**
 * html textarea.
 *
 * string name
 * string id?
 * string class='form-control'
 * string value?
 * bool   required?
 * string placeholder?
 * int    rows
 * int    cols
 */
function textarea()
{
    return createWg('textarea', func_get_args());
}

/**
 * radio widget.
 *
 * string text?
 * bool   checked?
 * string name?
 * bool   primary=true
 * string id
 * bool   disabled?
 * string value?
 * string typeClass?
 * string rootClass?
 */
function radio()
{
    return createWg('radio', func_get_args());
}

/**
 * switcher widget.
 *
 * string text?
 * bool   checked?
 * string name?
 * bool   primary=true
 * string id
 * bool   disabled?
 * string value
 * string typeClass?
 * string rootClass?
 */
function switcher()
{
    return createWg('switcher', func_get_args());
}

/**
 * checkbox widget.
 *
 * string text?
 * bool   checked?
 * string name?
 * bool   primary=true
 * string id
 * bool   disabled?
 * string value?
 * string typeClass?
 * string rootClass?
 */
function checkbox()
{
    return createWg('checkbox', func_get_args());
}

/**
 * form widget.
 *
 * string method='post'
 * string url?
 * array  actions?
 * string target?
 * array  items?
 * bool   grid?
 * int    labelWidth?
 * string submitBtnText?
 * string cancelBtnText?
 */
function form()
{
    return createWg('form',  func_get_args());
}

/**
 * form panel widget.
 *
 * string method?
 * string url?
 * array  actions?
 * string target?
 * array  items?
 * bool   grid?
 * int    labelWidth?
 */
function formPanel()
{
    return createWg('formPanel', func_get_args());
}

/**
 * control widget.
 * Dynamically create html input.
 *
 * string type - it can be text, password, email, number, date, time, datetime, month, url, search, tel, color, picker, select, checkbox, radio, checkboxList, radioList, checkboxListInline, radioListInline, file, textarea
 * string name
 * string id?
 * string value?
 * bool   required?
 * string placeholder?
 * bool   disabled?
 * string form?
 * array  items?
 */
function control()
{
    return createWg('control', func_get_args());
}

/**
 * html select.
 *
 * string name
 * string id?
 * string class="form-control"
 * string value?
 * bool   required?
 * bool   disabled?
 * bool   multiple?
 * array  items?
 * int    size?
 */
function select()
{
    return createWg('select', func_get_args());
}

/**
 * html label which use id form.
 *
 * string text?
 * bool   required?
 * string for?
 */
function formLabel()
{
    return createWg('formLabel', func_get_args());
}

/**
 * form group widget.
 *
 * string|array
 * string       name?
 * string|bool  label?
 * string       labelClass?
 * bool|string  required='auto'
 * string       tip?
 * string|array tipClass?
 * array        tipProps?
 * array|string control?
 * string       width?
 * bool         strong?
 * string|array value?
 * bool         disabled?
 * array        items?
 * string       placeholder?
 */
function formGroup()
{
    return createWg('formGroup', func_get_args());
}

/**
 * form row widget.
 *
 * string width?
 * array  items?
 * bool   hidden?
 */
function formRow()
{
    return createWg('formRow', func_get_args());
}

/**
 * html input with prefix or suffix.
 *
 * mixed         prefix
 * mixed         suffix
 * string|int    prefixWidth
 * string|int    suffixWidth
 */
function inputControl()
{
    return createWg('inputControl', func_get_args());
}

/**
 * input group widget.
 *
 * array items?
 * bool  seg?
 */
function inputGroup()
{
    return createWg('inputGroup', func_get_args());
}

/**
 * checkbox list widget.
 *
 * bool         primary=true
 * string       name?
 * string|array value?
 * array        items
 * bool         inline?
 */
function checkList()
{
    return createWg('checkList', func_get_args());
}

/**
 * radio list widget.
 *
 * bool         primary=true
 * string       name?
 * string|array value?
 * array        items
 * bool         inline?
 */
function radioList()
{
    return createWg('radioList', func_get_args());
}

/**
 * color picker widget which extends input.
 *
 * string name
 * string id?
 * string class?
 * string value?
 * bool   required?
 * string placeholder?
 * bool   autofocus?
 * bool   autocomplete=false
 * bool   disabled?
 */
function colorPicker()
{
    return createWg('colorPicker', func_get_args());
}

/**
 * date picker widget which extends input.
 *
 * string name
 * string id?
 * string class?
 * string value?
 * bool   required?
 * string placeholder?
 * bool   autofocus?
 * bool   autocomplete=false
 * bool   disabled?
 */
function datePicker()
{
    return createWg('datePicker', func_get_args());
}

/**
 * datetime picker widget which extends input.
 *
 * string name
 * string id?
 * string class?
 * string value?
 * bool   required?
 * string placeholder?
 * bool   autofocus?
 * bool   autocomplete=false
 * bool   disabled?
 */
function datetimePicker()
{
    return createWg('datetimePicker', func_get_args());
}

/**
 * time picker widget which extends input.
 *
 * string name
 * string id?
 * string class?
 * string value?
 * bool   required?
 * string placeholder?
 * bool   autofocus?
 * bool   autocomplete=false
 * bool   disabled?
 */
function timePicker()
{
    return createWg('timePicker', func_get_args());
}

/**
 * html file input which extends input.
 *
 * string name
 * string id?
 * string class?
 * string value?
 * bool   required?
 * string placeholder?
 * bool   autofocus?
 * bool   autocomplete=false
 * bool   disabled?
 */
function fileInput()
{
    return createWg('fileInput', func_get_args());
}

/**
 * page form widget which extends page.
 *
 * array        formPanel?
 * string|array metas=array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
 * string       title?
 * array        bodyProps?
 * array|string bodyClass?
 * bool         zui=true
 * bool         display=true
 *
 * ====== blocks ======
 * head   = array()
 * header = array('map' => 'header')
 * main   = array('map' => 'main')
 * footer = array()
 * ====================
 */
function pageForm()
{
    return createWg('pageForm', func_get_args());
}

/**
 * icon widget.
 *
 * string        name
 * string|int    size?
 */
function icon()
{
    return createWg('icon', func_get_args());
}

/**
 * button widget.
 *
 * string        icon?
 * string        text?
 * bool          square?
 * bool          disabled?
 * bool          active?
 * string        url?
 * string        target?
 * string|int    size?
 * string        trailingIcon?
 * string|bool   caret?
 * string        hint?
 * string        type?
 * string        btnType?
 */
function btn()
{
    return createWg('btn', func_get_args());
}

/**
 * page base widget.
 *
 * string|array metas=array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
 * string       title?
 * array        bodyProps?
 * array|string bodyClass?
 * bool         zui=false
 * bool         display=true
 */
function pageBase()
{
    return createWg('pageBase', func_get_args());
}

/**
 * page widget.
 *
 * string|array metas=array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
 * string       title?
 * array        bodyProps?
 * array|string bodyClass?
 * bool         zui=true
 * bool         display=true
 *
 * ====== blocks ======
 * head   = array()
 * header = array('map' => 'header')
 * main   = array('map' => 'main')
 * footer = array()
 * ====================
 */
function page()
{
    return createWg('page',    func_get_args());
}

/**
 * fragment widget.
 * lets you group elements without a wrapper node.
 */
function fragment()
{
    return createWg('fragment',    func_get_args());
}

/**
 * button group widget.
 *
 * array  items?
 * bool   disabled?
 * string size?
 */
function btnGroup()
{
    return createWg('btnGroup', func_get_args());
}

/**
 * zentao main menu widget.
 *
 * array statuses?
 * array btnGroup?
 * array others?
 */
function mainMenu()
{
    return createWg('mainMenu', func_get_args());
}

/**
 * row widget.
 *
 * string justify?
 * string align?
 */
function row()
{
    return createWg('row', func_get_args());
}

/**
 * col widget.
 *
 * string justify?
 * string align?
 */
function col()
{
    return createWg('col', func_get_args());
}

function column()
{
    return createWg('column', func_get_args());
}

/**
 * center widget.
 */
function center()
{
    return createWg('center', func_get_args());
}

/**
 * cell widget.
 * flex item.
 *
 * int        order
 * int        grow
 * string     shrink
 * string|int width   'auto'|'flex-start'|'flex-end'|'center'|'baseline'|'stretch'
 * string     align
 * string     flex
 */
function cell()
{
    return createWg('cell', func_get_args());
}

/**
 * action item widget.
 *
 * string name='action'
 * string type='item'
 * string outerTag='li'
 * string tagName='a'
 * string icon?
 * string text?
 * string url?
 * string target?
 * bool   active?
 * bool   disabled?
 * string trailingIcon?
 * array  outerProps?
 * string outerClass?
 * props  array?
 * string|array|object badge?
 */
function actionItem()
{
    return createWg('actionItem', func_get_args());
}

/**
 * nav widget.
 *
 * array items?
 */
function nav()
{
    return createWg('nav', func_get_args());
}

/**
 * label widget.
 *
 * string text?
 */
function label()
{
    return createWg('label', func_get_args());
}

/**
 * dtable widget.
 */
function dtable()
{
    return createWg('dtable', func_get_args());
}

/**
 * menu widget.
 *
 * array items?
 */
function menu()
{
    return createWg('menu', func_get_args());
}

/**
 * dropdown widget.
 *
 * array  items?
 * string placement?
 * string strategy?
 * int    offset?
 * bool   flip?
 * string subMenuTrigger?
 * string arrow?
 * string trigger?
 * array  menuProps?
 * string target?
 * string id?
 * string menuClass?
 * bool   hasIcons?
 * bool   staticMenu?
 */
function dropdown()
{
    return createWg('dropdown', func_get_args());
}

/**
 * header widget.
 *
 * ====== blocks ======
 * heading = array('map' => 'toolbar')
 * navbar  = array('map' => 'nav')
 * toolbar = array('map' => 'btn')
 * ====================
 */
function header()
{
    return createWg('header', func_get_args());
}

/**
 * heading widget.
 *
 * array items
 * bool  showAppName=true
 */
function heading()
{
    return createWg('heading', func_get_args());
}

/**
 * navbar widget.
 *
 * array items?
 */
function navbar()
{
    return createWg('navbar', func_get_args());
}

/**
 * main widget.
 *
 * ====== blocks ======
 * menu    = array('map' => 'featureBar,nav,toolbar')
 * sidebar = array('map' => 'sidebar')
 * ====================
 */
function main()
{
    return createWg('main', func_get_args());
}

/**
 * zentao sidebar widget.
 *
 * string side='left'
 * bool   showToggle=true
 */
function sidebar()
{
    return createWg('sidebar', func_get_args());
}

/**
 * zentao feature bar widget.
 *
 * array  items?
 * string current?
 * string link?
 * string linkParams?
 *
 * ====== blocks ======
 * nav      = array('map' => 'nav')
 * leading  = array()
 * trailing = array()
 * ====================
 */
function featureBar()
{
    return createWg('featureBar', func_get_args());
}

/**
 * avatar widget.
 *
 * string     className?
 * array      style?
 * int        size=32
 * bool       circle=true
 * string|int rounded?
 * string     background?
 * string     foreColor
 * string     text?
 * string     code?
 * int        maxTextLength=2
 * int        hueDistance=43
 * int        saturation=0.4
 * int        lightness=0.6
 * string     src?
 */
function avatar()
{
    return createWg('avatar', func_get_args());
}

/**
 * zentao user avatar widget.
 *
 * string       className?
 * array        style?
 * int          size=32
 * bool         circle=true
 * string|int   rounded
 * string       background
 * string       foreColor
 * string       text
 * string       code?
 * int          maxTextLength=2
 * int          hueDistance=43
 * int          saturation=0.4
 * int          lightness=0.6
 * string       src?
 * string       avatar?
 * string       account?
 * string       realname?
 * array|object user?
 */
function userAvatar()
{
    return createWg('userAvatar', func_get_args());
}

/**
 * pager widget.
 */
function pager()
{
    return createWg('pager', func_get_args());
}

/**
 * modal widget.
 *
 * string id="$GID"
 * array  modalProps=array()
 */
function modal()
{
    return createWg('modal', func_get_args());
}

/**
 * modal trigger widget.
 *
 * string                     target?
 * string|int|object|function position?
 * string|int|object          size?
 * bool|string                backdrop?
 * bool                       keyboard?
 * bool                       moveable?
 * bool                       animation?
 * int                        transTime?
 * bool                       responsive?
 * string                     type?
 * string                     loadingText?
 * int                        loadTimeout?
 * string                     failedTip?
 * string                     timeoutTip?
 * string                     title?
 * string                     content?
 * object                     custom?
 * string                     url?
 * object                     request?
 * string                     dataType?
 *
 * ====== blocks ======
 * trigger = array('map' => 'btn,a')
 * modal = array('map' => 'modal')
 * ====================
 */
function modalTrigger()
{
    return createWg('modalTrigger', func_get_args());
}

/**
 * modal dialog widget.
 *
 * string     title?
 * int        itemID?
 * string     headerClass?
 * array      headerProps?
 * array      actions?
 * bool|array closeBtn=true
 * array      footerActions
 * string     footerClass
 * array      footerProps
 */
function modalDialog()
{
    return createWg('modalDialog', func_get_args());
}

/**
 * tabs widget.
 *
 * string direction='h'
 * array  items
 * string activeId?
 */
function tabs()
{
    return createWg('tabs', func_get_args());
}

/**
 * tab pane widget.
 * bool isActive?
 */
function tabPane()
{
    return createWg('tabPane', func_get_args());
}

/**
 * panel widget.
 *
 * string class='rounded shadow ring-0 canvas'
 * string size?
 * string title?
 * string titleClass?
 * array  titleProps?
 * string headingClass?
 * array  headingProps?
 * array  headingActions?
 * string bodyClass?
 * array  bodyProps?
 * array  footerActions?
 * string footerClass?
 * array  footerProps?
 */
function panel()
{
    return createWg('panel', func_get_args());
}

/**
 * tooltip widget.
 */
function tooltip()
{
    return createWg('tooltip', func_get_args());
}

/**
 * toolbar widget.
 *
 * array  items?
 * string btnClass?
 * array  btnProps?
 */
function toolbar()
{
    return createWg('toolbar', func_get_args());
}

/**
 * zentao search form widget.
 */
function searchForm()
{
    return createWg('searchForm', func_get_args());
}

/**
 * zentao search toggle widget.
 *
 * bool open?
 */
function searchToggle()
{
    return createWg('searchToggle', func_get_args());
}

/**
 * zentao program menu widget.
 *
 * array  programs?
 * string activeClass?
 * string activeIcon?
 * string activeKey?
 * string closeLink?
 */
function programMenu()
{
    return createWg('programMenu', func_get_args());
}

/**
 * zentao module menu widget.
 *
 * int    productID
 * int    activeKey
 * string closeLink
 */
function moduleMenu()
{
    return createWg('moduleMenu', func_get_args());
}

/**
 * zentao history records widget.
 *
 * array  actions?
 * array  users?
 * string methodName?
 */
function history()
{
    return createWg('history', func_get_args());
}

/**
 * zentao float toolbar widget.
 * array prefix?    btns props array.
 * array main?      btns props array.
 * array suffix?    btns props array.
 * ====== blocks ======
 * dropdowns = array()
 * ====================
 */
function floatToolbar()
{
    return createWg('floatToolbar', func_get_args());
}
