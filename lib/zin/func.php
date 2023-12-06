<?php
declare(strict_types=1);
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
require_once __DIR__ . DS . 'zentao' . DS . 'bind.class.php';

/* Form */

/**
 * Html input.
 *
 * string  name
 * ?string type='text'
 * ?string id
 * ?string class='form-control'
 * ?string value
 * ?bool   required
 * ?string placeholder
 * ?bool   autofocus
 * ?bool   autocomplete=false
 * ?bool   disabled
 */
function input(): input
{
    return createWg('input', func_get_args());
}

/**
 * Html textarea.
 *
 * string  name
 * int     rows
 * int     cols
 * ?string id
 * ?string class='form-control'
 * ?bool   required
 * ?string placeholder
 */
function textarea(): textarea
{
    return createWg('textarea', func_get_args());
}

/**
 * Radio widget.
 *
 * string  id
 * ?string text
 * ?bool   checked
 * ?string name
 * ?bool   primary=true
 * ?bool   disabled
 * ?string value
 * ?string typeClass
 * ?string rootClass
 */
function radio(): radio
{
    return createWg('radio', func_get_args());
}

/**
 * Switcher widget.
 *
 * string  id
 * string  value
 * ?string text
 * ?bool   checked
 * ?string name
 * ?bool   primary=true
 * ?bool   disabled
 * ?string typeClass
 * ?string rootClass
 */
function switcher(): switcher
{
    return createWg('switcher', func_get_args());
}

/**
 * Checkbox widget.
 *
 * string  id
 * ?string text
 * ?bool   checked
 * ?string name
 * ?bool   primary=true
 * ?bool   disabled
 * ?string value
 * ?string typeClass
 * ?string rootClass
 * ?string labelClass
 */
function checkbox(): checkbox
{
    return createWg('checkbox', func_get_args());
}

/**
 * Checkbox group widget.
 *
 * array title
 * array items
 */
function checkboxGroup(): checkboxGroup
{
    return createWg('checkboxGroup', func_get_args());
}

/**
 * Base form widget.
 *
 * ?string id='$GID'
 * ?string method='post'
 * ?string url
 * ?array  actions
 * ?string actionsClass
 * ?string target
 * ?string submitBtnText
 * ?string cancelBtnText
 */
function formBase(): formBase
{
    return createWg('formBase',  func_get_args());
}

/**
 * Form widget.
 *
 * ?string id='$GID'
 * ?string method='post'
 * ?string url
 * ?array  actions
 * ?string actionsClass
 * ?string target
 * ?string submitBtnText
 * ?string cancelBtnText
 * ?bool   grid
 * ?int    labelWidth
 */
function form(): form
{
    return createWg('form',  func_get_args());
}

/**
 * Form panel widget.
 *
 * ?string method
 * ?string url
 * ?array  actions
 * ?string target
 * ?array  items
 * ?bool   grid
 * ?int    labelWidth
 */
function formPanel(): formPanel
{
    return createWg('formPanel', func_get_args());
}

/**
 * Zentao form batch wg.
 *
 * ?string id='$GID'
 * ?string method='post'
 * ?string url
 * ?array  actions
 * ?string actionsClass
 * ?string target
 * ?array  items
 * ?int    minRows=1
 * ?int    maxRows=100
 * ?array  data
 * ?string mode='add'
 */
function formBatch(): formBatch
{
    return createWg('formBatch', func_get_args());
}

/**
 * Zentao form batch item wg.
 *
 * ?string          name
 * ?string|bool     label
 * ?string          labelClass
 * ?string          labelProps
 * ?bool|string     required="auto"
 * ?array|string    control
 * ?string          width
 * ?string|array    value
 * ?bool            disabled
 * ?array           items
 * ?string          placeholder
 * ?bool            hidden=false
 */
function formBatchItem(): formBatchItem
{
    return createWg('formBatchItem', func_get_args());
}

/**
 * Form panel widget.
 *
 * ?string method
 * ?string url
 * ?array  actions
 * ?string target
 * ?array  items
 * ?bool   grid
 * ?int    labelWidth
 * ?int    minRows=1
 * ?int    maxRows=100
 * ?array  data
 * ?string mode='add'
 */
function formBatchPanel(): formBatchPanel
{
    return createWg('formBatchPanel', func_get_args());
}

/**
 * Batch actions: add, delete.
 */
