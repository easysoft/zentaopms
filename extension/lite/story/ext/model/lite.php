<?php
public function getExecutionStoryPairs(int $executionID = 0, int $productID = 0, string|int $branch = 'all', array|string|int $moduleIdList = 0, string $type = 'full', string $status = 'all', string $storyType = 'story'): array
{
    if($this->config->vision == 'lite')
    {
        $execution = $this->loadModel('execution')->getById($executionID);
        if($execution->project) $executionID = $execution->project;
    }
    return parent::getExecutionStoryPairs($executionID, $productID, $branch, $moduleIdList, $type, $status);
}
