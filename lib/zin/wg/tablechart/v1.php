<?php
declare(strict_types=1);
namespace zin;

class tableChart extends wg
{
    protected static array $defineProps = array(
        'type:string',
        'title:string',
        'datas?:array'
    );

    protected function build(): wg
    {
        global $lang;

        $type        = $this->prop('type');
        $title       = $this->prop('title');
        $datas       = $this->prop('datas');
        $colorList   = array('#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272', '#FC8452', '#9A60B4', '#EA7CCC');
        $chartOption = array();
        foreach($datas as $key => $data)
        {
            $color = current($colorList);
            $chartOption[] = array('name' => $data->name, 'value' => $type == 'pie' ? $data->value : array('value' => $data->value, 'itemStyle' => array('color' => $color)));
            $tableTR[] = h::tr
            (
                h::td(label(set::class('label-dot mr-2'), set::style(array('background-color' => $color, '--tw-ring-color' => $color))), $data->name),
                h::td($data->value),
                h::td(($data->percent * 100) . '%')
            );
            if(!next($colorList)) reset($colorList);
        }

        return div
        (
            set::class('flex border'),
            cell
            (
                set::width('50%'),
                set::class('border-r chart'),
                div(set::class('center text-base font-bold py-2'), $title),
                echarts
                (
                    set::color($colorList),
                    $type != 'pie' ? set::xAxis
                    (
                        array
                        (
                            'type' => 'category',
                            'data' => array_column($chartOption, 'name')
                        )
                    ) : null,
                    $type != 'pie' ? set::yAxis(array('type' => 'value')) : null,
                    set::series
                    (
                        array
                        (
                            array
                            (
                                'data' => $type == 'pie' ? $chartOption : array_column($chartOption, 'value'),
                                'type' => $type
                            )
                        )
                    )
                )->size('100%', 300),
            ),
            cell
            (
                set::width('50%'),
                h::table
                (
                    set::class('table'),
                    h::tr
                    (
                        h::th($lang->report->item),
                        h::th(set::width('100px'), $lang->report->value),
                        h::th(set::width('120px'), $lang->report->percent)
                    ),
                    $tableTR
                )
            )
        );
    }
}
