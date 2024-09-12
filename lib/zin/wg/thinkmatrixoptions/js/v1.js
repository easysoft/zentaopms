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

/** ThinkMatrixOptions component. */
class ThinkMatrixOptions extends zui.Component
{
    static NAME = 'ThinkMatrixOptions';

    static DEFAULT =
    {
        colName: 'colFields',
        cols: ['', '', '', ''],
        moveIcon: 'move',
        deleteIcon: 'close',
        deleteColTip: '',
        addColTip: '',
        changeLevelByDrag: false,
        colPlaceholder: '',
        addColText: '',
        isSaveData: true,
    };

    init()
    {
        this.reset(this.options.cols, true);
    }

    afterInit()
    {
        this.render();
        const $element        = this.$element;
        const quotedQuestions = $('.think-multiple').data().quotedQuestions;
        $element.on('focus', 'input', e =>
        {
            const $input = $(e.target);
            $input.closest('.think-multiple-item').addClass('is-focus');
        })
        .on('blur', 'input', e =>
        {
            const $input = $(e.target);
            $input.closest('.think-multiple-item').removeClass('is-focus');
            this.saveData();
        })
        .on('input', 'input', e => {this.saveData();})
        .on('click', '.btn-action', e =>
        {
            const $btn = $(e.currentTarget);
            if($btn.is('.disabled')) return;

            const action = $btn.attr('data-action');
            const name = $btn.closest('.think-multiple-item').attr('data-name');
            if(action === 'delete') this.deleteItem(name);
            else if(action === 'sib') this.addSib();
        }).on('mouseenter', '.think-multiple-item-move', e =>
        {
            $element.find('.move-hover').removeClass('move-hover');
            $(e.currentTarget).closest('.think-multiple-item').addClass('move-hover');
        }).on('mouseleave', '.think-multiple-item-move', e =>
        {
            $element.find('.move-hover').removeClass('move-hover');
        });

        /* 如果当前多列填空题被其他多选题引用，不可以重新排列列标题顺序。*/
        /* If the current multiple column fill in the blank question is referenced by other multiple-choice questions, the column headings cannot be rearranged. */
        if(!quotedQuestions || quotedQuestions.length == 0)
        {
            $element.draggable(
            {
                selector: '.think-multiple-item',
                handle: '.think-multiple-item-move',
                beforeDrag: (_event, dragElement) =>
                {
                    const dragName = $(dragElement).attr('data-name');
                    this.dragName = dragName;
                    this.dragItem = this.getByName(dragName);
                    this.dragMaxLevel = this.dragItem.level;
                    this.isValidLevel = true;
                    $element.find(`.think-multiple-item[data-name^="${dragName}."]`).addClass('is-sub-dragging').each((_index, ele) =>
                    {
                        const level = +($(ele).attr('data-level'));
                        if(level > this.dragMaxLevel) this.dragMaxLevel = level;
                    });
                    $element.removeClass('cursor-not-allowed');
                },
                onDragStart: (event, dragElement) =>
                {
                    event.dataTransfer.setDragImage($(dragElement).find('.think-multiple-drag-ghost')[0], 0, 0);
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
                    if(this.dropSide === 'right') this.moveAfter(dragName, dropName);
                    else this.moveBefore(dragName, dropName);
                    this.saveData(true);
                },
                onDragOver: (event, dragElement, dropElement) =>
                {
                    const $col = $(dropElement);
                    const dropName = $col.attr('data-name');
                    let idDiff = false;
                    if(dropName !== this.dropName)
                    {
                        this.dropName = dropName;
                        const $oldRow = this.getRow(dropName);
                        $oldRow.removeAttr('data-drop-side')
                            .removeAttr('data-drop-level')
                            .removeAttr('data-invalid-nested');
                        this.dropSide = '';
                        this.dropLevel = '';
                        this.isValidLevel = undefined;
                        idDiff = true;
                    }
                    const dropItem = this.getByName(dropName);
                    const dropBounding = dropElement.getBoundingClientRect();
                    const dropSide = event.clientX > (dropBounding.left + dropBounding.width / 2) ? 'right' : 'left';
                    const dropLevel = Math.max(1, Math.min(4, (!this.options.changeLevelByDrag || event.clientX <= (dropBounding.left + 24)) ? dropItem.level : (Math.round((event.clientX - dropBounding.left - 24 - 8) / 14))));
                    const isValidLevel = (this.dragMaxLevel + (dropLevel - this.dragItem.level)) <= 4;
                    if(this.isValidLevel !== isValidLevel)
                    {
                        this.isValidLevel = isValidLevel;
                        $col.toggleClass('is-invalid-drop-level', !isValidLevel);
                    }
                    $element.toggleClass('cursor-not-allowed', !isValidLevel);
                    if(this.dropSide !== dropSide)
                    {
                        this.dropSide = dropSide;
                        $col.attr('data-drop-side', dropSide);
                        idDiff = true;
                    }
                    if(this.dropLevel !== dropLevel)
                    {
                        this.dropLevel = dropLevel;
                        $col.attr('data-drop-level', dropLevel);
                        idDiff = true;
                    }
                },
                target: (dragElement) =>
                {
                    return $element.find('.think-multiple-item').not('.is-sub-dragging').not(dragElement);
                }
            });
        }
    }

