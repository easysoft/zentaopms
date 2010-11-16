<?php
/**
 * The model file of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     index
 * @version     $Id$
 */
?>
<?php
class indexModel extends model
{
    /**
     * Print stats.
     * 
     * @param  string $statusList 
     * @param  string $stats 
     * @access public
     * @return void
     */
    public function printStats($statusList, $stats)
    {
        global $lang;
        $sum = array_sum($stats);
        $string = sprintf($lang->index->total, $sum);
        foreach($stats as $status => $value)
        {
            $percent = round($value / $sum, 2) * 100 . '%';
            $string .= strtolower($statusList[$status]) . " <strong>$value<small><i>($percent)</i></small></strong>$lang->comma ";
        }
        echo rtrim($string, $lang->comma) . $lang->dot;
    }
}
