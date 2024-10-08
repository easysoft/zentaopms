/* 进入页面，树结构为展开状态。*/
/* Entering the page, the step tree is in an expanded state.*/
setTimeout(function()
{
    const from = $('.think-node-menu').data('from');
    if(from && !localStorage.getItem('thinkTreeFrom')) localStorage.setItem('thinkTreeFrom', from);
}, 15);
setTimeout(function()
{
    const thinkTreeFrom = localStorage.getItem('thinkTreeFrom');
    const thinkTreeInfo = localStorage.getItem('thinkTreeInfo') ? JSON.parse(localStorage.getItem('thinkTreeInfo')) : null;
    if(thinkTreeInfo && thinkTreeFrom != 'createNode')
    {
        localStorage.removeItem('thinkTreeInfo');
        localStorage.removeItem('thinkTreeFrom');
    }
}, 60);

var thinkTreeInfo = {id: 0, wizard: 0};

/**
 * 添加节点。
 * Add a node.
 *
 * @param  int    id
 * @param  string addType
 * @access public
 * @return void
 */
window.addNode = function(id, addType)
{
    const $element        = $(`div[data-id='${id}']`).not('div[data-type=annex]');
    const {wizard, order} = $element.data();
    let parentID          = $element.data('parent') || 0;
    thinkTreeInfo         = {id, wizard}
    if(addType == 'child') parentID = id;

    const level     = addType == 'same' ? $element.data('level') : $element.data('level') + 1;
    const style     = `style="margin-left: calc(${addType == 'same' ? -1 : 0} * var(--tree-indent, 42px))"`;
    const className = addType == 'child' ? 'mt-2' : '';

    let inputTpl  = `<li class="tree-item ${className} tree-item-input not-nested-toggle">`;
    inputTpl     += `<div class="tree-item-content item-content-thinmory" ${style}>`;
    inputTpl     += `<input id="stepName" class="form-control" data-id="${id}" data-parent="${parentID}" data-order="${order}" data-add-type="${addType}" data-wizard="${wizard}">`;
    inputTpl     += '</div></li>';

    /* 当添加同级节点且父节点为展开状态时，收起子节点。 */
    /* When adding a peer node and the parent node is in an expanded state, collapse the child node. */
    if(addType == 'same' && $element.parent().hasClass('is-nested-show')) $element.find('.tree-toggle').trigger('click');
    if(addType == 'same')
    {
        if($element.parent().hasClass('is-nested-show')) $element.find('.tree-toggle').trigger('click');
        setTimeout(function()
        {
            if($element.next('.tree').length == 0) $element.after(`<menu class="tree" level="${level}" data-level="${level}"></menu>`);
            $(`div[data-id='${id}']`).next('.tree').prepend(inputTpl);
        }, 1);
    }

    /* 当添加子节点且父节点为收起状态时，展开子节点。 */
    /* When adding a child node and the parent node is in an collapsed state, expand the child node. */
    if(addType == 'child' && !$element.parent().hasClass('is-nested-show') && $element.children('.icon-caret-right').length) $element.find('.tree-toggle').trigger('click');
    if(addType == 'child')
    {
        if(!$element.parent().hasClass('is-nested-show') && $element.children('.icon-caret-right').length) $element.find('.tree-toggle').trigger('click');
        setTimeout(function()
        {
            if($element.next('.tree').length == 0) $element.after(`<menu class="tree" level="${level}" data-level="${level}"></menu>`);
            $(`div[data-id='${id}']`).next('.tree').append(inputTpl);
        }, 1)
    }

    setTimeout(function()
    {
        $('#stepName').trigger('focus');
        $("#stepName").on('blur', saveStep);
        $("#stepName").on('keydown', function(e)
        {
            if(e.keyCode == 13) saveStep.call(this);
        });
    }, 1);
}

/**
 * 添加问题。
 * Add a question.
 *
 * @param  int    stepID       操作列所在的步骤id
 * @param  int    parentID     父节点id
 * @param  string type         分析步骤类型 transition|question
 * @param  string questionType 问题类型 radio|checkbox|input|tableInput
 * @access public
 * @return void
 */
