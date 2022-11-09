$(function()
{
    $('input#editProject1').click(function()
    {
        var editProject = $(this).is(':checked') ? 1 : 0;
        $.cookie('editProject', editProject, {expires:config.cookieLife, path:config.webRoot});
        dtableWithZentao.render({checkable: editProject});
    });

    if($.cookie('editProject') == 1) $('input#editProject1').prop('checked', 'true');
    var isEditMode = $('input#editProject1').is(':checked');
    dtableWithZentao.render({
        checkable: isEditMode,
        canRowCheckable(id)
        {
            const rowInfo = this.getRowInfo(id);
            return rowInfo.data?.type === 'project';
        },
        footToolbar: {
            items: [
                {size: 'sm', text: editLang, btnType: 'primary', className: 'edit-btn'},
            ],
        },
        footPager: {
            items: [
                {type: 'info', text: pagerLang.totalCountAB},
                {type: 'size-menu', text: pagerLang.pageSizeAB},
                {type: 'link', page: 'first', icon: 'icon-first-page', hint: pagerLang.firstPage},
                {type: 'link', page: 'prev', icon: 'icon-angle-left', hint: pagerLang.previousPage},
                {type: 'info', text: '{page}/{pageTotal}'},
                {type: 'link', page: 'next', icon: 'icon-angle-right', hint: pagerLang.nextPage},
                {type: 'link', page: 'last', icon: 'icon-last-page', hint: pagerLang.lastPage},
            ],
            page: pageID,
            recTotal: recTotal,
            recPerPage: recPerPage,
            linkCreator: pagerLink,
            },
            footer() {
                const statistic = () => {
                    const checkedCount = this.getChecks().length;
                    const text = isEditMode && checkedCount ? checkedProjects.replace('%s', checkedCount) : programSummary;
                    return [{children: text, className: 'text-dark'}];
                };
                if (isEditMode) {
                    return [
                        'checkbox',
                        'toolbar',
                        statistic,
                        'flex',
                        'pager',
                    ];
                }
                return [
                    statistic,
                    'flex',
                    'pager',
                ];
            },
    });

    $('.dtable').on('click', ".edit-btn", function()
    {
        console.log(11111);
        debugger
    });

    if(status == 'bySearch') $('.dtable-footer').hide();

    /* Solve the problem that clicking the browser back button causes the checkbox to be selected by default. */
    setTimeout(function()
    {
        $(":checkbox[name^='projectIdList']").each(function()
        {
            $(this).prop('checked', false);
        });
        $('.table-footer #checkAll').prop('checked', false);
    }, 10);
});
