/**
 * @typedef {'list'|'form'|'detail'} ZinPageLayout
 */

/**
 * @typedef {Object} ZinItemProps
 * @property {string} type
 * @property {string} text
 * @property {string} icon
 */

/**
 * @typedef {Object} ZinFeatureBar
 * @property {ZinItemProps[]} [items]
 * @property {string} [current]
 * @property {string} [linkParams]
 */

/**
 * @typedef {Object} ZinDtableProps
 * @property {Object[]} [cols]
 * @property {Object[]} [data]
 * @property {string[]} [plugins]
 * @property {boolean} [footPager]
 * @property {{items: Object[]}} [footToolbar]
 * @property {string[]} [footer]
 */

/**
 * @typedef {Object} ZinFormGroup
 * @property {string} type
 * @property {string} [label]
 * @property {string} [labelClass]
 * @property {string|number} [labelWidth]
 * @property {string} [tip]
 * @property {string} [tipClass]
 * @property {string} [value]
 * @property {string} [placeholder]
 * @property {string} [class]
 * @property {string|ZinFormControl} [control]
 * @property {string} [name]
 * @property {string} [readonly]
 * @property {string} [disabled]
 * @property {string} [required]
 */

/**
 * @typedef {Object} ZinFormRow
 * @property {ZinFormGroup[]} items
 * @property {string} [width]
 */

/**
 * @typedef {Object} ZinPageInfo
 * @property {string} url
 * @property {string} title
 * @property {ZinPageLayout} layout
 * @property {string} moduleName
 * @property {string} methodName
 * @property {ZinFeatureBar} featureBar
 * @property {ZinItemProps[]} toolbar
 * @property {ZinDtableProps} dtable
 * @property {boolean} tableCustomCols
 * @property {{type: string}} sidebar
 * @property {{title: string, toolbar: ZinItemProps[]}} mainHeader
 * @property {{submitBtnText?: string, rows: ZinFormRow[]}} form
 */

function getIconName(iconClass)
{
    const names = iconClass.split(' ');
    const excludeSet = new Set(['sm', 'lg', '2x', '3x', '4x', 'xs', 'xl']);
    for(const name of names)
    {
        if(!name.length || !name.startsWith('icon-') || name.startsWith('icon-common-')) continue;
        const icon = name.replace('icon-', '');
        if(excludeSet.has(icon)) continue;
        return icon;
    }
    return '';
}

function convertClassName(className, exclude = 'dropdown-toggle input-group-btn')
{
    const excludeSet = new Set(exclude.split(' '));
    return className.split(' ').reduce((list, name) =>
    {
        if(!name.length || excludeSet.has(name)) return list;
        if(name.endsWith('-primary')) list.push('primary');
        else if(name.endsWith('-secondary')) list.push('secondary');
        else if(name.endsWith('-warning')) list.push('warning');
        else if(name.endsWith('-success')) list.push('success');
        else if(name.endsWith('-danger')) list.push('danger');
        else if(name === 'btn-link') list.push('ghost');
        else list.push(name);
        return list;
    }, []).join(' ');
}

function getZinItemProps($item, props)
{
    if($item.hasClass('dropdown-menu')) return;
    if($item.is('.btn-group,.dropdown'))
    {
        const items = [];
        $item.children().each(function()
        {
            const $this = $(this);
            if(!$this.is('a,.btn')) return;
            items.push(getZinItemProps($this));
        });
        return {type: 'btnGroup', items, ...props};
    }
    const $icon = $item.find('.icon,i');
    const item = {
        icon: $icon.length ? getIconName($icon.attr('class')) : undefined,
        text: $item.text().trim(),
        class: convertClassName($item.attr('class'), 'btn'),
        ...props
    };
    if($item.data('toggle') === 'dropdown')
    {
        item.type = 'dropdown';
        item.items = [];
        const $menu = $item.next('.dropdown-menu');
        if($menu.length)
        {
            const $listGroup = $menu.children('.list-group');
            if($listGroup.length)
            {
                $listGroup.children('a').each(function()
                {
                    item.items.push($(this).text().trim());
                });
            }
            else
            {
                $menu.children('li').each(function()
                {
                    item.items.push($(this).text().trim());
                });
            }
        }
    }
    return item;
}

