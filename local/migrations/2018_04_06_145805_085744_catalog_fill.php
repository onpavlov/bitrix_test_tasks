<?php
$_SERVER["DOCUMENT_ROOT"] = __DIR__ . '/../../';
require($_SERVER["DOCUMENT_ROOT"] . "bitrix/modules/main/include/prolog_before.php");

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CatalogFill20180406145805085744 extends BitrixMigration
{
    const IBLOCK_CATALOG_CODE = 'catalog';
    const IBLOCK_CATALOG_PRICE_CODE = 'catalog_prices';

    private $iblockCatalogId;
    private $iblockCatalogPricesId;

    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $this->init();

        $sections = [
            [
                'NAME' => 'Коляски',
                'CODE' => 'strollers',
                'SUBSECTIONS' => [
                    [
                        'NAME' => 'Коляски 2 в 1',
                        'CODE' => 'strollers_2_1',
                    ],
                    [
                        'NAME' => 'Коляски 3 в 1',
                        'CODE' => 'strollers_3_1',
                    ],
                    [
                        'NAME' => 'Коляски для новорожденных',
                        'CODE' => 'strollers_newborn',
                        'SUBSECTIONS' => [
                            [
                                'NAME' => 'Коляски на прогулочной раме',
                                'CODE' => 'strollers_on_chassis',
                                'SUBSECTIONS' => [
                                    [
                                        'NAME' => 'Коляски с кожей',
                                        'CODE' => 'strollers_vip'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if (!$this->hasSections($this->iblockCatalogId)) {
            $this->createSections($sections, $this->iblockCatalogId);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $this->init();

        $elements = \Bitrix\Iblock\ElementTable::getList([
            'filter' => ['IBLOCK_ID' => [$this->iblockCatalogId, $this->iblockCatalogPricesId]],
            'select' => ['ID']
        ]);

        while ($element = $elements->fetch()) {
            CIBlockElement::Delete($element['ID']);
        }

        $sections = \Bitrix\Iblock\SectionTable::getList([
            'filter' => ['IBLOCK_ID' => [$this->iblockCatalogId, $this->iblockCatalogPricesId]],
            'select' => ['ID']
        ]);

        while ($section = $sections->fetch()) {
            CIBlockSection::Delete($section['ID']);
        }
    }

    /**
     * @throws MigrationException
     * @throws \Bitrix\Main\LoaderException
     */
    private function init()
    {
        $modules = ['iblock'];

        foreach ($modules as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) {
                throw new MigrationException('Не удалось подключить модуль ' . $module);
            }
        }

        $this->iblockCatalogId = getIblockIdByCode(self::IBLOCK_CATALOG_CODE);
        $this->iblockCatalogPricesId = getIblockIdByCode(self::IBLOCK_CATALOG_PRICE_CODE);
    }

    /**
     * @param $sections
     * @param $iblockId
     * @param int $parentSection
     * @throws MigrationException
     */
    public function createSections($sections, $iblockId, $parentSection = 0)
    {
        $s = new CIBlockSection();

        foreach ($sections as $section) {
            $section['IBLOCK_ID'] = $iblockId;
            $section['ACTIVE'] = 'Y';

            if ($parentSection > 0) {
                $section['IBLOCK_SECTION_ID'] = $parentSection;
            }

            if (!$sectionId = $s->Add($section)) {
                throw new MigrationException('Ошибка при добавлении секции ' . $section['NAME'] . ' ' . $s->LAST_ERROR);
            }

            if (!empty($section['SUBSECTIONS'])) {
                $this->createSections($section['SUBSECTIONS'], $iblockId, $sectionId);
            }
        }
    }

    /**
     * @param $iblockId
     * @return bool
     */
    public function hasSections($iblockId)
    {
        return !empty(\Bitrix\Iblock\SectionTable::getRow(['filter' => ['IBLOCK_ID' => $iblockId], 'select' => ['ID']]));
    }
}
