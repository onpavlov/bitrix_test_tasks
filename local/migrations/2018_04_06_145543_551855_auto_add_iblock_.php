<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AutoAddIblock20180406145543551855 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $fields = array (
  'ACTIVE' => 'Y',
  'NAME' => 'Торговые предложения',
  'CODE' => 'catalog_prices',
  'IBLOCK_TYPE_ID' => 'content',
  'LID' => 
  array (
    0 => 's1',
  ),
  'WORKFLOW' => 'N',
  'BIZPROC' => 'N',
  'LIST_PAGE_URL' => '',
  'SECTION_PAGE_URL' => '',
  'DETAIL_PAGE_URL' => '#PRODUCT_URL#',
  'INDEX_SECTION' => 'N',
  'RIGHTS_MODE' => 'S',
  'GROUP_ID' => 
  array (
    2 => 'R',
    1 => 'X',
    3 => '',
    4 => '',
  ),
  'FIELDS' => 
  array (
    'LOG_SECTION_ADD' => 
    array (
      'IS_REQUIRED' => 'N',
    ),
    'LOG_SECTION_EDIT' => 
    array (
      'IS_REQUIRED' => 'N',
    ),
    'LOG_SECTION_DELETE' => 
    array (
      'IS_REQUIRED' => 'N',
    ),
    'LOG_ELEMENT_ADD' => 
    array (
      'IS_REQUIRED' => 'N',
    ),
    'LOG_ELEMENT_EDIT' => 
    array (
      'IS_REQUIRED' => 'N',
    ),
    'LOG_ELEMENT_DELETE' => 
    array (
      'IS_REQUIRED' => 'N',
    ),
  ),
  'INDEX_ELEMENT' => 'Y',
  'SECTION_CHOOSER' => 'L',
  'DESCRIPTION_TYPE' => 'text',
  'VERSION' => '1',
);

        $ib = new CIBlock();
        $id = $ib->add($fields);

        if (!$id) {
            throw new MigrationException('Ошибка при добавлении инфоблока '.$ib->LAST_ERROR);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function down()
    {
        $id = $this->getIblockIdByCode('');

        $this->db->startTransaction();
        if (!CIBlock::delete($id)) {
            $this->db->rollbackTransaction();
            throw new MigrationException('Ошибка при удалении инфоблока');
        }

        $this->db->commitTransaction();
    }
}