function getFeatureBarInfo($)
{
    const $featureBar = $('#mainMenu .btn-toolbar.pull-left,#mainMenu .btn-toolBar.pull-left');
    if(!$featureBar.length) return;
    const featureBar = {items: []};
    $featureBar.children().each(function()
    {
        const $this = $(this);
        const id = $this.attr('id') || '';
        if($this.is('.querybox-toggle'))
        {
            featureBar.items.push({type: 'searchToggle'});
            return;
        }

        if($this.is('.checkbox-primary'))
        {
            featureBar.items.push({type: 'checkbox', text: $this.find('label').text().trim() || $this.text().trim()});
            return;
        }
        if($this.is('a.btn'))
        {
            if($this.hasClass('btn-active-text')) featureBar.current = id ? id.replace('Tab', '') : ($this.find('.text').text().trim() || $this.text().trim());
            return;
        }
    });
    if(!featureBar.items.length) return;
    return featureBar;
}

function getToolbarInfo($)
{
    const $toolbar = $('#mainMenu .btn-toolbar.pull-right');
    if(!$toolbar.length) return;
    const toolbar = [];
    $toolbar.children().each(function()
    {
        toolbar.push(getZinItemProps($(this)));
    });
    return toolbar.length ? toolbar : null;
}

function getTableInfo($)
{
    const info = {};
    const $table = $('#mainContent .table:not(.table-form)').first();
    if(!$table.length) return;
    if($('#mainContent .datatable').length)
    {
        return alert('zin: Please switch the table to simple table mode.');
    }
    const colTypesMap =
    {
        name: 'link',
        pri: 'pri',
        id: 'id',
        status: 'status',
        actions: 'actions',
        progress: 'circleProgress',
        assignedTo: 'avatarBtn',
    };
    const getColName = ($cell) => ($cell.attr('class').toLowerCase().split(' ').find(x => x.startsWith('c-')) || '').replace('c-', '');
    /**
     * @type {ZinDtableProps}
     */
    const setting = {cols: [], data: [], plugins: [], footer: []};
    let flexed = false;
    $table.find('thead>tr:first>th').each(function()
    {
        const $this = $(this);
        const data  = $this.data();
        const title = $this.attr('title') || $this.text().trim();
        const name = getColName($this) || title;
        if(!flexed && data.flex) flexed = true;
        const col =
        {
            title,
            name,
            width: data.width.endsWith('px') ? Number.parseInt(data.width, 10) : 200,
            flex: data.width === 'auto' ? 1 : 0,
            fixed: data.flex ? false : (flexed ? 'right' : 'left'),
            type: colTypesMap[name],
            sortType: !!$this.find('a.sort-up,a.sort-down,a.header').length
        };
        setting.cols.push(col);
    });

    $table.find('tbody>tr').each(function(idx)
    {
        const $this = $(this);
        const rowData = $this.data();
        $this.children('td').each(function()
        {
            $td = $(this);
            const name = getColName($td);
            if(name && rowData[name] === undefined)
            {
                rowData[name] = $td.text().trim();
                if(name === 'progress' && rowData[name]) rowData[name] = rowData[name].replace('%', 0);
            }
        });
        if(rowData.id === undefined) rowData.id = idx;
        rowData.id = String(rowData.id);
        setting.data.push(rowData);
        if(setting.data.length > 5) return;
    });

    const tableJs = $('#mainContent [data-ride="table"]').data('zui.table');
    if(tableJs)
    {
        if(tableJs.options.checkable) setting.plugins.push('checkable');
        if(tableJs.options.sortable) setting.plugins.push('sortable');
        if(tableJs.options.nested || $table.find('tbody>tr.table-parent,tbody>tr.has-child').length) setting.plugins.push('nested');
    }
    else
    {
        if($table.find('td .checkbox-primary').length) setting.plugins.push('checkable');
        if($table.hasClass('has-sort-head')) setting.plugins.push('sortable');
        if($table.find('tbody>tr.table-parent,tbody>tr.has-child').length) setting.plugins.push('nested');
    }
    if(setting.plugins.includes('checkable'))
    {
        const idCol = setting.cols.find(x => x.name === 'id');
        if(idCol) idCol.checkbox = true;
    }
    if(setting.plugins.includes('nested') && !setting.cols.find(x => x.nestedToggle))
    {
        const nameCol = setting.cols.find(x => x.name === 'name');
        if(nameCol) nameCol.nestedToggle = true;
    }

    const $footer = $('#mainContent .table-footer');
    if($footer.length)
    {
        if(setting.plugins.includes('checkable')) setting.footer.push('checkbox', 'divider');

        const $actions = $footer.find('.table-actions,.btn-toolbar');
        if($actions.length)
        {
            const actions = [];
            $actions.children().each(function()
            {
                actions.push(getZinItemProps($(this)));
            });
            if(actions.length) setting.footer.push('toolbar');
            setting.footToolbar = {items: actions.filter(Boolean)};
            setting.footer.push('toolbar');
        }

        const $statistic = $footer.find('.table-statistic');
        if($statistic.length) setting.footer.push($statistic.text().trim());

        const $pager = $footer.find('.pager');
        if($pager.length)
        {
            setting.footer.push('flex', 'pager');
            setting.footPager = true;
        }
    }

    info.dtable = setting;
    info.layout = 'list';
    info.sidebar = $('#sidebar').length ? {type: 'moduleMenu'} : undefined;

    if($('#tableCustomBtn').length) info.tableCustomCols = true;
    return info;
}

