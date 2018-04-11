<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class Catalog extends CBitrixComponent
{
    private $modules = ['iblock'];
    /**
     * шаблоны путей по умолчанию
     * @var array
     */
    protected $defaultUrlTemplates404 = array();

    /**
     * переменные шаблонов путей
     * @var array
     */
    protected $componentVariables = array();

    /**
     * страница шаблона
     * @var string
     */
    protected $page = 'index';

    public function onPrepareComponentParams($arParams)
    {
        foreach ($this->modules as $module) {
            try {
                \Bitrix\Main\Loader::includeModule($module);
            } catch (\Bitrix\Main\LoaderException $e) {
                ShowError($e->getMessage());
            }
        }

        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->setSefDefaultParams();
            $this->getResult();
            $this->includeComponentTemplate($this->page);
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

    /**
     * определяет переменные шаблонов и шаблоны путей
     */
    protected function setSefDefaultParams()
    {
        $this->defaultUrlTemplates404 = [
            'list' => 'index.php',
            'element' => '#ELEMENT_ID#.php'
        ];
        $this->componentVariables = $this->arParams['COMPONENT_VARIABLES'];
    }

    /**
     * получение результатов
     */
    protected function getResult()
    {
        $urlTemplates = [];

        if ($this->arParams['SEF_MODE'] == 'Y') {
            $variables = [];

            $urlTemplates = \CComponentEngine::MakeComponentUrlTemplates(
                $this->defaultUrlTemplates404,
                $this->arParams['SEF_URL_TEMPLATES']
            );

            $variableAliases = \CComponentEngine::MakeComponentVariableAliases(
                $this->defaultUrlTemplates404,
                $this->arParams['VARIABLE_ALIASES']
            );

            $engine = new CComponentEngine($this);
            $engine->addGreedyPart("#SECTION_CODE_PATH#");
            $engine->setResolveCallback(["CIBlockFindTools", "resolveComponentEngine"]);

            $this->page = $engine->guessComponentPath(
                $this->arParams['SEF_FOLDER'],
                $urlTemplates,
                $variables
            );

            if (strlen($this->page) <= 0) {
                $this->page = 'index';
            }

            \CComponentEngine::InitComponentVariables(
                $this->page,
                $this->componentVariables, $variableAliases,
                $variables
            );
        }

        $this->arResult = [
            'FOLDER' => $this->arParams['SEF_FOLDER'],
            'URL_TEMPLATES' => $urlTemplates,
            'VARIABLES' => $variables,
            'ALIASES' => $variableAliases
        ];
    }
}