function batchActions(): batchActions
{
    return createWg('batchActions', func_get_args());
}

/**
 * Control widget.
 * Dynamically create html input.
 *
 * string  type - it can be text, password, email, number, date, time, datetime, month, url, search, tel, color, picker, select, checkbox, radio, checkboxList, radioList, checkboxListInline, radioListInline, file, textarea.
 * string  name
 * ?string id
 * ?string value
 * ?bool   required
 * ?string placeholder
 * ?bool   disabled
 * ?string form
 * ?array  items
 */
function control(): control
{
    return createWg('control', func_get_args());
}

/**
 * Html select.
 *
 * string  name
 * ?string id
 * ?string class="form-control"
 * ?string value
 * ?bool   required
 * ?bool   disabled
 * ?bool   multiple
 * ?array  items
 * ?int    size
 */
function select(): select
{
    return createWg('select', func_get_args());
}

/**
 * Html label which use id form.
 *
 * ?string text
 * ?bool   required
 * ?string for
 */
function formLabel(): formLabel
{
    return createWg('formLabel', func_get_args());
}

/**
 * Form group widget.
 *
 * ?string           name
 * ?string           labelClass
 * ?string           tip
 * ?array            tipProps
 * ?string           width
 * ?bool             strong
 * ?bool             disabled
 * ?array            items
 * ?string           placeholder
 * bool|string|null  required='auto'
 * string|array|null tipClass
 * string|bool|null  label
 * string|array|null value
 * array|string|null control
 */
function formGroup(): formGroup
{
    return createWg('formGroup', func_get_args());
}

/**
 * Form row widget.
 *
 * ?string width
 * ?array  items
 * ?bool   hidden
 */
function formRow(): formRow
{
    return createWg('formRow', func_get_args());
}

/**
 * Form row group widget.
 *
 * ?string width
 * ?array  items
 * ?bool   hidden
 */
function formRowGroup(): formRowGroup
{
    return createWg('formRowGroup', func_get_args());
}

/**
 * Html input with prefix or suffix.
 *
 * mixed      prefix
 * mixed      suffix
 * string|int prefixWidth
 * string|int suffixWidth
 */
function inputControl(): inputControl
{
    return createWg('inputControl', func_get_args());
}

/**
 * Input group widget.
 *
 * ?array items
 * ?bool  seg
 */
function inputGroup(): inputGroup
{
    return createWg('inputGroup', func_get_args());
}

/**
 * Input group addon widget.
 */
function inputGroupAddon(): inputGroupAddon
{
    return createWg('inputGroupAddon', func_get_args());
}

/**
 * Checkbox list widget.
 *
 * ?string           name
 * ?bool             primary=true
 * ?array            items
 * ?bool             inline
 * string|array|null value
 */
function checkList(): checkList
{
    return createWg('checkList', func_get_args());
}

/**
 * Radio list widget.
 *
 * ?string           name
 * ?bool             primary=true
 * ?array            items
 * ?bool             inline
 * string|array|null value
 */
function radioList(): radioList
{
    return createWg('radioList', func_get_args());
}

/**
 * Color picker widget which extends input.
 *
 * ?string heading
 * ?string icon
 * ?bool   closeBtn=true
 * ?string syncText
 * ?string syncColor
 * ?string syncBackground
 * ?string syncBorder
 */
function colorPicker(): colorPicker
{
    return createWg('colorPicker', func_get_args());
}

/**
 * Date picker widget which extends input.
 *
 * string  name
 * ?string id
 * ?string class
 * ?string value
 * ?bool   required
 * ?string placeholder
 * ?bool   autofocus
 * ?bool   disabled
 * ?bool   autocomplete=false
 */
function datePicker(): datePicker
{
    return createWg('datePicker', func_get_args());
}

/**
 * Datetime picker widget which extends input.
 *
 * string  name
 * ?string id
 * ?string class
 * ?string value
 * ?bool   required
 * ?string placeholder
 * ?bool   autofocus
 * ?bool   disabled
 * ?bool   autocomplete=false
 */
function datetimePicker(): datetimePicker
{
    return createWg('datetimePicker', func_get_args());
}

/**
 * Time picker widget which extends input.
 *
 * string  name
 * ?string id
 * ?string class
 * ?string value
 * ?bool   required
 * ?string placeholder
 * ?bool   autofocus
 * ?bool   disabled
 * ?bool   autocomplete=false
 */
