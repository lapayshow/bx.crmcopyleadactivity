<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Localization\Loc;

$arActivityDescription = [
	'NAME' => 'Копирование лида с таймлайном',
	'DESCRIPTION' => 'Копирование лида с таймлайном',
	'TYPE' => ['activity'],
	'CLASS' => 'CrmCopyLeadActivity',
	'JSCLASS' => 'BizProcActivity',
	'CATEGORY' => [
		'ID' => 'document',
	],
	'RETURN' => [
        'NewLeadId' => [
            'NAME' => 'ID нового Лида',
            'TYPE' => 'string',
        ],
    ],
	'FILTER' => [
		'EXCLUDE' => [
			['tasks'],
		],
	],
];
