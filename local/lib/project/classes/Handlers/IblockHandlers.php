<?php

namespace Project\Handlers;

class IblockHandlers
{
    /**
     * Сбрасывает кеш по тегу
     *
     * @param $params
     * @throws \Bitrix\Main\SystemException
     */
    public static function onAfterIBlockElementUpdate($params)
    {
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        $cache->clean('iblock_id_' . $params['IBLOCK_ID']);
    }
}