class AICategoryManager extends zui.Component
{
    static NAME = "AICategoryManager";

    init() {
        this.render();
    }

    afterInit() {
        this.$element.on("click", ".btn-add", (e) => {
            const $target = $(e.currentTarget).closest(".category-item");
            const $newItem = this.createCustomItem();
            $target.after($newItem);
        });
        this.$element.on("click", ".btn-delete", (e) => {
            const $item = $(e.currentTarget).closest(".category-item");
            if ($item.find(".btn-delete").is(":disabled")) return;
            $item.remove();
        });
    }

    createCategoryItem(key, value, isBuiltIn, isUsed) {
        const $item = $(`
            <div class="category-item flex mt-4" data-key="${key}">
                <div class="category-input w-2/3">
                    <input type="text" name="${key}" value="${value}" class="form-control" ${isBuiltIn ? "disabled" : ""}>
                </div>
                <div class="category-actions">
                    <button type="button" class="btn ghost btn-icon btn-add">
                        <i class="icon icon-plus"></i>
                    </button>
                    ${!isBuiltIn ? `<button type="button" class="btn ghost btn-icon btn-delete" ${isUsed ? "disabled" : ""}>
                        <i class="icon icon-close"></i>
                    </button>` : ""}
                </div>
            </div>
        `);
        return $item;
    }

    createCustomItem() {
        const timestamp = Date.now();
        return $(`
            <div class="category-item flex mt-4" data-key="custom-${timestamp}">
                <div class="category-input w-2/3">
                    <input type="text" name="custom[]" value="" class="form-control">
                </div>
                <div class="category-actions">
                    <button type="button" class="btn ghost btn-icon btn-add">
                        <i class="icon icon-plus"></i>
                    </button>
                    <button type="button" class="btn ghost btn-icon btn-delete">
                        <i class="icon icon-close"></i>
                    </button>
                </div>
            </div>
        `);
    }

    render() {
        const options = this.options;
        const $element = this.$element;
        $element.empty();

        Object.entries(options.builtInList).forEach(([key, value]) => {
            const $item = this.createCategoryItem(key, value, true, false);
            $element.append($item);
        });

        Object.entries(options.customList).forEach(([key, value]) => {
            const isUsed = options.usedCustomList.includes(key);
            const $item = this.createCategoryItem(key, value, false, isUsed);
            $element.append($item);
        });
    }
}

AICategoryManager.defineFn();

/* Extend AICategoryManager to zui object. */
$.extend(zui, {AICategoryManager});
