<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
    <div>
        <h3>Гендерный состав аудитории:</h3>
        <hr>
<?php
    include 'data-base/personal-data.php';
    include 'library/auxiliary-functions.php';

    function getFullnameFromParts($family, $name, $patronymic) {
        return "$family" . ' ' . "$name" . ' ' . "$patronymic";
    }

    function getPartsFromFullname($familyNamePatr) {
        $keysFamNamePatr = ['family' , 'name', 'patronymic'];
        $valuesFamNamePatr = explode(' ', $familyNamePatr);
        return array_combine($keysFamNamePatr, $valuesFamNamePatr);
    }

    function getShortName($familyNamePatr) {
        $arrFamNamePatr = getPartsFromFullname($familyNamePatr);
        $subArrName = $arrFamNamePatr['name'];
        $subArrFam = $arrFamNamePatr['family'];
        $initOfTheFamily = mb_strtoupper(iconv_substr ($subArrFam, 0, 1, 'UTF-8')) . '.';
        return $subArrName . ' ' . $initOfTheFamily;
    }

   function  getGenderFromName($familyNamePatr) {
    $arrFamNamePatr = getPartsFromFullname($familyNamePatr);

    $subArrName = $arrFamNamePatr['name'];
    $subArrFam = $arrFamNamePatr['family'];
    $subArrPatr = $arrFamNamePatr['patronymic'];

    $totalSignOfGender = 0;

    if ((in_array(mb_substr(trim($subArrName), -1), array('й', 'н', 'д', 'р', 'а', 'в'))) &&
        (in_array(mb_substr(trim($subArrFam), -1), array('в', 'о', 'й', 'р', 'н', 'и' ))) && 
        (in_array(mb_substr(trim($subArrPatr), -2), array('ич', 'са', )))) {
        $totalSignOfGender++;       
    } elseif((in_array(mb_substr(trim($subArrName), -1), array('а', 'я', 'н'))) && 
            ((in_array(mb_substr(trim($subArrFam), -2), array('ва', 'ая', 'ой'))) || 
            (mb_substr($subArrFam, -1) === 'о')) && (mb_substr($subArrPatr, -3) === 'вна')) {
            $totalSignOfGender--;
    } else {
        $totalSignOfGender = 0;
    }

    return $totalSignOfGender;
   }

  function getGenderDescription($example_persons_array) {
    $arr = [];
   for($i = 0; $i < count($example_persons_array); $i++) {
    array_push($arr, getGenderFromName($example_persons_array[$i]['fullname']));
    }

    $numberOfMen = abs(array_sum(array_filter($arr, 'men')));
    $numberOfWomen = abs(array_sum(array_filter($arr, 'women')));

    $numOfMenAsPpercent = round(($numberOfMen * 100) / count($example_persons_array), 1);
    $numOfWomenAsPpercent = round(($numberOfWomen * 100) / count($example_persons_array), 1);
    $numOfIndetGendAsPpercent = 100 - ( $numOfMenAsPpercent +  $numOfWomenAsPpercent);
    
    return "<p>Мужчины - $numOfMenAsPpercent%</p>" . 
           "<p>Женщины - $numOfWomenAsPpercent%</p>" .
           "<p>Не удалось определить - $numOfIndetGendAsPpercent%</p>";
  }
  echo getGenderDescription($example_persons_array);

  function getPerfectPartner($family, $name, $patronymic, $example_persons_array) {
     $familyNamePatr = mb_convert_case(getFullnameFromParts($family, $name, $patronymic), MB_CASE_TITLE);
     $shortFamilyName = getShortName($familyNamePatr);
     $gender = getGenderFromName($familyNamePatr);
     $persons = array_rand($example_persons_array);
     $person = $example_persons_array[$persons]['fullname'];
     $shortPerFamilyName = getShortName($person);
     $genderPerson = getGenderFromName($person);
     $infAboutCompatOfPair = '';
     $compatibility = round(random_float(50, 100), 2);
     if($gender !== $genderPerson && $gender !== 0 && $genderPerson !== 0) {
        $infAboutCompatOfPair = "<hr><p>$shortFamilyName + $shortPerFamilyName = </p>" . 
        "<p>&#9825; идеально на $compatibility% &#9825;</p>";
     } else {
        $infAboutCompatOfPair = "<hr><p>&#9786; Немного терпения идёт процесс поиска &#9786;</p>";
     }
     return $infAboutCompatOfPair;
  }
  echo getPerfectPartner('ИвЛев', 'ЛЕв', 'СеМенович', $example_persons_array);
?> 
</div> 
</body>
</html>