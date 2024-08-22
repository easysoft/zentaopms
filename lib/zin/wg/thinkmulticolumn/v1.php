<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

class thinkMulticolumn extends thinkQuestion
{
    protected static array $defineProps = array
    (
        'enableOther?: bool',
        'fields?: array',
    );

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
    }
}
