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
        list($icon, $text, $theme) = $this->prop(array('icon', 'text', 'theme'));

        if(empty($theme)) $theme = 'danger';
        return div
        (
            setClass($theme == 'danger' ? 'border-danger border-2 bg-danger bg-opacity-5 rounded-lg p-2' : '', "w-full"),
            $icon ? icon(setClass("text-{$theme}"), $icon) : null,
            $icon ? span(setClass('ml-2'), $text) : span(html($text))
        );
    }
}
