window.renderRowData = function($row, index, row)
{
    /* 如果某个项目是长期项目，更新计划完成日期和可用工作日的样式。*/
    /* If a project is a long-term project, update the style of the planned completion date and available working days. */
    if(row.end == LONG_TIME)
    {
        $row.find('[data-name="end"] [id^=end]').attr('data-longTime', 1).addClass('hidden').next().removeClass('hidden');
        $row.find('[name^=days]').val(0).attr('readonly', true);
    }

    const aclList = !disabledprograms && row.parent ? programAclList : projectAclList;
    $row.find('[data-name="acl"]').find('.picker-box').on('inited', function(e, info)
    {
        let $acl = info[0];
        $acl.render({items: aclList, required: true});
    });
}
