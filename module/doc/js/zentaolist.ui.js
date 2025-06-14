function getType()
{
    return $('#zentaolist').data('type');
}

function getSettings(useFormData = false)
{
    const settings = $('#zentaolist').data('settings');
    const idList   = $('#zentaolist').data('idlist');
    settings.idList = idList;
    if(!useFormData) return settings;

    const formData = new FormData();
    for(const key in settings)
    {
        const value = settings[key];
        if(Array.isArray(value))
        {
            value.forEach((item, index) => (formData.append(`${key}[]`, item)));
        }
        else
        {
            formData.append(key, value);
        }
    }
    return formData;
}

function getValue(name)
{
    return $('#zentaolist [name=' + name + ']').val();
}

function updatePicker(name, items)
{
    const $picker = $('#zentaolist [name=' + name + ']').zui('picker');
    $picker.render({items});
    $picker.$.setValue(null);
}

function checkForm(form, formData)
{
    let isValid = true;
    $(form).find('.error-tip').addClass('hidden');
    $(form).find('.form-group').removeClass('has-error');

    // 遍历 FormData 中的所有键值对
    for (let [name, value] of formData.entries()) {
        const inputElement = form.querySelector(`[name="${name}"]`);
        const formGroup    = $(inputElement).closest('.form-group');

        // 检查是否是必填项
        if (inputElement && formGroup.hasClass('required') && !value?.length) {
            isValid = false;
            formGroup.find('.error-tip').removeClass('hidden');
            formGroup.addClass('has-error');
        }
    }

    return isValid;
}

window.backToSet = function()
{
    const settings = $('#previewForm').data('settings');
    const blockID  = $('#previewForm').data('blockid');
    parent.zui.Modal.open({
        size: 'lg',
        url: settings.replace('{blockID}', blockID)
    });
}

