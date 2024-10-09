<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class think3c extends thinkModel
{
    protected static array $defineProps = array
    (
        'key?: string="view"',
        'disabled?=false: bool',
    );
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildOptionsContent(object $step, int $blockID): array|null
    {
        global $lang;

        $unselectedOptions = array_diff($step->options->fields, $step->answer['result']);
        $showOptions       = !empty($step->link['selectedBlock']) && $step->link['selectedBlock'] == $blockID ? $step->answer['result'] :  $unselectedOptions;

        $content = array();
        foreach($showOptions as $option)
        {
            if($option == 'other') $option = $step->answer['other'] ? $step->answer['other'] : $lang->other;
            if(!empty($option)) $content[] = div(setClass('mt-0.5'), $option);
        }

        return $content;
    }

    protected function buildMulticolumnContent(object $step, int $blockID): array
    {
        $title  = '';
        $colKey = $step->link['column'][0];
        if(isset($step->options->fields[$colKey - 1])) $title = $step->options->fields[$colKey - 1];

        $result = array();
        foreach($step->answer['result'] as $col => $answer)
        {
            $answerKey = 'col' . $colKey;
            if($col == $answerKey) $result = $answer;
        }

        $content = array();
        foreach($result as $item)
        {
            if(!empty($item)) $content[] = div(setClass('mt-0.5'), $item);
        }

        return empty($content) ? array() : array
        (
            div(setClass('text-lg'), $title),
            $content
        );
    }

    protected function buildBody(): node
    {
        global $lang, $app;

        list($blocks, $mode, $disabled, $key) = $this->prop(array('blocks', 'mode', 'disabled', 'key'));
        jsVar('blockName', $lang->thinkwizard->placeholder->blockName);
        jsVar('model3cKey', $key);

        return div
        (
            setData(array('clientLang' => $app->getClientLang(), 'model' => '3c', 'mode' => $mode, 'blocks' => $blocks, 'disabled' => $disabled)),
            setClass('model-canvas relative', "model-canvas-$key", $mode == 'view' ? '' : 'flex justify-center'),
            h::canvas(setID('canvas_' . $key)),
            on::blur('.model-canvas input')
            ->const('blockName', $lang->thinkwizard->block)
            ->const('blocksData', $blocks)
            ->const('repeatTips', $lang->thinkwizard->error->blockRepeat)
            ->const('confirmLang', $lang->thinkwizard->actions->confirm)
            ->do(
                'const $tatget = $(this);',
                'const index = $tatget.data("index");',
                'const block = $tatget.data("block");',
                'const value = $tatget.val() || block;',
                'const $blockTitle = $(`.block-title-${index}`);',
                'const currentValue = blocksData[index];',
                'const values = [];',
                'const inputs = $(`input[name="blocks[]"]`);',
                'inputs.each((index, ele) => {values.push($(ele).val());});',
                'if(value != currentValue && new Set(values).size != values.length)
                {
                    return zui.Modal.confirm({message: repeatTips, actions: [{text: confirmLang, type: "primary", class: "w-20"}]}).then(() => {
                        $tatget.val(currentValue);
                        $tatget.attr("title", currentValue);
                        if($blockTitle.length)
                        {
                            $blockTitle.text(currentValue);
                            $blockTitle.closest(".block-title").attr("title", currentValue + blockName);
                        }
                    });
                }',
                '$tatget.attr("title", value);',
                '$tatget.val(value);',
                'if($blockTitle.length) {$blockTitle.text(value); $blockTitle.closest(".block-title").attr("title", value + blockName);}'
            )
        );
    }

    protected function build(): node
    {
        $mode  = $this->prop('mode');
        $style = $mode == 'preview' ? setStyle(array('min-height' => '254px')) : setStyle(array('min-height' => '254px', 'width' => '2156px'));

        return div
        (
            setClass('model-3c my-1 flex col flex-wrap justify-between'),
            $style,
            $this->buildBody(),
            on::init()->call('initThinkCanvas')
        );
    }
}
