<?php js::set('module',  $module)?>

<div class='cell'>
    <div class='list-group'>
        <?php
        foreach ($lang->ci->subModules as $key => $value) {
            echo html::a(inlink('browse' . $key, '', ''), '<span class="text">'.$value.'</span>', '', " id='{$key}Tab'");
        }
        ?>
    </div>
</div>
