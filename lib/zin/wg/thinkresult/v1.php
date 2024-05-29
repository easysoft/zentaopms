<?php
declare(strict_types=1);
namespace zin;

class thinkResult extends wg
{
    protected static array $defineProps = array(
        'wizard: object',       // 模型数据
        'mode?: string="view"', // 模型展示模式 preview|view
    );

    protected function buildModel(): wg|node
    {
        // TODO
        return div('模型区域');
    }

    protected function build(): wg|node
    {
        $wizard = $this->prop('wizard');
        return div
        (
            setClass('think-result-content col items-center px-7 py-6'),
            div(setClass('w-full h-10 text-center text-md py-2.5 ellipsis overflow-hidden whitespace-nowrap'), $wizard->introduction),
            div(setClass('my-4'), setStyle('min-height', '200px'), $this->buildModel()),
            div(setClass('w-full text-center text-md py-2.5'),  html(htmlspecialchars_decode($wizard->suggestion)))
        );
    }
}
