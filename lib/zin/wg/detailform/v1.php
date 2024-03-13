<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'backbtn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'history' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'entitytitle' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formbase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';

class detailForm extends wg
{
    protected static array $defineProps = array
    (
        /* ID，如果不指定则自动生成（使用 zin 部件 GID）。 */
        'id '        => '?string="$GID"',

        /* 对象类型，例如 `story`、`task` 等，如果不指定则已当前的模块名称作为对象类型。 */
        'objectType' => '?string',

        /* 对象 ID，如果不指定则尝试使用当前页面上的 `${$objectType}->id` 或者 `${$objectType}ID` 的值，例如 `$task->id` 或 `$taskID`。 */
        'objectID'   => '?int',

        /* 对象标题颜色。 */
        'color'      => '?string',

        /* 对象，如果不指定则尝试使用当前页面上的 `${$objectType}` 的值，例如 `$task`。 */
        'object'     => '?object',

        /* 标题，如果不指定则尝试使用当前页面上的 `${$objectType}->title` 或 `${$objectType}->name` 的值，例如 `$story->title`、`$task->name` 。 */
        'title'      => '?string',

        /* 标题前缀。 */
        'titlePrefix' => '?string|bool',

        /* 表单操作按钮，如果不指定则使用默认行为的 “保存” 和 “返回” 按钮。 */
        'actions'    => '?array',

        /* 表单操作按钮栏类名。 */
        'actionsClass' => '?string',

        /* 右上方工具栏的定义。 */
        'toolbar'    => '?array',

        /* 表单提交地址。 */
        'url'        => '?string',

        /* 表单项标签默认数据。 */
        'labelData'  => 'null|array|object',

        /* 返回按钮，可以为：`true` 显示默认的返回按钮，`false` 不显示返回按钮，或者指定返回按钮 backBtn 的 back 属性，或者通过数组指定 backBtn 所有属性。 */
        'backBtn'    => '?bool|string|array',

        /* 主要栏表单字段列表。 */
        'fields'     => 'null|string|array|fieldList',

        /* 提交备注。 */
        'comment'    => '?string|bool|array=true',

        /* 侧边栏表单项分组标题。 */
        'groupTitles'=> '?array',

        /* 右侧侧边栏宽度，如果不指定则默认为 `370`。 */
        'sideWidth'  => '?int=370',

        /* 左侧主栏表单项标签宽度。 */
        'sideLabelWidth' => '?int|string',

        /* 左侧主栏目历史记录，如果设定为 `true` 显示当前对象默认的理智记录，否如果设置为 `false` 不显示历史记录，如果设置为数组则作为 `history()` 部件的属性来创建历史记录。 */
        'history'    => '?array|bool=true'
    );

    protected static array $defineBlocks = array
    (
        'header'   => array(),
        'title'    => array(),
        'main'     => array('map' => 'content'),
        'side'     => array('map' => 'tabs'),
        'actions'  => array('map' => 'btn'),
        'toolbar'  => array('map' => 'btnGroup,toolbar')
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .detail-form-main .form-label {font-size: 14px; font-weight: bold; margin-bottom: 4px; position: sticky; top: 0; background: var(--color-canvas); z-index: 1}
        .detail-form-main {border-right: 1px solid var(--color-border)}
        .detail-form-main .form-grid .form-label.required:after {order: 1}
        .detail-form-side {border-left: 1px solid var(--color-border); margin-left: -1px}
        .detail-form-main .form-grid {padding: 12px 16px}
        .detail-form-history {padding: 12px 16px}
        .detail-form-side .form-row {padding: 8px 0}
        .detail-form-side .form-group {align-items: center; min-height: 20px}
        .detail-form-side .form-label {height: 20px; top: auto}
        .detail-form-side .form-control-static {padding: 0;  min-height: 20px}
        CSS;
    }

    protected ?array $fieldMap = null;
    protected ?object $object = null;
    protected null|array|object $labelData = null;

