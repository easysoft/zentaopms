<?php
declare(strict_types=1);
namespace zin;

class thinkStep  extends wg
{
    protected static array $defineProps = array(
        'item: object',
        'action?: string="detail"',
        'addType?: string',
        'isRun?: bool=false', // 是否是分析活动
    );

    protected function buildBody(): wg|array
    {
        list($item, $action, $addType, $isRun) = $this->prop(array('item', 'action', 'addType', 'isRun'));

        $step         = $addType ? null : $item;
        $questionType = $addType ? $addType : ($item->options->questionType ?? '');
        if($addType === 'node' || !$addType && $item->type === 'node') return thinkNodeBase(set::step($step), set::mode($action), set::isRun($isRun));
        if($addType === 'transition' || !$addType && $item->type === 'transition') return thinkTransition(set::step($step), set::mode($action), set::isRun($isRun));
        if($questionType === 'input')    return thinkInput(set::step($step), set::question('input'), set::mode($action), set::isRun($isRun));
        if($questionType === 'radio')    return thinkRadio(set::step($step), set::question('radio'), set::mode($action), set::isRun($isRun));
        if($questionType === 'checkbox') return thinkCheckbox(set::step($step), set::question('checkbox'), set::mode($action), set::isRun($isRun));

        $isEdit = $action === 'edit' ? true : false;
        $fields = !empty($item->options->fields) ? explode(', ', $itemi->options->fields) : array();
        if($action == 'detail' && $questionType === 'tableInput')
        {
            return thinkStepDetail
            (
                set::item($item),
                thinkTableInputDetail
                (
                    set::item($item),
                    set::required($item->options->required ?? 0),
                    set::rowsTitle($fields)
                )
            );
        }
        else
        {
            if($questionType == 'tableInput')
            {
                return thinkTableInput(
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? ($item->options->required ?? 0) : 0),
                    set::type('question'),
                    set::requiredRows($isEdit ? ($item->options->requiredRows ?? 1) : 1),
                    set::isSupportAdd($isEdit ? ($item->options->isSupportAdd ?? false) : false),
                    set::canAddRows($isEdit ? ($item->options->canAddRows ?? 1) : 1),
                    set::rowsTitle(!$isEdit ? null : $fields)
                );
            }
        }
    }

    protected function build(): wg|array
    {
        global $lang;
        list($item, $action, $addType) = $this->prop(array('item', 'action', 'addType'));
        if(!$item) return array();

        $title = isset($item->id) && !$addType ? ($item->type == 'question' ? $lang->thinkwizard->step->editTitle[$item->options->questionType] : $lang->thinkwizard->step->editTitle[$item->type]) : $lang->thinkwizard->step->addTitle[$addType];

        return array(
            div
            (
                setClass($action == 'detail' ? 'relative pt-6 px-8 mx-4' : 'relative'),
                $action !== 'detail' ? array(
                    div
                    (
                        setClass('flex items-center text-gray-950 h-12 py-0 px-8 mx-4'),
                        div(setClass('font-medium'), $title)
                    ),
                    h::hr()
                ) : null,
                $this->buildBody()
            )
        );
    }
}
