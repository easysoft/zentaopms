<?php
class chartZen extends chart
{
    /**
     * Set pivot Menu of a dimension.
     *
     * @param  int    $dimension
     * @access protected
     * @return void
     */
    protected function setFeatureBar($dimension)
    {
        if(!$dimension) return false;

        $groups = $this->loadModel('tree')->getGroupPairs($dimension, 0, 1);
        if(!$groups) return false;

        $this->lang->chart->featureBar['preview'] = array();
        foreach($groups as $groupID => $groupName)
        {
            if(empty($groupID) || empty($groupName)) continue;
            $this->lang->chart->featureBar['preview'][$groupID] = $groupName;
        }
    }
}