function getMainHeaderInfo($)
{
    const $mainHeader = $('#mainContent .main-header');
    if(!$mainHeader.length) return;

    const mainHeader = {title: $mainHeader.find('h2').text().trim()};
    const $toolbar = $mainHeader.find('.btn-toolbar');
    if($toolbar.length)
    {
        const toolbar = [];
        $toolbar.children().each(function()
        {
            toolbar.push(getZinItemProps($(this)));
        });
        mainHeader.toolbar = toolbar.filter(Boolean);
    }
    return mainHeader;
}

function getFormGroupProps($control)
{
    if($control.hasClass('input-group'))
    {
        const info = {type: 'inputGroup', items: []};
        $control.children('.form-control,.input-group-addon,.input-group-btn').each(function()
        {
            const $this = $(this);
            if($this.hasClass('form-control'))           info.items.push(getFormGroupProps($this));
            else if($this.hasClass('input-group-addon')) info.items.push({type: 'addon', text: $this.text().trim()});
            else if($this.hasClass('input-group-btn'))   info.items.push({type: 'btn', ...getZinItemProps($this)});
        });
        return info;
    }
    if($control.hasClass('file-input-list'))
    {
        const $file = $control.find('input[type="file"]');
        return {type: 'file', name: $control.attr('name') || $file.attr('name'), multiple: $file.prop('multiple')};
    }
    if($control.hasClass('checkbox-primary'))
    {
        const $checkboxes = $control.find('input[type="checkbox"]');
        const info = {type: 'checkList', name: $control.attr('name') || $checkboxes.attr('name'), items: [], inline: true};
        $checkboxes.each(function()
        {
            const $checkbox = $(this);
            info.items.push({checked: $checkbox.prop('checked'), value: $checkbox.val(), disabled: $checkbox.prop('disabled'), text: $checkbox.parent().find('label').text().trim()});
            if(info.items.length > 5) return;
        });
        if($checkboxes.closest('td').prev('td').length) info.class = 'ml-4';
        return info;
    }
    if($control.hasClass('radio') || $control.hasClass('radio-inline'))
    {
        const $radioes = $control.closest('td').find('input[type="radio"]');
        const info = {type: 'radioList', inline: $control.hasClass('radio-inline'), name: $radioes.attr('name'), items: []};
        $radioes.each(function()
        {
            const $radio = $(this);
            info.items.push({checked: $radio.prop('checked'), value: $radio.val(), disabled: $radio.prop('disabled'), text: $radio.parent().text().trim()});
            if(info.items.length > 5) return;
        });
        return info;
    }

    const info = {name: $control.attr('name'), id: $control.attr('id'), placeholder: $control.attr('placeholder'), value: $control.val(), disabled: $control.prop('disabled'), type: $control.is('input') ? $control.attr('type') : null};
    if($control.attr('onchange')) info.onchange = $control.attr('onchange').split('(').shift().trim();
    if($control.is('textarea'))
    {
        info.rows = +$control.attr('rows') || null;
        info.type = $control.data('keditor') ? 'editor' : 'textarea';
        return info;
    }
    if($control.is('select'))
    {
        info.items = [];
        info.type = $control.prop('multiple') ? {type: 'picker', multiple: true} : 'picker';
        $control.children().each(function()
        {
            const $option = $(this);
            info.items.push({value: $option.attr('value'), text: $option.text().trim()});
            if(info.items.length > 5) return;
        });
        return info;
    }
    if($control.is('.form-date,.form-time,.form-datetime'))
    {
        info.type = $control.hasClass('form-date') ? 'date' : $control.hasClass('form-time') ? 'time' : 'datetime';
    }
    return info;
}

