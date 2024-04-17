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
class ThinkOptions extends zui.Component
{
    static NAME = 'StepsEditor';

    static DEFAULT =
    {
        name: 'fields',
        data: ['1', '2', '3'],
        sameLevelIcon: 'plus',
        moveIcon: 'move',
        deleteIcon: 'trash',
        deleteStepTip: '',
        dragNestedTip: '',
        changeLevelByDrag: false,
        enterPlaceholder: '',
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
            $textarea.closest('.think-options-col-step').addClass('focus');
            $textarea.closest('.think-options-item').addClass('is-focus');
        })
        .on('blur', 'textarea', e =>
        {
            const $textarea = $(e.target);
            $textarea.closest('.think-options-col-step').removeClass('focus');
            $textarea.closest('.think-options-item').removeClass('is-focus');

            if($textarea.val().length > 0 && $textarea.closest('.think-options-row').next('.think-options-row').length == 0)
            {
                this.addSib($textarea.closest('.think-options-body').find('.think-options-row[data-level="1"]').last().attr('data-name'), false);
            }
        })
        .on('click', '.btn-action', e =>
        {
            const $btn = $(e.currentTarget);
            if($btn.is('.disabled')) return;

            const action = $btn.attr('data-action');
            const name = $btn.closest('.think-options-item').attr('data-name');
            if(action === 'delete') this.deleteStep(name);
            else if(action === 'sib') this.addSib(name);
        }).on('mouseenter', '.think-options-step-move', e =>
        {
            $element.find('.move-hover').removeClass('move-hover');
            $(e.currentTarget).closest('.think-options-item').addClass('move-hover');
        }).on('mouseleave', '.think-options-step-move', e =>
        {
            $element.find('.move-hover').removeClass('move-hover');
        });

        $element.draggable(
        {
            selector: '.think-options-item',
            handle: '.think-options-step-move',
            beforeDrag: (_event, dragElement) =>
            {
                const dragName = $(dragElement).attr('data-name');
                this.dragName = dragName;
                this.dragItem = this.getByName(dragName);
                this.dragMaxLevel = this.dragItem.level;
                this.isValidLevel = true;
                $element.find(`.think-options-item[data-name^="${dragName}."]`).addClass('is-sub-dragging').each((_index, ele) =>
                {
                    const level = +($(ele).attr('data-level'));
                    if(level > this.dragMaxLevel) this.dragMaxLevel = level;
                });
                $element.removeClass('cursor-not-allowed');
            },
            onDragStart: (event, dragElement) =>
            {
                event.dataTransfer.setDragImage($(dragElement).find('.think-options-drag-ghost')[0], 0, 0);
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
                    $oldRow.find('.think-options-step-name').removeClass('is-invalid-drop-level');
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
                    const $name = $row.find('.think-options-step-name');
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
                return $element.find('.think-options-item').not('.is-sub-dragging').not(dragElement);
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
        return this.$element.find(`.think-options-item[data-name="${name}"]`);
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
        item = typeof item === 'string' ? {name: item, id: $.guid++, step: ''} : $.extend({id: $.guid++, step: ''}, item);
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
            `<div class="think-options-row think-options-item" data-id="${item.id}">`,
                '<div class="think-options-drag-ghost"></div>',
                '<div class="think-options-col think-options-col-step form-control">',
                    '<div class="think-options-step-name"></div>',
                    `<textarea class="think-options-step-text" rows="1" placeholder="${options.enterPlaceholder}">${item.step}</textarea>`,
                '</div>',
                '<div class="think-options-col think-options-col-add">',
                    `<div><button type="button" class="btn ghost rounded size-sm square btn-action" data-action="sib"><i class="icon icon-${options.sameLevelIcon}"></i></button></div>`,
                    '<div class="think-options-col think-options-col-delete">',
                        `<div><button type="button" class="btn ghost rounded size-sm square btn-action" data-action="delete"><i class="icon icon-${options.deleteIcon}"></i></button></div>`,
                    '</div>',
                    '<div class="think-options-step-move"><i class="icon icon-move"></i></div>',
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
        $row.css('--name-indent', `${(item.level - 1) * 14}px`).find('.think-options-step-name').text(item.name).css('paddingLeft', (item.level - 1) * 14);
        $row.find('.think-options-step-text').attr('name', `${options.name}[${item.name}]`);
        $row.find('.think-options-col-delete .btn').toggleClass('disabled', hasSub || this._items.length == 1);
        $row.find('.think-options-col-delete .btn.disabled').attr('title', options.deleteStepTip);
        $row.find('.think-options-col-add .btn-action[data-action="sub"]').toggleClass('disabled', item.level >= 3);
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

        const $list = this.$element.find('.think-options-body');
        const $rows = $list.find('.think-options-item').addClass('is-expired');
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
        const $step = this.$element.find('.think-options-item[data-name="' + name + '"] .think-options-step-text');
        if($step.length) $step[0].focus();
    }

    addSib(fromName, focus = true)
    {
        const item = this.getByName(fromName);
        if(!item) return;

        const newItem = this.updateItem(item.name);
        if(focus) this.focus(newItem.name);
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
ThinkOptions.defineFn();

/* Extend StepsEditor to zui object. */
$.extend(zui, {ThinkOptions});