function timePicker(): timePicker
{
    return createWg('timePicker', func_get_args());
}

/**
 * Html file input which extends input.
 *
 * string  name
 * ?string id
 * ?string class
 * ?string value
 * ?bool   required
 * ?string placeholder
 * ?bool   autofocus
 * ?bool   disabled
 * ?bool   autocomplete=false
 */
function fileInput(): fileInput
{
    return createWg('fileInput', func_get_args());
}

/**
 * Page form widget which extends page.
 *
 * ?array            formPanel
 * ?string           title
 * ?array            bodyProps
 * ?bool             zui=true
 * ?bool             display=true
 * array|string|null bodyClass
 * string|array|null metas=array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
 *
 * ====== blocks ======
 * head   = array()
 * header = array('map' => 'header')
 * main   = array('map' => 'main')
 * footer = array()
 * ====================
 */
function pageForm(): pageForm
{
    return createWg('pageForm', func_get_args());
}

/**
 * Icon widget.
 *
 * string          name
 * string|int|null size
 */
function icon(): icon
{
    return createWg('icon', func_get_args());
}

/**
 * Button widget.
 *
 * ?string          icon
 * ?string          text
 * ?bool            square
 * ?bool            disabled
 * ?bool            active
 * ?string          url
 * ?string          target
 * ?string          trailingIcon
 * ?string          hint
 * ?string          type
 * ?string          btnType
 * string|int|null  size
 * string|bool|null caret
 */
function btn(): btn
{
    return createWg('btn', func_get_args());
}

/**
 * Page base widget.
 *
 * ?string           title
 * ?array            bodyProps
 * ?bool             zui=false
 * ?bool             display=true
 * array|string|null bodyClass
 * string|array|null metas=array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
 */
function pageBase(): pageBase
{
    return createWg('pageBase', func_get_args());
}

/**
 * Page widget.
 *
 * ?string           title
 * ?array            bodyProps
 * ?array|string     bodyClass
 * ?bool             zui=true
 * ?bool             display=true
 * string|array|null metas=array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
 *
 * ====== blocks ======
 * head   = array()
 * header = array('map' => 'header')
 * main   = array('map' => 'main')
 * footer = array()
 * ====================
 */
function page(): page
{
    return createWg('page',    func_get_args());
}

/**
 * Fragment widget.
 * Let you group elements without a wrapper node.
 */
function fragment(): fragment
{
    return createWg('fragment',    func_get_args());
}

/**
 * Button group widget.
 *
 * ?array  items
 * ?bool   disabled
 * ?string size
 */
function btnGroup(): btnGroup
{
    return createWg('btnGroup', func_get_args());
}

/**
 * Zentao main menu widget.
 *
 * ?array statuses
 * ?array btnGroup
 * ?array others
 */
function mainMenu(): mainMenu
{
    return createWg('mainMenu', func_get_args());
}

/**
 * Row widget.
 *
 * ?string justify
 * ?string align
 */
function row(): row
{
    return createWg('row', func_get_args());
}

/**
 * Col widget.
 *
 * ?string justify
 * ?string align
 */
function col(): col
{
    return createWg('col', func_get_args());
}

/**
 * Center widget.
 */
function center(): center
{
    return createWg('center', func_get_args());
}

/**
 * Cell widget.
 * Flex item.
 *
 * int        order
 * int        grow
 * string     shrink
 * string|int width   'auto'|'flex-start'|'flex-end'|'center'|'baseline'|'stretch'
 * string     align
 * string     flex
 */
function cell(): cell
{
    return createWg('cell', func_get_args());
}

/**
 * Divider widget.
 */
function divider(): divider
{
    return createWg('divider', func_get_args());
}

/**
 * Action item widget.
 *
 * ?string name='action'
 * ?string type='item'
 * ?string outerTag='li'
 * ?string tagName='a'
 * ?string icon
 * ?string text
 * ?string url
 * ?string target
 * ?bool   active
 * ?bool   disabled
 * ?string trailingIcon
 * ?array  outerProps
 * ?string outerClass
 * ?props  array
 * string|array|object|null badge
 */
function actionItem(): actionItem
{
    return createWg('actionItem', func_get_args());
}

/**
 * Nav widget.
 *
 * ?array items
 */
function nav(): nav
{
    return createWg('nav', func_get_args());
}

