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
        this.update(this.options.data, true, true);
    }

    afterInit()
    {
        this.render();
        this.$element
            .on('focus', 'textarea', e =>
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
            const action = $(e.currentTarget).attr('data-action');
            const name = $(e.currentTarget).closest('.steps-editor-row').attr('data-name');
            if(action === 'delete') this.deleteStep(name);
            else if(action === 'sib') this.addSib(name);
            else if(action === 'sub') this.addSub(name);
        });
    }

    getByID(id)
    {
        const map = this._map;
        const keys = Object.keys(map);
        for(let i = 0; i < keys.length; i++)
        {
            const item = map[keys[i]];
            if(item.id === id) return item;
        }
        return null;
    }

    update(data, reset, skipRender)
    {
        this._map = (reset ? {} : this._map) || {};
        data.forEach(item =>{this.updateItem(item, true);});
        if(!skipRender) this.render();
    }

    updateItem(item, skipRender)
    {
        const map = this._map;
        if(typeof item === 'string') item = {name: item, id: $.guid++};
        item = $.extend({id: $.guid++, step: '', expect: ''}, item);
        const namePath = item.name.split('.');
        item.order      = namePath.reduce((total, level, index) => total + (+level * ([1000000, 1000, 1, 0.001, 0.000001][index])), 0);
        item.level      = namePath.length;
        item.selfName   = namePath.pop();
        item.parentName = namePath.join('.');

        const oldItem = this.getByID(item.id);

        if(oldItem && oldItem.name !== item.name) delete map[oldItem.name];
        map[item.name] = oldItem ? $.extend({}, oldItem, item) : item;

        if(!skipRender) this.render();
    }

    render()
    {
        const map = this._map;
        const list = Object.keys(map).map(key =>
        {
            const item = map[key];
            return $.extend(item, {children: [], parent: item.parentName ? map[item.parentName] : null})
        })
        .sort((a, b) => a.order - b.order);
        list.forEach(item =>
        {
            if(item.parent) item.parent.children.push(item);
        });

        const $list = this.$element.find('.steps-editor-body');
        const $rows = $list.find('.steps-editor-row').addClass('is-expired');
        const options = this.options;
        let $preRow = null;
        let $row = null;
        list.forEach(item =>
        {
            $row = $rows.filter(`[data-id="${item.id}"]`);
            if(!$row.length)
            {
                $row = $
                ([
                    `<div class="steps-editor-row row ring items-streach" data-id="${item.id}">`,
                        '<div class="steps-editor-col steps-editor-col-step form-control">',
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
            }
            if($preRow) $row.insertAfter($preRow);
            else $list.prepend($row);
            $preRow = $row;

            const hasSub = !!(item.children && item.children.length);
            $row.attr('data-level', item.level)
                .attr('data-name', item.name)
                .toggleClass('has-children', hasSub)
                .removeClass('is-expired');
            $row.find('.steps-editor-step-name').css('width', 6 + (item.level * 18)).text(item.name);
            $row.find('.steps-editor-step-text').attr('name', `${options.name}[${item.name}]`);
            $row.find('.steps-editor-step-type').attr('name', `stepType[${item.name}]`).val(hasSub ? 'item' : 'step');
            const $expect = $row.find('.steps-editor-step-expect').attr(
            {
                name: `${options.expectsName}[${item.name}]`,
                placeholder: hasSub ? options.expectDisabledTip : null,
                disabled: hasSub ? 'disabled' : null,
            }).toggleClass('disabled', hasSub);
            if(hasSub) $expect.val('');
            $row.find('.steps-editor-col-delete .btn').toggleClass('disabled', hasSub).attr('disabled', hasSub ? 'disabled' : null);
            $row.find('.steps-editor-col-add .btn-action[data-action="sub"]').attr('disabled', item.level >= 3 ? 'disabled' : null);
        });
        $rows.filter('.is-expired').remove();
    }

    deleteStep(name)
    {
        const item = this._map[name];
        if (!item) return;
        delete this._map[name];
        const siblings = item.parent ? item.parent.children.filter(x => x.name !== name) : Object.keys(this._map).map(key => this._map[key]).filter(x => !x.parent);
        const updateItems = [];
        siblings.forEach((sibling, idx) =>
        {
            const newName = item.parent ? `${item.parent.name}.${idx + 1}` : `${idx + 1}`;
            if(sibling.name !== newName)
            {
                updateItems.push($.extend({}, sibling, {name: newName}));
            }
        });
        if(updateItems.length) this.update(updateItems, false, true);
        if(!Object.keys(this._map).length) this.update(['1'], false, true);
        this.render();
    }

    focus(name)
    {
        const $step = this.$element.find('.steps-editor-row[data-name="' + name + '"] .steps-editor-step-text');
        if($step.length) $step[0].focus();
    }

    addSub(fromName)
    {
        const item = this._map[fromName];
        if(!item) return;

        const newStepName = item.children ? `${item.name}.${item.children.length + 1}` : `${item.name}.1`;

        this.updateItem({name: newStepName});
        this.focus(newStepName);
    }

    addSib(fromName)
    {
        const item = this._map[fromName];
        if(!item) return;

        const siblings = item.parent ? item.parent.children : Object.keys(this._map).map(key => this._map[key]).filter(item => !item.parent);
        const index = siblings.indexOf(item);
        const newStepName = item.parent ? `${item.parent.name}.${index + 2}` : `${index + 2}`;
        const updateItems = [newStepName];
        if(index < siblings.length - 1)
        {
            siblings.slice(index + 1).forEach((nextItem, idx) =>
            {
                const newIndex = index + idx + 3;
                updateItems.push($.extend({}, nextItem, {name: nextItem.parent ? `${nextItem.parent.name}.${newIndex}` : `${newIndex}`}))
            });
        }
        this.update(updateItems);
        this.focus(newStepName);
    }

    addStep(fromName, asSib)
    {
        if(asSib) this.addSib(fromName);
        else this.addSub(fromName);
    }
}

/* Define $.fn.stepsEditor() helper. */
StepsEditor.defineFn();

$.extend(zui, {StepsEditor});
