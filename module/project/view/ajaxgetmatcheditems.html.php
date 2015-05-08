<div class='search-list'>
  <ul>
  <?php if(!$projects) echo "<li class='no-result-tip'>" . sprintf($lang->project->noMatched, $keywords) . '</li>';?>
  <?php
  foreach($projects as $project)
  {
      echo "<li>" . html::a(sprintf($link, $project->id), "<i class='icon-cube'></i> " . $project->name, '', "class='$project->status'"). "</li>";
  }
  ?>
  </ul>
</div>
