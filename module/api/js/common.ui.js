$('.form-group').on('click', '.btn-add', function()
{
    let $newRow   = $(this).closest('tr').clone();

    $newRow.find('input').val('');
    $newRow.find('textarea').val('');

    let key = $newRow.data('key');

    $newRow.attr('data-key', genKey());

    let parentRow   = $(this).closest('.input-row')[0];
    let nextSibling = parentRow.nextElementSibling;
    while(nextSibling && parseInt(nextSibling.dataset.level) > parseInt(parentRow.dataset.level))
    {
        nextSibling = nextSibling.nextElementSibling;
    }

    if(nextSibling)
    {
        parentRow.parentNode.insertBefore($newRow[0], nextSibling);
    }
    else
    {
        parentRow.parentNode.appendChild($newRow[0]);
    }
});

$('.form-group').on('click', '.btn-split', function()
{
    let $newRow = $(this).closest('tr').clone();
    $newRow.find('input').val('');
    $newRow.find('textarea').val('');

    $newRow.attr('data-parent', $newRow.data('key'));
    $newRow.attr('data-key', genKey());
    $newRow.attr('data-level', $newRow.data('level') + 1);
    $newRow.addClass('child');
    $newRow.find('td').first().css('padding-left', $newRow.data('level') * 10 + 'px');

    $(this).closest('tr').after($newRow);
});

$('.form-group').on('click', '.btn-delete', function()
{
    if($(this).closest('table').find('.input-row').length == 1) return false;

    let $table = $(this).closest('table');
    let isResponse = $(this).closest('div.form-group').hasClass('response');
    $(this).closest('tr').remove();

    if(isResponse)
    {
        generateResponse($table);
    }
    else
    {
        generateParams($table);
    }
});

$('.params-group').on('keyup', 'input,textarea', function(){
    generateParams($(this));
})

$('.params-group').on('change', 'input[type=checkbox]', function(){
    generateParams($(this));
})

$('.params-group').on('change', 'select', function(){
    generateParams($(this));
})

/* 变更请求类型时，判断是否隐藏拆分按钮. */
$('.form-group').on('change', '.objectType', function(){
    if($(this).val() != 'array' && $(this).val() != 'object')
    {
        $(this).closest('tr').find('.btn-split').addClass('hidden');
    }
    else
    {
        $(this).closest('tr').find('.btn-split').removeClass('hidden');
    }
})

/* 请求响应单独绑定事件. */
$('#form-response').on('keyup', 'input,textarea', function(){generateResponse($(this))});
$('#form-response').on('change', 'input[type=checkbox]', function(){generateResponse($(this))});
$('#form-response').on('change', 'select', function(){generateResponse($(this))});

/* 更改请求体类型. */
$('.params-group').on('change', 'input[type=radio]', function()
{
    const isStruct = $(this).closest('div.form-group').hasClass('struct');
    if(!isStruct)
    {
        let params = $('input[name=params]').val();
        params = JSON.parse(params);
        params['paramsType'] = $(this).val();
        $('input[name=params]').val(JSON.stringify(params));
    }

    if($(this).val() != 'formData')
    {
        $('#form-params').find('.btn-split').removeClass('hidden');
    }
    else
    {
        $('#form-params').find('.btn-split').addClass('hidden');
    }
})

/**
 * 更改请求参数、请求头、请求体时，将表单值放到隐藏域中.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function generateParams($obj)
{
    const groupID   = $obj.closest('.params-group').attr('id');
    const groupName = groupID.replace('form-', '');
    let   group     = [];

    if(groupName != 'params')
    {
        $obj.closest('.params-group').find('.input-row').each(function()
        {
            let values = {};
            $(this).find('input,textarea,select').each(function()
            {
                buildValues($(this), values);
            })

            group.push(values);
        })
    }
    else
    {
        /* 请求体是无限级的. */
        group = buildNestedParams($obj);
    }

    if($obj.closest('div.form-group').hasClass('struct'))
    {
        $('input[name=attribute]').val(JSON.stringify(group));
    }
    else
    {
        params = JSON.parse($('input[name=params]').val());
        params[groupName] = group;
        $('input[name=params]').val(JSON.stringify(params));
    }
}

function generateResponse($obj)
{
    let group = buildNestedParams($obj);

    $('input[name=response]').val(JSON.stringify(group));
}

/**
 * 将params构造成无限级的树状结构.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function buildNestedParams($obj)
{
    const rows = Array.from($obj.closest('.form-group').find('.input-row'));

    const group = [];

    rows.filter(row => row.dataset.parent === "0").forEach(parentRow => {
        const transformedRow = processRow(parentRow);
        group.push(transformedRow);
    });

    return group;
}

/**
 * 处理每一行的数据.
 *
 * @param  object row
 * @access public
 * @return void
 */
