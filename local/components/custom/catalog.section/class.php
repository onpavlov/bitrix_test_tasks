<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CatalogSection extends CBitrixComponent
{
    const DEFAULT_CACHE_TIME = 3600;

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        $cacheTime = empty($this->arParams['CACHE_TIME']) ? self::DEFAULT_CACHE_TIME : $this->arParams['CACHE_TIME'];
        $cacheId = 'iblock_id_' . $this->arParams['IBLOCK_ID'];

        if ($cache->read($cacheTime, $cacheId)) {
            $this->arResult = $cache->get($cacheId);
        } else {

            $cache->set($cacheId, $this->arResult);
        }

        $this->includeComponentTemplate();
    }
}