window.addQuestion = function(stepID, parentID, type, questionType = '')
{
    $('.listitem').removeClass('selected');
    $('.tree-item-uncreated').remove();

    const $element = $(`div[data-id='${stepID}']`).not('div[data-type=annex]');
    const {wizard, parent = 0, order} = $element.data();

    const level     = parent == parentID ? $element.data('level') : $element.data('level') + 1;
    const style     = `style="margin-bottom: 6px; margin-left: calc(${parent == parentID ? 12 : 3} * (-4px)); padding-left: 12px;"`;
    const className = parent == parentID ? '' : 'mt-2';
    const $title    = `<li class="tree-item tree-item-uncreated ${className}"><div class="listitem item-inner tree-item-inner selected text-primary font-bold" ${style}>${untitledLangs[type]}</div></li>`;
    const marketID  = $('.think-node-menu').data('marketID');

    /* 当父节点为收起状态时，展开子节点。 */
    /* When the parent node is in a collapsed state, expand the child nodes. */
    if(!$(`div[data-id='${parentID}']`).parent().hasClass('is-nested-show')) $(`div[data-id='${parentID}']`).find('.tree-toggle').trigger('click');

    setTimeout(function()
    {
        if($element.next('.tree').length == 0) $element.after(`<menu class="tree" level="${level}" data-type=${type} data-level="${level}"></menu>`);
        $(`div[data-id='${stepID}']`).next('.tree').append($title);
        loadPartial($.createLink('thinkstep', 'create', `marketID=${marketID}&wizardID=${wizard}&parent=${parentID}&type=${type}&questionType=${questionType}&currentParent=${parent}&currentOrder=${order}`), '.think-step');
        /* 去掉节点收起状态。*/
        /* Remove the node's collapsed state. */
        localStorage.removeItem('thinkTreeInfo');
        localStorage.removeItem('thinkTreeFrom');
        this.showSelected();
    }, 1);
}

/**
 * 保存分析步骤。
 * Save a step.
 *
 * @access public
 * @return void
 */
window.saveStep = function()
{
    const name = $(this).val();
    if(!name) return $(this).closest('.tree-item').remove();
    const {id, addType, parent, order, wizard} = $(this).data();
    const currentParent = addType == 'same' ? parent : 0;
    const marketID      = $('.think-node-menu').data('marketID');

    $.ajaxSubmit({
        url:  $.createLink('thinkstep', 'ajaxCreate', `marketID=${marketID}&currentParent=${currentParent}&currentOrder=${order}`),
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
            localStorage.setItem('thinkTreeFrom', 'createNode');
            localStorage.setItem('thinkTreeInfo', JSON.stringify(thinkTreeInfo));
        },
        onFail: (error) => {
            if(error?.message) zui.Modal.alert({message: error?.message.title});
        }
    });
}

/**
 * 切换所有问题步骤的展示。
 * Toggle the display of all question steps.
 *
 * @access public
 * @return void
 */
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

/**
 * 隐藏侧边栏。
 * Hide the sidebar.
 *
 * @access public
 * @return void
 */
window.hideSidebar = function()
{
    localStorage.setItem('hiddenSidebar', true);
    $('.think-node-menu').closest(".sidebar").sidebar("toggle");
}

/**
 * 展示侧边栏。
 * Show the sidebar.
 *
 * @access public
 * @return void
 */
$(document).on('click', '.gutter-toggle', function ()
{
    localStorage.setItem('hiddenSidebar', false);
})

/**
 * 被选中的节点或问题保持始终展示在页面上。
 * The selected node or question remains displayed on the page.
 *
 * @access public
 * @return void
 */
window.showSelected = function ()
{
    const selectedElement = $('#thinkNodeMenu .selected');
    if (selectedElement && selectedElement[0]) selectedElement[0].scrollIntoView({ behavior: 'auto', block: 'center' });
}

setTimeout(function()
{
    this.showSelected();
}, 100)