function getFormInfo($)
{
    const $form = $('#mainContent').find('.table-form,.main-form>.table');
    if(!$form.length) return;

    const form = {items: [], grid: 4, vars: [], hiddens: []};
    const $submit = $form.find('#submit');
    if($submit.length)
    {
        form.grid = +$submit.closest('td').attr('colspan') || 1;
        form.submitBtnText = $submit.text().trim();
        if(form.submitBtnText === '保存') form.submitBtnText = null;
    }
    $form.find('tbody>tr').each(function()
    {
        const $tr = $(this);
        if($tr.find('#submit').length) return;
        let label = '';
        const formRow = {items: [], hidden: $tr.is(':hidden')};
        $tr.children().each(function()
        {
            const $thd = $(this);
            if($thd.is('th'))
            {
                label = $thd.text().trim();
            }
            else
            {
                const $control = $thd.find('.form-control,.input-group,textarea.kindeditor,.file-input-list,.checkbox-primary,.radio,.radio-inline').first();
                if(!$control.length) return;

                const colspan = +$thd.attr('colspan') || 1;
                const width = colspan !== form.grid ? `${colspan}/${form.grid}` : null;
                const formGroup = {label: label, required: $thd.hasClass('required'), width: width, value: ''};
                $.extend(formGroup, getFormGroupProps($control));
                if(!formGroup.name && formGroup.type !== 'inputGroup') return;
                if(formGroup.items)
                {
                    if(formGroup.name)
                    {
                        form.vars.push({name: `${formGroup.name.replace('[]', '')}Options`, value: formGroup.items.slice(0, Math.min(formGroup.items.length, 5))});
                    }
                    else if(formGroup.type === 'inputGroup')
                    {
                        formGroup.items.forEach(item =>
                        {
                            if(item.items && item.name) form.vars.push({name: `${item.name.replace('[]', '')}Options`, value: item.items.slice(0, Math.min(item.items.length, 5))});
                        });
                    }
                }
                formRow.items.push(formGroup);
                label = '';
            }
        });
        if(formRow.items.length) form.items.push(formRow);
    });

    $form.find('input[type="hidden"]').each(function()
    {
        const $input = $(this);
        form.hiddens.push({name: $input.attr('name'), value: $input.val()});
    });

    return {form, layout: 'form'};
}

