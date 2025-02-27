<?php

$areasToJson = [];

foreach ($areas as $area) {
    $areasToJson[] = ['id' => $area->id, 'title' => $area->title];
}

$json['areas'] = $areasToJson;
$json['pagination'] = [
    'page'                       => $paginator->getPage(),
    'per_page'                   => $paginator->perPage(),
    'total_of_pages'             => $paginator->totalOfPages(),
    'total_of_registers'         => $paginator->totalOfRegisters(),
    'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
