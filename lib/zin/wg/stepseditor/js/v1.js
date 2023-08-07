function updateItemName(item, name)
{
    if(name === undefined) name = item.name;
    if(item.infoName === name) return;
    item.infoName   = name;
    item.name       = name;
    const namePath  = name.split('.');
    item.order      = namePath.reduce((total, level, index) => total + (+level * ([1000000, 1000, 1, 0.001, 0.000001][index])), 0);
    item.level      = namePath.length;
    item.selfName   = namePath.pop();
    item.parentName = namePath.join('.');
}

/** Steps editor component. */
class StepsEditor extends zui.Component
{
    static NAME = 'StepsEditor';

    static DEFAULT =
    {
        name: 'steps',
        expectsName: 'expects',
        data: ['1', '2', '3'],
        sameLevelIcon: 'plus',
        subLevelIcon: 'split',
        moveIcon: 'move',
        deleteIcon: 'trash',
        expectDisabledTip: '',
    };

    init()
    {
        this.reset(this.options.data, true);
    }

    afterInit()
    {
        this.render();
        this.$element.on('focus', 'textarea', e =>
        {
            const $textarea = $(e.target);
            $textarea.closest('.steps-editor-col-step').addClass('focus');
            $textarea.closest('.steps-editor-row').addClass('is-focus');
        })
        .on('blur', 'textarea', e =>
        {
            const $textarea = $(e.target);
            $textarea.closest('.steps-editor-col-step').removeClass('focus');
            $textarea.closest('.steps-editor-row').removeClass('is-focus');
        })
        .on('click', '.btn-action', e =>
        {
            const $btn = $(e.currentTarget);
            if($btn.is('.disabled')) return;

            const action = $btn.attr('data-action');
            const name = $btn.closest('.steps-editor-row').attr('data-name');
            if(action === 'delete') this.deleteStep(name);
            else if(action === 'sib') this.addSib(name);
            else if(action === 'sub') this.addSub(name);
        }).on('mouseenter', '.steps-editor-step-move', e =>
        {
            this.$element.find('.move-hover').removeClass('move-hover');
            $(e.currentTarget).closest('.steps-editor-row').addClass('move-hover');
        }).on('mouseleave', '.steps-editor-step-move', e =>
        {
            this.$element.find('.move-hover').removeClass('move-hover');
        });
    }

    getByID(id)
    {
        return this._items.find(x => x.id === id);
    }

    getByName(name)
    {
        return this._items.find(x => x.name === name);
    }

    getParent(item)
    {
        if(typeof item === 'string') item = this.getByName(item);
        return (item && item.parentName) ? this.getByName(item.parentName) : null;
    }

    reset(data, skipRender)
    {
        this._items = [];
        this.update(data, skipRender);
    }

    update(data, skipRender)
    {
        data.forEach(item =>{this.updateItem(item, true);});
        if(!skipRender) this.render();
    }

    updateItem(item, skipRender)
    {
        item = typeof item === 'string' ? {name: item, id: $.guid++, step: '', expect: ''} : $.extend({id: $.guid++, step: '', expect: ''}, item);
        updateItemName(item);

        const oldItem = this.getByID(item.id);
        if(oldItem)
        {
            $.extend(oldItem, item);
        }
        else
        {
            if(item.level > 1)
            {
                this._items.push(item);
                const parent = this._ensureItem(item.parentName);
                const siblings = parent.children || [];
                const index = siblings.findIndex(x => x.order > item.order);
                if(index === 0) siblings.unshift(item);
                else if(index < 0) siblings.push(item);
                else if(index > 0) siblings.splice(index, 0, item);
                parent.children = siblings;
            }
            else
            {
                const siblings = this._items;
                const index = siblings.findIndex(x => x.level === 1 && x.order > item.order);
                if(index === 0) siblings.unshift(item);
                else if(index < 0) siblings.push(item);
                else if(index > 0) siblings.splice(index, 0, item);
            }
        }

        if(!skipRender) this.render();
        return item;
    }

    _ensureItem(name)
    {
        return this.getByName(name) || this.updateItem(name);
    }

