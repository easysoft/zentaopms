<div class='block linkbox2'>
<table class='mainTable'>
  <tr>
    <td>
      <table class='headTable'>
        <caption>
          <div class='f-left'><span class='icon-bug'></span> <?php echo $lang->my->bug;?></div>
          <div class='f-right'><?php echo html::a($this->createLink('my', 'bug'), $lang->more . "<span class='icon-more'></span>");?></div>
        </caption>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <div class='contentDiv2'>
      <table class='table-1 fixed colored'>  
      <?php 
      foreach($bugs as $bugID => $bugTitle)
      {
          echo "<tr><td class='nobr'>" . "#$bugID " . html::a($this->createLink('bug', 'view', "id=$bugID"), $bugTitle) . "</td><td width='5'></td></tr>";
      }
      ?>
      </table>
      </div>
    </td>
  </tr>
</table>
</div>