/**
 * Get page info for zin
 * @param {Window} win
 * @returns {ZinPageInfo}
 */
function getPageInfo(win)
{
    const {document, config, $, pageVars} = win;
    return $.extend(
    {
        url: win.location.href,
        title: document.title.replace(' - 禅道', ''),
        moduleName: config.currentModule,
        methodName: config.currentMethod,
        featureBar: getFeatureBarInfo($),
        toolbar: getToolbarInfo($),
        mainHeader: getMainHeaderInfo($),
        vars: pageVars,
    }, getTableInfo($), getFormInfo($));
}

/**
 * Indent string lines
 * @param {string[]|string} lines
 * @param {number} [indent=0]
 * @returns {string[]|string}
 */
function indentLines(lines, indent = 0)
{
    const isArray = Array.isArray(lines);
    if(!isArray) lines = lines.split('\n');
    const indentStr = ' '.repeat(indent * 4);
    lines = lines.map(x => x.includes('\n') ? indentLines(x, indent) : (indentStr + x));
    return isArray ? lines : lines.join('\n');
}

/**
 * Generate item statement
 * @param {ZinItemProps} name
 * @param {any} value
 * @param {number} [indent=0]
 * @returns {string}
 */
function genSetStatement(name, value, indent = 0)
{
    return indentLines(`set::${name}(${JSON.stringify(value)})`, indent);
}

/**
 * Find dotted var name from an object
 */
function findVarName(val, vars, preName = '')
{
    if(vars === val) return preName;
    if(!vars || typeof vars !== 'object') return '';
    const props = Object.keys(vars);
    for(const prop of props)
    {
        if(!Number.isNaN(+prop)) return (preName.length && val === vars[prop]) ? `${preName}[${prop}]` : '';
        const name = findVarName(val, vars[prop], preName.length ? `${preName}->${prop}` : prop);
        if(name.length) return name;
    }
    return '';
}

/**
 * Get var name matches the given value
 */
function getVarName(val)
{
    if(!pageInfo || !pageInfo.vars || typeof val === 'object') return;
    return findVarName(val, pageInfo.vars);
}

/**
 * Gen value to php statement
 * @param {any} value
 * @param {number} indent
 * @returns {string}
 */
function genValueStatement(value, indent = 0, join = '\n')
{
    if(value === undefined) return;
    if(value === null) return indentLines('NULL', indent);
    if(Array.isArray(value))
    {
        return indentLines(`array(${value.map(val => genValueStatement(val, indent, join)).join(', ')})`, indent);
    }
    if(typeof value === 'object')
    {
        return genArrayStatement(value, null, indent, '', '', join);
    }
    if(typeof value === 'string' && value.startsWith('RAW_PHP<')) return indentLines(value.substring(8, value.length - 8), indent);
    if(typeof value !== 'object' && value !== '')
    {
        const varName = getVarName(value);
        if(varName.length) return `$${varName}`;
    }
    return indentLines(JSON.stringify(value), indent);
}

/**
 * Generate php array statement
 * @param {Reacord<string, any>} array Php array object
 * @param {string|string[]} props Prop names list
 * @param {number} [indent=0]
 * @returns {string}
 */
function genArrayStatement(array, props = null, indent = 0, prefix = '', suffix = '', join = '\n')
{
    if(typeof props === 'string') props = props.split(',');
    else if(props === null) props = Object.keys(array);
    const propLines = props.reduce((lines, prop) =>
    {
        const value = genValueStatement(array[prop], 0, join);
        if(typeof value === 'string' && value.length)
        {
            lines.push(`'${prop}' => ${value}`);
        }
        return lines;
    }, []);
    if(!propLines.length) return [indentLines('array()', indent)];
    return indentLines(
    [
        `${prefix}array`,
        '(',
            indentLines(propLines.join(`,${join === '' ? ' ' : join}`), join === '' ? 0 : (1 + indent)),
        `)${suffix}`
    ], indent).join(join);
}

