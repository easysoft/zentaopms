<?php
declare(strict_types=1);
namespace zin;

class thinkVennLink extends wg
{
    protected static array $defineProps = array(
        'wizard: object', // 模型数据
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }
}
