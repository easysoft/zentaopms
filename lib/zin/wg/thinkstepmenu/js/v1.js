setTimeout(function(){if(from && !localStorage.getItem('thinkTreefrom')) localStorage.setItem('thinkTreefrom', from);}, 15);
setTimeout(function()
{
    const thinkTreefrom = localStorage.getItem('thinkTreefrom');
    const thinkTreeInfo = localStorage.getItem('thinkTreeInfo') ? JSON.parse(localStorage.getItem('thinkTreeInfo')) : null;
    if(thinkTreeInfo && thinkTreefrom == 'create' && $(`div[data-id='${thinkTreeInfo.id}'][data-wizard='${thinkTreeInfo.wizard}']`).parent().hasClass('is-nested-show')) $(`div[data-id='${thinkTreeInfo.id}']`).find('.tree-toggle').trigger('click');
    if(thinkTreeInfo && thinkTreefrom != 'create') localStorage.removeItem('thinkTreeInfo');
}, 60);

var thinkTreeInfo = {id: 0, wizard: 0};
window.addStep = function(id, addType)
{
    const $element = $(`div[data-id='${id}']`).not('div[data-type=annex]');
    const {wizard} = $element.data();
    let parentID   = $element.data('parent') || 0;
    thinkTreeInfo  = {id, wizard}
    if(addType == 'child') parentID = id;

    const level     = addType == 'same' ? $element.data('level') : $element.data('level') + 1;
    const style     = `style="margin-left: calc(${addType == 'same' ? -1 : 0} * var(--tree-indent, 20px))"`;
    const className = addType == 'child' ? 'mt-2' : '';

    let inputTpl  = `<li class="tree-item ${className} tree-item-input">`;
    inputTpl     += `<div class="tree-item-content item-content-thinmory" ${style}>`;
    inputTpl     += `<input id="stepName" class="form-control" data-id="${id}" data-parent="${parentID}" data-add-type="${addType}" data-wizard="${wizard}">`;
    inputTpl     += '</div></li>';

    if($element.parent().hasClass('is-nested-show')) $element.find('.tree-toggle').trigger('click');

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

window.addQuestion = function(stepID, parentID, type, questionType = '')
{
    $('.listitem').removeClass('selected');
    $('.tree-item-uncreated').remove();

    const $element = $(`div[data-id='${stepID}']`).not('div[data-type=annex]');
    const {wizard, parent = 0} = $element.data();

    const level     = parent == parentID ? $element.data('level') : $element.data('level') + 1;
    const style     = `style="margin-left: calc(${parent == parentID ? 12 : 3} * (-4px)); padding-left: 12px;"`;
    const className = parent == parentID ? '' : 'mt-2';
    const $title    = `<li class="tree-item tree-item-uncreated ${className}"><div class="listitem item-inner tree-item-inner selected text-primary font-bold" ${style}>${untitledGroup[type]}</div></li>`;

    if(!$(`div[data-id='${parentID}']`).parent().hasClass('is-nested-show')) $(`div[data-id='${parentID}']`).find('.tree-toggle').trigger('click');

    setTimeout(function()
    {
        if($element.next('.tree').length == 0) $element.after(`<menu class="tree" level="${level}" data-type=${type} data-level="${level}"></menu>`);
        $(`div[data-id='${stepID}']`).next('.tree').append($title);
        loadPartial($.createLink('thinkstep', 'create', `wizardID=${wizard}&parent=${parentID}&type=${type}&questionType=${questionType}`), '.think-step');
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
            localStorage.setItem('thinkTreefrom', 'create');
            localStorage.setItem('thinkTreeInfo', JSON.stringify(thinkTreeInfo));
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