/**
 * Label widget.
 *
 * ?string text
 */
function label(): label
{
    return createWg('label', func_get_args());
}

/**
 * DTable widget.
 *
 * ?string className
 * ?string id
 * ?bool   customCols
 * ?array  cols
 * ?string module
 */
function dtable(): dtable
{
    return createWg('dtable', func_get_args());
}

/**
 * Menu widget.
 *
 * ?array items
 */
function menu(): menu
{
    return createWg('menu', func_get_args());
}

/**
 * Dropdown widget.
 *
 * ?array  items
 * ?string placement
 * ?string strategy
 * ?int    offset
 * ?bool   flip
 * ?string arrow
 * ?string trigger
 * ?array  menu
 * ?string target
 * ?string id
 * ?string menuClass
 * ?bool   hasIcons
 * ?bool   staticMenu
 */
function dropdown(): dropdown
{
    return createWg('dropdown', func_get_args());
}

/**
 * Header widget.
 *
 * ====== blocks ======
 * heading = array('map' => 'toolbar')
 * navbar  = array('map' => 'nav')
 * toolbar = array('map' => 'btn')
 * ====================
 */
function header(): header
{
    return createWg('header', func_get_args());
}

/**
 * Heading widget.
 *
 * array items
 * ?bool showAppName=true
 */
function heading(): heading
{
    return createWg('heading', func_get_args());
}

/**
 * Navbar widget.
 *
 * ?array items
 */
function navbar(): navbar
{
    return createWg('navbar', func_get_args());
}

/**
 * Dropmenu widget.
 *
 * ?string   id       // ID，当页面有多个 dropmenu 时确保有唯一的 ID。
 * ?string   tab      // 应用名。
 * ?string   module   // 模块名。
 * ?string   method   // 方法名。
 * ?string   objectID // 当前选中项的 ID。
 * ?string   extra    // 额外参数。
 * ?string   url      // 异步获取下拉菜单选项数据的 URL。
 * ?string   text     // 选择按钮上显示的文本。
 * ?bool|int cache    // 是否启用缓存。
 * ?array    data     // 手动指定数据。
 * ?string   menuID   // 指定下拉菜单的ID。
 */
function dropmenu(): dropmenu
{
    return createWg('dropmenu', func_get_args());
}

/**
 * Main widget.
 *
 * ====== blocks ======
 * menu    = array('map' => 'featureBar,nav,toolbar')
 * sidebar = array('map' => 'sidebar')
 * ====================
 */
function main(): main
{
    return createWg('main', func_get_args());
}

/**
 * Zentao sidebar widget.
 *
 * ?string side='left'
 * ?bool   showToggle=true
 */
function sidebar(): sidebar
{
    return createWg('sidebar', func_get_args());
}

/**
 * Zentao feature bar widget.
 *
 * ?array  items
 * ?string current
 * ?string link
 * ?string linkParams
 * ?string module
 * ?string method
 *
 * ====== blocks ======
 * nav      = array('map' => 'nav')
 * leading  = array()
 * trailing = array()
 * ====================
 */
function featureBar(): featureBar
{
    return createWg('featureBar', func_get_args());
}

/**
 * Avatar widget.
 *
 * ?string     className
 * ?array      style
 * ?int        size=32
 * ?bool       circle=true
 * ?string|int rounded
 * ?string     background
 * ?string     foreColor
 * ?string     text
 * ?string     code
 * ?int        maxTextLength=2
 * ?int        hueDistance=43
 * ?int        saturation=0.4
 * ?int        lightness=0.6
 * ?string     src
 */
function avatar(): avatar
{
    return createWg('avatar', func_get_args());
}

/**
 * Zentao user avatar widget.
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
function userAvatar(): userAvatar
{
    return createWg('userAvatar', func_get_args());
}

/**
 * Pager widget.
 */
function pager(): pager
{
    return createWg('pager', func_get_args());
}

/**
 * Modal widget.
 *
 * ?string id="$GID"
 * ?array  modalProps=array()
 */
function modal(): modal
{
    return createWg('modal', func_get_args());
}

