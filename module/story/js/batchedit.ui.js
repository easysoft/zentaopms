window.renderRowData = function($row, index, story)
{
    if(story.rawStatus != 'closed') $row.find('.form-batch-input[data-name="closedBy"]').attr('disabled', 'disabled');
    if(story.rawStatus != 'closed') $row.find('.form-batch-input[data-name="closedReason"]').attr('disabled', 'disabled');
    if(story.rawStatus == 'draft')  $row.find('.form-batch-input[data-name="draft"]').attr('disabled', 'disabled');

    var $title  = $row.find('.form-batch-input[data-name="title"]');
    var $module = $row.find('.form-batch-input[data-name="module"]');
    var $plan   = $row.find('.form-batch-input[data-name="plan"]');
    var $branch = $row.find('.form-batch-input[data-name="branch"]');

    $title.attr('disabled', 'disabled').attr('title', story.title).after("<input type='hidden' name='title[" + story.id + "]' value='" + story.title + "' />");
    $row.find('.form-control-static[data-name="status"]').addClass('status-' + story.rawStatus);
    if($branch.length > 0)
    {
        var branches = typeof branchTagOption[story.product] == 'undefined' ? [] : branchTagOption[story.product];

        $branch.empty();
        $branch.append('<option value=""></option>');
        for(let branch in branches)
        {
            if(branch == '') continue;
            $branch.append('<option value="' + branch + '" ' + (story.branch == branch ? 'selected' : '') + '>' + branches[branch] + '</option>');
        }
        $branch.attr('onchange', 'loadBranches(' + story.product + ', this)')
    }

    if($module.length > 0)
    {
        var modules = typeof moduleList[story.id] == 'undefined' ? ['/'] : moduleList[story.id];

        $module.empty();
        for(let module in modules)
        {
            $module.append('<option value="' + module + '" ' + (story.module == module ? 'selected' : '') + '>' + modules[module] + '</option>');
        }
    }

    if($plan.length > 0)
    {
        var plans = typeof(planGroups[story.product]) == 'undefined' ? [] : planGroups[story.product];
        var plans = typeof(plans[story.branch]) == 'undefined' ? [] : plans[story.branch];

        $plan.empty();
        $plan.append("<option value='0'></option>");
        for(let plan in plans)
        {
            if(plan == '') continue;
            $plan.append('<option value="' + plan + '" ' + (story.plan == plan ? 'selected' : '') + '>' + plans[plan] + '</option>');
        }
        if(story.parent < 0) $plan.attr('disabled', 'disabled');
    }

    if(story.source == 'meeting' || story.source == 'researchreport')
    {
        objects = story.source == 'meeting' ? meetings : researchReports;
        var $sourceNoteTd  = $row.find('.form-batch-control[data-name="sourceNote"]');
        var sourceNoteHtml = "<select class='form-control form-batch-input' name='sourceNote[" + story.id + "]' id='sourceNote_" + index + "' data-name='source'>";
        for(let note in objects) sourceNoteHtml += "<option value='" + note + "' " + (story.sourceNote == note ? 'selected' : '') + '>' + objects[note] + '</option>';
        $sourceNoteHtml += '</select>';
        $sourceNoteTd.html(sourceNoteHtml);
    }

    if(story.rawStatus == 'closed')
    {
        var $closedReason = $row.find('.form-batch-input[data-name="closedReason"]');
        $closedReason.attr('onchange', 'setDuplicateAndChild(this.value, ' + story.id + ')');

        var productStories  = typeof(productStoryList[story.product]) == 'undefined' ? [] : productStoryList[story.product];
        var productStories  = typeof(productStories[story.branch]) == 'undefined' ? [] : productStories[story.branch];

        var appendStoryHtml = "<span id='duplicateStoryBox" + story.id + "' " + (story.closedReason != 'duplicate' ? "class='hidden'" : '') + ">";
        appendStoryHtml    += "<select class='form-control form-batch-input' name='duplicateStory[" + story.id + "]' id='duplicateStory_" + index + "' data-name='duplicateStory'>";
        for(let storyID in productStories) appendStoryHtml += "<option value='" + storyID + "' " + (story.duplicateStory == storyID ? 'selected' : '') + '>' + productStories[storyID] + '</option>';
        appendStoryHtml += '</select></span>';

        appendStoryHtml += "<span id='childStoryBox" + story.id + "' " + (story.closedReason != 'subdivided' ? "class='hidden'" : '') + ">";
        appendStoryHtml += "<input type='text' class='form-control form-batch-input' name='childStories[" + story.id + "]' value='" + (typeof story.childStories == 'undefined' ? '' : story.childStories) + "' id='childStories_" + index + "' data-name='childStories' autocomplete='off'>";
        appendStoryHtml += '</span>';

        $closedReason.after(appendStoryHtml);
    }
};

window.loadBranches = function(product, obj)
{
    $this  = $(obj);
    branch = $this.val();

    var index           = $this.closest('tr').data('index');
    var storyID         = $this.closest('tr').find('.form-batch-input[data-name="storyIdList"]').val();
    var $module         = $this.closest('tr').find('.form-batch-control[data-name="module"]');
    var currentModuleID = $module.val();
    var moduleLink      = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + product + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + storyID + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModuleID);
    $this.closest('tr').find('[data-name="module"]').load(moduleLink, function()
    {
        $module.find('[id^=module]').attr('name', 'module[' + storyID + ']').attr('data-name', 'module').attr('id', 'module_' + index).addClass('form-control form-batch-input');
    });

    var $plan    = $this.closest('tr').find('.form-batch-control[data-name="plan"]');
    var planID   = $plan.val();
    var planLink = $.createLink('product', 'ajaxGetPlans', 'productID=' + product + '&branch=' + branch + '&planID=' + planID + '&fieldID=' + storyID + '&needCreate=false&expired=&param=skipParent');
    $this.closest('tr').find('[data-name="plan"]').load(planLink, function()
    {
        $plan.find('[id^=plan]').attr('name', 'plan[' + storyID + ']').attr('data-name', 'plan').attr('id', 'plan_' + index).addClass('form-control form-batch-input');
    });
}

window.setDuplicateAndChild = function(resolution, storyID)
{
    if(resolution == 'duplicate')
    {
        $('#childStoryBox' + storyID).addClass('hidden');
        $('#duplicateStoryBox' + storyID).removeClass('hidden');
    }
    else if(resolution == 'subdivided')
    {
        $('#duplicateStoryBox' + storyID).addClass('hidden');
        $('#childStoryBox' + storyID).removeClass('hidden');
    }
    else
    {
        $('#duplicateStoryBox' + storyID).addClass('hidden');
        $('#childStoryBox' + storyID).addClass('hidden');
    }
};
