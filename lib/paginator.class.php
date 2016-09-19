<?php

class Paginator extends SafeMySQL
{

    $db = new safeMysql();

$per_page = 10;

//получаем номер страницы и значение для лимита
$cur_page = 1;
if (isset($_GET['page']) && $_GET['page'] > 0)
{
$cur_page = $_GET['page'];
}
$start = ($cur_page - 1) * $per_page;

//выполняем запрос и получаем данные для вывода
$sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM news LIMIT ?i,?i";
$data = $db->getAll($sql, $start, $per_page);
$rows = $db->getOne("SELECT FOUND_ROWS()");

//узнаем общее количество страниц и заполняем массив со ссылками
$num_pages = ceil($rows / $per_page);

// зададим переменную, которую будем использовать для вывода номеров страниц
$page = 0;

//а дальше выводим в шаблоне днные и навигацию:
?>
    Найдено сообщений: <b><?=$rows?></b><br><br>
<? foreach ($data as $row): ?>
    <?=++$start?>. <a href="?id=<?=$row['id']?>"><?=$row['title']?></a><br>
<? endforeach ?>

    <br>
    Страницы:
<? while ($page++ < $num_pages): ?>
    <? if ($page == $cur_page): ?>
        <b><?=$page?></b>
    <? else: ?>
        <a href="?page=<?=$page?>"><?=$page?></a>
    <? endif ?>
<? endwhile ?>
}