/**
 * Modal trigger widget.
 *
 * ?string                         target
 * ?bool                           keyboard
 * ?bool                           moveable
 * ?bool                           animation
 * ?int                            transTime
 * ?bool                           responsive
 * ?string                         type
 * ?string                         loadingText
 * ?int                            loadTimeout
 * ?string                         failedTip
 * ?string                         timeoutTip
 * ?string                         title
 * ?string                         content
 * ?object                         custom
 * ?string                         url
 * ?object                         request
 * ?string                         dataType
 * bool|string|null                backdrop
 * string|int|object|null          size
 * string|int|object|function|null position
 *
 * ====== blocks ======
 * trigger = array('map' => 'btn,a')
 * modal = array('map' => 'modal')
 * ====================
 */
function modalTrigger(): modalTrigger
{
    return createWg('modalTrigger', func_get_args());
}

/**
 * Modal header widget.
 *
 * ?string title
 * ?string entityText
 * ?int    entityID
 */
function modalHeader(): modalHeader
{
    return createWg('modalHeader', func_get_args());
}

/**
 * Modal dialog widget.
 *
 * ?string         title
 * ?int            itemID
 * ?string         headerClass
 * ?array          headerProps
 * ?array          actions
 * ?array          footerActions
 * ?string         footerClass
 * ?array          footerProps
 * bool|array|null closeBtn=true
 */
function modalDialog(): modalDialog
{
    return createWg('modalDialog', func_get_args());
}

/**
 * Tabs widget.
 *
 * ?string direction='h'
 * ?bool   collapse=false
 */
function tabs(): tabs
{
    return createWg('tabs', func_get_args());
}

/**
 * Tab pane widget.
 *
 * ?bool  active
 * string key
 * string title
 *
 * ====== blocks ======
 * prefix = array()
 * suffix = array()
 * ====================
 */
function tabPane(): tabPane
{
    return createWg('tabPane', func_get_args());
}

/**
 * Panel widget.
 *
 * ?string class='rounded shadow ring-0 canvas'
 * ?string size
 * ?string title
 * ?string titleClass
 * ?array  titleProps
 * ?string headingClass
 * ?array  headingProps
 * ?array  headingActions
 * ?string bodyClass
 * ?array  bodyProps
 * ?array  footerActions
 * ?string footerClass
 * ?array  footerProps
 */
function panel(): panel
{
    return createWg('panel', func_get_args());
}

function pasteDialog(): pasteDialog
{
    return createWg('pasteDialog', func_get_args());
}

/**
 * Tooltip widget.
 */
function tooltip(): tooltip
{
    return createWg('tooltip', func_get_args());
}

/**
 * Toolbar widget.
 *
 * ?array  items
 * ?string btnClass
 * ?array  btnProps
 */
function toolbar(): toolbar
{
    return createWg('toolbar', func_get_args());
}

/**
 * Zentao search toggle widget.
 *
 * ?bool open
 */
function searchToggle(): searchToggle
{
    return createWg('searchToggle', func_get_args());
}

/**
 * Zentao search form widget.
 */
function searchForm(): searchForm
{
    return createWg('searchForm', func_get_args());
}

/**
 * Zentao program menu widget.
 *
 * ?array  programs
 * ?string activeClass
 * ?string activeIcon
 * ?string activeKey
 * ?string closeLink
 */
function programMenu(): programMenu
{
    return createWg('programMenu', func_get_args());
}

/**
 * Zentao product menu widget.
 *
 * ?string title
 * ?array  items
 * ?string activeKey
 * ?string link
 */
function productMenu(): productMenu
{
    return createWg('productMenu', func_get_args());
}

/**
 * Zentao module menu widget.
 *
 * array  modules
 * int    activeKey
 * string closeLink
 */
function moduleMenu(): moduleMenu
{
    return createWg('moduleMenu', func_get_args());
}

/**
 * Zentao doc module menu widget.
 *
 * array  modules
 * int    activeKey
 * string closeLink
 */
function docMenu(): docMenu
{
    return createWg('docMenu', func_get_args());
}

/**
 * Zentao tree widget.
 *
 * ?array items
 */
function tree(): Tree
{
    return createWg('tree', func_get_args());
}

/**
 * Zentao tree editor widget.
 *
 * ?array items
 */
function treeEditor(): TreeEditor
{
    return createWg('treeEditor', func_get_args());
}

/**
 * Zentao file list widget.
 *
 * ?array  files
 * ?int    objectID
 * ?string objectType
 */
function fileList(): fileList
{
    return createWg('fileList', func_get_args());
}

