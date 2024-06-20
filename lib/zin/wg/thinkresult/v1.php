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
        'conclusion: string'    // 分析结论
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildModel(): wg|array
    {
        list($wizard, $mode, $blocks) = $this->prop(array('wizard', 'mode', 'blocks'));

        $model = $wizard->model;
        $wgMap = array('swot' => 'thinkSwot', 'pffa' => 'thinkPffa', 'pestel' => 'thinkPestel');
        if(!isset($wgMap[$model])) return array();

        return createWg($wgMap[$model], array(set::mode($mode), set::blocks($blocks)));
    }

    protected function build(): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        list($wizard, $mode, $models, $conclusion) = $this->prop(array('wizard', 'mode', 'models', 'conclusion'));
        $introduction = $mode == 'preview' ? $lang->thinkwizard->introduction : '';
        $introduction = $wizard->introduction ? $wizard->introduction : $introduction;
        $suggestion   = $mode == 'preview' ? $lang->thinkwizard->suggestion : '';
        $suggestion   = $wizard->suggestion ? htmlspecialchars_decode($wizard->suggestion) : $suggestion;
        return div
        (
            setClass('think-result-content col items-center px-7 py-6 gap-4 mx-auto'),
            div
            (
                setClass('w-full flex items-center justify-center ellipsis overflow-hidden whitespace-nowrap'),
                setStyle(array('font-size' => '20px', 'height' => '30px')),
                set::title($wizard->introduction),
                $introduction
            ),
            div
            (
                setClass('think-model-content w-full'),
                setStyle('min-height', '200px'),
                $this->buildModel(),
                div(setClass('mt-4 text-center font-bold text-gray-950 text-3xl'), $models[$wizard->model])
            ),
            div
            (
                setClass('w-full text-center text-base py-2.5 leading-5'),
                html($suggestion)
            ),
            div
            (
                setClass('w-full text-center text-base leading-5'),
                $conclusion
            )
        );
    }
}
