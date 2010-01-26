<div id='tabbar' class='yui-d0' style='clear:right'>
  <ul>
    <?php
    echo "<li><nobr>{$app->user->realname}</nobr></li>";
    echo "<li id='todotab'><nobr>"   . html::a($this->createLink('my', 'todo'),    $lang->my->todo)    . "</nobr></li>";
    echo "<li id='tasktab'><nobr>"   . html::a($this->createLink('my', 'task'),    $lang->my->task)    . "</nobr></li>";
    echo "<li id='projecttab'><nobr>". html::a($this->createLink('my', 'project'), $lang->my->project) . "</nobr></li>";
    echo "<li id='storytab'><nobr>"  . html::a($this->createLink('my', 'story'),   $lang->my->story)   . "</nobr></li>";
    echo "<li id='bugtab'><nobr>"    . html::a($this->createLink('my', 'bug'),     $lang->my->bug)     . "</nobr></li>";
    echo "<li id='teamtab'><nobr>"   . html::a($this->createLink('my', 'team'),    $lang->my->team)    . "</nobr></li>";
    echo <<<EOT
<script language="Javascript">
$("#{$tabID}tab").addClass('active');
</script>
EOT;
    ?>
</ul>
</div>