/**
 * Zentao history records widget.
 *
 * ?array  actions
 * ?array  users
 * ?string methodName
 * ?bool   commentBtn
 */
function history(): history
{
    return createWg('history', func_get_args());
}

/**
 * Zentao float toolbar widget.
 *
 * ?array  prefix btns props array.
 * ?array  main   btns props array.
 * ?array  suffix btns props array.
 * ?object object
 *
 * ====== blocks ======
 * dropdowns = array()
 * ====================
 */
function floatToolbar(): floatToolbar
{
    return createWg('floatToolbar', func_get_args());
}

/**
 * Zentao customize form item dropdown.
 *
 * ?string method
 * ?string url
 * ?array  actions
 * ?string target
 * ?array  items
 * ?array  value
 */
function formItemDropdown(): formItemDropdown
{
    return createWg('formItemDropdown', func_get_args());
}

/**
 * Zentao editor wg.
 *
 * ?bool   createInput=false
 * ?string uploadUrl=''
 * ?string placeholder=''
 * ?bool   fullscreenable=false
 * ?bool   resizable=false
 * ?bool   exposeEditor=true
 * ?string size='sm'
 * ?bool   hideMenubar=false
 * ?bool   bubbleMenu=false
 */
function editor(): editor
{
    return createWg('editor', func_get_args());
}

/**
 * Zentao comment button wg.
 *
 * ?string          dataTarget
 * ?string          dataUrl
 * ?string          dataType
 * ?string          icon
 * ?string          text
 * ?bool            square
 * ?bool            disabled
 * ?bool            active
 * ?string          url
 * ?string          target
 * ?string          trailingIcon
 * ?string          hint
 * ?string          type
 * ?string          btnType
 * string|int|null  size
 * string|bool|null caret
 */
function commentBtn(): commentBtn
{
    return createWg('commentBtn', func_get_args());
}

/**
 *
 * Zentao comment dialog wg.
 *
 * ?string title,
 * ?string url
 * ?string name='comment'
 * ?string method='POST'
 */
function commentDialog(): commentDialog
{
    return createWg('commentDialog', func_get_args());
}

/**
 * Zentao comment form wg.
 *
 * ?string url
 * ?string name='comment'
 * ?string method='POST'
 */
function commentForm(): commentForm
{
    return createWg('commentForm', func_get_args());
}

/**
 * Zentao priority number wg.
 *
 * int pri
 */
function priLabel(): priLabel
{
    return createWg('priLabel', func_get_args());
}

/**
 * Zentao risk label wg.
 *
 * ?string text
 * ?string level 'h'|'m'|'l'
 */
function riskLabel(): riskLabel
{
    return createWg('riskLabel', func_get_args());
}

/**
 * Zentao severity label wg.
 *
 * ?string     text
 * ?int|string level 1|2|3|4
 */
function severityLabel(): severityLabel
{
    return createWg('severityLabel', func_get_args());
}

/**
 * Zentao dashboard widget.
 *
 *  ?bool                responsive         是否启用响应式。
 *  array                blocks             区块列表。
 *  ?int                 grid               栅格数。
 *  ?int                 gap                间距。
 *  ?int                 leftStop           区块水平停靠间隔。
 *  ?int                 cellHeight         网格高度。
 *  ?stringunction|array blockFetch         区块数据获取 url 或选项。
 *  ?array               blockDefaultSize   区块默认大小。
 *  array                blockSizeMap       区块大小映射。
 *  ?array               blockMenu          区块菜单。
 *  ?function            onLayoutChange     布局变更事件。
 */
function dashboard(): dashboard
{
    return createWg('dashboard', func_get_args());
}

/**
 * Dashboard panel widget.
 */
function blockPanel(): blockPanel
{
    return createWg('blockPanel', func_get_args());
}

/**
 * Zentao detail page section widget.
 *
 * string            title
 * string|array|null content
 * ?bool             useHtml=false
 *
 * ====== blocks ======
 * subTitle => array()
 * actions  => array()
 * ====================
 */
function section(): section
{
    return createWg('section', func_get_args());
}

/**
 * Zentao detail page section card widget.
 *
 * ====== blocks ======
 * title => array('map' => 'entityLabel')
 * ====================
 */
function sectionCard(): sectionCard
{
    return createWg('sectionCard', func_get_args());
}

/**
 * Zentao detail page section list widget.
 */
