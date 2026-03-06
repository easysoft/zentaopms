<?php
declare(strict_types=1);
namespace zin;

class schedule extends wg
{
    protected static array $defineProps = array
    (
        'projectID' => '?int=0',         // 所属项目。
        'value'     => '?string',        // 默认值。
        'begin'     => '?string',        // 开始控件。
        'end'       => '?string',        // 结束控件。
        'type'      => '?string="form"', // 表单类型。
        'disabled'  => '?bool=false',    // 是否禁用。
        'callback'  => '?string'         // 回调函数。
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $lang;

        $longTime = LONG_TIME;
        list($projectID, $value, $begin, $end, $disabled, $type, $callback) = $this->prop(array('projectID', 'value', 'begin', 'end', 'disabled', 'type', 'callback'));
        pageJS(<<<JS
            $(document).off('change', `{$begin},{$end}`).on('change', `{$begin},{$end}`, function()
            {
                const parent     = $(this).closest(`{$type}` == 'form' ? '.form-group' : 'tr');
                const begin      = parent.find(`{$begin}`).val();
                const end        = parent.find(`{$end}`).val();
                const objectType = $(this).attr('name');
                const projectID  = parent.find('.btn-calendar').data('project');
                generateSchedule(objectType, parent.find('input[name^=schedule]'), `{$projectID}`, begin, end);

                let disabled = true;
                if(disabled && (!begin || !end  || end == `{$longTime}`)) disabled = false;
                if(disabled && begin > end)                               disabled = false;
                parent.find('.btn-calendar').toggleClass('disabled', !disabled);
            });
JS
);

        jsVar('type',     $type);
        jsVar('callback', $callback ? $callback : '');
        jsVar('longTime', $longTime);
        return div
        (
            input(set::name('schedule'), setClass('hidden'), $value ? set::value($value) : null),
            btn
            (
                setClass('btn-calendar', $disabled ? 'disabled' : ''),
                setData(array('on' => 'click', 'call' => 'clickCalendar', 'params' => 'event', 'begin' => $begin, 'end' => $end, 'projectID' => $projectID, 'project' => $projectID)),
                $lang->schedule
            )
        );
    }
}
