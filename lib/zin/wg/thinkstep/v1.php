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
        'modeClass?: string=""',     // 弹窗样式名称
    );

    protected function buildBody(): wg|array
    {
        global $config;
        list($item, $action, $addType, $isRun, $quoteQuestions, $quotedQuestions, $modeClass, $wizard) = $this->prop(array('item', 'action', 'addType', 'isRun', 'quoteQuestions', 'quotedQuestions', 'modeClass', 'wizard'));

        $step            = $addType ? null : $item;
        $questionType    = $addType ? $addType : ($item->options->questionType ?? '');
        if($addType === 'node' || !$addType && $item->type === 'node') return thinkNode(set::step($step), set::mode($action), set::isRun($isRun), set::wizard($wizard));
        if($addType === 'transition' || !$addType && $item->type === 'transition') return thinkTransition(set::step($step), set::mode($action), set::isRun($isRun), set::wizard($wizard));
        if($questionType === 'input')       return thinkInput(set::step($step), set::questionType('input'), set::mode($action), set::isRun($isRun), set::quotedQuestions($quotedQuestions), set::wizard($wizard));
        if($questionType === 'radio')       return thinkRadio(set::step($step), set::questionType('radio'), set::mode($action), set::isRun($isRun), set::quotedQuestions($quotedQuestions), set::wizard($wizard));
        if($questionType === 'checkbox')    return thinkCheckbox(set::step($step), set::questionType('checkbox'), set::mode($action), set::isRun($isRun), set::quoteQuestions($quoteQuestions), set::quotedQuestions($quotedQuestions), set::wizard($wizard));
        if($questionType === 'tableInput')  return thinkTableInput(set::step($step), set::questionType('tableInput'), set::mode($action), set::isRun($isRun), set::quoteQuestions($quoteQuestions), set::quotedQuestions($quotedQuestions), set::wizard($wizard));
        if($questionType === 'multicolumn') return thinkMulticolumn(set::step($step), set::questionType('multicolumn'), set::mode($action), set::isRun($isRun), set::quoteQuestions($quoteQuestions), set::quotedQuestions($quotedQuestions), set::modeClass($modeClass), set::wizard($wizard));
        if($questionType === 'score')       return thinkScore(set::step($step), set::questionType('score'), set::mode($action), set::isRun($isRun), set::quoteQuestions($quoteQuestions), set::quotedQuestions($quotedQuestions), set::wizard($wizard));
        return array();
    }

    protected function build(): wg|node|array
    {
        global $lang, $app, $config;
        $app->loadLang('thinkstep');

        list($item, $action, $wizard, $addType, $isRun, $quotedQuestions) = $this->prop(array('item', 'action', 'wizard', 'addType', 'isRun', 'quotedQuestions'));
        if(!$item && !$addType) return array();
        $hiddenModelType   = in_array($wizard->model, $config->thinkwizard->hiddenMenuModel);
        $previewCanActions = !$hiddenModelType || ($hiddenModelType && !empty($item->type) && $item->type == 'transition');

        $marketID  = data('marketID');
        $basicType = $item->type ?? '';
        $typeLang  = $action . 'Step';
        $type      = $addType ? $addType : ($basicType == 'question' ? $item->options->questionType : $basicType);
        $title     = $action == 'detail' ? sprintf($lang->thinkstep->info, $lang->thinkstep->$basicType) : sprintf($lang->thinkstep->formTitle[$type], $lang->thinkstep->$typeLang);
        $canEdit   = common::hasPriv('thinkstep', 'edit');
        $canDelete = common::hasPriv('thinkstep', 'delete') && $previewCanActions;
        $linkmodel = !$isRun && in_array($wizard->model, $config->thinkwizard->venn);
        $canLink   = common::hasPriv('thinkstep', 'link') && $linkmodel && $basicType == 'question';
        $from      = '';

        if($hiddenModelType)
        {
            $from = strtolower($wizard->type);
            if($wizard->model == 'appeals') $from = 'appeals';
        }
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
                            ($canLink && $previewCanActions) ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5 mr-1'),
                                set::icon('link'),
                                set::url(createLink('thinkstep', 'link', "marketID={$marketID}&stepID={$item->id}")),
                                set::hint($lang->thinkstep->actions['link']),
                                set('data-toggle', 'modal'),
                                set('data-dismiss', 'modal'),
                                set('data-size', 'sm'),
                            ): null,
                            ($canEdit && $previewCanActions) ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5'),
                                set::icon('edit'),
                                set::hint($lang->thinkstep->actions['edit']),
                                set::url(createLink('thinkstep', 'edit', "marketID={$marketID}&stepID={$item->id}&from={$from}")),
                            ) : null,
                            $canDelete ? ((!$item->existNotNode && empty($quotedQuestions)) ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5 ml-1 ajax-submit'),
                                set::icon('trash'),
                                set::hint($lang->thinkstep->actions['delete']),
                                setData('url', createLink('thinkstep', 'delete', "marketID={$marketID}&stepID={$item->id}&from={$from}")),
                                setData('confirm',  empty($item->link) ? $lang->thinkstep->deleteTips[$basicType] : array('message' => $lang->thinkstep->tips->deleteLinkStep, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x', 'size' => 'sm'))
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