    protected function created()
    {
        global $app, $lang;

        $objectType = $this->prop('objectType');
        $objectID   = $this->prop('objectID');
        $object     = $this->prop('object');

        if(!$objectType) $objectType = $app->rawModule;
        if(!$object)     $object     = data($objectType);
        if(!$objectID)   $objectID   = $object ? $object->id : data($objectType . 'ID');

        if(!$objectType || !$objectID || !$object)
        {
            $this->triggerError('The objectType, objectID or object property of widget "detail" is undefined.');
        }

        if(!$this->prop('objectType')) $this->setProp('objectType', $objectType);
        if(!$this->prop('objectID'))   $this->setProp('objectID',   $objectID);
        if(!$this->prop('object'))     $this->setProp('object',     $object);
        if(!$this->prop('backBtn'))    $this->setProp('backBtn',    !isInModal());

        if($object)
        {
            if(!$this->hasProp('title')) $this->setProp('title', isset($object->name) ? $object->name : $object->title);
            if(!$this->hasProp('color') && isset($object->color)) $this->setProp('color', $object->color);
        }

        if(!$this->hasProp('titlePrefix') && isset($lang->$objectType->edit)) $this->setProp('titlePrefix', $lang->$objectType->edit);
        if(!$this->hasProp('labelData') && isset($lang->$objectType)) $this->setProp('labelData', $lang->$objectType);

    }

    protected function buildTitle()
    {
        list($object, $objectID, $title, $color, $objectType, $titlePrefix) = $this->prop(array('object', 'objectID', 'title', 'color', 'objectType', 'titlePrefix'));
        $titleBlock = $this->block('title');

        return new entityTitle
        (
            setClass('min-w-0'),
            set::id($objectID),
            set::object($object),
            set::title($title),
            set::titleClass('text-lg text-clip font-bold'),
            set::type($objectType),
            set::color($color),
            $titlePrefix ? to::prefix(span(setClass('text-gray'), $titlePrefix)) : null,
            $titleBlock
        );
    }

    protected function buildToolbar()
    {
        $toolbar      = $this->prop('toolbar');
        $toolbarBlock = $this->block('toolbar');

        if(!$toolbarBlock && !$toolbar) return null;

        $toolbarProps = array_is_list($toolbar) ? array('items' => $toolbar) : $toolbar;

        return div
        (
            setClass('detail-form-toolbar'),
            $toolbarProps ? toolbar
            (
                set::urlFormatter($this->prop('urlFormatter')),
                set($toolbarProps),
            ) : null,
            $toolbarBlock
        );
    }

    protected function buildBackBtn(?array $props = null)
    {
        global $lang;

        $backBtn = $this->prop('backBtn');
        if($backBtn === false) return null;

        if(is_string($backBtn))     $backBtn = array('back' => $backBtn);
        elseif(!is_array($backBtn)) $backBtn = array();

        if($props) $backBtn = array_merge($backBtn, $props);
        return new backBtn
        (
            set::icon('back'),
            set::text($lang->goback),
            set($backBtn)
        );
    }

    protected function buildHeader()
    {
        return div
        (
            setClass('detail-form-header row gap-2 items-center flex-none'),
            $this->buildBackBtn(array('type' => 'primary-outline', 'class' => 'mr-2 size-md')),
            $this->buildTitle(),
            $this->block('header'),
            div(setClass('flex-auto')),
            $this->buildToolbar()
        );
    }

    protected function buildHistory()
    {
        $history = $this->prop('history');
        if($history === false) return null;

        if(!is_array($history)) $history = array();

        return new history
        (
            set::class('detail-form-history overflow-visible ring-0 border-t'),
            set::objectType($this->prop('objectType')),
            set::objectID($this->prop('objectID')),
            set::commentBtn(false),
            set($history)
        );
    }

    protected function getFieldLabel(string $name): ?string
    {
        $labelData = $this->labelData;
        $lblName   = 'lbl' . ucfirst($name);

        if(is_object($labelData)) return isset($labelData->$lblName) ? $labelData->$lblName : (isset($labelData->$name) ? $labelData->$name : null);
        if(is_array($labelData))  return isset($labelData[$lblName]) ? $labelData[$lblName] : (isset($labelData[$name]) ? $labelData[$name] : null);
        return null;
    }

    protected function getFieldValue(string $name): ?string
    {
        $object = $this->object;
        if(!$object) return null;
        return isset($object->$name) ? strval($object->$name) : null;
    }

    protected function buildField(array $field, bool $useRow = false)
    {
        $name = $field['name'];
        if(!isset($field['value'])) $field['value'] = $this->getFieldValue($name);
        if(!isset($field['label'])) $field['label'] = $this->getFieldLabel($name);

        $control = isset($field['control']) ? $field['control'] : null;
        if(is_null($control))   $control = array('name' => $name);
        if(is_string($control)) $control = array('control' => $control, 'name' => $name);
        if(is_array($control) && !isset($control['id'])) $control['id'] = '';
        $field['control'] = $control;

        $view = new formGroup(set($field));
        if($useRow) $view = new formRow($view);
        return $view;
    }

