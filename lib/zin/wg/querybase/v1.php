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
        'widgets?: array=[]',
        'changeModeDisabled?: bool=false',
        'mode?: string=text',
        'error?: string',
        'onChangeMode?: function',
        'onQuery?: function',
        'onSqlChange?: function'
    );

    protected static array $defineBlocks = array(
        'heading'     => array(),
        'formActions' => array(),
        'formFooter'  => array()
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildPreviewSql()
    {
        global $lang;

        return btn
        (
            setID('previewSql'),
            setClass('ghost'),
            $lang->bi->previewSql
        );
    }

    protected function buildChangeMode()
    {
        global $lang;
        list($mode, $changeModeDisabled, $onChangeMode) = $this->prop(array('mode', 'changeModeDisabled', 'onChangeMode'));
        $isTextMode = $mode == 'text';

        return btn
        (
            setID('changeMode'),
            setClass('ghost'),
            set('data-mode', $mode),
            $changeModeDisabled ? set::hint($lang->bi->modeDisableTip) : null,
            set::icon('exchange'),
            set::disabled($changeModeDisabled),
            $isTextMode ? $lang->bi->toggleSqlBuilder : $lang->bi->toggleSqlText,
            !$changeModeDisabled ? on::click()->do($onChangeMode) : null
        );
    }

    protected function buildDivider()
    {
        return span(setClass('divider'));
    }

    protected function buildHeadingActions()
    {
        list($widgets) = $this->prop(array('widgets'));
        $headingWidgets = array('previewSql', 'changeMode');
        $actions = array();

        foreach($widgets as $widget) if(in_array($widget, $headingWidgets)) $actions[] = $widget;
        if(empty($actions)) return null;

        for($i = count($actions) - 1; $i > 0; $i--) array_splice($actions, $i, 0, 'divider');

        $items = array();
        foreach($actions as $action) $items[] = $this->{"build$action"}();

        return div
        (
            setClass('absolute right-4 top-2'),
            $items
        );
    }

    protected function buildTitleTip()
    {
        list($titleTip) = $this->prop(array('titleTip'));
        if(empty($titleTip)) return null;

        return div
        (
            setClass('sql-help-text'),
            sqlBuilderHelpIcon('', false),
            span(setClass('text-gray-500'), $titleTip)
        );
    }

    protected function buildQueryPanel()
    {
        global $lang;
        list($title, $titleTip, $sql, $onQuery, $onSqlChange, $error) = $this->prop(array('title', 'titleTip', 'sql', 'onQuery', 'onSqlChange', 'error'));
        $headingBlock     = $this->block('heading');
        $formActionsBlock = $this->block('formActions');
        $formFooterBlock  = $this->block('formFooter');

        return panel
        (
            setClass('mb-4 sql-panel'),
            set::title($title),
            to::heading
            (
                setClass('relative'),
                $this->buildTitleTip(),
                $this->buildHeadingActions(),
                $headingBlock
            ),
            form
            (
                setID('sqlForm'),
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
                    set::control(array('type' => 'textarea', 'rows' => 4)),
                    set::name('sql'),
                    set::value($sql),
                    on::change()->do($onSqlChange)
                ),
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
        list($sql, $cols, $data) = $this->prop(array('sql', 'cols', 'data'));

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
                set::footPager(usePager('pager', 'customLink', null, null, 'window.postQueryResult')),
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
        list($cols, $data, $settings, $tableOptions) = $this->prop(array('cols', 'data', 'settings', 'tableOptions'));
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
            set::size('lg'),
            formBatch
            (
                setID('fieldSettingsForm'),
                set::mode('read-only'),
                set::data($data),
                set::actions(array()),
                $app->rawMethod == 'design' ? on::change('[data-name="object"]')->do('clearField(event)') : null,
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
                    $lang->save
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
