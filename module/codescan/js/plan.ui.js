window.renderPlanCell = function (result, {col, row, value})
{
    if(col.name == 'latestExecStatus' && row.data.latestExecStatus == 'failed')  result[0].props.class = 'text-danger';
    if(col.name == 'latestExecResult' && row.data.latestExecResult == 'no-pass') result[0].props.class = 'text-danger';

    if(col.name == 'scanBranch')
    {
        const branch    = row.data.scanBranch;
        const branchReg = row.data.branchReg;

        const decodeEntity = str => {
            const textarea = document.createElement('textarea');
            textarea.innerHTML = str;
            return textarea.value;
        };

        let title = '';
        if(branch.length > 0) title += scanLang.branch + ':' + decodeEntity(branch);
        if(branchReg.length > 0) title += '\n' + scanLang.keywords + ':' + decodeEntity(branchReg);

        const branchList    = branch.split(',');
        const branchRegList = branchReg.split(',');

        let branchLabel = '';
        if(branchList.length > 0)
        {
            branchList.forEach((item) => {
                if(item) branchLabel += '<span class="label success-pale mr-1">' + item + '</span>';
            })
        }

        if(branchRegList.length > 0)
        {
            branchRegList.forEach((item) => {
                if(item) branchLabel += '<span class="label gray-pale mr-1">' + item + '</span>';
            })
        }
        result[0] = {html: branchLabel};
        result[1].attrs.title = title;
    }
    return result;
}

$(function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
    timer = setInterval(loadTable, 60000);
});

window.onPageUnmount = function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
}
