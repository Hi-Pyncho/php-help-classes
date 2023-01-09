<?php

class JBitrix {
  static function includeBitrix() {
    require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
    CModule::IncludeModule("iblock");
  }

  function includeFile($path, $options) {
    $APPLICATION->IncludeFile($path, $options, ['MODE' => 'php']);
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

  static function getInfoByIblockIdandSectionID($iblockId, $sectionId = '') {
    CModule::IncludeModule("iblock");
  
    $info = [];
  
    $arSelect = Array('ID', 'IBLOCK_ID', '*', 'PROPERTY_*');
    $arFilter = Array('IBLOCK_ID' => IntVal($iblockId));

    if(!empty($sectionId)) {
      array_push($arFilter, Array('IBLOCK_SECTION_ID' => intval($sectionId)));
    }

    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
  
    while($ob = $res->GetNextElement()){ 
      $info_item['props'] = $ob->GetProperties();
      $info_item['fields'] = $ob->GetFields();

      array_push($info, $info_item);
    }
  
    return $info;
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
