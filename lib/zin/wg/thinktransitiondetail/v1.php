<?php
declare(strict_types=1);
namespace zin;

/**
 * 思引师前台节点和过渡页详情部件类。
 * thinmory front node and transition detail widget class.
 */
class thinkTransitionDetail extends wg
{
    protected static array $defineProps = array(
        'item: object',
    );

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .run-desc * {font-size: 16px !important;}
        CSS;
    }

    protected function build()
    {
        global $lang;

        $item    = $this->prop('item');
        $options = json_decode($item->options);
        return div
        (
            setClass('flex bg-white px-8 w-full items-center w-full justify-center pt-10 pb-10 mb-4'),
            div
            (
                setClass('px-4 mt-10'),
                setStyle(array('max-width' => '878px')),
                $item->type == 'question' ? array
                (
                    setStyle(array('min-width' => '643px')),
                    div
                    (
                        setClass('text-xl mb-3 flex items-center'),
                        !empty($options->required) ? div(setClass('text-danger mr-0.5 h-5'), '*') : null,
                        $item->title,
                        !empty($lang->thinkrun->questionType[$options->questionType]) ? span(setClass('text-gray'), '（'. $lang->thinkrun->questionType[$options->questionType].'）') : null,
                        !empty($lang->thinkrun->error->requiredType[$options->questionType]) ? span
                        (
                            setClass('run-error-msg h-5 inline-block text-canvas text-md px-2 ml-0.5 rounded-md hidden'),
                            setStyle('background', 'var(--color-danger-600)'),
                            $lang->thinkrun->error->requiredType[$options->questionType]
                        ) : null,
                    ),
                ) : div
                (
                    setClass('text-2xl'),
                    $item->title
                ),
                div
                (
                    setClass('text-lg run-desc'),
                    setStyle(array('margin-top' => '-18px')),
                    section
                    (
                        setClass('break-words'),
                        set::content(htmlspecialchars_decode($item->desc)),
                        set::useHtml(true)
                    )
                ),
                $this->children()
            )
        );
    }
}
