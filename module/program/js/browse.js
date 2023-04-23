$(function()
{
    var orderList  = orderBy.split('_');
    var orderField = orderList[0];
    var orderType  = orderList[1];
    setTimeout(function()
    {
        $(document).find('.dtable-header div[data-col="' + orderField + '"] > a').addClass(orderType == 'asc' ? 'sort-up' : 'sort-down');
    }, 100);

    $('input#editProject1').click(function()
    {
        var editProject = $(this).is(':checked') ? 1 : 0;
        $.cookie('editProject', editProject, {expires:config.cookieLife, path:config.webRoot});
        dtableWithZentao.render({checkable: editProject});
    });

    if($.cookie('editProject') == 1) $('input#editProject1').prop('checked', 'true');
    var isEditMode    = $('input#editProject1').is(':checked');
    var projectIdList = [];
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

                    projectIdList = this.getChecks();
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

    $(document).on('click', ".dtable-footer .edit-btn.toolbar-item", function()
    {
        var batchEditLink = createLink('project', 'batchEdit');
        var tempform      = document.createElement("form");
        tempform.action   = batchEditLink;
        tempform.method   = "post";
        tempform.style.display = "none";

        var opt   = document.createElement("input");
        opt.name  = 'projectIdList';
        opt.value = projectIdList;

        tempform.appendChild(opt);
        document.body.appendChild(tempform);
        tempform.submit();
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

function showEditCheckbox(show)
{
    $('.icon-project,.icon-waterfall,.icon-scrum,.icon-kanban,.icon-agileplus,.icon-waterfallplus').each(function()
    {
        $this     = $(this);
        $tr       = $(this).closest('tr');
        projectID = $tr.attr('data-id');
        if(show)
        {
            var marginLeft = $tr.find('td:first').find('span.table-nest-icon').css('margin-left');

            $tr.find('td:first').prepend("<div class='checkbox-primary'><input type='checkbox' name='projectIdList[]' value='" + projectID + "' id='projectIdList" + projectID + "'/><label for='projectIdList" + projectID + "'></lable></div>");
            $tr.find('td:first').find('.checkbox-primary').css('margin-left', marginLeft).css('width', '14');
            $tr.find('td:first').find('span.table-nest-icon').css('margin-left', '0');
        }
        else
        {
            var marginLeft = $tr.find('td:first').find('.checkbox-primary').css('margin-left');
            $tr.find('td:first').find('span.table-nest-icon').css('margin-left', marginLeft);
            $tr.find('td:first').find('[name^="projectIdList"]').parent().remove();
        }
    });
    if(show && hasProject)
    {
        var tableFooter = "<div class='editCheckbox'><div class='checkbox-primary check-all'><input type='checkbox' id='checkAll' /><label>" + selectAll + "</label></div><div class='table-actions btn-toolbar'><button type='submit' class='btn'>" + edit + "</button></div></div>";
        $('#programForm').attr('action', createLink('project', 'batchEdit', 'from=program'));
        $('.table-footer').prepend(tableFooter).show();
        $('body').scroll();
    }
    else
    {
        $('#programForm').removeClass('has-row-checked');
        $('#projectsSummary').addClass('hidden');
        $('#programSummary').removeClass('hidden');
        $('#programForm').find('.editCheckbox').remove();
        if($('#programForm .pager').length == 0) $('.table-footer').hide();
        $('#programForm').removeAttr('action');
    }
}
