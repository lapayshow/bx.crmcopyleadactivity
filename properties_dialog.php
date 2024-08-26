<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$entityTypeId = \CCrmOwnerType::Lead;
$factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);

foreach ($factory->getStages() as $stage) {
    $listDefaultEntity[$stage->getStatusId()] = $stage->getName();
}

$currentEntityType = !empty($arCurrentValues['TargetStageId']) ? $arCurrentValues['TargetStageId'] : '';
?>

<tr>
    <td align="right" width="40%">
        <span style="font-weight: bold">Перенести на стадию</span>
    </td>
    <td width="60%">
        <select name="TargetStageId">
            <option value="">Выберите новую стадию Лида</option>
            <?php foreach($listDefaultEntity as $entityType => $entityName):?>
                <option value="<?=htmlspecialcharsbx($entityType)?>"
                    <?=($currentEntityType == $entityType) ? 'selected' : ''?>>
                    <?=htmlspecialcharsbx($entityName)?>
                </option>
            <?php endforeach;?>
        </select>
    </td>
</tr>

<!-- Поле для указания родительского документа элемента CRM -->
<tr>
  <td align="right"><span class="adm-required-field"><?= 'ID Лида' ?>:</span></td>
  <td>
    <?= CBPDocument::ShowParameterField("string", 'LeadId', $arCurrentValues['LeadId'], array("size"=>"5"))?>
  </td>
</tr>
