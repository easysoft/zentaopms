<?php
declare(strict_types=1);
/**
 * The ajaxcustom view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

global $lang, $app;
$app->loadLang('datatable');
$formGID = null;
jsVar('ajaxSaveUrl', $this->createLink('datatable', 'ajaxSave', "module={$module}&method={$method}"));

function buildItem($item)
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
            set::checked($item['show'] || $isRequired)
        ),
        h::label
        (
            setClass('flex-auto'),
            set::for($item['name'] . '_'),
            $item['title']
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

function buildBody($cols)
{
    global $formGID;
    $itemsList = array(
        'left' => array(),
        'no' => array(),
        'right' => array(),
    );

    foreach($cols as $col)
    {
        $itemsList[$col['fixed']][] = array(
            'required' => $col['fixed'] === 'yes',
            'title' => $col['title'],
            'width' => $col['width'],
            'name' => $col['name'],
            'show' => true
        );
    }

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
        foreach ($items as $item) $ul->add(buildItem($item));
        $body->add($ul);
    }

    $body->setProp('data-zin-gid', $body->gid);
    $formGID = $body->gid;
    return $body;
}

function submitFunc()
{
    global $formGID;

    return <<<FUNC
        const formData = [];
        let index = 0;
        const types = ['left', 'no', 'right'];
        const getSelector = (value) => '[data-zin-gid="{$formGID}"] .drag-ul.' + value + '-cols';
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
                    fixed: types[i],
                });
            }
        }

        fetch(ajaxSaveUrl, {method: 'post', body: JSON.stringify(formData)})
            .then(res => res.json())
            .then((json) => {if(json.result === 'success') loadTable();});
    FUNC;
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
        item
        (
            set(array('text' => $lang->save, 'type' => 'primary', 'class' => 'w-28', 'data-dismiss' => 'modal')),
            on::click($this->submitFunc())
        )
    )
);

render('modalDialog');
