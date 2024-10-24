<?php
declare(strict_types=1);
namespace zin;

class thinkStepQuote extends wg
{
    private array $modules = array();

    protected static array $defineProps = array
    (
        'step?: object',           // 整个步骤的对象
        'questionType?: string',   // 问题类型
        'quoteQuestions?: array',  // 引用问题
        'setOption?: bool=false',  // 选项配置方式
        'quoteTitle?: string',     // 列标题
        'citation?: int=1',        // 引用方式
        'selectColumn?: string',   // 选择列
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        list($step, $setOption, $quoteTitle, $quoteQuestions, $citation, $selectColumn, $questionType) = $this->prop(array('step', 'setOption', 'quoteTitle', 'quoteQuestions', 'citation', 'selectColumn', 'questionType'));
        if($step)
        {
            $enableOther  = $step->options->enableOther ?? 0;
            $setOption    = isset($step->options->setOption) ? $step->options->setOption : false;
            $defaultQuote = !empty($quoteQuestions) ? $quoteQuestions[0]->id : null;
            $quoteTitle   = isset($step->options->quoteTitle) ? $step->options->quoteTitle : $defaultQuote;
            $citation     = isset($step->options->citation) ? $step->options->citation : 1;
            $selectColumn = isset($step->options->selectColumn) ? $step->options->selectColumn : null;
        }
        $quoteQuestionsItems = array();
        if(!empty($quoteQuestions))
        {
            foreach($quoteQuestions as $item)
            {
                $quoteQuestionsItems[] = array('text' => $item->index . '. ' . $item->title, 'value' => $item->id);
            }
        }

        return array
        (
        );
    }
}