/**
 * Generate item statement
 * @param {ZinItemProps} item
 * @param {number} [indent=0]
 * @returns {string}
 */
function genItemStatement(item, indent = 0)
{
    if(item.type === 'searchToggle') return indentLines('li(searchToggle())', indent);
    return genArrayStatement(item, null, indent, 'item(set(', '))');
}

/**
 * Generate php variable statement
 * @param {string} name
 * @param {any} value
 * @param {number} indent
 * @param {string} join
 */
function genVarStatement(name, value, indent = 0, join = '')
{
    return indentLines(`$${name} = ${genValueStatement(value, indent, join)};`, indent);
}

function genInputGroupItemStatement(item, indent = 0)
{
    if(item.type === 'addon') return indentLines(genValueStatement(item.text), indent);
    if(item.type === 'btn') return indentLines(genArrayStatement(item, null, 0, 'btn(set(', '))'), indent);
    item = $.extend({}, item);
    if(typeof item.type === 'object') item = $.extend(item, item.type);
    if(item.items) item.items = `RAW_PHP<$${item.name.replace('[]', '')}Options>RAW_PHP`;
    return indentLines(genArrayStatement(item, null, 0, 'control(set(', '))'), indent);
}

function genInputGroupStatement(items, indent = 0)
{
    return indentLines(
    [
        'inputGroup',
        '(',
            items.map(item => genInputGroupItemStatement(item, 1)).join(',\n'),
        ')'
    ], indent).join('\n');
}

function genFormGroupStatement(formGroup, indent = 0)
{
    return indentLines(
    [
        'formGroup',
        '(',
            indentLines(
            [
                formGroup.width    ? `set::width(${JSON.stringify(formGroup.width)})` : null,
                formGroup.name     ? `set::name(${JSON.stringify(formGroup.name)})` : null,
                formGroup.label    ? `set::label(${genValueStatement(formGroup.label)})` : null,
                formGroup.class    ? `set::class(${JSON.stringify(formGroup.class)})` : null,
                formGroup.disabled ? 'set::disabled(true)' : null,
                formGroup.required ? 'set::required(true)' : null,
                formGroup.value    ? `set::value(${JSON.stringify(formGroup.value)})` : null,
                formGroup.type && formGroup.type !== 'inputGroup' ? `set::control(${JSON.stringify(formGroup.type)})` : null,
                formGroup.id && formGroup.id !== formGroup.name ? `set::id(${JSON.stringify(formGroup.id)})` : null,
                formGroup.items && formGroup.type !== 'inputGroup' ? `set::items($${formGroup.name.replace('[]', '')}Options)` : null,
                formGroup.type === 'inputGroup' ? genInputGroupStatement(formGroup.items) : null,
            ].filter(x => typeof x === 'string'), 1).join(',\n'),
        ')'
    ].filter(x => typeof x === 'string'), indent).join('\n');
}

function getFormRowStatement(formRow, indent = 0)
{
    return indentLines(
    [
        'formRow',
        '(',
            formRow.hidden ? '    set::hidden(true),' : null,
            formRow.items.map(item => genFormGroupStatement(item, 1)).join(',\n'),
        ')'
    ].filter(Boolean), indent).join('\n');
}

/**
 * Get page template
 * @param {ZinPageInfo} info
 * @returns {string}
 */
