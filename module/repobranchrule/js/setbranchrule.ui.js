window.onRadioForAllowCreateChange = function()
{
    const selectedValue = $("input[name=\"allowCreate[option]\"]:checked").val();
    $('#userAllowCreateGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowDeleteChange = function()
{
    const selectedValue = $("input[name=\"allowDelete[option]\"]:checked").val();
    $('#userAllowDeleteGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowUpdateChange = function()
{
    const selectedValue = $("input[name=\"allowUpdate[option]\"]:checked").val();
    $('#userAllowUpdateGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowForcePushChange = function()
{
    const selectedValue = $("input[name=\"allowForcePush[option]\"]:checked").val();
    $('#userAllowForcePushGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowMergeFromChange = function()
{
    const selectedValue = $("input[name=\"allowMergeFrom[option]\"]:checked").val();
    $('#branchTypeAllowMergeFromGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowMergeToChange = function()
{
    const selectedValue = $("input[name=\"allowMergeTo[option]\"]:checked").val();
    $('#branchTypeAllowMergeToGroup').toggleClass('hidden', selectedValue !== 'specify');
}

// Initialize on page load
$(function()
{
    onRadioForAllowCreateChange();
    onRadioForAllowDeleteChange();
    onRadioForAllowUpdateChange();
    onRadioForAllowForcePushChange();
    onRadioForAllowMergeFromChange();
    onRadioForAllowMergeToChange();
});