function processRow(row)
{
    let values = {
        field: row.dataset.level,
        paramsType: "object",
        required: "",
        desc: "",
        structType: $('#form-paramsType').find('input[type=radio]:checked').val(),
        level: row.dataset.level,
        key: row.dataset.key,
        parentKey: row.dataset.parent,
        children: []
    };

    $(row).find('input,textarea,select').each(function()
    {
        buildValues($(this), values);
    })

    const childRows = Array.from($(row).closest('.form-group').find(`.input-row[data-parent=${row.dataset.key}]`));
    childRows.forEach(childRow => {
        const transformedChild = processRow(childRow);
        values.children.push(transformedChild);
    });

    return values;
}

/**
 * 获取各个表单的值.
 *
 * @param  object $obj
 * @param  object $values
 * @access public
 * @return void
 */
function buildValues($obj, values)
{
    let value = $obj.val();
    if($obj.prop("type") === "text")
    {
        values.field = value;
    }
    else if($obj.prop("type") === "checkbox")
    {
        values.required = $obj.prop('checked');
    }
    else if($obj.prop("tagName").toLowerCase() === "select")
    {
        values.paramsType = value;
    }
    else if($obj.prop("tagName").toLowerCase() === "textarea")
    {
        values.desc = value;
    }

    return values;
}

/**
 * 给tr生成唯一的key.
 *
 * @access public
 * @return void
 */
function genKey()
{
    let key = Date.now().toString(36)
    key += Math.random().toString(36).substr(2)
    return key
}

/**
 * Toggle acl.
 *
 * @param  string $acl
 * @param  string $type
 * @access public
 * @return void
 */
function toggleAcl(type)
{
    const acl = $('input[name=acl]:checked').val();
    let libID = $('input[name=lib]').val();
    if($('input[name=lib]').length == 0 && $('input[name=module]').length > 0)
    {
        let moduleID = $('input[name=module]').val();
        if(moduleID.indexOf('_') >= 0) libID = moduleID.substr(0, moduleID.indexOf('_'));
    }
    if(acl == 'private')
    {
        $('#whiteListBox').removeClass('hidden');
        $('#groupBox').removeClass('hidden');
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
        $('#groupBox').addClass('hidden');
    }

    if(type == 'lib')
    {
        if(libType == 'project' && typeof(doclibID) != 'undefined')
        {
            let link = $.createLink('doc', 'ajaxGetWhitelist', 'doclibID=' + doclibID + '&acl=' + acl);
            $.getJSON(link, function(users)
            {
                if(users != 'private' && users)
                {
                    const $usersPicker = $('select[name^=users]').zui('picker');
                    $usersPicker.render({items: users});
                    $usersPicker.$.setValue('');
                }
            })
        }
    }
    else if(type == 'doc')
    {
        $('#whiteListBox').toggleClass('hidden', acl == 'open');
        $('#groupBox').toggleClass('hidden', acl == 'open');
        loadWhitelist(libID);
    }
}

/**
 * Load whitelist by libID.
 *
 * @param  int    $libID
 * @access public
 * @return void
 */
window.loadWhitelist = function(libID)
{
    let groupLink = $.createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=group');
    let userLink  = $.createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=user');
    $.getJSON(groupLink, function(groups)
    {
        if(groups != 'private' && groups)
        {
            groups = JSON.parse(groups);
            const $groupsPicker = $('select[name^=groups]').zui('picker');
            $groupsPicker.render({items: groups});
            $groupsPicker.$.setValue('');
        }
    });

    $.getJSON(userLink, function(users)
    {
        if(users != 'private' && users)
        {
            const $usersPicker = $('select[name^=users]').zui('picker');
            $usersPicker.render({items: users});
            $usersPicker.$.setValue('');
        }
    });
}

/**
 * Toggle lib type.
 *
 * @param  string $libType
 * @access public
 * @return void
 */
function toggleLibType(e)
{
    libType = $(e.target).val() == undefined ? libType : $(e.target).val();
    if(libType == 'project')
    {
        $('#projectBox').removeClass('hidden');
        $('#productBox').addClass('hidden');
        $('#acldefault').closest('.radio-primary').show();
        $('#acldefault').next('label').html($('#acldefault').next('label').html().replace(productLang, projectLang));
    }
    else if(libType == 'product')
    {
        $('#projectBox').addClass('hidden');
        $('#productBox').removeClass('hidden');
        $('#acldefault').closest('.radio-primary').show();
        $('#acldefault').next('label').html($('#acldefault').next('label').html().replace(projectLang, productLang));
    }
    else
    {
        var acl = $("input[name='acl']:checked").val();
        if(acl == 'default') $("input[id='aclopen']").prop('checked', true);

        $('#projectBox').addClass('hidden');
        $('#productBox').addClass('hidden');
        $('#acldefault').closest('.radio-primary').hide();
    }
}
