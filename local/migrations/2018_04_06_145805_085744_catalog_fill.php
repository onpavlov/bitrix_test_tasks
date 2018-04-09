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
    private $sectionsIds;
    private $catalogPriceGroupId;

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

        $elements = [
            [
                'CODE' => 'element1',
                'NAME' => 'Коляска 1',
                'SECTION_CODE' => 'strollers_2_1',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска 1 красная',
                        'PRICE' => 500
                    ],
                    [
                        'NAME' => 'Коляска 1 синяя',
                        'PRICE' => 600
                    ],
                    [
                        'NAME' => 'Коляска 1 золотая',
                        'PRICE' => 700
                    ],
                ]
            ],
            [
                'CODE' => 'element2',
                'NAME' => 'Коляска 2',
                'SECTION_CODE' => 'strollers_2_1',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска 2 желтая',
                        'PRICE' => 5000
                    ],
                    [
                        'NAME' => 'Коляска 2 бирюзовая',
                        'PRICE' => 6000
                    ],
                    [
                        'NAME' => 'Коляска 2 платиновая',
                        'PRICE' => 7000
                    ],
                ]
            ],
            [
                'CODE' => 'element3',
                'NAME' => 'Коляска 3',
                'SECTION_CODE' => 'strollers_3_1',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска 3 с карманом под пиво',
                        'PRICE' => 3000
                    ],
                    [
                        'NAME' => 'Коляска 3 обычная',
                        'PRICE' => 1000
                    ]
                ]
            ],
            [
                'CODE' => 'element4',
                'NAME' => 'Коляска 4',
                'SECTION_CODE' => 'strollers_3_1',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска 4 с противоугонной системой',
                        'PRICE' => 3500
                    ],
                    [
                        'NAME' => 'Коляска 4 с антирадаром',
                        'PRICE' => 3000
                    ]
                ]
            ],
            [
                'CODE' => 'element5',
                'NAME' => 'Коляска 5',
                'SECTION_CODE' => 'strollers_3_1',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска 5 с GPS',
                        'PRICE' => 4000
                    ],
                    [
                        'NAME' => 'Коляска 5 с ГЛОНАСС',
                        'PRICE' => 3800
                    ]
                ]
            ],
            [
                'CODE' => 'element6',
                'NAME' => 'Коляска кожаная 1',
                'SECTION_CODE' => 'strollers_vip',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска кожаная 1 из кожи аллигатора',
                        'PRICE' => 50000
                    ],
                    [
                        'NAME' => 'Коляска кожаная 1 из кожи северного оленя',
                        'PRICE' => 30000
                    ]
                ]
            ],
            [
                'CODE' => 'element7',
                'NAME' => 'Коляска кожаная 2',
                'SECTION_CODE' => 'strollers_vip',
                'VARIANTS' => [
                    [
                        'NAME' => 'Коляска кожаная 2 со стразами',
                        'PRICE' => 20000
                    ],
                    [
                        'NAME' => 'Коляска кожаная 2 с золотым напылением',
                        'PRICE' => 40000
                    ]
                ]
            ],
            [
                'CODE' => 'element8',
                'NAME' => 'Коляска простая 1',
                'SECTION_CODE' => 'strollers_on_chassis',
                'PRICE' => 500
            ],
            [
                'CODE' => 'element9',
                'NAME' => 'Коляска простая 2',
                'SECTION_CODE' => 'strollers_on_chassis',
                'PRICE' => 700
            ],
            [
                'CODE' => 'element10',
                'NAME' => 'Коляска простая 3',
                'SECTION_CODE' => 'strollers_on_chassis',
                'PRICE' => 900
            ],
            [
                'CODE' => 'element11',
                'NAME' => 'Коляска простая 4',
                'SECTION_CODE' => 'strollers_on_chassis',
                'PRICE' => 1100
            ],
        ];

        if (!$this->hasSections($this->iblockCatalogId)) {
            $this->createSections($sections, $this->iblockCatalogId);
        }

        if (!$this->hasElements($this->iblockCatalogId)) {
            if (empty($this->catalogPriceGroupId)) {
                CCatalogGroup::Add([
                    'NAME' => 'BASE',
                    'BASE' => 'Y',
                    'XML_ID' => 'BASE',
                    'USER_GROUP' => [1, 2, 3, 4],
                    'USER_GROUP_BUY' => [1, 2, 3, 4],
                    'USER_LANG' => [
                        'ru' => 'Базовая',
                        'en' => 'Base'
                    ]
                ]);
            }
            $this->createElements($elements, $this->iblockCatalogId, $this->iblockCatalogPricesId);
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
        $modules = ['iblock', 'catalog'];

        foreach ($modules as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) {
                throw new MigrationException('Не удалось подключить модуль ' . $module);
            }
        }

        $catalogGroup = CCatalogGroup::GetList([], ['CODE' => 'BASE'], false, false, ['ID'])->Fetch();

        $this->catalogPriceGroupId = $catalogGroup['ID'];
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
     * @param $elements
     * @param $iblockElementsId
     * @param int $iblockPricesId
     * @param int $parentElementId
     * @throws MigrationException
     * @throws \Bitrix\Main\ArgumentException
     */
    public function createElements($elements, $iblockElementsId, $iblockPricesId = 0, $parentElementId = 0)
    {
        $el = new CIBlockElement();

        if (empty($this->sectionsIds)) {
            $sections = \Bitrix\Iblock\SectionTable::getList([
                'filter' => ['IBLOCK_ID' => $iblockElementsId],
                'select' => ['ID', 'CODE', 'NAME']
            ]);

            while ($section = $sections->fetch()) {
                $this->sectionsIds[$section['CODE']] = $section;
            }
        }

        foreach ($elements as $element) {
            $fields = [
                'NAME' => $element['NAME'],
                'CODE' => $element['CODE'],
                'IBLOCK_ID' => $iblockElementsId,
                'ACTIVE' => 'Y',
                'IBLOCK_SECTION_ID' => $this->sectionsIds[$element['SECTION_CODE']]['ID']
            ];

            if ($parentElementId > 0) { // привязка к товару
                $fields['PROPERTY_VALUES'] = ['CML2_LINK' => $parentElementId];
            }

            $elementId = $el->Add($fields);

            if (!$elementId) {
                throw new MigrationException('Ошибка добавления элемента ' . $element['NAME'] . ' ' . $el->LAST_ERROR);
            }

            if (CCatalogProduct::add(["ID" => $elementId]) && !empty($element['PRICE'])) {
                // добавляем цену если это ТП
                $priceData = [
                    'PRODUCT_ID' => $elementId,
                    'CATALOG_GROUP_ID' => $this->catalogPriceGroupId, // тип цены BASE
                    'PRICE' => $element['PRICE'],
                    'CURRENCY' => "RUB",
                ];

                if (CPrice::Add($priceData)) {
                    CCatalogProduct::Update($element['ID'], ['QUANTITY_TRACE' => 'N']);
                } else {
                    throw new MigrationException('Ошибка добавления цены для ТП ' . $element['NAME']);
                }
            }

            if (!empty($element['VARIANTS']) && $iblockPricesId != 0) {
                // Если есть ТП - добавляем
                $this->createElements($element['VARIANTS'], $iblockPricesId, 0, $elementId);
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

    /**
     * @param $iblockId
     * @return bool
     */
    public function hasElements($iblockId)
    {
        return !empty(\Bitrix\Iblock\ElementTable::getRow(['filter' => ['IBLOCK_ID' => $iblockId], 'select' => ['ID']]));
    }
}
