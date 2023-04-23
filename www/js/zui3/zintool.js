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

function convertClassName(className, exclude = 'dropdown-toggle')
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
            if($(this).hasClass('dropdown-menu')) return;
            items.push(getZinItemProps($(this)));
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

/**
 * Get page info for zin
 * @param {Window} win
 * @returns {ZinPageInfo}
 */
function getPageInfo(win)
{
    const {document, config, $} = win;
    const info =
    {
        url: win.location.href,
        title: document.title.replace(' - 禅道', ''),
        moduleName: config.currentModule,
        methodName: config.currentMethod,
    };

    const $featureBar = $('#mainMenu .btn-toolbar.pull-left,#mainMenu .btn-toolBar.pull-left');
    if($featureBar.length)
    {
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
        info.featureBar = featureBar;
    }

    const $toolbar = $('#mainMenu .btn-toolbar.pull-right');
    if($toolbar.length)
    {
        const toolbar = [];
        $toolbar.children().each(function()
        {
            toolbar.push(getZinItemProps($(this)));
        });
        if(toolbar.length) info.toolbar = toolbar;
    }

    const $table = $('#mainContent .table').first();
    if($table.length)
    {
        if($('#mainContent .datatable').length)
        {
            return alert('zin: Please switch the table to simple table mode');
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
        info.sidebar = $('#sidebar').length ? {type: 'moduleTree'} : undefined;

        if($('#tableCustomBtn').length) info.tableCustomCols = true;
    }

    return info;
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

/**
 * Get page template
 * @param {ZinPageInfo} info
 * @returns {string}
 */
function getPageTemplate(info)
{
    /** @type {string[]} */
    const lines = [];

    const {featureBar, toolbar, dtable} = info;
    const variables = [];
    const widgets = [];
    if(featureBar && featureBar.current)
    {
        lines.push
        (
            '/* zin: Set variable $browseType to store the current active item in feature bar */',
            `${genVarStatement('browseType', featureBar.current)} // the variable may already defined in control method`,
            ''
        );
    }

    if(dtable)
    {
        lines.push('/* zin: Set variables to define columns and rows data for dtable */');
        lines.push(genVarStatement('dtableCols', dtable.cols));
        lines.push(genVarStatement('dtableRows', dtable.data));
        variables.push('dtableCols', 'dtableRows');

        if(dtable.footToolbar)
        {
            variables.push('dtableToolbar');
            lines.push(genVarStatement('dtableToolbar', dtable.footToolbar));
        }
    }

    lines.push('\n\n/* ====== Define the page structure with zin widgets ====== */\n');

    if(featureBar)
    {
        widgets.push('featureBar');
        lines.push
        (
            '/* zin: Define the feature bar on main menu */',
            'featureBar',
            '(',
                indentLines([
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
            '/* zin: Define the toolbar on main menu */',
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
            '/* zin: Define the sidebar in main content */',
            '/* sidebar',
            '(',
                genSetStatement('type', info.sidebar.type, 1),
            '); */ // Sidebar is not work yet',
            ''
        );
    }

    if(dtable)
    {
        widgets.push('dtable');
        lines.push
        (
            '/* zin: Define the dtable in main content */',
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

    lines.push
    (
        '\n/* ====== Render page ====== */\n',
        'render();'
    );

    lines.unshift(
        '<?php',
        '/**',
        ` * The ${info.methodName} view of ${info.moduleName} of ZenTaoPMS.`,
        ' */',
        '',
        '/*',
        '  ======= Attention ======',
        '',
        '  This file is generated by zin-tool, you should check the following to-do list.',
        '',
        `  + Familiar with the use of these widgets in zin: ${widgets.join(', ')}.`,
        variables.length ? `  + Check the following variables which used in widgets: ${variables.map(x => `$${x}`).join(', ')}.` : null,
        featureBar.items && featureBar.items.length ? `  + Check the ${featureBar.items.length.length} items difinition in featureBar widget.` : null,
        toolbar && toolbar.length ? `  + Check the ${toolbar.length} items difinition in toolbar widget.` : null,
        `  + Check the origin code in module/${info.moduleName}/view/${info.methodName}.html.php, and ensure that all features have been implemented.`,
        `  + Check the origin js code in module/${info.moduleName}/js/common.js and module/${info.moduleName}/js/${info.methodName}.js`,
        `  + Check the origin css code in module/${info.moduleName}/css/common.css and module/${info.moduleName}/css/${info.methodName}.css`,
        '  + Remove the comments which starts with "zin:"',
        '  + Test according to the new design draft and the original implementation',
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
    win = win || window;

    if(!win.config) return $.zui.messager.danger('zin: Current page is not supported yet, may be it rendered by zin already!');

    const pageInfo = getPageInfo(win);
    if(!pageInfo) return $.zui.messager.danger('zin: Current page is not supported temporarily.');

    const template = getPageTemplate(pageInfo);
    console.log('> pageInfo', pageInfo);
    console.log('> template', template);

    const $dialog = bootbox.dialog(
    {
        title: 'zin 视图模版',
        message: `<div class="strong">module/${pageInfo.moduleName}/ui/${pageInfo.methodName}.html.php</div><pre class="prettyprint"><code></code></pre>`,
        size: 'large',
        buttons:
        {
            copy:
            {
                label: '复制到剪贴板',
                className: 'btn-primary',
                callback: () =>
                {
                    navigator.clipboard.writeText(template);
                    $.zui.messager.success(`zin 视图模版已复制到剪贴板，请创建文件 module/${pageInfo.moduleName}/ui/${pageInfo.methodName}.html.php 并粘贴`);
                }
            },
            close:
            {
                label: '关闭',
                className: 'btn-default',
                callback: () => {}
            },
        }
    });
    $dialog.find('.modal-dialog').width(1200).find('pre>code').text(template);
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
