<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
?>

<? $APPLICATION->IncludeComponent(
        'custom:catalog',
        '',
        [
            'IBLOCK_ID' => '',
            'SEF_MODE' => 'Y',
            'SEF_FOLDER' => '/products/',
            'CACHE_TIME' => 3600,
            'SEF_URL_TEMPLATES' => [
                'list' => 'index.php',
                'section' => '#SECTION_CODE#/'
            ],
            'COMPONENT_VARIABLES' => ['SECTION_CODE']
        ]
) ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>