function sectionList(): sectionList
{
    return createWg('sectionList', func_get_args());
}

/**
 * Zentao entity label widget.
 *
 * string|int|null entityID
 * string|int|null level
 * string          text
 *
 * ====== blocks ======
 * suffix = array()
 * ====================
 */
function entityLabel(): entityLabel
{
    return createWg('entityLabel',func_get_args());
}

/**
 * Zentao table data widget.
 */
function tableData(): tableData
{
    return createWg('tableData',func_get_args());
}

/**
 * Zentao detail page header widget.
 *
 * ====== blocks ======
 * prefix => array()
 * title  => array()
 * suffix => array()
 * ====================
 */
function detailHeader(): detailHeader
{
    return createWg('detailHeader', func_get_args());
}

/**
 * Zentao detail page side widget.
 */
function detailSide(): detailSide
{
    return createWg('detailSide', func_get_args());
}

/**
 * Zentao detail page body widget.
 */
function detailBody(): detailBody
{
    return createWg('detailBody', func_get_args());
}

/**
 * ECharts widget.
 */
function echarts(): echarts
{
    return createWg('echarts', func_get_args());
}

/**
 * Popovers widget.
 *
 * ?string     placement='bottom'
 * ?string     strategy='fixed'
 * ?bool       flip=true
 * ?array|bool shift=array('padding' => 5)
 * ?bool       arrow=false
 * ?int        offset=1
 *
 * ====== blocks ======
 * trigger = array()
 * target = array()
 * ====================
 */
function popovers(): popovers
{
    return createWg('popovers', func_get_args());
}

/**
 * Back btn widget.
 *
 * ?string back
 */
function backBtn(): backBtn
{
    return createWg('backBtn', func_get_args());
}

/**
 * Collapse btn widget.
 *
 * string target
 * string parent
 */
function collapseBtn(): collapseBtn
{
    return createWg('collapseBtn', func_get_args());
}

/**
 * Main navbar widget.
 *
 * ?array items
 */
function mainNavbar(): mainNavbar
{
    return createWg('mainNavbar', func_get_args());
}

/**
 * Zentao float Pre and Next Button widget.
 *
 * ?string  preLink  link of pre-button.
 * ?string  nextLink link of next-button.
 */
function floatPreNextBtn(): floatPreNextBtn
{
    return createWg('floatPreNextBtn', func_get_args());
}

/**
 * Upload widget.
 *
 * string            name
 * ?string           icon
 * ?bool             showIcon=true
 * ?bool             showSize=true
 * ?bool             multiple=true
 * ?string           listPosition="bottom"
 * ?string           uploadText
 * ?string           uploadIcon
 * ?bool             renameBtn=true
 * ?string           renameIcon
 * ?string           renameText
 * ?string           renameClass
 * ?bool             deleteBtn=true
 * ?string           deleteText
 * ?string           deleteIcon
 * ?string           deleteClass
 * ?string           confirmText
 * ?string           cancelText
 * ?bool             useIconBtn=true
 * ?string           tip
 * ?string           btnClass
 * ?callable         onAdd
 * ?callable         onDelete
 * ?callable         onRename
 * ?callable         onSizeChange
 * ?bool             draggable
 * ?int              limitCount
 * ?string           accept
 * ?object[]         defaultFileList
 * false|string|null limitSize=false
 * ?string           duplicatedHint
 * ?string           exceededSizeHint
 * ?string           exceededCountHint
 */
function upload(): upload
{
    return createWg('upload', func_get_args());
}

/**
 * Upload images widget.
 *
 * string            name
 * ?bool             showSize=true
 * ?bool             multiple=true
 * ?string           uploadText
 * ?string           uploadIcon
 * ?bool             renameBtn=true
 * ?string           renameIcon
 * ?string           renameText
 * ?string           renameClass
 * ?bool             deleteBtn=true
 * ?string           deleteIcon
 * ?string           deleteText
 * ?string           deleteClass
 * ?string           tip
 * ?string           btnClass
 * ?callable         onAdd
 * ?callable         onDelete
 * ?callable         onRename
 * ?callable         onSizeChange
 * ?int              limitCount
 * ?string           accept
 * ?object[]         defaultFileList
 * false|string|null limitSize=false
 * ?string           duplicatedHint
 * ?string           exceededSizeHint
 * ?string           exceededCountHint
 * ?string           totalCountText
 */
