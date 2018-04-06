<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AutoAddIblockElementPropertyCml2LinkToIb920180406145543726440 extends BitrixMigration
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
  'PROPERTY_TYPE' => 'E',
  'USER_TYPE' => 'SKU',
  'NAME' => 'Элемент каталога',
  'CODE' => 'CML2_LINK',
  'XML_ID' => 'CML2_LINK',
  'MULTIPLE' => 'N',
  'ACTIVE' => 'Y',
  'LINK_IBLOCK_ID' => 8,
  'IBLOCK_ID' => 9,
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
);

        $ibp = new CIBlockProperty();
        $propId = $ibp->add($fields);

        if (!$propId) {
            throw new MigrationException('Ошибка при добавлении свойства инфоблока '.$ibp->LAST_ERROR);
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
        $id = $this->getIblockPropIdByCode('CML2_LINK', 9);

        $ibp = new CIBlockProperty();
        $deleted = $ibp->delete($id);

        if (!$deleted) {
            throw new MigrationException('Ошибка при удалении свойства инфоблока '.$ibp->LAST_ERROR);
        }
    }
}
