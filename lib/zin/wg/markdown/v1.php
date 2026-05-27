<?php
declare(strict_types=1);
namespace zin;

class markdown extends wg
{
    protected static array $defineProps = array(
        'content: string',
        'html?: bool',
        'morph?: bool',
        'htmlSandbox?: bool',
        'marked?: bool|array|string',
        'markedExts?: array',
        'highlight?: bool|array',
        'copyCode?: bool',
        'fixQuote?: bool=true',
        'assetBaseUrl?: string|func'
    );

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('content'))
        {
            $this->props->set('content', $child);
            return false;
        }
    }

    protected function build()
    {
        return zui::markdown
        (
            $this->prop('fixQuote') ?set('$options', jsRaw("(_,o) => ({content: o.content.replaceAll('&quot;', '\"').replaceAll('&#039;', \"'\")})")) : null,
            set($this->props->skip('fixQuote'))
        );
    }
}
