<?php
declare(strict_types=1);
/**
 * The ajaxcustom view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

global $lang, $app;
$app->loadLang('datatable');
jsVar('ajaxSaveUrl', $this->createLink('datatable', 'ajaxSaveFields', "module={$module}&method={$method}"));

function buildItem(array $item): wg
{
    global $lang;

    $isRequired = $item['required'];
    $isPercent  = $item['width'] < 1;
    $unitTypes  = array('px' => 'px', '%' => '%');
    $value      = $isPercent ? strval(round((float)$item['width'] * 100)) : $item['width'];
    return li
    (
        setClass('row items-center ring px-5 h-9 cursor-move mt-px'),
        set('data-key', $item['name']),
        set('data-value', $item['width']),
        $isRequired ? setClass('required-col') : null,
        checkbox
        (
            set::disabled($isRequired),
            set::name($item['name']),
            set::checked($item['show'] || $isRequired)
        ),
        h::label
        (
            setClass('flex-auto cursor-move'),
            set('for', $item['name'] . '_'),
            $item['title']
        ),
        div
        (
            setClass('hidden row items-center gap-1'),
            span($lang->datatable->width, setClass('muted')),
            inputGroup
            (
                input
                (
                    setClass('size-sm text-center w-14'),
                    set::value($value === '0' ? '' : $value)
                ),
                select
                (
                    setClass('size-sm w-12'),
                    set::required(true),
                    set::items($unitTypes),
                    set::value($isPercent ? '%' : 'px')
                )
            )
        )
    );
}

function getDefaultConfig(string $name): array
{
    global $config;
    $defaultConfig = $config->datatable->defaultColConfig;

    if(isset($defaultConfig[$name])) return $defaultConfig[$name];
    return array();
}

function buildBody(array $cols): form
{
    $itemsList = array(
        'left' => array(),
        'no' => array(),
        'right' => array()
    );

    foreach($cols as $col)
    {
        if($_SESSION['currentProductType'] == 'normal' && $col['name'] == 'branch') continue;
        if($col['type']) $col = array_merge(getDefaultConfig($col['type']), $col);
        if(!isset($col['fixed']) || empty($col['fixed'])) $col['fixed'] = 'no';
        $itemsList[$col['fixed']][] = array(
            'required' => isset($col['required']) && $col['required'] === true,
            'title' => $col['title'],
            'width' => $col['width'],
            'name' => $col['name'],
            'show' => $col['show']
        );
    }

    $body = form
    (
        setClass('col', 'gap-0.5'),
        set::grid(false),
        set::actions(array())
    );

    foreach($itemsList as $key => $items)
    {
        if(empty($items)) continue;

        $ul = zui::sortable
        (
            set::_tag('ul'),
            set::_class("{$key}-cols")
        );
        foreach($items as $item) $ul->add(buildItem($item));
        $body->add($ul);
    }

    $body->setProp('data-zin-gid', $body->gid);
    jsVar('formGID', $body->gid);
    return $body;
}

setClass('edit-cols');
set::title($lang->datatable->custom);
to::header(span($lang->datatable->customTip, setClass('text-gray', 'text-md')));
set::footerClass('justify-center');
buildBody($cols);
to::footer
(
    toolbar
    (
        btn
        (
            setClass('toolbar-item w-28'),
            set::type('primary'),
            on::click('handleEditColsSubmit'),
            $lang->save
        )
    )
);

render();
