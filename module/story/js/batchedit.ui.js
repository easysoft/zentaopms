$(function()
{
    $('#mainNavbar .nav .nav-item').find("[href$='" + storyType + "']").addClass('active');
    if($.apps.currentCode == 'qa' && $('#mainNavbar .nav .nav-item').find("[href$='" + storyType + "']").length == 0)  $('#navbar  .nav .nav-item').find("[data-id='testcase']").addClass('active');
});

window.renderRowData = function($row, index, story)
{
    $row.find('[data-name="closedBy"]').find('.picker-box').on('inited', function(e, info)
    {
        let $picker = info[0];
        let options = $picker.options;
        options.disabled = story.rawStatus != 'closed';
        $picker.render(options);
    });

    $row.addClass('story' + story.id);

    let $closedReasonTD = $row.find('[data-name="closedReason"]');
    $closedReasonTD.find('.picker-box').on('inited', function(e, info)
    {
        $closedReasonTD.find('.picker-box').wrap("<div class='input-group'></div>");
        let $picker = info[0];
        let options = $picker.options;
        options.disabled = story.rawStatus != 'closed';
        if(story.rawStatus == 'closed') options.onChange = function(value){setDuplicateAndChild(value, story.id)};

        $picker.render(options);

        if(story.rawStatus == 'closed')
        {
            let productStories = typeof(productStoryList[story.product]) == 'undefined' ? [] : productStoryList[story.product];
            productStories     = typeof(productStories[story.branch]) == 'undefined' ? [] : productStories[story.branch];

            let appendStoryHtml = "<span id='duplicateStoryBox" + story.id + "' " + (story.closedReason != 'duplicate' ? "class='hidden'" : '') + ">";
            appendStoryHtml    += "<div class='form-control picker-box' data-name='duplicateStory' style='padding:0'></div></span>";

            $closedReasonTD.find('.input-group').append(appendStoryHtml);

            items = [];
            for(let storyID in productStories) items.push({text: productStories[storyID], value: storyID});
            $closedReasonTD.find('.picker-box[data-name=duplicateStory]').picker({items: items, name: 'duplicateStory[' + story.id + ']'});
        }
    });

    $row.find('[data-name="draft"]').find('.picker-box').on('inited', function(e, info)
    {
        let $picker = info[0];
        let options = $picker.options;
        options.disabled = story.rawStatus == 'draft';
        $picker.render(options);
    });

    let $title    = $row.find('.form-batch-input[data-name="title"]');
    let $module   = $row.find('.form-batch-control[data-name="module"]');
    let $plan     = $row.find('.form-batch-control[data-name="plan"]');
    let $branch   = $row.find('.form-batch-control[data-name="branch"]');
    let $stage    = $row.find('.form-batch-control[data-name="stage"]');
    let $estimate = $row.find('.form-batch-control[data-name="estimate"]');
    let $roadmap  = $row.find('.form-batch-control[data-name="roadmap"]');

    if($estimate.length > 0 && story.isParent == '1') $estimate.find('input.form-control').attr('readonly', 'readonly');

    if($stage.length > 0)
    {
        $stage.find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            let items   = options.items;

            if(story.type == 'story' && story.isParent == '0')
            {
                /* 叶子需求删除父需求的阶段。*/
                items.splice(0, 1);
                items.splice(1, 1);
                items.splice(11, 1);
                options.items = items;
            }
            else if(story.type != 'story' || story.isParent == '1')
            {
                options.disabled = true;
            }

            $picker.render(options);
        })
    }

    $title.attr('disabled', 'disabled').attr('title', story.title).after("<input type='hidden' name='title[" + story.id + "]' value='" + story.title + "' />");
    $row.find('.form-control-static[data-name="status"]').addClass('status-' + story.rawStatus);
    if($branch.length > 0)
    {
        let branches = typeof branchTagOption[story.product] == 'undefined' ? [] : branchTagOption[story.product];

        $branch.find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            let items   = [{text: '', value: ''}];
            for(let branch in branches)
            {
                if(branch == '') continue;
                items.push({text: branches[branch], value: branch});
            }
            options.items        = items;
            options.onChange     = function(){loadBranches(story.product, e)};
            options.defaultValue = story.branch;
            options.required     = true;

            $picker.render(options);
        });
    }

    if($module.length > 0)
    {
        let modules = typeof moduleList[story.id] == 'undefined' ? ['/'] : moduleList[story.id];

        $module.find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            let items   = [];
            for(let module in modules) items.push({text: modules[module], value: module});
            options.items = items;
            options.defaultValue = story.module;

            $picker.render(options);
        });
    }

    if($plan.length > 0)
    {
        const branchID = story.branch.replace('branch', '');
        let plans      = typeof(planGroups[story.product]) == 'undefined' ? [] : planGroups[story.product];
        plans          = typeof(plans[branchID]) == 'undefined' ? [] : plans[branchID];

        $plan.find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            let items   = [];
            for(let plan in plans)
            {
                if(plan == '') continue;
                items.push({text: plans[plan], value: plan});
            }
            options.items = items;
            options.defaultValue = story.plan;
            options.multiple = story.type != 'story' ? true : false;

            $picker.render(options);
        });

        $row.attr('type', story.type);
    }

    if($estimate.length > 0)
    {
        if(story.parent == -1) $estimate.find('[name^=estimate]').attr('readonly', 'readonly');
    }

    if($roadmap && $roadmap.length > 0)
    {
        const roadmapFilter  = ['launching', 'launched', 'closed'];
        const storyFileter   = ['draft', 'reviewing', 'closed'];
        let roadmapCondition = (allRoadmaps[story.roadmap] && roadmapFilter.indexOf(allRoadmaps[story.roadmap].status) !== -1);
        let storyCondition   = storyFileter.indexOf(story.rawStatus) !== -1;

        $roadmap.find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;

            $.each(roadmaps, function(key, value)
            {
                options.items.push({text: value, value: key});
            })
            if(allRoadmaps[story.roadmap] && !roadmaps[story.roadmap]) options.items.push({text: allRoadmaps[story.roadmap].name, value: story.roadmap});

            options.disabled = (roadmapCondition || storyCondition);
            $picker.render(options);
        });

        if(roadmapCondition || storyCondition) $roadmap.append("<input type='hidden' name='roadmap[" + story.id + "]' value='" + story.roadmap + "' />");
    }

    if(story.source == 'meeting' || story.source == 'researchreport')
    {
        objects = story.source == 'meeting' ? meetings : researchReports;
        let $sourceNoteTd = $row.find('.form-batch-control[data-name="sourceNote"]');
        $sourceNoteTd.html("<div class='form-control picker-box' data-name='source'></div>");

        items = [];
        for(let note in objects) items.push({text:objects[note], value:note});
        $sourceNoteTd.find('.picker-box').render({items: items, name: 'sourceNote[' + story.id + ']', defaultValue: story.sourceNote});
    }
};

