<?php
declare(strict_types=1);
namespace zin;

class popovers extends wg
{

    protected static array $defineProps = array(
        'placement?: string="bottom"', // 位置
        'strategy?: string="fixed"',   // 定位类型
        'flip?: bool=true',            // 是否启用 flip
        'shift?: array|bool',          // 是否启用 shift
        'arrow?: bool=false',          // 是否启用箭头
        'offset?: int=1',              // 偏移量
    );

    protected static array $defaultProps = array(
        'shift' => array('padding' => 5),
    );

    protected static array $defineBlocks = array(
        'trigger' => array(),
        'target' => array(),
    );

    protected function build(): array
    {
        $trigger = $this->block('trigger')[0]->children()[0];
        $target  = $this->block('target')[0];
        if(!($target instanceof \zin\zui)) $target = $target->children()[0];

        $trigger->setProp('data-zin-id', $trigger->gid);
        $trigger->setProp('data-target', "[data-zin-id='{$target->gid}']");
        $target->setProp('data-zin-id', $target->gid);

        $props = array_merge($this->props->pick(array('placement', 'strategy', 'flip', 'shift', 'arrow', 'offset')), array('_to' => "[data-zin-id='{$trigger->gid}']"));

        return array(
            $trigger,
            $target,
            zui::popovers(set($props)),
        );
    }
}
