<?php
declare(strict_types=1);
namespace zin;

class editCols extends wg
{
    protected static $defineProps = array(
        'leftItems?: array',     // 左侧固定列
        'flexItems?: array',     // 中间弹性列
        'rightItems?: array',    // 右侧固定列
        'url?: string',          // 表单地址
        'method?: string="post"' // 表单方法
    );

    private $formGID;

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function onBuildItem($item)
    {
        global $lang;

        $isRequired = $item['required'];
        return li
        (
            setClass('row', 'items-center', 'border', 'px-5', 'h-9'),
            set('data-key', $item['name']),
            $isRequired ? setClass('required-col') : null,
            checkbox
            (
                set::disabled($isRequired),
                set::name($item['name']),
                set::checked($item['checked'] || $isRequired)
            ),
            h::label
            (
                setClass('flex-auto'),
                set::for($item['name'] . '_'),
                $item['text']
            ),
            div
            (
                setClass('row', 'items-center', 'gap-1'),
                span($lang->datatable->width),
                input
                (
                    setClass('w-8', 'h-5', 'shadow-none', 'px-1', 'text-center'),
                    set::value($item['width']),
                ),
                span('px'),
            ),
        );
    }

    private function buildBody()
    {
        $itemsList = array(
            'left'  => $this->prop('leftItems'),
            'flex'  => $this->prop('flexItems'),
            'right' => $this->prop('rightItems'),
        );

        $body = form
        (
            setClass('col', 'gap-0.5'),
            set::grid(false),
            set::actions(null),
        );

        foreach($itemsList as $key => $items)
        {
            if(empty($items)) continue;

            $ul = dragUl(setClass("{$key}-cols"));
            foreach ($items as $item) $ul->add($this->onBuildItem($item));
            $body->add($ul);
        }

        $body->setProp('data-zin-gid', $body->gid);
        $this->formGID = $body->gid;
        return $body;
    }

    private function submitFunc()
    {
        $url    = $this->prop('url');
        $method = $this->prop('method');

        return <<<FUNC
            const formData = [];
            let index = 0;
            const types = ['left', 'flex', 'right'];
            const getSelector = (value) => '[data-zin-gid="{$this->formGID}"] .drag-ul.' + value + '-cols';
            const colsList = types.map(x => document.querySelector(getSelector(x)));

            for(let i = 0; i < colsList.length; i++)
            {
                const cols = colsList[i];
                if(!cols) continue;

                const children = Array.from(cols.children);
                for(let j = 0; j < children.length; j++)
                {
                    const li = children[j];
                    const checkbox = li.querySelector('input[type="checkbox"]');
                    const input = li.querySelector('input[type="text"]');
                    formData.push({
                        id: li.dataset.key,
                        order: ++index,
                        show: checkbox.checked,
                        width: input.value + 'px',
                        fixed: types[i] === 'flex' ? 'no' : types[i],
                    });
                }
            }

            fetch('{$url}', {method: '{$method}', body: JSON.stringify(formData)})
                .then(() => loadTable());
        FUNC;
    }

    protected function build()
    {
        global $lang, $app;
        $app->loadLang('datatable');

        return modalDialog
        (
            setClass('edit-cols'),
            set::title($lang->datatable->custom),
            to::header(span($lang->datatable->customTip, setClass('text-gray', 'text-md'))),
            set::footerClass('justify-center'),
            $this->buildBody(),
            to::footer
            (
                toolbar
                (
                    item
                    (
                        set(array('text' => $lang->save, 'type' => 'primary', 'class' => 'w-28', 'data-dismiss' => 'modal')),
                        on::click($this->submitFunc())
                    )
                )
            ),
        );
    }
}