window.loadBranches = function(product, obj)
{
    $this  = $(obj.target);
    branch = $this.find('input[name^=branch]').val();

    let storyID         = $this.closest('tr').find('.form-batch-input[data-name="storyIdList"]').val();
    let $module         = $this.closest('tr').find('.form-batch-control[data-name="module"]');
    let currentModuleID = $module.val();
    let moduleLink      = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + product + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=items&fieldID=' + storyID + '&extra=nodeleted&currentModuleID=' + currentModuleID);
    $.getJSON(moduleLink, function(items)
    {
        let $picker = $this.closest('tr').find('.picker-box[data-name="module"]').zui('picker');
        let options = $picker.options;
        options.items = items;
        $picker.render(options);
        $picker.$.setValue(0);
    });

    let $plan    = $this.closest('tr').find('.form-batch-control[data-name="plan"]');
    let planID   = $plan.val();
    let planLink = $.createLink('product', 'ajaxGetPlans', 'productID=' + product + '&branch=' + branch + '&planID=' + planID + '&fieldID=' + storyID + '&needCreate=false&expired=&param=skipParent');
    $.getJSON(planLink, function(items)
    {
        let $picker = $this.closest('tr').find('.picker-box[data-name="plan"]').zui('picker');
        let options = $picker.options;
        options.items = items;
        $picker.render(options);
        $picker.$.setValue('');
    });
}

window.setDuplicateAndChild = function(resolution, storyID)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateStoryBox' + storyID).removeClass('hidden');
    }
    else
    {
        $('#duplicateStoryBox' + storyID).addClass('hidden');
    }
};
