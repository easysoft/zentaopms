<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class collapseBtn extends wg
{
    protected static array $defineProps = array(
        'target: string', // 展开折叠的目标元素选择器。
        'parent: string' // 目标元素与按钮共同的父级元素选择器，使用 closest 辅助目标元素的确定。
    );

    protected function build(): wg
    {
        $target = $this->prop('target');
        $parent = $this->prop('parent');

        return btn
        (
            setClass('btn-link', 'collapse-btn'),
            set($this->getRestProps()),
            set::icon('angle-down'),
            on::click
            (
                <<<FUNC
                    const btn = event.target.closest('.collapse-btn');
                    const icon = btn.querySelector('.icon');
                    icon.classList.toggle('icon-angle-down');
                    icon.classList.toggle('icon-angle-top');

                    const parentElm = btn.closest('$parent');
                    const targetElm = parentElm.querySelector('$target');
                    if(targetElm) targetElm.classList.toggle('hidden');
                FUNC
            )
        );
    }
}
