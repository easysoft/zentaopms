<?php
declare(strict_types=1);
namespace zin;

class thinkRun extends wg
{
    protected static array $defineProps = array(
        'item: object',      // 模型信息
    );

    protected function buildBody(): wg|array
    {
        $item = $this->prop('item');

        return thinktransitiondetail
        (
            set::item($item)
        );
    }


    protected function build():array
    {
        return array
        (
            $this->buildBody(),
        );
    }
}
