# Учебный проект "Работа с массивом персональных данных и строками". #

Данный проект состоит из 2 частей. **Перавя часть** посвящена определению процентного соотношения гендерного состава массива **personal-data (мужчины - такое-то количество в %, женщины - такое-то количество в %)**, состоящего из вымышленных персональных данных сотрудников, некой вымышленной компании, а также **выявлению ошибок (после мужской фамилии и имени стоит женское отчество и т.п.)** имеющихся в **массиве персональных данных personal-data (Не удалось определить - такое-то количество в %)**. **Вторая часть проекта**, посвящена поиску идеальной пары для вымышленных сотрудников массива **personal-data**.

## 1. Определение процентного соотношения гендерного состава массива personal-data. ##

В данной части проекта производится работа с массивом персональных данных **personal-data** и извлечению из него данных, содержащих в себе **фамилию, имя и отчество** вымышленного сотрудника ввиде строки и разбиению данной строки, с помощью функции **getPartsFromFullname** на ассоативный массив возвращающий данные ввиде: **[‘surname’ => ‘фамилия’ ,‘name’ => ‘имя’, ‘patronomyc’ => ‘отчество’]** для последующей работы. Далее с помощью функции **getGenderFromName** определяем **пол** или **выявляем ошибку в персональных данных (после мужской фамилии и имени стоит женское отчество и т.п.)** и на заключительном этапе, с помощью функции  **getGenderDescriptio** определяем процентное соотношение гендерного состава вымышленных сотрудников из массива персональных данных **personal-data** и выводим информацию ввиде:

**Гендерный состав аудитории:**

Мужчины - 53.8% (данные приведены для примера)

Женщины - 30.8% (данные приведены для примера)

Не удалось определить - 15.4% (данные приведены для примера)


## 2. Поиск идеальной пары для вымышленных сотрудников массива personal-data. ##

На 1 этапе работы в функцию для определения идеальной пары **getPerfectPartner** передаются **4 аргумента: фамилия, имя и отчество** (любого произвольного субъекта), при этом регистр при передаче этих данных может быть абсолютно любым, например ФИО можно вводить в таком виде:  ИВАНОВ ИВАН ИВАНОВИЧ или ИваНов Иван иваНОвиЧ, в качестве **4 аргумента передается массив персональных данных personal-data**. Далее все действия и вывод информании производятся с помощью и внутри данной функции. Основной алгоритм следующий:

1. приводим **фамилию, имя, отчество** (переданных первыми тремя аргументами) к привычному регистру;
2. с помощью функции **getFullnameFromParts** соединяем аргументы: **фамилия, имя и отчество** в единую строку;
3. определяем пол с помощью функции **getGenderFromName**;
4. случайным образом выбираем любого человека из массива **personal-data**;
5. проверяем с помощью функции **getGenderFromName**, что выбранное из массива **personal-data** ФИО - противоположного пола, если нет, то **возвращаемся к пункту 4** и выводим для пользователя информацию (**"Немного терпения идёт процесс поиска"**), если да - возвращаем информацию ввиде (где для сокращения фамилии и откидывания отчества использовалась функция **getShortName**, а процент совместимости "Идеально на ..." — **случайное число от 50% до 100% с точностью два знака после запятой (расчитывался с помощью функции генерации случайных чисел)**):

    Лев И. + Антонина Ш.(для примера) =

    ♡ идеально на 94.41% (для примера) ♡

