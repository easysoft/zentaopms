<?php
declare(strict_types=1);
namespace zin;

class thinkStep  extends wg
{
    protected static array $defineProps = array(
        'item: object',
        'action?: string="detail"',
        'addType?: string',
        'wizard: object',
        'isRun?: bool=false',        // 是否是分析活动
        'quoteQuestions?: array',    // 引用题目的下拉选项
        'quotedQuestions?: array',   // 被引用的题目
    );

    protected function buildBody(): wg|array
    {
        list($item, $action, $addType, $isRun, $quoteQuestions, $quotedQuestions) = $this->prop(array('item', 'action', 'addType', 'isRun', 'quoteQuestions', 'quotedQuestions'));

        $step         = $addType ? null : $item;
        $questionType = $addType ? $addType : ($item->options->questionType ?? '');
        if($addType === 'node' || !$addType && $item->type === 'node') return thinkNode(set::step($step), set::mode($action), set::isRun($isRun));
        if($addType === 'transition' || !$addType && $item->type === 'transition') return thinkTransition(set::step($step), set::mode($action), set::isRun($isRun));
        if($questionType === 'input')       return thinkInput(set::step($step), set::questionType('input'), set::mode($action), set::isRun($isRun));
        if($questionType === 'radio')       return thinkRadio(set::step($step), set::questionType('radio'), set::mode($action), set::isRun($isRun), set::quotedQuestions($quotedQuestions));
        if($questionType === 'checkbox')    return thinkCheckbox(set::step($step), set::questionType('checkbox'), set::mode($action), set::isRun($isRun), set::quoteQuestions($quoteQuestions), set::quotedQuestions($quotedQuestions));
        if($questionType === 'tableInput')  return thinkTableInput(set::step($step), set::questionType('tableInput'), set::mode($action), set::isRun($isRun));
        if($questionType === 'multicolumn') return thinkMulticolumn(set::step($step), set::questionType('multicolumn'), set::mode($action), set::isRun($isRun), set::quotedQuestions($quotedQuestions));
        return array();
    }

    protected function build(): wg|node|array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');

        list($item, $action, $wizard, $addType, $isRun, $quotedQuestions) = $this->prop(array('item', 'action', 'wizard', 'addType', 'isRun', 'quotedQuestions'));
        if(!$item && !$addType) return array();
        $marketID  = data('marketID');
        $basicType = $item->type ?? '';
        $typeLang  = $action . 'Step';
        $type      = $addType ? $addType : ($basicType == 'question' ? $item->options->questionType : $basicType);
        $title     = $action == 'detail' ? sprintf($lang->thinkstep->info, $lang->thinkstep->$basicType) : sprintf($lang->thinkstep->formTitle[$type], $lang->thinkstep->$typeLang);
        $canEdit   = common::hasPriv('thinkstep', 'edit');
        $canDelete = common::hasPriv('thinkstep', 'delete');
        $linkType  = $type === 'checkbox' || $type === 'radio' || $type === 'multicolumn';
        $canLink   = !$isRun && $wizard->model === '3c' && $linkType;

        return div
        (
            setClass('think-step relative h-full overflow-y-auto scrollbar-thin'),
            !$isRun ? array(
                div
                (
                    setClass('flex items-center justify-between text-gray-950 h-12 step-header'),
                    setStyle(array('padding-left' => '30px', 'padding-right' => '30px')),
                    div(setClass('font-medium'), $title),
                    ($action != 'detail') ? null : div
                    (
                        setClass('ml-2'),
                        setStyle(array('min-width' => '48px')),
                        btnGroup
                        (
                            $canLink ? ($item->options->required ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5 mr-1'),
                                set::icon('link'),
                                set::url(createLink('thinkstep', 'link', "marketID={$marketID}&stepID={$item->id}")),
                                set('data-toggle', 'modal'),
                                set('data-dismiss', 'modal'),
                                set('data-size', 'sm'),
                            ) : icon(
                                setClass('w-5 h-5 text-gray opacity-50 ml-1 text-md pl-1 mr-1'),
                                set::title($lang->thinkstep->tips->linkBlocks),
                                'link'
                            )): null,
                            $canEdit ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5'),
                                set::icon('edit'),
                                set::hint($lang->thinkstep->actions['edit']),
                                set::url(createLink('thinkstep', 'edit', "marketID={$marketID}&stepID={$item->id}")),
                            ) : null,
                            $canDelete ? ((!$item->existNotNode && empty($quotedQuestions)) ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5 ml-1 ajax-submit'),
                                set::icon('trash'),
                                set::hint($lang->thinkstep->actions['delete']),
                                setData('url', createLink('thinkstep', 'delete', "marketID={$marketID}&stepID={$item->id}")),
                                setData('confirm',  $lang->thinkstep->deleteTips[$basicType])
                            ) : icon
                            (
                                setClass('w-5 h-5 text-gray opacity-50 ml-1 text-md pl-1'),
                                set::title($item->existNotNode ? $lang->thinkstep->cannotDeleteNode : $lang->thinkstep->cannotDeleteQuestion),
                                'trash'
                            )) : null
                        )
                    )
                ),
                h::hr()
            ) : null,
            div(setClass('pt-6 pb-2 question-detail'), setStyle(array('padding-left' => '30px', 'padding-right' => '30px')), $this->buildBody())
        );
    }
}
