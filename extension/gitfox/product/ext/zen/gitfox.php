<?php
/**
 * GitFox 编辑页只提交部分字段，未提交字段保持数据库原值，避免被表单默认值覆盖。
 * Build edit data of product module for the GitFox vision.
 *
 * @param  int $productID
 * @param  int $workflowGroup
 * @access public
 * @return object
 */
public function buildProductForEdit(int $productID, int $workflowGroup = 0): object
{
    $productData = parent::buildProductForEdit($productID, $workflowGroup);
    if($this->config->vision == 'gitfox')
    {
        $product = $this->product->fetchByID($productID);
        if($product)
        {
            foreach($this->config->product->form->edit as $field => $fieldConfig)
            {
                if($this->post->$field !== false) continue;
                if(!property_exists($product, $field)) continue;

                $productData->$field = $product->$field;
            }
        }
    }

    return $productData;
}

/**
 * GitFox 的“应用”编辑保存后同步 zt_system 并返回应用列表。
 * The edit response of product module for the GitFox vision.
 *
 * @param  int $productID
 * @param  int $programID
 * @access public
 * @return array
 */
public function responseAfterEdit(int $productID, int $programID): array
{
    $response = parent::responseAfterEdit($productID, $programID);
    if($this->config->vision == 'gitfox')
    {
        $product = $this->product->fetchByID($productID);
        if($product) $this->product->updateSystemsByProduct($productID, $product->name);

        $spaceID = (int)$this->get->spaceID;
        $response['load'] = $this->createLink('product', 'browse', $spaceID ? "spaceID={$spaceID}" : '');
    }

    return $response;
}
