window.renderRuleCell = function (result, {col})
{
    if(col.name == 'tag')
    {
        const tag = result[0];
        if(!tag) return result;

        const tagList  = tag.split(',');
        let   tagLabel = '';
        if(tagList.length > 0)
        {
            tagList.forEach((item) => {
                if(item) tagLabel += '<span class="label gray-pale mr-1">' + item + '</span>';
            })
        }
        result[0] = {html: tagLabel};
    }

    return result;
};

window.handleClickBatchBtn = function(obj)
{
    const dtable = zui.DTable.query(obj);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url   = obj.data('url');
    const form  = new FormData();
    const field = config.currentMethod.includes('solution') ? 'rulesets[]' : 'rules[]';
    checkedList.forEach((id) => form.append(field, id));

    $.ajaxSubmit({url, data: form});
};

window.getBranches = function()
{
    setTimeout(function()
    {
        const repoID = $('[name="repo"]').val();
        if(!repoID) return;

        const $branch = $('#branch').zui('picker');
        if(!$branch) return;

        $branch.$.setValue('');
        $.getJSON($.createLink('repo', 'ajaxGetBranchOptions', `repoID=${repoID}`), function(data)
        {
            $branch.render({items: data, multiple: true, toolbar: true, checkbox: true});
            if(typeof plan == 'object' && plan.branches && plan.branches.include && plan.repoID == repoID) $branch.$.setValue(plan.branches.include);
        });
    }, 100);
};

window.changeMetric = function(obj)
{
    const metric = $(obj).val();
    if(!metric) return;

    $(obj).closest('.tr-row').find('.input-control-suffix').text(metric == 'count' ? numberUnit : '%');
};

window.addCondition = function()
{
    const $condition = $('#conditionTpl tbody').clone();
    const index      = $('.conditions').data('index');
    $('.conditions').attr('data-index', index + 1);

    $('.conditions').append($condition.html().replace(/%index%/g, index));
    new zui.Picker(`#type${index}`,     {style: {width: '100px'},  name: `type[${index}]`,     items: typeList,     defaultValue: 'all'});
    new zui.Picker(`#severity${index}`, {style: {width: '80px'}, name: `severity[${index}]`, items: severityList, defaultValue: 'all'});
    new zui.Picker(`#metric${index}`,   {style: {width: '100px'}, name: `metric[${index}]`,   items: metricList,   defaultValue: 'count'});
};

window.removeCondition = function(event)
{
    const obj = $(event.target);
    obj.closest('.tr-row').remove();
};

window.updateOperation = function()
{
    $('.keyword').addClass('hidden');

    const action = $('[name=operation]').val();
    if(!action) return;
    const scanType = $('[name=scanType]').zui('picker');

    if(action == 'pullreq_created')
    {
        $('.keyword').removeClass('hidden');
        scanType.$.setValue('incremental');
    }
    else if(action == 'branch_created' || action == 'tag_created')
    {
        scanType.$.setValue('full');
    }
    else
    {
        scanType.$.setValue('incremental');
    }
};

window.updateTriggerType = function()
{
    $('.operation, .keyword, .cron').addClass('hidden');

    const type = $('[name=triggerType]').val();
    if(!type) return;
    const scanTypePicker = $('[name=scanType]').zui('picker');
    const scanTypeList   = scanTypePicker.options.items;

    if(type == 'action')
    {
        $('.operation').removeClass('hidden');
        scanTypePicker.render({disabled: true, items: scanTypeList});
        window.updateOperation();
    }
    else if(type == 'cron')
    {
        $('.cron').removeClass('hidden');
        scanTypePicker.render({disabled: false, items: scanTypeList});
    }
};

window.changeType = function(params)
{
    $.cookie.set('scanIssueUrlParams', `${taskID}-${params}`);
    loadPartial($.createLink('codescan', 'issue', params), '.scan-issue-block');
};

window.changeIssueTask = function()
{
    const task = $('[name=scanTask]').val();
    if(!task) return;

    let type = $('#featureBar .nav-item a.active').data('id');
    if(!type) type = 'all';

    let url = taskURL.replace('%s', type);
    loadPage(url.replace('%s', 'taskID=' + task));
}

window.toggleSearchForm = function()
{
    $('#searchForm').toggleClass('text-primary active');

    $('.search-form-block').toggleClass('hidden', !$('#searchForm').hasClass('active'));
};

window.initMonacoContainer = function()
{
    const height = $('.sidebar').height() - $('.detail-header').height() - $('#basicInfo').height() - $('.issue-file').height() - $('.tabs-header').height() - $('.detail-actions').height() - 50;
    if(height < 0)  return;

    $('#issueLocation').css('height', height);
    $('.monaco-container').css('height', height);
    setTimeout(function(){$('#issueContainer').css('height', height);}, 1000);
};
