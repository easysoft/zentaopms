window.renderRowData = function($row, index, row)
{
    if(type == 'all')
    {
        if(row.type == 'outside')
        {
            $row.find('[data-name="dept"]').find('.picker-box').on('inited', function(e, info)
            {
                let $dept = info[0];
                $dept.render({disabled: true});
                $($dept._element).siblings().attr('disabled', true);
            });

            $row.find('[data-name="join"] div').on('inited', function(e, info){ info[0].render({disabled: true}); });
            $row.find('[data-name="commiter"] input').attr('disabled', true);
        }
        else if(row.type == 'inside')
        {
            $row.find('[data-name="company"]').find('.picker-box').on('inited', function(e, info){ info[0].render({disabled: true}); });
        }
    }
}
