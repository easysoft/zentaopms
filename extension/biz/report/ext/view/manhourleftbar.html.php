<div class="sidebar-toggle">
    <i class="icon icon-angle-left"></i>
</div>
<div class="cell">
    <div class='panel'>
        <div class='panel-heading'>
            <div class='panel-title'><?php echo $lang->manhour->list;?></div>
        </div>
        <div class='panel-body'>
            <div class='list-group'>
                <?php
                $title = $lang->manhour->tableName;
                echo html::a($this->createLink("manhour", "index", ""), '<i class="icon icon-file-text"></i> ' . $lang->manhour->tableName, '', "class='selected' title='$title'");
                ?>
            </div>
        </div>
    </div>
    <?php if($this->config->edition == 'open'):?>
        <div class='panel panel-body' style='padding: 10px 6px'>
            <div class='text proversion'>
                <strong class='text-danger small text-latin'>BIZ</strong> &nbsp;<span class='text-important'><?php echo (!empty($config->isINT)) ? $lang->manhour->proVersionEn : $lang->manhour->proVersion;?></span>
            </div>
        </div>
    <?php endif;?>
</div>