    saveData(isClear = false)
    {
        if(!this.options.isSaveData) return;

        const cols = [];
        $('.think-multiple-body .think-multiple-item-text').each(function()
        {
            if($(this).val())
            {
                const item = {value: $(this).closest('.think-multiple-item').data('name'), text: $(this).val()};
                cols.push(item);
            }
        });

        let values   = $('.required-options .picker-box').zui('picker').$.value;
        values       = values.split('');
        const result = [];
        values.forEach(function(item)
        {
            if($(`.think-multiple-item[data-name="${item}"] .think-multiple-item-text`).val()) result.push(item);
        });

        $('.required-options .picker-box').zui('picker').$.setValue(result.join(','));
        $('.required-options .picker-box').zui('picker').render({items: cols});
        if(isClear) $('.required-options .picker-box').zui('picker').$.setValue('');
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
        return this.$element.find(`.think-multiple-item[data-name="${name}"]`);
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

    _createCol(item, options)
    {
        const name = options.cols[item.index] || '';
        const $col = $
        ([
            `<div class="think-multiple-item flex-1 py-1.5" data-id="${item.id}">`,
                '<div class="think-multiple-drag-ghost"></div>',
                '<div class="think-multiple-item-move think-multiple-action"><i class="icon icon-move"></i></div>',
                '<div class="flex-1">',
                    '<div class="form-control">',
                        `<input class="think-multiple-item-text w-full h-full" placeholder="${options.colPlaceholder}${item.name}" value="${name}" />`,
                    '</div>',
                    '<div class="mt-2 rounded ring bg-gray-100" style="height: 30px; --tw-ring-color: var(--form-control-border);"></div>',
                '</div>',
                '<div class="think-multiple-col-delete think-multiple-action">',
                    `<button type="button" class="btn ghost rounded size-sm square btn-action border-none" data-action="delete"><i class="icon icon-${options.deleteIcon}"></i></button>`,
                '</div>',
            '</div>'
        ].join(''));
        return $col;
    }

    _renderCol(item, $preCol, $list, $cols)
    {
        const options = this.options;
        const tipQuestion = $('.think-multiple').data().tipQuestion;
        const cannotDeleteColumnTip = $('.think-multiple').data().cannotDeleteColumnTip;
        let $col = $cols.filter(`[data-id="${item.id}"]`);
        if(!$col.length) $col = this._createCol(item, options);

        if($preCol) $col.insertAfter($preCol);
        else $list.prepend($col);

        const hasSub = !!(item.children && item.children.length);
        $col.attr('data-level', item.level)
            .attr('data-name', item.name)
            .attr('data-index', item.index)
            .toggleClass('has-children', hasSub)
            .toggleClass('no-child', !hasSub)
            .removeClass('is-expired');
        $col.find('.think-multiple-item-text').attr({'name': `${options.colName}[${item.name}]`, placeholder: `${options.colPlaceholder}${item.name}`});
        $col.find('.think-multiple-col-delete .btn').toggleClass('disabled', hasSub || this._items.length == 1);
        $col.find('.think-multiple-col-delete .btn.disabled').attr('title', options.deleteColTip);
        $col.find('.think-multiple-item-move').toggleClass('disabled', this._items.length == 1);

        /* 当前多列填空被其他多选题引用时禁用删除按钮和移动按钮，并提示出引用当前问题的题号。 */
        /* When the current multi-column fill-in-the-blank question is referenced by other multiple-choice questions, the delete button and move button are disabled, and the question number that references the current question is prompted. */
        const quotedQuestions = $('.think-multiple').data().quotedQuestions;
        if(quotedQuestions && quotedQuestions.length > 0)
        {
            let quotedIndex = '';
            quotedQuestions.forEach(function(item, index)
            {
                quotedIndex = quotedIndex + tipQuestion.replace(/%s/g, item.index) + ((index + 1 == quotedQuestions.length) ? '' : '、');
            })
            $col.find('.think-multiple-item-move').attr('disabled', true);
            $col.find('.think-multiple-col-delete .btn').attr('disabled', true);
            $col.find('.think-multiple-col-delete .btn').attr('title', cannotDeleteColumnTip.replace(/%s/g, quotedIndex));
            $col.find('.think-multiple-item-move').attr('title', cannotDeleteColumnTip.replace(/%s/g, quotedIndex));
        }
        else
        {
            $col.find('.think-multiple-item-move').removeAttr('disabled');
            $col.find('.think-multiple-col-delete .btn').removeAttr('disabled');
            $col.find('.think-multiple-col-delete .btn').removeAttr('title');
            $col.find('.think-multiple-item-move').removeAttr('title');
        }

        return $col;
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

        const $list = this.$element.find('.think-multiple-body');
        const $cols = $list.find('.think-multiple-item').addClass('is-expired');
        let $preCol = null;
        items.forEach(item =>
        {
            $preCol = this._renderCol(item, $preCol, $list, $cols);
        });
        $cols.filter('.is-expired').remove();
        if(!this.$element.find('.think-multiple-buttons').length) this.$element.find('.think-multiple-body').append(`<div class="think-multiple-buttons"><button type="button" class="btn ghost text-primary rounded size-sm square btn-action action-add" data-action="sib"><i class="icon icon-plus"></i>${this.options.addColText}</button></div>`);
        this.$element.find('.think-multiple-buttons .action-add').prop('disabled', items?.length && items.length > 15).attr('title', items?.length && items.length > 15 ? this.options.addColTip : '');
    }

    deleteItem(name)
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
        const inputValue = this.$element.find(`.think-multiple-item[data-id="${item.id}"] .think-multiple-item-text`).val();
        this.render();
        this.saveData(!!inputValue);
    }

    focus(name)
    {
        const $step = this.$element.find('.think-multiple-item[data-name="' + name + '"] .think-multiple-item-text');
        if($step.length) $step[0].focus();
    }

    addSib(focus = true)
    {
        if(this._items.length > 15) return;

        const fromName = this.$element.find('.think-multiple-item').last().attr('data-name');
        const item = this.getByName(fromName);
        if(!item) return;

        const newItem = this.updateItem(item.name);
        if(focus) this.focus(newItem.name);
        this.$element.find(`.think-multiple-item[data-id="${newItem.id}"] .think-multiple-item-text`).val('');
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

/* Define $.fn.ThinkMatrixOptions() helper. */
ThinkMatrixOptions.defineFn();

/* Extend ThinkMatrixOptions to zui object. */
$.extend(zui, {ThinkMatrixOptions});