function uploadImgs(): uploadImgs
{
    return createWg('uploadImgs', func_get_args());
}

/**
 * Burn widget.
 *
 * ?string|array data
 * ?bool         referenceLine
 */
function burn(): burn
{
    return createWg('burn', func_get_args());
}

/**
 * Monaco widget.
 *
 * string  id
 * ?string action
 * ?string options
 * ?string diffContent
 * ?string onMouseDown
 */
function monaco(): monaco
{
    return createWg('monaco', func_get_args());
}

/**
 * Dynamic widget.
 *
 * ?array  dynamics
 * ?array  users
 * ?string className
 */
function dynamic(): dynamic
{
    return createWg('dynamic', func_get_args());
}

/**
 * Form setting button.
 *
 * ?array  customFields
 * ?string urlParams
 */
function formSettingBtn(): formSettingBtn
{
    return createWg('formSettingBtn', func_get_args());
}

/**
 * Overview block widget.
 *
 * string id
 * string title
 */
function overviewBlock(): overviewBlock
{
    return createWg('overviewBlock', func_get_args());
}

/**
 * Statistic block widget.
 *
 */
function statisticBlock(): statisticBlock
{
    return createWg('statisticBlock', func_get_args());
}

/**
 * Picker widget.
 */
function picker(): picker
{
    return createWg('picker', func_get_args());
}

/**
 * Pri picker widget.
 *
 * ?string placeholder
 * ?string[]|object[]|object|array items
 */
function priPicker(): priPicker
{
    return createWg('priPicker', func_get_args());
}

/**
 * Severity picker widget.
 *
 * ?string placeholder
 * ?string[]|object[]|object|array items
 */
function severityPicker(): severityPicker
{
    return createWg('severityPicker', func_get_args());
}

function hr(): hr
{
    return createWg('hr', func_get_args());
}

/**
 * Global search widget.
 *
 * string commonSearchText
 * string commonSearchUrl
 * array  searchItems
 */
function globalSearch(): globalSearch
{
    return createWg('globalSearch', func_get_args());
}

/**
 * Steps editor widget.
 */
function stepsEditor(): stepsEditor
{
    return createWg('stepsEditor', func_get_args());
}

/**
 * table and chart widget.
 */
function tableChart(): tableChart
{
    return createWg('tableChart', func_get_args());
}

/**
 * Password widget.
 */
function password(): password
{
    return createWg('password', func_get_args());
}

/**
 * Mindmap widget.
 */
function mindmap(): mindmap
{
    return createWg('mindmap', func_get_args());
}

/**
 * Treemap widget.
 */
function treemap(): treemap
{
    return createWg('treemap', func_get_args());
}

/**
 * Image cutter widget.
 */
function imgCutter(): imgCutter
{
    return createWg('imgCutter', func_get_args());
}

/**
 * Next step modal widget.
 *
 * string tip
 * array  items
 */
function modalNextStep(): modalNextStep
{
    return createWg('modalNextStep', func_get_args());
}

/**
 * Navigator widget.
 */
function navigator(): navigator
{
    return createWg('navigator', func_get_args());
}

/**
 * Gantt widget.
 */
function gantt(): gantt
{
    return createWg('gantt', func_get_args());
}

/**
 * road map widget.
 */
function roadMap(): roadMap
{
    return createWg('roadmap', func_get_args());
}

/**
 * Progress bar widget.
 */
function progressBar(): progressBar
{
    return createWg('progressBar', func_get_args());
}

/**
 * Progress circle widget.
 */
function progressCircle(): progressCircle
{
    return createWg('progressCircle', func_get_args());
}

/**
 * Filter widget.
 */
function filter(): filter
{
    return createWg('filter', func_get_args());
}

/**
 * Result filter widget.
 */
function resultFilter(): resultFilter
{
    return createWg('resultFilter', func_get_args());
}

/**
 * Contact list widget.
 */
function contactList(): contactList
{
    return createWg('contactList', func_get_args());
}

/**
 * Users widget.
 */
function users(): users
{
    return createWg('users', func_get_args());
}

/**
 * Mailto widget.
 */
function mailto(): mailto
{
    return createWg('mailto', func_get_args());
}

/**
 * whitelist widget.
 */
function whitelist(): whitelist
{
    return createWg('whitelist', func_get_args());
}
