<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CatalogList extends CBitrixComponent
{
    const DEFAULT_CACHE_TIME = 3600;

    private $iblocks;
    private $modules = ['iblock'];

    public function onPrepareComponentParams($arParams)
    {
        $this->init();
        $this->iblocks = getIblockIds();

        return $arParams;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        $cacheTime = empty($this->arParams['CACHE_TIME']) ? self::DEFAULT_CACHE_TIME : $this->arParams['CACHE_TIME'];
        $cacheId = 'list_iblock_id_' . $this->arParams['IBLOCK_ID'];

        if ($cache->read($cacheTime, $cacheId)) {
            $this->arResult = $cache->get($cacheId);
        } else {
            $sections = \Bitrix\Iblock\SectionTable::getList([
                'filter' => ['IBLOCK_ID' => $this->iblocks[IBLOCK_CATALOG_CODE], 'ACTIVE' => 'Y'],
                'order' => ['SORT' => 'asc', 'IBLOCK_SECTION_ID' => 'asc'],
                'select' => ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'CODE', 'SORT']
            ]);

            while ($section = $sections->fetch()) {
                $this->arResult['sections'][$section['ID']] = $section;

                if ($section['IBLOCK_SECTION_ID'] > 0) {
                    $this->arResult['sections'][$section['IBLOCK_SECTION_ID']]['subsections'][] = &$this->arResult['sections'][$section['ID']];
                } else {
                    $this->arResult['sections_tree'][$section['ID']] = &$this->arResult['sections'][$section['ID']];
                }
            }

            $this->arResult['HTML'] = $this->getSectionsTree($this->arResult['sections_tree']);

            $cache->set($cacheId, $this->arResult);
        }

        $this->includeComponentTemplate();
    }

    private function init()
    {
        foreach ($this->modules as $module) {
            try {
                \Bitrix\Main\Loader::includeModule($module);
            } catch (\Bitrix\Main\LoaderException $e) {
                ShowError($e->getMessage());
            }
        }
    }

    /**
     * @param $item
     * @return string
     */
    private function getSectionsTree($tree)
    {
        if (empty($tree)) return '';

        $html = '<ul>';

        foreach ($tree as $item) {
            $path = $this->arParams['SEF_FOLDER'] . $item['CODE'] . '/';
            $html .= '<li><a href="' . $path . '">' . $item['NAME'] . '</a>';

            if (!empty($item['subsections'])) {
                $html .= $this->getSectionsTree($item['subsections']);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}