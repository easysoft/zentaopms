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

function updateChildrenName(item)
{
    if(!item.children) return;
    item.children.forEach((subItem, subIndex) =>
    {
        updateItemName(subItem, `${item.name}.${subIndex + 1}`);
        updateChildrenName(subItem);
    });
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
        dragNestedTip: '',
        changeLevelByDrag: false,
    };

    init()
    {
        this.reset(this.options.data, true);
    }

    afterInit()
    {
        this.render();
        const $element = this.$element;
        $element.on('focus', 'textarea', e =>
        {
            const $textarea = $(e.target);
            $textarea.closest('.steps-editor-col-step').addClass('focus');
            $textarea.closest('.steps-editor-item').addClass('is-focus');
        })
        .on('blur', 'textarea', e =>
        {
            const $textarea = $(e.target);
            $textarea.closest('.steps-editor-col-step').removeClass('focus');
            $textarea.closest('.steps-editor-item').removeClass('is-focus');

            if($textarea.val().length > 0 && $textarea.closest('.steps-editor-row').next('.steps-editor-row').length == 0)
            {
                const item    = {name: $textarea.closest('.steps-editor-body').find('.steps-editor-row[data-level="1"]').length + 1, id: $.guid++, step: '', expect: '', level: 1};
                const $preRow = $textarea.closest('.steps-editor-row');
                const $list   = this.$element.find('.steps-editor-body');
                const $rows = $list.find('.steps-editor-item').addClass('is-expired');
                $preRow = this._renderRow(item, $preRow, $list, $rows);
            }
        })
        .on('click', '.btn-action', e =>
        {
            const $btn = $(e.currentTarget);
            if($btn.is('.disabled')) return;

            const action = $btn.attr('data-action');
            const name = $btn.closest('.steps-editor-item').attr('data-name');
            if(action === 'delete') this.deleteStep(name);
            else if(action === 'sib') this.addSib(name);
            else if(action === 'sub') this.addSub(name);
        }).on('mouseenter', '.steps-editor-step-move', e =>
        {
            $element.find('.move-hover').removeClass('move-hover');
            $(e.currentTarget).closest('.steps-editor-item').addClass('move-hover');
        }).on('mouseleave', '.steps-editor-step-move', e =>
        {
            $element.find('.move-hover').removeClass('move-hover');
        });

        $element.draggable(
        {
            selector: '.steps-editor-item',
            handle: '.steps-editor-step-move',
            beforeDrag: (_event, dragElement) =>
            {
                const dragName = $(dragElement).attr('data-name');
                this.dragName = dragName;
                this.dragItem = this.getByName(dragName);
                this.dragMaxLevel = this.dragItem.level;
                this.isValidLevel = true;
                $element.find(`.steps-editor-item[data-name^="${dragName}."]`).addClass('is-sub-dragging').each((_index, ele) =>
                {
                    const level = +($(ele).attr('data-level'));
                    if(level > this.dragMaxLevel) this.dragMaxLevel = level;
                });
                $element.removeClass('cursor-not-allowed');
            },
            onDragStart: (event, dragElement) =>
            {
                event.dataTransfer.setDragImage($(dragElement).find('.steps-editor-drag-ghost')[0], 0, 0);
            },
            onDragEnd: () =>
            {
                $element.find('.is-sub-dragging').removeClass('is-sub-dragging');
                const dropName = this.dropName;
                if(dropName)
                {
                    this.dropName = null;
                    const $dropRow = this.getRow(dropName);
                    if($dropRow && $dropRow.length) $dropRow.removeAttr('data-drop-side').removeAttr('data-drop-level');
                }

                if(!this.isValidLevel) return;
                const dragName = this.dragName;
                if(!dropName || dragName === dropName) return;
                if(this.dropSide === 'bottom') this.moveAfter(dragName, dropName);
                else this.moveBefore(dragName, dropName);
            },
            onDragOver: (event, dragElement, dropElement) =>
            {
                const $row = $(dropElement);
                const dropName = $row.attr('data-name');
                let idDiff = false;
                if(dropName !== this.dropName)
                {
                    this.dropName = dropName;
                    const $oldRow = this.getRow(dropName);
                    $oldRow.removeAttr('data-drop-side')
                        .removeAttr('data-drop-level')
                        .removeAttr('data-invalid-nested');
                    $oldRow.find('.steps-editor-step-name').removeClass('is-invalid-drop-level');
                    this.dropSide = '';
                    this.dropLevel = '';
                    this.isValidLevel = undefined;
                    idDiff = true;
                }
                const dropItem = this.getByName(dropName);
                const dropBounding = dropElement.getBoundingClientRect();
                const dropSide = event.clientY > (dropBounding.top + dropBounding.height / 2) ? 'bottom' : 'top';
                const dropLevel = Math.max(1, Math.min(3, (!this.options.changeLevelByDrag || event.clientX <= (dropBounding.left + 24)) ? dropItem.level : (Math.round((event.clientX - dropBounding.left - 24 - 8) / 14))));
                const isValidLevel = (this.dragMaxLevel + (dropLevel - this.dragItem.level)) <= 3;
                if(this.isValidLevel !== isValidLevel)
                {
                    this.isValidLevel = isValidLevel;
                    $row.toggleClass('is-invalid-drop-level', !isValidLevel);
                    const $name = $row.find('.steps-editor-step-name');
                    if(isValidLevel) $name.removeAttr('data-invalid-nested');
                    else $name.attr('data-invalid-nested', this.options.dragNestedTip);
                }
                $element.toggleClass('cursor-not-allowed', !isValidLevel);
                if(this.dropSide !== dropSide)
                {
                    this.dropSide = dropSide;
                    $row.attr('data-drop-side', dropSide);
                    idDiff = true;
                }
                if(this.dropLevel !== dropLevel)
                {
                    this.dropLevel = dropLevel;
                    $row.attr('data-drop-level', dropLevel);
                    idDiff = true;
                }
            },
            target: (dragElement) =>
            {
                return $element.find('.steps-editor-item').not('.is-sub-dragging').not(dragElement);
            }
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

    getRow(name)
    {
        return this.$element.find(`.steps-editor-item[data-name="${name}"]`);
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

        const index = this._items.findIndex(x => x.id === item.id);
        if(index >= 0)
        {
            const oldItem = this._items[index];
            const parent = this.getParent(oldItem);
            if(parent) parent.children.splice(parent.children.indexOf(oldItem), 1);
            item = $.extend(oldItem, item);
            this._items.splice(index, 1);
            updateChildrenName(item);
        }
        if(item.level > 1)
        {
            this._items.push(item);
            const parent = this._ensureItem(item.parentName);
            const siblings = parent.children || [];
            const oldIndex = siblings.findIndex(x => x.id === item.id);
            if(oldIndex >= 0) siblings.splice(oldIndex, 1);
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
            `<div class="steps-editor-row steps-editor-item" data-id="${item.id}">`,
                '<div class="steps-editor-drag-ghost"></div>',
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
            .attr('data-index', item.index)
            .toggleClass('has-children', hasSub)
            .toggleClass('no-child', !hasSub)
            .removeClass('is-expired');
        $row.css('--name-indent', `${(item.level - 1) * 14}px`).find('.steps-editor-step-name').css('width', item.name.length * 12).text(item.name).css('paddingLeft', (item.level - 1) * 14);
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
        const $rows = $list.find('.steps-editor-item').addClass('is-expired');
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
        const $step = this.$element.find('.steps-editor-item[data-name="' + name + '"] .steps-editor-step-text');
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

    moveAfter(fromName, toName)
    {
        if(fromName === toName) return;
        const item = this.getByName(fromName);
        if(!item) return;
        item.name = toName;
        this.updateItem(item);
    }

    moveBefore(fromName, toName)
    {
        if(fromName === toName) return;
        const item = this.getByName(fromName);
        const toItem = this.getByName(toName);
        if(!item || !toItem) return;
        item.name = toItem.level > 1 ? `${toItem.parentName}.${+toItem.selfName - 1}` : `${+toItem.selfName - 1}`;
        this.updateItem(item);
    }
}

/* Define $.fn.stepsEditor() helper. */
StepsEditor.defineFn();

/* Extend StepsEditor to zui object. */
$.extend(zui, {StepsEditor});
