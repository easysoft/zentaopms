<?php
/**
 * The roadmap view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<table class='table-6' align='center'>
  <caption><?php echo $lang->product->roadmap;?></caption>
  <?php 
  $years = array_keys($roadmaps);
  echo '<tr class="f-16px">';
  foreach($years as $year)
  {
      $year = $year == '0000' ? $lang->feature : $year . $lang->year;
      echo "<th>$year</th>";
  }
  echo '</tr>';
  echo '<tr valign="top">';
  foreach($years as $year)
  {
      echo '<td class="a-center">';
      foreach($roadmaps[$year] as $key => $roadmap)
      {
          if(isset($roadmap->build))
          {
              echo "<div class='roadmap release'>";
              echo "<p class='f-16px'>" . html::a($this->createLink('release', 'view', "releaseID=$roadmap->id"), $roadmap->name, '_blank') . '</p>' . $roadmap->date;
          }
          else
          {
              echo "<div class='roadmap plan'>";
              echo "<p class='f-16px'>" . html::a($this->createLink('productplan', 'view', "planID=$roadmap->id"), $roadmap->title, '_blank') . '</p>' . $roadmap->begin . ' ~ ' . $roadmap->end;
          }
          echo "</div>";
          if(isset($roadmaps[$year][$key + 1])) echo "<span class='f-16px'>{$lang->downArrow}</span>";
      }
      echo '</td>';
  }
  echo '</tr>';
  ?>
</table>
<?php include '../../common/view/footer.html.php';?>
