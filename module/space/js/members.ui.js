window.renderMemberList = function(result, {col, row, value})
{
    if(['group', 'repo', 'artifactRepo'].includes(col.name))
    {
        const type    = col.name;
        const rowData = row.data[type];

        if (rowData && typeof rowData === 'object') {
            let html = '';
            Object.keys(rowData).forEach((key, index) => {
                html += `<span class="label secondary-pale lg mr-1" title="${rowData[key]}">${rowData[key]}</span>`;
            });
            result[0] = {html: html, className: 'overflow-x-auto'};
        }
    }
    return result;
}

window.renderHeaderCell = function(result, {col, row, value})
{
    if(['repo', 'artifactRepo'].includes(col.name))
    {
        const colName = col.name;
        const colText = result[0];
        const notice  = colName == 'repo' ? noticeLang.accessRepo : noticeLang.accessArtifact;
        result[1] = {
            html: `<icon class="mr-1 icon-help" title="${notice}"></icon>`
        };
    }
    return result;
}
