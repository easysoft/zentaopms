window.addStep = function(id, addType)
{
    const $element = $(`div[data-id='${id}']`).not('div[data-type=annex]');

    const {wizard} = $element.data();
    let parentID = $element.data('parent') || 0;
    if(addType == 'child') parentID = id;

    const level     = addType == 'same' ? $element.data('level') : $element.data('level') + 1;
    const style     = `style="margin-left: calc(${level} * var(--tree-indent, 20px))"`;
    const className = addType == 'child' ? 'mt-2' : '';

    let inputTpl  = `<li class="tree-item ${className} tree-item-input">`;
    inputTpl     += `<div class="tree-item-content item-content-thinmory" ${style}>`;
    inputTpl     += `<input id="stepName" class="form-control" data-id="${id}" data-parent="${parentID}" data-add-type="${addType}" data-wizard="${wizard}">`;
    inputTpl     += '</div></li>';

    if(!$element.parent().hasClass('show')) $element.find('.tree-toggle-icon').trigger('click');

    setTimeout(function()
    {
        if($element.next('.tree').length == 0) $element.after(`<menu class="tree" level="${level}" data-level="${level}"></menu>`);

        $(`div[data-id='${id}']`).next('.tree').prepend(inputTpl);
    }, 1);

    setTimeout(function()
    {
        $('#stepName').trigger('focus');
        document.getElementById("stepName").addEventListener('blur', saveStep);
        document.getElementById("stepName").addEventListener('keydown', function(e)
        {
            if(e.keyCode == 13) saveStep.call(this);
        });
    }, 1);
}

window.saveStep = function()
{
    const name = $(this).val();
    if(!name) return $(this).closest('.tree-item').remove();
    const {id, addType, parent, wizard} = $(this).data();

    $.ajaxSubmit({
        url:  $.createLink('thinkstep', 'ajaxCreate'),
        data: {
            wizard,
            title: name,
            parent: addType == 'same' ? parent : id,
            objectID: id,
            createType: addType,
            type: 'node',
        },
        onSuccess: () =>
        {
            $(this).val('');
        },
        onFail: (error) => {
            if(error?.message) zui.Modal.alert({message: error?.message.title});
        }
    });
}

var nodeElement = [];
window.toggleQuestionShow = function()
{
    $(this).toggleClass('is-collapsed');
    const questionElemnt = $('div[data-type=question], div[data-type=transition]');
    if(questionElemnt.length)
    {
        /* 获取所有问题节点的父级节点 */
        /* Get the parent nodes of all question nodes. */
        questionElemnt.each(function(id, item)
        {
            const node = $(item).closest('.tree').siblings('.listitem[data-type="node"]');
            nodeElement.push(node);
        });
    }

    nodeElement.forEach(function(node)
    {
        $(node).find('.tree-toggle').trigger('click');
    });
}