function getPageTemplate(info)
{
    /** @type {string[]} */
    const lines = [];

    const {featureBar = {}, toolbar, dtable, form, mainHeader = {}} = info;
    const variables = [];
    const widgets   = [];
    if(featureBar && featureBar.current && !info.vars.browseType)
    {
        lines.push
        (
            '/* zin: Set variable $browseType to store the current active item in feature bar. */',
            `${genVarStatement('browseType', featureBar.current)} // the variable may already defined in control method.`,
            ''
        );
    }

    if(dtable)
    {
        lines.push('/* zin: Set variables to define columns and rows data for dtable. */');
        lines.push(genVarStatement('dtableCols', dtable.cols));
        lines.push(genVarStatement('dtableRows', dtable.data));
        variables.push('dtableCols', 'dtableRows');

        if(dtable.footToolbar)
        {
            variables.push('dtableToolbar');
            lines.push(genVarStatement('dtableToolbar', dtable.footToolbar));
        }
    }

    if(form && form.vars.length)
    {
        lines.push('/* zin: Set variables to define picker options for form. */');
        if(mainHeader.title !== info.title) lines.push(genVarStatement('formTitle', mainHeader.title));
        form.vars.forEach(item => lines.push(genVarStatement(item.name, item.value)));
        variables.push(...form.vars.map(item => item.name));
    }

    lines.push('\n\n/* ====== Define the page structure with zin widgets ====== */\n');

    if(featureBar && featureBar.items)
    {
        widgets.push('featureBar');
        lines.push
        (
            '/* zin: Define the feature bar on main menu. */',
            'featureBar',
            '(',
                indentLines(
                [
                    featureBar.current ? 'set::current($browseType)' : null,
                    featureBar.linkParams ? `set::linkParams(${JSON.stringify(featureBar.linkParams)}),` : null,
                    ...featureBar.items.map(item => genItemStatement(item))
                ].filter(x => typeof x === 'string'), 1).join(',\n'),
            ');',
            ''
        );
    }

    if(toolbar && toolbar.length)
    {
        lines.push
        (
            '/* zin: Define the toolbar on main menu. */',
            'toolbar',
            '(',
                indentLines(toolbar.map(item => genItemStatement(item)).filter(x => typeof x === 'string'), 1).join(',\n'),
            ');',
            ''
        );
    }

    if(info.sidebar)
    {
        widgets.push('sidebar');
        lines.push
        (
            '/* zin: Define the sidebar in main content. */',
            'sidebar',
            '(',
            `    ${info.sidebar.type}()`,
            ');',
            ''
        );
    }

    if(dtable)
    {
        widgets.push('dtable');
        lines.push
        (
            '/* zin: Define the dtable in main content. */',
            'dtable',
            '(',
                indentLines(
                [
                    "set::className('shadow rounded')",
                    'set::cols($dtableCols)',
                    'set::data($dtableRows)',
                    dtable.plugins ? `set::plugins(array(${dtable.plugins.map(x => JSON.stringify(x)).join(', ')}))` : null,
                    (dtable.plugins && dtable.plugins.includes('checkable')) ? 'set::checkable(true)' : null,
                    (dtable.plugins && dtable.plugins.includes('nested')) ? 'set::nested(true)' : null,
                    dtable.footToolbar ? 'set::toolbar($dtableToolbar)' : null,
                    dtable.footPager ? 'set::footPager(usePager())' : null,
                    dtable.footer ? `set::footer(array(${dtable.footer.map(x => JSON.stringify(x)).join(', ')}))` : null,
                ].filter(x => typeof x === 'string'), 1).join(',\n'),
            ');',
            ''
        );
    }
    else if(form)
    {
        widgets.push('formPanel', 'formRow', 'inputGroup', 'input', 'control');
        const mainHeader = info.mainHeader || {};
        lines.push
        (
            '/* zin: Define the form in main content. */',
            'formPanel',
            '(',
                indentLines
                ([
                    mainHeader.title !== info.title ? "set::title($formTitle), // The form title is diffrent from the page title" : null,
                    form.items.map((formRow) =>
                    {
                        if(!formRow.hidden && formRow.items.length === 1)
                        {
                           return genFormGroupStatement(formRow.items[0]);
                        }
                        return getFormRowStatement(formRow);
                    }).join(',\n'),
                ].filter(x => typeof x === 'string'), 1).join(',\n'),
            ');',
            ''
        );
    }

    lines.push
    (
        '\n/* ====== Render page ====== */\n',
        'render();'
    );

    lines.unshift(
        '<?php',
        'declare(strict_types=1);',
        '/**',
        ` * The ${info.methodName} view file of ${info.moduleName} module of ZenTaoPMS.`,
        ' *',
        ' * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)',
        ' * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)',
        ' * @author      yourname<yourname@easycorp.ltd>',
        ` * @package     ${info.moduleName}`,
        ' * @link        http://www.zentao.net',
        ' */',
        '',
        '/*',
        '  ======= Attention ======',
        '',
        '  This file is generated by zin-tool, you should check the following to-do list.',
        '',
        `  + Familiar with the use of these widgets in zin: ${widgets.join(', ')}.`,
        variables.length ? `  + Check the following variables which used in widgets: ${variables.map(x => `$${x}`).join(', ')}.` : null,
        featureBar.items && featureBar.items.length ? `  + Check the ${featureBar.items.length} items definition in featureBar widget.` : null,
        toolbar && toolbar.length ? `  + Check the ${toolbar.length} items definition in toolbar widget.` : null,
        `  + Check the origin code in module/${info.moduleName}/view/${info.methodName}.html.php, and ensure that all features have been implemented.`,
        `  + Check the origin js code in module/${info.moduleName}/js/common.js and module/${info.moduleName}/js/${info.methodName}.js.`,
        `  + Check the origin css code in module/${info.moduleName}/css/common.css and module/${info.moduleName}/css/${info.methodName}.css.`,
        '  + Remove the comments which start with "zin:".',
        '  + Test according to the new design draft and the original implementation.',
        ' */',
        '',
        'namespace zin;',
        '',
        '/* ====== Preparing and processing page data ====== */',
        '',
    );

    return lines.filter(x => typeof x === 'string').join('\n');
}