    _createRow(item, options)
    {
        const $row = $
        ([
            `<div class="steps-editor-row" data-id="${item.id}">`,
                '<div class="steps-editor-col steps-editor-col-step form-control">',
                    '<div class="steps-editor-step-move"><i class="icon icon-move"></i></div>',
                    '<div class="steps-editor-step-name"></div>',
                    `<textarea class="steps-editor-step-text" rows="1">${item.step}</textarea>`,
                '</div>',
                '<div class="steps-editor-col steps-editor-col-add">',
                    `<div><button type="button" class="btn ghost rounded size-sm square btn-action" data-action="sib"><i class="icon icon-${options.sameLevelIcon}"></i></button></div>`,
                    `<div><button type="button" class="btn ghost rounded size-sm square btn-action" data-action="sub"><i class="icon icon-${options.subLevelIcon}"></i></button></div>`,
                '</div>',
                '<div class="steps-editor-col steps-editor-col-expect">',
                    `<textarea class="steps-editor-step-expect form-control" rows="1">${item.expect}</textarea>`,
                '</div>',
                '<div class="steps-editor-col steps-editor-col-delete">',
                    `<div><button type="button" class="btn ghost rounded size-sm square btn-action" data-action="delete"><i class="icon icon-${options.deleteIcon}"></i></button></div>`,
                    '<input class="steps-editor-step-type" type="hidden" />',
                '</div>',
            '</div>'
        ].join(''));
        $row.find('textarea').autoHeight();
        return $row;
    }

    _renderRow(item, $preRow, $list, $rows)
    {
        const options = this.options;
        let $row = $rows.filter(`[data-id="${item.id}"]`);
        if(!$row.length) $row = this._createRow(item, options);

        if($preRow) $row.insertAfter($preRow);
        else $list.prepend($row);

        const hasSub = !!(item.children && item.children.length);
        $row.attr('data-level', item.level)
            .attr('data-name', item.name)
            .toggleClass('has-children', hasSub)
            .removeClass('is-expired');
        $row.find('.steps-editor-step-name').css('width', 6 + (item.level * 18)).text(item.name);
        $row.find('.steps-editor-step-text').attr('name', `${options.name}[${item.name}]`);
        $row.find('.steps-editor-step-type').attr('name', `stepType[${item.name}]`).val(hasSub ? 'group' : (!!item.parent ? 'item' : 'step'));
        const $expect = $row.find('.steps-editor-step-expect').attr(
        {
            name: `${options.expectsName}[${item.name}]`,
            placeholder: hasSub ? options.expectDisabledTip : null,
        }).toggleClass('disabled', hasSub);
        if(hasSub) $expect.val('');
        $row.find('.steps-editor-col-delete .btn').toggleClass('disabled', hasSub);
        $row.find('.steps-editor-col-add .btn-action[data-action="sub"]').toggleClass('disabled', item.level >= 3);
        return $row;
    }

    render()
    {
        const items = this._items;
        let rootIndex = 0;
        items.forEach(item =>
        {
            updateItemName(item);
            if(item.level === 1)
            {
                item.index = rootIndex;
                rootIndex++;
                updateItemName(item, `${rootIndex}`);
            }
            if(item.children)
            {
                item.children.forEach((subItem, subIndex) =>
                {
                    subItem.index = subIndex;
                    updateItemName(subItem, `${item.name}.${subIndex + 1}`);
                });
            }
        });
        items.sort((a, b) => a.order - b.order);

        const $list = this.$element.find('.steps-editor-body');
        const $rows = $list.find('.steps-editor-row').addClass('is-expired');
        let $preRow = null;
        items.forEach(item =>
        {
            $preRow = this._renderRow(item, $preRow, $list, $rows);
        });
        $rows.filter('.is-expired').remove();
    }

    deleteStep(name)
    {
        const index = this._items.findIndex(x => x.name === name);
        if (index < 0) return;
        const item = this._items[index];
        this._items.splice(index, 1);
        const parent = this.getParent(item);
        if(parent)
        {
            const siblings = parent.children;
            const index = siblings.indexOf(item);
            if(index > -1) siblings.splice(index, 1);
        }
        if (!this._items.length) this.update(['1'], false);
        this.render();
    }

    focus(name)
    {
        const $step = this.$element.find('.steps-editor-row[data-name="' + name + '"] .steps-editor-step-text');
        if($step.length) $step[0].focus();
    }

    addSub(fromName)
    {
        const item = this.getByName(fromName);
        if(!item) return;

        const newStepName = item.children ? `${item.name}.${item.children.length + 1}` : `${item.name}.1`;
        this.updateItem(newStepName);
        this.focus(newStepName);
    }

    addSib(fromName)
    {
        const item = this.getByName(fromName);
        if(!item) return;

        const newItem = this.updateItem(item.name);
        this.focus(newItem.name);
    }
}

/* Define $.fn.stepsEditor() helper. */
StepsEditor.defineFn();

/* Extend StepsEditor to zui object. */
$.extend(zui, {StepsEditor});
