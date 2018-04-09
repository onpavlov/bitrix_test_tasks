<?php

$eventManager = \Bitrix\Main\EventManager::getInstance();

// Обновление элемента инфоблока
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [
    '\\Project\\Handlers\\IblockHandlers',
    'onAfterIBlockElementUpdate'
]);

// Добавление элемента инфоблока
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [
    '\\Project\\Handlers\\IblockHandlers',
    'onAfterIBlockElementUpdate'
]);

// Удаление элемента инфоблока
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementDelete', [
    '\\Project\\Handlers\\IblockHandlers',
    'onAfterIBlockElementUpdate'
]);