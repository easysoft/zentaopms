<?php
declare(strict_types=1);

namespace zin;
class formTips extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'icon: string',  // 图标。
        'text: string',  // 内容。
        'theme: string', // 主题。
    );

    protected function build()
    {
        list($icon, $text) = $this->prop(array('icon', 'text'));
        return div
        (
            setClass('border-danger border-2 bg-danger bg-opacity-5 rounded-lg w-full p-2'),
            $icon ? icon(setClass('text-danger'), $icon) : null,
            $icon ? span(setClass('ml-2'), $text) : span(html($text))
        );
    }
}