window.toggleCheckRows = function()
{
    const idList = $('#zentaolist').data('idlist');
    if(!idList?.length) return;
    const dtable = zui.DTable.query($('#previewTable'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}

function loadWithForm(formData, view = 'setting', action = 'load')
{
    const sessionUrl = $.createLink('doc', 'buildZentaoList', 'type=' + getType());
    const loadUrl    = $.createLink('doc', 'zentaolist', 'type=' + getType() + '&view=' + view);

    $.post(sessionUrl, formData, function(data)
    {
        data = JSON.parse(data);
        if(data.result == 'success') action === 'load' ? loadPage(loadUrl) : loadCurrentPage('#customSearchContent');
    });
}

function preview()
{
    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    if(!checkForm(form[0], formData)) return;

    formData.append('action', 'preview');
    loadWithForm(formData);
}

function insert()
{
    const dtable = zui.DTable.query($('#previewTable'));
    const checkedList = dtable.$.getChecks();
    const tip = $('#insert').data('tip');
    if(checkedList.length == 0)
    {
        zui.Modal.alert(tip);
        return;
    }

    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    formData.append('action', 'insert');
    formData.append('idList', checkedList.join(','));
    loadWithForm(formData, 'list');
}

window.cancel = function()
{
    zui.Editor.iframe.delete();
}

function changeCondition()
{
    const condition = getValue('condition');
    if(condition == 'customSearch')
    {
        $('#customSearchContent').removeClass('hidden');
    }
    else
    {
        $('#customSearchContent').addClass('hidden');
    }
}

window.updateCustomSearchItem = function($this, action)
{
    const index    = $this.data('index');
    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    formData.append('conditionAction', action);
    formData.append('conditionIndex',  index);
    loadWithForm(formData, 'setting', 'post');
}

window.updateCustomSearch = function()
{
    const form = $('#zentaolist form');
    const formData = new FormData(form[0]);
    loadWithForm(formData, 'setting', 'post');
}

function changeProduct()
{
    const product = getValue('product');
    const type = getType();
    if(type === 'planStory' || type == 'planBug')
    {
        const link = $.createLink('productplan', 'ajaxGetProductplans', 'product=' + product);
        $.get(link, function(resp)
        {
            resp = JSON.parse(resp);
            updatePicker('plan', resp);
        });
    }

    if(type === 'productCase')
    {
        const condition = getValue('condition');
        if(condition == 'customSearch') updateCustomSearch();
    }
}

window.renderCell = function(result, info)
{
    if(['productStory', 'ER', 'UR', 'planStory', 'projectStory'].indexOf(blockType) !== -1)
    {
        if(info.col.name == 'title' && result)
        {
            const story = info.row.data;
            let html = '';

            if(blockType == 'planStory' || blockType == 'projectStory')
            {
                let gradeLabel = gradeGroup[story.type][story.grade];
                if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
            }
            else
            {
                let gradeLabel = '';
                let showGrade  = false;
                const gradeMap = gradeGroup[story.type] || {};

                if(story.type != storyType) showGrade = true;
                if((story.type == 'epic' || story.type == 'requirement') && Object.keys(gradeMap).length >= 2) showGrade = true;
                if(story.type == 'story' && Object.keys(gradeMap).length >= 3) showGrade = true;
                if(story.grade > 1) showGrade  = true;

                if(showGrade) gradeLabel = gradeMap[story.grade];
                if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";

                if(story.color) result[0].props.style = 'color: ' + story.color;
            }

            if(html) result.unshift({html});
        }
    }

    if(blockType == 'productRelease')
    {
        if(info.col.name == 'build')
        {
            result = [];
            if(!info.row.data.build.name) return result;

            result.push({html: info.row.data.build.name});
        }

        if(info.col.name == 'project')
        {
            result = [];
            if(!info.row.data.projectName) return result;

            result.push({html: `<span title='${info.row.data.projectName}'>${info.row.data.projectName}</span>`});
        }
    }

    if(blockType == 'projectRelease')
    {
        if(info.col.name == 'name')
        {
            if(info.row.data.marker == 1)
            {
                result[result.length] = {html: "<icon class='icon icon-flag text-danger' title='" + markerTitle + "'></icon>"};
            }
        }

        if(info.col.name == 'build')
        {
            if(!info.row.data.buildInfos) info.row.data.buildInfos = info.row.data.builds;

            let result = [];
            for(key in info.row.data.buildInfos) result.push({html: info.row.data.buildInfos[key].name})
            return result;
        }
    }

    if(blockType == 'productCase')
    {
        if(result)
        {
            if(info.col.name == 'caseID' && info.row.data.isScene) result.shift(); // 移除场景ID

            if(info.col.name == 'title')
            {
                const data = info.row.data;
                if(data.color) result[0].props.style = 'color: ' + data.color;
                if(data.isScene) // 场景
                {
                    result.shift(); // 移除带链接的场景名称
                    result.push({html: data.title}); // 添加不带链接的场景名称
                    result.unshift({html: '<span class="label gray-300-outline text-gray rounded-full nowrap">' + scene + '</span>'}); // 添加场景标签
                }
                else // 用例
                {
                    if(data.auto == 'auto') result.unshift({html: '<span class="label gray-pale rounded-full nowrap">' + automated + '</span>'}); // 添加自动化标签
                    if(info.row.data.fromCaseID > 0) result.push({html: `[<i class='icon icon-share'></i> #${info.row.data.fromCaseID}]`}); // 添加来源用例
                }
            }

            if(info.col.name == 'pri' && info.row.data.isScene) result.shift(); // 移除场景优先级

            if(info.col.name == 'status' && info.row.data.status == 'casechanged') result[0] = {html:  '<span style="color:#ff6f42">' + caseChanged + '</span>'};
        }

        if(info.row.data.lastEditedDate == '0000-00-00 00:00:00') info.row.data.lastEditedDate = '';
        if(info.row.data.reviewedDate == '0000-00-00') info.row.data.reviewedDate = '';
    }

    return result;
};

window.getCellSpan = function(cell)
{
    if(['id', 'branchName', 'name', 'branch', 'status', 'date', 'desc', 'releasedDate', 'actions', 'system'].includes(cell.col.name) && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}

/**
 * Get by id for gantt.
 *
 * @param  array  $list
 * @param  string $id
 * @access public
 * @return string
 */
function getByIdForGantt(list, id)
{
    for(let i = 0; i < list.length; i++)
    {
        if (list[i].key == id) return list[i].label || "";
    }
    return id;
}

window.onInitGantt = function()
{
    gantt.plugins({marker: true, critical_path: true, tooltip: true});

    gantt.serverList("userList", userList);

    gantt.config.readonly            = true;
    gantt.config.details_on_dblclick = false;
    gantt.config.order_branch        = false;
    gantt.config.drag_progress       = false;
    gantt.config.drag_links          = false;
    gantt.config.drag_move           = false;
    gantt.config.drag_resize         = false;
    gantt.config.smart_rendering     = true;
    gantt.config.smart_scales        = true;
    gantt.config.static_background   = true;
    gantt.config.show_task_cells     = false;
    gantt.config.row_height          = 32;
    gantt.config.details_on_create   = false;
    gantt.config.duration_unit       = "day";
    gantt.config.show_chart          = true;

    gantt.config.columns = [];
    gantt.config.columns.push({name: 'text', width: '*', tree: true, resize: true, min_width:120, width:200});
    if(showFields.indexOf('PM') != -1) gantt.config.columns.push({name: 'owner_id', align: 'left', resize: true, width: 80, template: function(task){return getByIdForGantt(gantt.serverList('userList'), task.owner_id)}})
    if(showFields.indexOf('status') != -1) gantt.config.columns.push({name: 'status', align: 'center', resize: true, width: 80});
    gantt.config.columns.push({name: 'begin', align: 'center', resize: true, width: 80});
    gantt.config.columns.push({name: 'duration', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('estimate') != -1) gantt.config.columns.push({name: 'estimate', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('progress') != -1) gantt.config.columns.push({name: 'percent', align: 'center', resize: true, width:70, template: function(plan){ if(plan.percent) return Math.round(plan.percent) + '%';}});
    if(showFields.indexOf('taskProgress') != -1) gantt.config.columns.push({name: 'taskProgress', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('realBegan') != -1) gantt.config.columns.push({name: 'realBegan', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('realEnd') != -1) gantt.config.columns.push({name: 'realEnd', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('consumed') != -1) gantt.config.columns.push({name: 'consumed', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('delay') != -1)
    {
        gantt.config.columns.push({name: 'delay', align: 'center', resize: true, width: 60, template:function(item)
        {
            if(item.delayDays > 0) return "<div class='delayed'>" + item.delay + "</div>";
            return item.delay;
        }});
    }
    if(showFields.indexOf('delayDays') != -1) gantt.config.columns.push({name: 'delayDays', align: 'center', resize: false, width: 60});

    endField = gantt.config.columns.pop();
    endField.resize = false;
    gantt.config.columns.push(endField);

    gantt.locale.labels = {
        ...gantt.locale.labels,
        ...ganttFields
    };

    let gridDateToStr = gantt.date.date_to_str("%Y-%m-%d");
    gantt.templates.grid_date_format = function(date, column)
    {
        if(column === "end_date") return gridDateToStr(new Date(date.valueOf() - 1));
        return gridDateToStr(date);
    }

    $('#' + ganttID).css('height', 400);

    gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'day', step: 1, format: '%m-%d'}];
    gantt.config.min_column_width = 70;
    gantt.config.scale_height = 22 * gantt.config.scales.length;

    gantt._clear_data();

    gantt.init(ganttID);
    gantt.parse(ganttData);
    gantt.showDate(new Date());
}
