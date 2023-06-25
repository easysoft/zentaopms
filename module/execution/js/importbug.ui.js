window.handleImportBug = function(element, config, data)
{
    if(config.name == 'severity')
    {
        $(element).html(`<span class='severity' data-severity='${data.severity}'></span>`);
    }
    else if(config.name == 'title')
    {
        const bugLink = $.createLink('bug', 'view', `bugID=${data.id}`);
        $(element).html(`<a href='${bugLink}' data-toggle='modal'>${data.title}</span>`);
    }
    else if(config.name == 'status')
    {
        $(element).html(`<span class='status-${data.status}'>${statusList[data.status]}</span>`);
    }
}
