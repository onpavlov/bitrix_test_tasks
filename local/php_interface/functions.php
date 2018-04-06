<?php

if(!function_exists('getIblockIdByCode')) {
    function getIblockIdByCode($iblockCode) {
        \Bitrix\Main\Loader::includeModule('iblock');
        $iblock = \Bitrix\Iblock\IblockTable::getList(['filter' => ['CODE' => $iblockCode], 'select' => ['ID']])->fetch();

        return $iblock['ID'] ? $iblock['ID'] : false;
    }
}