/**
 * 父阶段更改值操作。
 * Change parent stage.
 *
 * @param  stageID stageID
 * @return void
 */
function changeParentStage(event)
{
    const stageID = parseInt($(event.target).val());
    if(stageID == 0) $('#acl').attr('disabled', false);
    $('#acl').attr('disabled', true);

    $.get($.createLink('programplan', 'ajaxGetAttribute', 'stageID=' + stageID + '&attribute=' + plan.attribute), function(attribute)
    {
        $('#attributeType').html(attribute);
    });
}

/**
 * 编辑阶段提交操作。
 * Submit form for edit stage.
 *
 * @return void
 */
function editStage()
{
    if(plan.parent != $('#parent').val() && $('#parent').val() != 0)
    {
        var result = true;

        $.get($.createLink('programplan', 'ajaxGetStageAttr', 'stageID=' + $('#parent').val()), function(attribute)
        {
            if(attribute != 'mix' && plan.attribute != attribute)
            {
                result = confirm(changeAttrLang.replace('%s', stageTypeList[attribute]));
            }
        });

        if(!result) return;
    }

    var currentAttribute    = $('#attribute').val();
    var currentParent       = $('#parent').val();
    var hasChangedAttribute = (currentAttribute && currentAttribute != 'mix' && plan.attribute != currentAttribute);
    var hasChangedParent    = ((isTopStage && $('#parent').val() != 0) || (!isTopStage && plan.parent != $('#parent').val()));
    if(hasChangedAttribute && !hasChangedParent && !isLeafStage)
    {
        var result = confirm(changeAttrLang.replace('%s', stageTypeList[currentAttribute]));

        if(!result) return;
    }
}
