<?php
declare(strict_types=1);
namespace zin;

class tableChart extends wg
{
    protected static array $defineProps = array(
        'type:string',
        'title:string',
        'tableHeaders?:array',
        'datas?:array',
        'tableWidth?:string',
        'chartHeight?:int',
        'overflow?:bool'
    );

    private function genTableHeaders(): wg
    {
        global $lang;

        $tableHeaders = $this->prop('tableHeaders');
        if(empty($tableHeaders))
        {
            $tableHeaders = array
            (
                'item'    => $lang->report->item,
                'value'   => $lang->report->value,
                'percent' => $lang->report->percent
            );
        }

        return h::tr
        (
            setClass('border-t'),
            h::th($tableHeaders['item']),
            h::th(set::width('100px'), $tableHeaders['value']),
            h::th(set::width('120px'), $tableHeaders['percent'])
        );
    }

    protected function build(): wg
    {
        $type        = $this->prop('type');
        $title       = $this->prop('title');
        $datas       = $this->prop('datas');
        $colorList   = array('#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272', '#FC8452', '#9A60B4', '#EA7CCC');
        $chartOption = array();

        shuffle($colorList);

        foreach($datas as $data)
        {
            $color = current($colorList);
            $chartOption[] = array('name' => $data->name, 'value' => $type == 'pie' ? $data->value : array('value' => $data->value, 'itemStyle' => array('color' => $color)));
            $tableTR[] = h::tr
            (
                h::td(label(set::className('label-dot mr-2'), set::style(array('background-color' => $color, '--tw-ring-color' => $color))), $data->name),
                h::td($data->value),
                h::td(($data->percent * 100) . '%')
            );
            if(!next($colorList)) reset($colorList);
        }

        $tableWdith  = $this->prop('tableWidth', '50%');
        $chartHeight = $this->prop('chartHeight', 300);
        $overflow    = $this->prop('overflow', true);
        return div
        (
            set::className('flex border py-2'),
            cell
            (
                setClass('border-r chart flex-auto'),
                div(set::className('center text-base font-bold py-2'), $title),
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
                )->size('100%', $chartHeight),
            ),
            cell
            (
                set::width($tableWdith),
                div
                (
                    setClass('overflow-y-auto'),
                    $overflow ? setStyle('max-height', ($chartHeight + 50) .'px') : null,
                    h::table
                    (
                        set::className('table'),
                        $this->genTableHeaders(),
                        $tableTR
                    )
                )
            )
        );
    }
}
