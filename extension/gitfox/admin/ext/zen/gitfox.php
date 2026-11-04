<?php
/**
 * Sync GitFox dynamics from the GitFox website.
 *
 * @param  int    $limit
 * @access protected
 * @return bool
 */
protected function syncDynamics(int $limit = 2): bool
{
    if($this->config->vision != 'gitfox' && $this->config->edition != 'gitfox') return parent::syncDynamics($limit);

    $result   = json_decode(preg_replace('/[[:cntrl:]]/mu', '', common::http($this->config->admin->dynamicAPIURL)));
    $dynamics = array();

    $index = 1;
    if(!empty($result->data->release))
    {
        foreach($result->data->release as $release)
        {
            if($index > $limit) break;

            $dynamic = new stdClass();
            $dynamic->id        = $release->version;
            $dynamic->title     = $release->content;
            $dynamic->addedDate = $release->time;
            $dynamic->link      = $release->link;

            $dynamics[$index] = $dynamic;
            $index++;
        }
    }

    return $this->loadModel('setting')->setItem('system.common.zentaoWebsite.dynamic', json_encode($dynamics));
}
