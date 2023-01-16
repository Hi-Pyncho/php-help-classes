<?php

class BXHelper {
  static function includeBitrix() {
    require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
    CModule::IncludeModule("iblock");
  }

  static function getListByParams($iblockId, $filter = [], $select = [], $order = []) {
    CModule::IncludeModule("iblock");

    $arSelect = array_merge(["ID", "IBLOCK_ID", "*", "PROPERTY_*"], $select);
    $arFilter = array_merge(["IBLOCK_ID" => $iblockId, "ACTIVE" => "Y"], $filter);
 
    $res = CIBlockElement::GetList($order, $arFilter, false, ["nPageSize"=>500], $arSelect);
    $result = [];

    while($ob = $res->GetNextElement()) {
      array_push($result, [
        'fields' => $ob->GetFields(),
        'props' => $ob->GetProperties(),
      ]);
    }

    return $result;
  }

  static function getSectionInfoById($sectionId, $iblockId) {
    $uf_arresult = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => $iblockId, "ID" => $sectionId), false, Array('*', 'UF_*'));

    if($uf_value = $uf_arresult->GetNext()) {
      return $uf_value;
    }
  }

  static function getSectionInfoByCode($code, $iblockId) {
    $uf_arresult = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => $iblockId, "CODE" => $code), false, Array('*', 'UF_*'));

    if($uf_value = $uf_arresult->GetNext()) {
      return $uf_value;
    }
  }

  static function getResizedImagePath($id, $width = 1000, $height = 1000) {
    $image = CFile::ResizeImageGet($id, array('width' => $width, 'height' => $height), BX_RESIZE_IMAGE_PROPORTIONAL_ALT , true);

    return $image['src'];
  }

  static function createElementBlock($iblockId, $name, $props) {
    $element = new \CIBlockElement();
    $options = [
      'IBLOCK_ID' => $iblockId,
      'NAME' => $name,
      'PROPERTY_VALUES' => $props,
    ];

    $res = $element->Add($options);

    return $res;
  }
}
