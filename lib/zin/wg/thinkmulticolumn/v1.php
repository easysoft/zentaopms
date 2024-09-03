<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

class thinkMulticolumn extends thinkQuestion
{
    protected static array $defineProps = array
    (
        'fields?: array',       // 列标题
        'requiredCols?: array', // 必填列
        'supportAdd?: int=1',   // 是否支持用户添加行
        'canAddRows: int',      // 可添加行数
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        $baseCss = file_get_contents(dirname(__FILE__, 2) . DS . 'thinkstepbase' . DS . 'css' . DS . 'v1.css');
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css') . $baseCss;
    }

    protected function buildFormBatchItem(string $label, int $index): wg
    {
        $step         = $this->prop('step');
        $key          = $index + 1;
        $requiredCols = isset($step->options->requiredCols) ? $step->options->requiredCols : array();

        return formBatchItem
        (
            set::label($label),
            set::name("result[col$key]"),
            set::width('110px'),
            set::required(in_array($key, $requiredCols))
        );
    }

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $fields, $canAddRows, $mode, $isRun) = $this->prop(array('step', 'fields', 'canAddRows', 'mode', 'isRun'));
        if($mode != 'detail') return array();

        $result = array();
        if($step)
        {
            $fields     = $step->options->fields;
            $canAddRows = $step->options->supportAdd == 1 ? $step->options->canAddRows : 0;
            $answer     = $step->answer;
            $result     = isset($answer->result) && !empty($answer->result) ? (array) $answer->result : array();
        }
        jsVar('canAddRowsOfMulticol', $canAddRows + 5);
        jsVar('addRowsTips', $lang->thinkrun->tips->addRow);
        jsVar('addLang', $lang->thinkrun->add);

        $fields     = array_values((array)$fields);
        $batchItems = array();
        foreach($fields as $key => $field) $batchItems[] = $this->buildFormBatchItem($field, (int)$key);
        $detailWg[] = formBatch
        (
            setClass('think-form-batch'),
            set::minRows(5),
            set::actions(array()),
            set::onRenderRow(jsRaw('renderRowData')),
            $isRun ? formHidden('status', '') : null,
            set::data($result),
            $batchItems
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $questionType, $required, $fields, $supportAdd, $canAddRows, $requiredCols) = $this->prop(array('step', 'questionType', 'required', 'fields', 'supportAdd', 'canAddRows', 'requiredCols'));
        $requiredItems = $lang->thinkstep->requiredList;

        $requiredOptions = array();
        if($step)
        {
            $required = isset($step->options->required) ? $step->options->required : 1;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields       = $step->options->fields;
            $requiredCols = $required && isset($step->options->requiredCols) ? $step->options->requiredCols : '';
            $supportAdd   = $step->options->supportAdd;
            $canAddRows   = $supportAdd && isset($step->options->canAddRows) ? $step->options->canAddRows : '';
            foreach($fields as $key => $field) $requiredOptions[] = array('value' => $key + 1, 'text' => $field);
        }
        jsVar('canAddRowsOfMulticol', (int)$canAddRows + 5);
        jsVar('addRowsTips', $lang->thinkrun->tips->addRow);
        jsVar('addLang', $lang->thinkrun->add);

        $formItems[] = array(
            formHidden('options[questionType]', $questionType),
            formGroup
            (
                set::label($lang->thinkstep->label->columnTitle),
                setStyle(array('padding-bottom' => 'calc(4 * var(--space))')),
                thinkMatrixOptions(set::colName('options[fields]'), set::cols($fields))
            ),
            formRow
            (
                setClass('mb-3'),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->thinkstep->label->required),
                    radioList
                    (
                        set::name('options[required]'),
                        set::inline(true),
                        set::value($required),
                        set::items($requiredItems),
                        on::change()->toggleClass('.required-options', 'hidden', 'target.value == 0')
                    )
                ),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->thinkstep->label->requiredCol),
                    set::required(true),
                    setClass('required-options', $required ? '' : 'hidden'),
                    picker
                    (
                        set::name('options[requiredCols][]'),
                        set::items($requiredOptions),
                        set::value($requiredCols),
                        set::multiple(true)
                    )
                )
            ),
            formRow
            (
                setClass('mb-3'),
                formGroup
                (
                    setClass('w-1/2'),
                    set::label($lang->thinkstep->label->isSupportAdd),
                    set::labelHint($lang->thinkstep->tips->supportAdd),
                    radioList
                    (
                        set::name('options[supportAdd]'),
                        set::inline(true),
                        set::items($lang->thinkstep->requiredList),
                        set::value(is_null($supportAdd) ? 1 : $supportAdd),
                        on::change()->toggleClass('.can-add-rows', 'hidden', 'target.value == 0')
                    )
                ),
                formGroup
                (
                    setClass('w-1/2 can-add-rows', (is_null($supportAdd) || $supportAdd) ? '' : 'hidden'),
                    set::label($lang->thinkstep->label->canAddRows),
                    set::required(true),
                    input
                    (
                        set::type('number'),
                        set::name('options[canAddRows]'),
                        set::value($canAddRows),
                        set::placeholder($lang->thinkstep->placeholder->inputContent),
                        set::min(1),
                        on::input('changeRows')
                    )
                )
            )
        );
        return $formItems;
    }
}
