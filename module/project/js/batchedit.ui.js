window.renderRowData = function($row, index, row)
{
    const aclList = !disabledprograms && row.parent ? programAclList : projectAclList;
    $row.find('[data-name="acl"]').find('.picker-box').on('inited', function(e, info)
    {
        let $acl = info[0];
        $acl.render({items: aclList, required: true});
    });
}
