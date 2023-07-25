<?php
declare(strict_types=1);
namespace zin;

class modalTrigger extends wg
{
    protected static array $defineProps = array(
        'target?:string',
        'position?:string|int|object|function',
        'size?:string|int|object',
        'backdrop?:bool|string',
        'keyboard?:bool',
        'moveable?:bool',
        'animation?:bool',
        'transTime?:int',
        'responsive?:bool',
        'type?:string',
        'loadingText?:string',
        'loadTimeout?:int',
        'failedTip?:string',
        'timeoutTip?:string',
        'title?:string',
        'content?:string',
        'custom?:object',
        'url?:string',
        'request?:object',
        'dataType?:string',
    );

    protected static array $defineBlocks = array(
        'trigger' => array('map' => 'btn,a'),
        'modal' => array('map' => 'modal')
    );

    protected function build(): array
    {
        list($target, $url, $type) = $this->prop(['target', 'url', 'type']);

        $triggerBlock = $this->block('trigger');
        $modalBlock   = $this->block('modal');

        if(empty($target) && !empty($modalBlock))
        {
            $modal = $modalBlock[0];
            $target = $modal->id();
            if(empty($target))
            {
                $target = $modal->gid;
                $modal->setProp('id', $target);
            }
            $target = "#$target";
        }
        if(!empty($url) && empty($type)) $type = 'ajax';

        if(empty($triggerBlock)) $triggerBlock = h::a($this->children());
        elseif(is_array($triggerBlock)) $triggerBlock = $triggerBlock[0];

        if($triggerBlock instanceof wg)
        {
            $triggerBlock->setProp($this->getRestProps());

            $triggerProps = array(
                'data-toggle'         => 'modal',
                'data-target'         => $triggerBlock->hasProp('target', 'href') ? null : $target,
                'data-type'           => $type,
                'data-url'            => $url,
                'data-position'       => $this->prop('position'),
                'data-size'           => $this->prop('size'),
                'data-backdrop'       => $this->prop('backdrop'),
                'data-keyboard'       => $this->prop('keyboard'),
                'data-moveable'       => $this->prop('moveable'),
                'data-animation'      => $this->prop('animation'),
                'data-trans-time'     => $this->prop('transTime'),
                'data-responsive'     => $this->prop('responsive'),
                'data-loading-text'   => $this->prop('loadingText'),
                'data-loadTimeout'    => $this->prop('loadTimeout'),
                'data-failed-tip'     => $this->prop('failedTip'),
                'data-timeout-tip'    => $this->prop('timeoutTip'),
                'data-title'          => $this->prop('title'),
                'data-content'        => $this->prop('content'),
                'data-custom'         => $this->prop('custom'),
                'data-request'        => $this->prop('request'),
                'data-data-type'      => $this->prop('dataType')
            );
            $triggerBlock->setProp($triggerProps);
        }

        return array($triggerBlock, $modalBlock);
    }
}
