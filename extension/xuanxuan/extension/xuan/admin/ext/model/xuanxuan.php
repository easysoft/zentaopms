<?php
public function blockStatus($block = null)
{
    return $this->loadExtension('xuanxuan')->blockStatus($block);
}

public function blockStatistics($block = null)
{
    return $this->loadExtension('xuanxuan')->blockStatistics($block);
}

public function getXxcAllFileSize(): string
{
    return $this->loadExtension('xuanxuan')->getXxcAllFileSize();
}
