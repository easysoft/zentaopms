<?php
declare(strict_types=1);
namespace zin;

class thinkRun extends wg
{
    protected static array $defineProps = array(
        'item: object', // 模型信息
    );

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .think-run-form, .think-run-form .panel-body, .think-run-form.size-lg .panel-body {padding: 0;}
        CSS;
    }

    protected function buildQuestion():array|wg
    {
        global $lang;

        $item    = $this->prop('item');
        $options = json_decode($item->options);
        $answer  = json_decode($item->answer);

        // TODO: if($options->questionType == 'xxx')
        
        return array();
    }

    protected function build(): array
    {
        $item = $this->prop('item');

        return array(
            thinktransitiondetail
            (
                set::item($item),
                formPanel
                (
                    setClass('think-run-form'),
                    set::actions(array()),
                    $item->type == 'question' ? $this->buildQuestion() : null
                )
            ),
            $this->children()
        );
    }
}
