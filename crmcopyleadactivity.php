<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Crm\Timeline\Entity\TimelineBindingTable;

/** @property-write string|null ErrorMessage */
class CBPCrmCopyLeadActivity extends CBPActivity
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->arProperties = [
			'Title' => '',
            'LeadId' => null,  // ID лида
            'NewLeadId' => null, // ID нового лида
            'TargetStageId' => null, // ID новой стадии
		];

		$this->setPropertiesTypes([
            'LeadId' => array(
                'Type' => 'string',
            ),
            'NewLeadId' => array(
                'Type' => 'string',
            ),
            'TargetStageId' => array(
                'Type' => 'string',
            ),
			'ErrorMessage' => [
                'Type' => 'string'
            ],
		]);
	}

	public function Execute()
	{
        if (\Bitrix\Main\Loader::includeModule('crm')) {
            $CCRMLead = new CCRMLead();

            preg_match('/\d+/', $this->__get('LeadId'), $matches);
            $leadId = (string)$matches[0];

            // Получаем данные старого лида
            $arFields = $CCRMLead::GetList(
                [],
                [
                    'ID' => $leadId,
                ],
                [],
                false
            )->Fetch();

            $arFields['TITLE'] = $arFields['TITLE'] . ' (Копия)';

            // Получаем новый тип стадии
            $TargetStageId = $this->__get('TargetStageId');
            $arFields['STATUS_ID'] = $TargetStageId;

            // Создаём нового лида
            $newLeadId = $CCRMLead->Add($arFields);

            // Возвращаем ID нового лида в RETURN
            $this->NewLeadId = $newLeadId;
        }

        return CBPActivityExecutionStatus::Closed;
	}

    public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues = null, $formName = "")
    {
        if (!is_array($arCurrentValues)) {
            $arCurrentValues = array(
                'LeadId' => null,
                'TargetStageId' => null,
            );

            $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName(
                $arWorkflowTemplate, $activityName);
            if (is_array($arCurrentActivity['Properties'])) {
                $arCurrentValues = array_merge($arCurrentValues,
                    $arCurrentActivity['Properties']);
            }
        }

        $runtime = CBPRuntime::GetRuntime();
        return $runtime->ExecuteResourceFile(__FILE__, "properties_dialog.php",
            array(
                "arCurrentValues" => $arCurrentValues,
                "formName" => $formName
            ));
    }

    public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
    {
        $arProperties = array(
            'LeadId' => $arCurrentValues['LeadId'],
            'TargetStageId' => $arCurrentValues['TargetStageId'],
        );

        $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName(
            $arWorkflowTemplate,
            $activityName
        );

        if (empty($arCurrentValues['LeadId']) || empty($arCurrentValues['TargetStageId']))
        {
            $arErrors[] = [
                'code'    => 'emptyRequiredField',
                'message' => 'Заполнены не все поля',
            ];
        }

        if (!empty($arErrors))
            return false;

        $arCurrentActivity['Properties'] = $arProperties;

        return true;
    }
}
