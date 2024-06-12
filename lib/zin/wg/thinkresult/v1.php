<?php
declare(strict_types=1);
namespace zin;

class thinkResult extends wg
{
    protected static array $defineProps = array(
        'wizard: object',       // 模型数据
        'mode?: string="view"', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array',        // 模型节点
        'models: array',        // 模型列表
        'steps?: array',        // 所有问题步骤数据
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildModel(): wg|array
    {
        list($wizard, $mode, $blocks, $steps) = $this->prop(array('wizard', 'mode', 'blocks', 'steps'));

        if($wizard->model == 'swot')   return thinkSwot(set::mode($mode), set::blocks($blocks), set::steps($steps));
        if($wizard->model == 'pffa')   return thinkPffa(set::mode($mode), set::blocks($blocks), set::steps($steps));
        if($wizard->model == 'pestel') return thinkPestel(set::mode($mode), set::blocks($blocks), set::steps($steps));
        return array();
    }

    protected function build(): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        list($wizard, $mode, $models) = $this->prop(array('wizard', 'mode', 'models'));
        return div
        (
            setClass('think-result-content col items-center px-7 py-6 gap-4 mx-auto'),
            div
            (
                setClass('w-full flex items-center justify-center ellipsis overflow-hidden whitespace-nowrap'),
                setStyle(array('font-size' => '20px', 'height' => '30px')),
                set::title($wizard->introduction),
                $wizard->introduction ? $wizard->introduction : ($mode == 'preview' ? $lang->thinkwizard->introduction : '')
            ),
            div
            (
                setClass('w-full'),
                setStyle('min-height', '200px'),
                $this->buildModel(),
                div(setClass('mt-4 text-center font-bold text-gray-950 text-3xl'), $models[$wizard->model])
            ),
            div
            (
                setClass('w-full text-center text-md py-2.5 leading-5'),
                html($wizard->suggestion ? htmlspecialchars_decode($wizard->suggestion) : ($mode == 'preview' ? $lang->thinkwizard->suggestion : ''))
            )
        );
    }
}
