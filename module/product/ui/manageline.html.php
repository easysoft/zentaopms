<?php
namespace zin;

$lineMenuList = array();
$formRowList  = array();
foreach($lines as $line)
{
    $lineMenuList = div(
        $line->name,
        icon('move'),
        icon('trash')
    );

    $formRowList[] = item(formRow
    (
        set::width('full'),
        formGroup
        (
            set::width('2/5'),
            $line->name
        ),
        formGroup
        (
            set::width('2/5'),
            $this->config->systemMode == 'ALM' ? select(set::value($line->root), set::items($programs)) : ''
        )
    ));
}

for($i = 0; $i <= 5; $i++)
{
    $formRowList[] = item(formRow
    (
        set::width('full'),
        formGroup(set::width('2/5'), input()),
        formGroup(set::width('2/5'), setClass('ml-4'), $this->config->systemMode == 'ALM' ? input() : ''),
        formGroup(set::width('1/5'), setClass('ml-4'), btn(icon('plus')), btn(icon('close')))
    ));
}

div(
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
            formPanel(
                set::title(''),
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

render('panel');
