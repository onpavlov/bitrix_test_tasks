#Тестовые задания на Bitrix разработчика


## Задание №1
Есть интернет-магазин с > 40000 товарами. Товары могут  быть как простыми так и с торговыми предложениями. 
Структура каталога имеет неограниченный уровень вложености. 
В качестве примера предположим что есть раздел Коляски  с такой структорой
+ Коляски
++ Коляски 2 в 1 
-- Коляски 3 в 1
-- Коляски для новорожденных
--- Коляски на прогулочной раме
---- С кожей

Задача:
Нужно написать комплесный компонент, который будет принимать следюущие параметры:
1) ID инфоблока
3) Время жизни кеша

Компонент должен обрабатывать следующие адреса:
1) /products/ - главная страница где и вызывается компонент
2) /products/section/CODE - страница раздела, где CODE - это символьный код раздела который мы выбрали

На главной страницы показать древовидный вид структуры каталога, т.е. разделы в виде дерева.
При клики на любой раздел мы проваливаемся на вторую страницу, где в свою очередь мы должны увидеть:
1) Количество товаров в этом разделе, включая подразделы 
2) Список товаров состоящий из Названия и Минимальной цены товара. 

Дополнительные условия:
- кеш должен сбрысываться при изменение данных в инфоблоке