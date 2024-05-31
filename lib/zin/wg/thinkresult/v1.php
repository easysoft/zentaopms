<?php
declare(strict_types=1);
namespace zin;

class thinkResult extends wg
{
    protected static array $defineProps = array(
        'wizard: object',       // 模型数据
        'mode?: string="view"', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array',        // 模型节点
    );

    protected function buildModel(): wg|array
    {
        list($wizard, $mode, $blocks) = $this->prop(array('wizard', 'mode', 'blocks'));

        if($wizard->model == 'swot')   return thinkSwot(set::mode($mode), set::blocks($blocks));
        if($wizard->model == 'pffa')   return thinkPffa(set::mode($mode), set::blocks($blocks));
        if($wizard->model == 'pestel') return thinkPestel(set::mode($mode), set::blocks($blocks));
        return array();
    }

    protected function build(): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        list($wizard, $mode) = $this->prop(array('wizard', 'mode'));
        return div
        (
            setClass('think-result-content col items-center px-7 py-6'),
            div
            (
                setClass('w-full h-10 text-center text-md py-2.5 ellipsis overflow-hidden whitespace-nowrap'),
                $wizard->introduction ? $wizard->introduction : ($mode == 'preview' ? $lang->thinkwizard->introduction : '')
            ),
            div
            (
                setClass('w-full my-4'),
                setStyle('min-height', '200px'),
                $this->buildModel(),
                div(setClass('mt-4 text-center text-lg font-medium text-gray-950'), $lang->thinkwizard->modelTitle[$wizard->model])
            ),
            div
            (
                setClass('w-full text-center text-md py-2.5'),
                html($wizard->suggestion ? htmlspecialchars_decode($wizard->suggestion) : ($mode == 'preview' ? $lang->thinkwizard->suggestion : ''))
            )
        );
    }
}