function zin(win)
{
    if(!win)
    {
        if(config.currentModule === 'index' && config.currentMethod === 'index') win = $.apps.getLastApp().$iframe[0].contentWindow;
        else win = window;
    }

    if(!win.config) return $.zui.messager.danger('zin: Current page is not supported yet, may be it rendered by zin already!');

    const pageInfo = window.pageInfo = getPageInfo(win);
    if(!pageInfo) return $.zui.messager.danger('zin: Current page is not supported temporarily.');
    const template = getPageTemplate(pageInfo);
    console.log('> [ZIN-TOOL] pageInfo', pageInfo);
    console.log('> [ZIN-TOOL] template', template);

    const $dialog = bootbox.dialog(
    {
        title: '<strong><i class="icon icon-magic"></i> zin view file</strong>',
        message: `<div class="strong">module/${pageInfo.moduleName}/ui/${pageInfo.methodName}.html.php</div><pre class="prettyprint"><code></code></pre>`,
        size: 'large',
        buttons:
        {
            copy:
            {
                label: 'Copy to clipboard',
                className: 'btn-primary',
                callback: () =>
                {
                    navigator.clipboard.writeText(template);
                    $.zui.messager.success(`zin view file copied to clipboard, you can create file module/${pageInfo.moduleName}/ui/${pageInfo.methodName}.html.php and paste to it.`);
                }
            },
            close:
            {
                label: 'Close',
                className: 'btn-default',
                callback: () => {}
            },
        }
    });
    $dialog.find('.modal-dialog').width(1200).find('pre>code').text(template);
    if(!window.prettyPrint) $.getScript(config.webRoot + 'js/kindeditor/plugins/code/prettify.js', () => window.prettyPrint());
    else window.prettyPrint();
}

$(function()
{
    if(!config) return;
    if(config.currentModule === 'index' && config.currentMethod === 'index')
    {
        $('<button type="button" class="btn btn-danger code">zin()</button>').prependTo('#globalBarLogo').on('click', () =>
        {
            const app = $.apps.getLastApp();
            if(!app) return;
            zin(app.$iframe[0].contentWindow);
        });
    }
});
