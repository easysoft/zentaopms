<?php
declare(strict_types=1);
namespace zin;

featureBar(set::current($status), set::linkParams("category={$category}&status={key}"));
toolbar
(
    common::hasPriv('ai', 'importMiniProgram') ? a
    (
        setClass('btn ghost'),
        setData('toggle', 'tooltip'),
        setData('title', $lang->ai->toZentaoStoreAIPage),
        setData('placement', 'left'),
        set::href($config->ai->storeUrl),
        set::target('_blank'),
        h::svg
        (
            set::width('14'),
            set::height('14'),
            set::viewBox('0 0 14 14'),
            set::fill('none'),
            set::xmlns('http://www.w3.org/2000/svg'),
            h::path(set::d('M1.75962 11.375V9.49025C1.75787 9.47362 1.75087 9.45963 1.75087 9.44213L1.75 7.45412C1.75 7.20125 1.94687 7 2.1875 7C2.429 6.99825 2.6075 7.30275 2.6075 7.55212V9.541C2.6075 9.57163 2.61363 9.49988 2.6075 9.52875V10.5079H10.5V8.463C10.5009 8.46475 10.4991 8.46387 10.5 8.46475V7.45412C10.5 7.20125 10.7004 7 10.941 7C11.1825 6.99825 11.375 7.203 11.375 7.45325V9.44213C11.375 9.44825 11.3759 9.4535 11.375 9.45963V11.375H1.75962ZM3.6155 4.592C3.6155 5.43813 4.27175 6.125 5.08113 6.125C5.88875 6.125 6.545 5.43813 6.545 4.592C6.545 5.43813 7.20125 6.125 8.00975 6.125C8.81913 6.125 9.47538 5.43813 9.47538 4.592C9.47538 5.43813 9.97588 6.125 10.7844 6.125C11.5938 6.125 12.25 5.43813 12.25 4.592L10.7844 0.875H2.33975L0.875 4.592C0.875 5.43813 1.34225 6.125 2.15075 6.125C2.95925 6.125 3.6155 5.43813 3.6155 4.592ZM12.25 12.6875C12.25 12.446 12.054 12.25 11.8125 12.25H1.3125C1.071 12.25 0.875 12.446 0.875 12.6875C0.875 12.929 1.071 13.125 1.3125 13.125H11.8125C12.054 13.125 12.25 12.929 12.25 12.6875Z'), set::fill('#838A9D'))
        ),
        $lang->ai->store,
    ) : null,
    common::hasPriv('aiapp', 'square') ? item(set(array(
        'class' => 'secondary',
        'text'  => $lang->ai->exitManage,
        'url'   => createLink('aiapp', 'square'),
    ))) : null,
    common::hasPriv('ai', 'importMiniProgram') ? item(set(array(
        'class'       => 'primary',
        'icon'        => 'import',
        'text'        => $lang->ai->import,
        'data-toggle' => 'modal',
        'data-target' => '#importMiniProgramModal',
    ))) : null,
    $config->edition != 'open' && common::hasPriv('ai', 'createMiniProgram') ? item(set(array(
        'class' => 'primary',
        'icon'  => 'plus',
        'text'  => $lang->ai->miniPrograms->create,
        'url'   => createLink('ai', 'createMiniProgram'),
    ))) : null,
);

$moduleTree = array();
$index      = 1;
$activeKey  = 0;
foreach($categoryList as $key => $value)
{
    $item = new stdClass();
    $item->id     = $index;
    $item->parent = 0;
    $item->name   = $value;
    $item->url    = inlink('miniprograms', "category=$key");
    if($key == $category) $activeKey = $item->id;
    $moduleTree[] = $item;
    $index++;
}

sidebar
(
    moduleMenu
    (
        set::showDisplay(false),
        set::modules($moduleTree),
        set::activeKey($activeKey),
        set::closeLink(inlink('miniprograms')),
        to::footer
        (
            common::hasPriv('ai', 'editMiniProgramCategory') ? btn
            (
                setClass('secondary-pale mx-4'),
                set::text($lang->ai->maintenanceGroup),
                set::url(createLink('ai', 'editMiniProgramCategory')),
                setData('toggle', 'modal'),
                setData('size', 'sm')
            ) : null
        )
    ),
);

$config->ai->dtable->miniPrograms['category']['map'] = array_merge($lang->ai->miniPrograms->categoryList, $categoryList);
$cols         = $config->ai->dtable->miniPrograms;
$miniPrograms = initTableData($miniPrograms, $cols, $this->ai);
dtable
(
    set::cols($cols),
    set::data($miniPrograms),
    set::orderBy($orderBy),
    set::sortLink(inlink('miniprograms', "category=$category&status=$status&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::emptyTip($lang->ai->miniPrograms->emptyList),
);

modal
(
    setID('importMiniProgramModal'),
    set::title($lang->ai->import),
    set::size('sm'),
    form
    (
        set::url(createLink('ai', 'importMiniProgram')),
        set::actions(array('submit')),
        set::ajax(array('cleanEmptyFiles' => 'delete')),
        formGroup
        (
            set::label($lang->ai->installPackage),
            set::required(true),
            h::create('input', set::type('file'), set::accept('.zip'), set::name('file'), setClass('form-control'))
        ),
        formGroup
        (
            set::label($lang->ai->miniPrograms->category),
            picker
            (
                set::name('category'),
                set::required(true),
                set::items(array_merge($lang->ai->miniPrograms->categoryList, $categoryList)),
                set::value($category)
            )
        ),
        formGroup
        (
            set::label($lang->ai->toPublish),
            radioList
            (
                set::name('published'),
                set::items($lang->ai->miniPrograms->field->requiredOptions),
                set::inline(true),
                set::value(0)
            )
        )
    )
);
