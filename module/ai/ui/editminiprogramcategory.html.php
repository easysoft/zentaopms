<?php
declare(strict_types=1);
namespace zin;

formPanel
(
    set::title($lang->ai->miniPrograms->maintenanceGroup),
    zui::AICategoryManager
    (
        set::builtInList($lang->ai->miniPrograms->categoryList),
        set::customList($categoryList),
        set::usedCustomList($usedCustomCategories)
    )
);