    protected function buildFields(string|array $groupOrFields = 'main', bool $useRow = false)
    {
        if(is_string($groupOrFields))
        {
            if(!isset($this->fieldMap[$groupOrFields])) return null;
            $fields = $this->fieldMap[$groupOrFields];
        }
        else
        {
            $fields = $groupOrFields;
        }
        if(!$fields) return null;

        $items = array();
        foreach($fields as $field)
        {
            $item = $this->buildField($field, $useRow);
            if(empty($field)) continue;

            $items[] = $item;
        }

        return $items;
    }

    protected function buildComment()
    {
        $comment = $this->prop('comment');
        if(!$comment) return null;

        $props = array('name' => 'comment', 'control' => 'editor');
        if(is_string($comment))     $props['name'] = $comment;
        elseif(is_array($comment))  $props = array_merge($props, $comment);

        if(!isset($props['label']))
        {
            global $lang;
            $label = $this->getFieldLabel('comment');
            $props['label'] = $label ? $label : $lang->comment;
        }

        return new formGroup(set($props));
    }

    protected function buildMain()
    {
        return div
        (
            setClass('detail-form-main flex-auto'),
            div
            (
                setClass('form-grid'),
                $this->buildFields(),
                $this->buildComment()
            ),
            $this->block('main'),
            $this->buildHistory()
        );
    }

    protected function buildSideGroups()
    {
        $groupTitles = $this->prop('groupTitles', array());
        $items       = array();
        foreach($this->fieldMap as $group => $fields)
        {
            if($group === 'main') continue;
            $items[] = div
            (
                setClass('form-horz col pt-2 pr-5'),
                div
                (
                    setClass('text-md font-bold sticky top-0 canvas py-2 px-4 z-5'),
                    isset($groupTitles[$group]) ? $groupTitles[$group] : $group
                ),
                $this->buildFields($fields, true)
            );
        }

        return div
        (
            setClass('detail-form-side-groups pb-3'),
            setCssVar('--form-horz-label-width', $this->prop('sideLabelWidth')),
            $items
        );
    }

    protected function buildSide()
    {
        return div
        (
            setClass('detail-form-side flex-none col gap-1'),
            setStyle('width', $this->prop('sideWidth') . 'px'),
            $this->buildSideGroups(),
            $this->block('side')
        );
    }

    protected function buildBody()
    {
        return div
        (
            setClass('detail-form-body row items-start'),
            $this->buildMain(),
            $this->buildSide()
        );
    }

    protected function beforeBuild()
    {
        list($object, $labelData, $fields) = $this->prop(array('object', 'labelData', 'fields'));

        $this->object    = $object;
        $this->labelData = $labelData ? $labelData : array();
        $this->fieldMap  = array();
        if($fields instanceof fieldList) $fields = $fields->toArray();
        foreach($fields as $field)
        {
            if(!$field) continue;
            if($field instanceof setting) $field = $field->toArray();
            if(is_object($field)) $field = get_object_vars($field);
            if(!isset($field['name'])) continue;

            $group = (isset($field['group']) && $field['group']) ? $field['group'] : 'main';
            if(!isset($this->fieldMap[$group])) $this->fieldMap[$group] = array();
            $this->fieldMap[$group][$field['name']] = $field;
        }
    }

    protected function build()
    {
        $this->beforeBuild();

        list($objectType, $objectID, $backBtn) = $this->prop(array('objectType', 'objectID', 'backBtn'));
        $formProps = array_keys(formBase::definedPropsList());

        $backProps = array();
        if(is_string($backBtn))    $backProps['back'] = $backBtn;
        elseif(is_array($backBtn)) $backProps = $backBtn;

        return div
        (
            setClass('detail-form col gap-4'),
            setData('id', $objectID),
            setData('type', $objectType),
            $this->buildHeader(),
            new formBase
            (
                setClass('canvas shadow rounded gap-0'),
                set($this->props->pick($formProps)),
                set($backProps),
                set::actionsClass('py-4 sticky border-t bottom-0 canvas z-5', isset($formProps['actionsClass']) ? $formProps['actionsClass'] : null),
                $this->buildBody(),
            )
        );
    }
}
