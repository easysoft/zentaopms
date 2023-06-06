<?php
declare(strict_types=1);
namespace zin;

class editCols extends wg
{
    protected static $defineProps = array(
        'leftItems?: array', // 左侧固定列
        'flexItems?: array', // 中间弹性列
        'rightItems?: array' // 右侧固定列
    );

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
        global $lang;
        $itemsList = $this->prop(array('leftItems', 'flexItems', 'rightItems'));
        $body = form
        (
            set::grid(false),
            setClass('col', 'gap-0.5'),
            set::actions(null),
        );
        foreach($itemsList as $items)
        {
            $ul = dragUl();
            foreach ($items as $item) $ul->add($this->onBuildItem($item));
            $body->add($ul);
        }
        $body->add
        (
            toolbar
            (
                setClass('modal-footer justify-center'),
                item
                (
                    set(array('text' => $lang->save, 'type' => 'primary', 'btnType' => 'submit', 'class' => 'w-28', 'data-dismiss' => 'modal')),
                    on::click('loadTable();')
                )
            )
        );
        return $body;
    }

    protected function build()
    {
        global $lang, $app;
        $app->loadLang('datatable');

        return modalDialog
        (
            setClass('edit-cols'),
            set::title($lang->datatable->custom),
            set::footerClass('justify-center'),
            to::header(span($lang->datatable->customTip, setClass('text-gray', 'text-md'))),
            $this->buildBody(),
        );
    }
}
