<?php
namespace zin;

$lineMenuList = array();
$formRowList  = array();
foreach($lines as $line)
{
    $lineMenuList[] = div
    (
        setClass('flex items-center'),
        $line->name,
        div
        (
            setClass('self-end ml-auto'),
            btn(set::size('sm'), setClass('ghost'), icon('move')),
            btn
            (
                icon('trash'),
                set::size('sm'),
                setClass('ghost'),
                set::url(createLink('tree', 'delete', array('rootID' => 0, 'moduleID' => $line->id)))
            )
        )
    );

    $formRowList[] = item(formRow
    (
        set::width('full'),
        formGroup
        (
            set::width('2/5'),
            input
            (
                set::name("modules[id{$line->id}]"),
                set::value($line->name)
            )
        ),
        formGroup
        (
            set::width('2/5'),
            setClass('ml-4'),
            $this->config->systemMode == 'ALM' ? select
            (
                set::name("programs[id{$line->id}]"),
                set::value($line->root),
                set::items($programs)
            ) : ''
        ),
        formGroup
        (
            set::width('1/5'),
            setClass('ml-4')
        )
    ));
}

/* Attach input group rows. */
for($i = 0; $i <= 5; $i++)
{
    $formRowList[] = item(formRow
    (
        set::width('full'),
        formGroup(set::width('2/5'), input(set::name('modules[]'))),
        formGroup(set::width('2/5'), setClass('ml-4'), $this->config->systemMode == 'ALM' ? select(set::name('programs[]'), set::items($programs)) : ''),
        formGroup(set::width('1/5'), setClass('ml-4'), btn(icon('plus')), btn(icon('close')))
    ));
}

div(
    on::click('window.onClickPanel'),
    setClass('w-full flex'),
    cell
    (
        setClass('w-2/6'),
        panel
        (
            set::title($lang->product->line),
            menu($lineMenuList)
        )
    ),
    cell
    (
        setClass('w-4/6 pl-6'),
        panel
        (
            set::title($lang->product->manageLine),
            form(
                formRow
                (
                    set::width('full'),
                    formGroup
                    (
                        set::width('2/5'),
                        $lang->product->lineName
                    ),
                    formGroup
                    (
                        set::width('2/5'),
                        $this->config->systemMode == 'ALM' ? $lang->product->program : ''
                    )
                ),
                $formRowList
            )
        )
    )
);

setStyle('min-width', '1024px');

render('modalDialog');
