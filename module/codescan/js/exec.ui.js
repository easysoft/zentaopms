window.getScanBranches = function()
{
    setTimeout(function()
    {
        const planID = $('[name="plan"]').val();
        if(!planID || !repoID) return;

        const $branch = $('#branch').zui('picker');
        if(!$branch) return;

        $branch.$.setValue('');
        $.getJSON($.createLink('codescan', 'ajaxGetBranchByPlan', `planID=${planID}&repoID=${repoID}`), function(data)
        {
            $branch.render({items: data.data});
        });
    }, 100);
};
