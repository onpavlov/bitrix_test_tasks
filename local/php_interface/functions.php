<?php

if(!function_exists('getIblockIdByCode')) {
    function getIblockIdByCode($iblockCode)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $iblock = \Bitrix\Iblock\IblockTable::getList(['filter' => ['CODE' => $iblockCode], 'select' => ['ID']])->fetch();

        return $iblock['ID'] ? $iblock['ID'] : false;
    }
}

if(!function_exists('getIblockIds')) {
    function getIblockIds()
    {
        $result = [];
        $iblockCodes = [
            IBLOCK_CATALOG_CODE,
            IBLOCK_CATALOG_PRICES_CODE
        ];

        $cache = \Bitrix\Main\Application::getInstance()->getCache();
        $cacheTime = 86400;
        $cachePath = '/iblocks/';
        $cacheId = serialize($iblockCodes);

        if ($cache->initCache($cacheTime, $cacheId, $cachePath)) {
            $result = $cache->getVars();
        } else {
            $cache->startDataCache();

            $iblocks = \Bitrix\Iblock\IblockTable::getList([
                'filter' => ['CODE' => $iblockCodes, 'ACTIVE' => 'Y'],
                'select' => ['ID', 'CODE']
            ]);

            while ($iblock = $iblocks->fetch()) {
                $result[$iblock['CODE']] = $iblock['ID'];
            }

            $cache->endDataCache($result);
        }

        return $result;
    }
}