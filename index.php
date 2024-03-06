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
    /*Функция, которая  принимает как аргумент три строки — фамилию, имя и отчество, а
     возвращает как результат их же, но склеенные через пробел.*/
    function getFullnameFromParts($surname, $name, $patronomyc) {
        return $surname . ' ' . $name . ' ' . $patronomyc;
    }
    /*Функция, которая  принимает как аргумент одну строку — склеенное ФИО, а возвращает как 
    результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.*/
    function getPartsFromFullname($surNamePatr) {
            $keyArrSurNamePatr = ['surname', 'name', 'patronomyc'];
            $valueArrSurNamePatr = explode(' ', $surNamePatr);
        return array_combine($keyArrSurNamePatr, $valueArrSurNamePatr);
    }
    /*Функция, которая принимает как аргумент строку, содержащую ФИО вида "Иванов Иван Иванович" 
    и возвращающую строку вида "Иван И.", где сокращается фамилия и отбрасывается отчество.*/
    function getShortName($surNamePatr) {
        $arrSurNamePatr = getPartsFromFullname($surNamePatr);
        return $arrSurNamePatr['name'] . ' ' . mb_substr($arrSurNamePatr['surname'], 0, 1) . '.';
    }

    function getGenderFromName($surNamePatr) {
        //делим ФИО на составляющие с помощью функции getPartsFromFullname
        $arrSurNamePatr = getPartsFromFullname($surNamePatr);
        //Первоначально "суммарный признак пола" равен 0
        $totalSignOfGender = 0;
        /*В имени, фамилии и отчестве определяет признаки мужского и женского пола согласно
        ТЗ, если присутствует признак мужского пола прибавляем единицу к суммарному признаку пола
        если присутствует признак женского пола отнимаем единицу от суммарного признака пола*/
        if(mb_substr($arrSurNamePatr['surname'], -1) === 'в') $totalSignOfGender++;
        elseif(mb_substr($arrSurNamePatr['surname'], -2) === 'ва') $totalSignOfGender--;

        if(mb_substr($arrSurNamePatr['name'], -1) === 'й' || 
            (mb_substr($arrSurNamePatr['name'], -1) === 'н')) $totalSignOfGender++;
        elseif(mb_substr($arrSurNamePatr['name'], -1) === 'а') $totalSignOfGender--; 

        if(mb_substr($arrSurNamePatr['patronomyc'], -2) === 'ич') $totalSignOfGender++;
        elseif(mb_substr($arrSurNamePatr['patronomyc'], -3) === 'вна') $totalSignOfGender--;
        /* 1) если "суммарный признак пола" больше нуля возвращаем 1 (мужской пол);
           2) если"суммарный признак пола" меньше нуля возвращаем -1 (женский пол);
           3)"суммарный признак пола" равен 0 возвращаем 0 (пол не удалось определить).*/
        return $totalSignOfGender <=> 0;
    }
    /*Функция, которая как аргумент принимает массив персональных данных $example_persons_array,
    а как результат возвращает информацию о процентном соотношении гендерного состава аудитории,
    включая информацию когда пол не удалось определить*/
    function getGenderDescription($example_persons_array) {
        $arrGenderFromName = [];

        for($i = 0; $i < count($example_persons_array); $i++) {
            array_push($arrGenderFromName, getGenderFromName($example_persons_array[$i]['fullname']));
         
    }
    $numberOfMen = abs(array_sum(array_filter($arrGenderFromName, 'men')));
    $numberOfWomen = abs(array_sum(array_filter($arrGenderFromName, 'women')));
  
    $numOfMenAsPpercent = round(($numberOfMen * 100) / count($example_persons_array), 1);
    $numOfWomenAsPpercent = round(($numberOfWomen * 100) / count($example_persons_array), 1);
    $numOfIndetGendAsPpercent = 100 - ( $numOfMenAsPpercent +  $numOfWomenAsPpercent);
    
    return "<p>Мужчины - $numOfMenAsPpercent%</p>" . 
           "<p>Женщины - $numOfWomenAsPpercent%</p>" .
           "<p>Не удалось определить - $numOfIndetGendAsPpercent%</p>";
  }
    //Выводим информацию на страницу.
    echo getGenderDescription($example_persons_array);
  /*Функция принимает 4 аргумента: фамилию, имя, отчество (первые три аргумента могут быть с любым регистром) и
    массив персональных данных $example_persons_array*/
  function getPerfectPartner($surname, $name, $patronomyc, $example_persons_array) {
    /*Приводим фамилию, имя, отчество (переданных первыми тремя аргументами) к правильному регистру
      и склеиваем ФИО, используя функцию getFullnameFromParts*/
     $surNamePatr = mb_convert_case(getFullnameFromParts($surname, $name, $patronomyc), MB_CASE_TITLE);
     //сокращаем фамилию и отбрасываем отчество
     $shortName = getShortName($surNamePatr);
     //определяем пол для ФИО с помощью функции getGenderFromName
     $gender = getGenderFromName($surNamePatr);
     //Выбираем случайным образом одного из сотрудников персональной базы данных $example_persons_array
     $persons = array_rand($example_persons_array);
     $person = $example_persons_array[$persons]['fullname'];
     //сокращаем фамилию и отбрасываем отчество сотрудника
     $shortPerName = getShortName($person);
     //определяем пол сотрудника для ФИО с помощью функции getGenderFromName
     $genderPerson = getGenderFromName($person);
     $infAboutCompatOfPair = '';
     //Вычисляем % совместимости (случайное число от 50% до 100%, сточностью 2 знака после запятой)
     $compatibility = round(random_float(50, 100), 2);
     /*Проверяем с помощью getGenderFromName, что выбранное из Массива ФИО - противоположного пола, если нет,
      то возвращаемся к шагу 4 (для пользователя на странице выводим информацию: "Не удалось подыскать пару, 
      перезагрузите страницу ещё раз"), если да - возвращаем информацию. Здесь:
      $gender = getGenderFromName($surNamePatr) и $genderPerson = getGenderFromName($person)*/
     if($gender !== $genderPerson && $gender !== 0 && $genderPerson !== 0) {
        $infAboutCompatOfPair = "<hr><p>$shortName + $shortPerName = </p>" . 
        "<p>&#9825; идеально на $compatibility% &#9825;</p>";
     } else {
        $infAboutCompatOfPair = "<hr><p>&#9786; Не удалось подыскать пару, перезагрузите страницу ещё раз &#9786;</p>";
     }
     return $infAboutCompatOfPair;
  }
  //Выводим информацию на страницу.
  echo getPerfectPartner('ГриГорЬЕва', 'ЕЛЕНА', 'ВлаДИмиРовНа', $example_persons_array);
?> 
</div> 
</body>
</html>