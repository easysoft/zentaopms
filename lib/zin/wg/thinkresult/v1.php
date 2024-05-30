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

    protected function buildModel(): wg|node
    {
        list($wizard, $mode, $blocks) = $this->prop(array('wizard', 'mode', 'blocks'));

        if($wizard->model == 'swot') return thinkSwot(set::mode($mode), set::blocks($blocks));
        return div('模型区域');
    }

    protected function build(): wg|node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $wizard = $this->prop('wizard');
        return div
        (
            setClass('think-result-content col items-center px-7 py-6'),
            div(setClass('w-full h-10 text-center text-md py-2.5 ellipsis overflow-hidden whitespace-nowrap'), $wizard->introduction ? $wizard->introduction : $lang->thinkwizard->introduction),
            div(setClass('w-full my-4'), setStyle('min-height', '200px'), $this->buildModel()),
            div(setClass('w-full text-center text-md py-2.5'), html($wizard->suggestion ? htmlspecialchars_decode($wizard->suggestion) : $lang->thinkwizard->suggestion))
        );
    }
}
