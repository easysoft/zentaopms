<?php
/**
 * The roadmap view file of product module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div class='yui-d0'>
  <table class='table-1'>
    <caption><?php echo $lang->product->roadmap;?></caption>
    <?php 
    $years = array_keys($roadmaps);
    echo '<tr class="f-16px">';
    foreach($years as $year)
    {
        if($year == '0000') $year = $lang->feature;
        echo "<th>$year$lang->year</th>";
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
</div>
<?php include '../../common/footer.html.php';?>
