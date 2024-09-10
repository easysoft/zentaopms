<?php
declare(strict_types=1);
namespace zin;

class queryBase extends wg
{
    protected static array $defineProps = array(
        'title?: string',
        'titleTip?: string',
        'sql?: string',
        'cols?: array',
        'data?: array',
        'settings?: array',
        'tableOptions?: array',
        'pager?: array',
        'mode?: string=text',
        'error?: string',
        'onQuery?: function',
        'onSqlChange?: function',
        'onSaveFields?: function'
    );

    protected static array $defineBlocks = array(
        'heading'     => array(),
        'formActions' => array(),
        'formFooter'  => array(),
        'builder'     => array()
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildTitleTip()
    {
        list($titleTip) = $this->prop(array('titleTip'));
        if(empty($titleTip)) return null;

        return div
        (
            setClass('sql-help-text pl-2'),
            sqlBuilderHelpIcon('', false),
            span(setClass('text-gray-500'), $titleTip)
        );
    }

    protected function buildQueryPanel()
    {
        global $lang;
        list($title, $sql, $mode, $onQuery, $onSqlChange, $error) = $this->prop(array('title', 'sql', 'mode', 'onQuery', 'onSqlChange', 'error'));
        $headingBlock     = $this->block('heading');
        $formActionsBlock = $this->block('formActions');
        $formFooterBlock  = $this->block('formFooter');

        return panel
        (
            setClass('mb-4 sql-panel'),
            set::title($title),
            to::heading
            (
                setClass('relative justify-start-important gap-0-important'),
                $this->buildTitleTip(),
                $headingBlock
            ),
            form
            (
                setID('sqlForm'),
                setClass('relative'),
                set::actionsClass('pull-left'),
                to::actions
                (
                    btn
                    (
                        setClass('query primary'),
                        set::text($lang->dataview->query),
                        on::click()->do($onQuery)
                    ),
                    $formActionsBlock
                ),
                set::submitBtnText($lang->pivot->query),
                formGroup
                (
                    setID('querySql'),
                    setClass(array('hidden' => $mode != 'text')),
                    set::control(array('type' => 'textarea', 'rows' => 4)),
                    set::name('sql'),
                    set::value($sql),
                    on::change()->do($onSqlChange)
                ),
                $this->block('builder'),
                !empty($error) ? formGroup
                (
                    set::tipClass('text-danger'),
                    set::tip($error)
                ) : null,
                $formFooterBlock
            )
        );
    }

    protected function buildTablePanel()
    {
        global $lang;
        list($sql, $cols, $data, $pager) = $this->prop(array('sql', 'cols', 'data', 'pager'));

        return panel
        (
            set::title($lang->dataview->result),
            to::headingActions
            (
                empty($sql) ? null :
                modalTrigger
                (
                    btn
                    (
                        set::type('ghost'),
                        set::icon('cog-outline'),
                        $lang->dataview->fieldSettings
                    ),
                    set::target('#fieldSettingsModal')
                )
            ),
            dtable
            (
                set::cols($cols),
                set::data($data),
                set::height(440),
                set::footPager($pager),
            )
        );
    }

    protected function buildFormBatchItem()
    {
        global $config;
        $items = array();
        foreach($config->langs as $key => $name)
        {
            $items[] = formBatchItem
            (
                set::name($key),
                set::label($name),
                set::width('120px')
            );
        }

        return $items;
    }

    protected function buildFieldSettingsModal()
    {
        global $lang, $app;
        list($cols, $settings, $tableOptions, $onSaveFields) = $this->prop(array('cols', 'settings', 'tableOptions', 'onSaveFields'));
        if(empty($cols)) return null;

        $data = array();
        foreach((array)$settings as $key => $setting)
        {
            $setting = (array)$setting;
            $setting['key'] = $key;
            $data[] = $setting;
        }

        return modal
        (
            setID('fieldSettingsModal'),
            set::title($lang->dataview->fieldSettings),
            setData('backdrop', 'static'),
            setData('tables', $tableOptions),
            set::size('lg'),
            formBatch
            (
                setID('fieldSettingsForm'),
                set::mode('read-only'),
                set::data($data),
                set::actions(array()),
                on::change('[data-name="object"]')->do('clearField(event)'),
                on::change('[name^=object]')->do('loadFields(event)'),
                on::change('[name^=field]')->do('changeFields(event)'),
                formBatchItem
                (
                    set::name('key'),
                    set::label($lang->dataview->field),
                    set::control('input'),
                    set::width('100px'),
                    set::readonly(true)
                ),
                formBatchItem
                (
                    set::name('object'),
                    set::label($lang->dataview->relatedTable),
                    set::control('picker'),
                    set::width('120px'),
                    set::items($tableOptions)
                ),
                formBatchItem
                (
                    set::name('field'),
                    set::label($lang->dataview->relatedField),
                    set::control('picker'),
                    set::width('120px'),
                    set::items(array())
                ),
                formBatchItem
                (
                    set::name('type'),
                    set::control('input'),
                    set::width('120px'),
                    set::hidden(true)
                ),
                $this->buildFormBatchItem(),
                set::onRenderRow(jsRaw('renderRow')),
            ),
            set::footerClass('form-actions gap-4 mt-4'),
            to::footer
            (
                btn
                (
                    setID('saveFields'),
                    set::type('primary'),
                    $lang->save,
                    on::click()->do($onSaveFields)
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

    protected function build()
    {
        return array
        (
            $this->buildQueryPanel(),
            $this->buildTablePanel(),
            $this->buildFieldSettingsModal()
        );
    }
}
