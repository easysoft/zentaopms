<?php
declare(strict_types=1);
namespace zin;

class queryFilterModal extends wg
{
    protected static array $defineProps = array(
        'data?: array',
        'addData?: array',
        'defaultOptions?: array',
        'onAdd?: function',
        'onRemove?: function',
        'onChange?: function',
        'onSave?: function'
    );

    protected function buildControl($name, $class, $children)
    {
        global $lang;
        return formGroup
        (
            setID("{$name}Box"),
            setClass("self-center $class"),
            $children,
            set::tip($lang->pivot->notEmpty),
            set::tipClass('text-danger hidden')
        );
    }

    protected function buildFormRow($index, $value, $mode = 'edit')
    {
        global $lang;
        list($onRemove, $onChange) = $this->prop(array('onRemove', 'onChange'));
        if(empty($value)) return null;

        $field = $this->buildControl
        (
            'field',
            'basis-48',
            input
            (
                set::name('field'),
                set::disabled($mode == 'edit'),
                set::value($value['field'])
            )
        );

        $name = $this->buildControl
        (
            'name',
            'basis-48',
            input
            (
                set::name('name'),
                set::value($value['name']),
                set::required()
            )
        );

        $defaultWgList = array('date' => 'datePicker', 'datetime' => 'datetimePicker', 'input' => 'input', 'select' => 'picker', 'multipleselect' => 'picker');

        $type = $this->buildControl
        (
            'type',
            'basis-72',
            div
            (
                setClass('input-group'),
                picker
                (
                    set::name('type'),
                    set::items($lang->dataview->varFilter->requestTypeList),
                    set::value($value['type']),
                    set::required()
                ),
                picker
                (
                    setClass(array('hidden' => $defaultWgList[$value['type']] !== 'picker')),
                    set::name("typeOption"),
                    set::items($lang->dataview->varFilter->selectList),
                    set::value($value['typeOption'])
                )
            )
        );

        list($defaultType, $defaultItems, $defaultValue) = array($value['type'], $value['items'], $value['default']);

        $default = $this->buildControl
        (
            'default',
            'basis-48',
            createWg($defaultWgList[$defaultType], set(array('name' => 'default', 'items' => $defaultItems, 'value' => $defaultValue, 'multiple' => ($defaultType == 'multipleselect' ? true : false))))
        );

        return formRow
        (
            setID("queryFormItem$index"),
            setClass('flex form-body justify-between items-end max-h-16 query-filter-row'),
            set('data-index', $index),
            set('data-mode', $mode),
            $field,
            $name,
            $type,
            $default,
            formGroup
            (
                setClass('flex justify-center basis-16 self-center'),
                btnGroup
                (
                    btn
                    (
                        setClass('btn btn-link text-gray delete-query-filter'),
                        set::icon('trash'),
                        on::click()->do($onRemove)
                    )
                )
            ),
            on::change('.query-filter-row .form-control')->do($onChange)
        );
    }

    protected function buildFormHeader()
    {
        global $lang;

        return div
        (
            setClass('flex form-header justify-between h-10'),
            div
            (
                setClass("form-header-item font-bold text-center basis-48 text-gray"),
                $lang->dataview->varFilter->varCode
            ),
            div
            (
                setClass("form-header-item font-bold text-center basis-48"),
                $lang->dataview->varFilter->varLabel
            ),
            div
            (
                setClass("form-header-item font-bold text-center basis-72"),
                $lang->dataview->varFilter->requestType
            ),
            div
            (
                setClass("form-header-item font-bold text-center basis-48"),
                $lang->pivot->default
            ),
            div
            (
                setClass("form-header-item font-bold text-center basis-16"),
                $lang->actions
            )
        );
    }

    protected function buildAddBtn()
    {
        global $lang;
        list($addData, $onAdd) = $this->prop(array('addData', 'onAdd'));
        if(!empty($addData)) return null;

        return div
        (
            setClass('flex justify-center'),
            btn
            (
                setID('addQueryFilter'),
                $lang->dataview->add . $lang->dataview->queryFilters,
                on::click()->do($onAdd)
            )
        );
    }

    protected function build()
    {
        global $lang;
        list($data, $addData, $onSave) = $this->prop(array('data', 'addData', 'onSave'));

        $formRowItems = array();
        foreach($data as $index => $filter) $formRowItems[] = $this->buildFormRow($index, $filter);

        $index = count($data);
        $formRowItems[] = $this->buildFormRow($index, $addData, 'add');
        return modal
        (
            setID('queryFilterModal'),
            setData('backdrop', 'static'),
            set::title($lang->dataview->add . $lang->dataview->queryFilters),
            set::size('lg'),
            hr(),
            div
            (
                setClass('alert secondary-pale'),
                div($lang->dataview->queryFilterTip)
            ),
            panel
            (
                setID('queryFilterPanel'),
                formBase
                (
                    set::actions(array()),
                    $this->buildFormHeader(),
                    $formRowItems,
                    $this->buildAddBtn()
                )
            ),
            set::footerClass('form-actions gap-4 mt-4'),
            to::footer
            (
                btn
                (
                    setID('saveQueryFilter'),
                    set::type('primary'),
                    $lang->save,
                    on::click()->do($onSave)
                ),
                btn
                (
                    set::type('default'),
                    set('data-dismiss', 'modal'),
                    $lang->close
                )
            )
        );
    }
}
