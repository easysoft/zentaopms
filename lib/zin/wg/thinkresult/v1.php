<?php
declare(strict_types=1);
namespace zin;

class thinkResult extends wg
{
    protected static array $defineProps = array(
        'wizard: object',       // 模型数据
        'mode?: string="view"', // 模型展示模式 preview|view
        'blocks: array',        // 模型节点
        'blockCount: int'       // 模型区块数量
    );

    protected function buildModel(): wg|node
    {
        list($wizard, $mode, $blocks) = $this->prop(array('wizard', 'mode', 'blocks'));

        if($wizard->model == 'swot') return thinkSwot(set::wizard($wizard), set::mode($mode), set::blocks($blocks));
        return div('模型区域');
    }

    protected function build(): wg|node
    {
        $wizard = $this->prop('wizard');
        return div
        (
            setClass('think-result-content col items-center px-7 py-6'),
            div(setClass('w-full h-10 text-center text-md py-2.5 ellipsis overflow-hidden whitespace-nowrap'), $wizard->introduction),
            div(setClass('w-full my-4'), setStyle('min-height', '200px'), $this->buildModel()),
            div(setClass('w-full text-center text-md py-2.5'),  html(htmlspecialchars_decode($wizard->suggestion)))
        );
    }
}
