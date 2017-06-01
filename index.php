<?php
error_reporting(E_ALL);
include 'connection.php';

#region //Определение заголовков и стилей на странице
$fieldset_class = "main-container-fieldset";
$page = 0;
if (!empty($_GET["page"])) {
    $page = $_GET["page"];
        if ($page == "1") {
            $fieldset_class = "main-container-fieldset sub-page";
        }
}
#endregion

#region //Отображение информации о таблице
$tab_name = " пока не выбрана!";
if (isset($_GET['table_name'])) {
    $tab_name = $_GET['table_name'];
    $sql1 = "DESCRIBE $tab_name";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
}
#endregion

#region //Удаление поля таблицы
if (isset($_GET['table_name'], $_GET['del_field'], $_GET['action']) && $_GET['action'] == 'delete') {
    $name = $_GET['table_name'];
    $del_field = $_GET['del_field'];
    $sql1 = "ALTER TABLE {$name} DROP COLUMN {$del_field}";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();

    $sql1 = "DESCRIBE $tab_name";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
}
#endregion

if (isset($_GET['table_name'], $_GET['field'], $_GET['type']) && !empty($_POST['rename_field_form'])) {
    $name = $_GET['table_name'];
    $change = $_GET['field'];
    $new = $_POST['rename_field_form'];
    $type = $_GET['type'];
    $sql1 = "ALTER TABLE {$name} CHANGE {$change} {$new} {$type}";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();


    $sql1 = "DESCRIBE $tab_name";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
    unset($_POST['rename_field_form']);
}

#region //Изменение типа поля таблицы
if (isset($_GET['table_name'], $_GET['field']) && !empty($_POST['change_type_form'])) {
    $name = $_GET['table_name'];
    $field = $_GET['field'];
    $change_type = $_POST['change_type_form'];
    if (isset($_POST['check_null'])) {
        $sql1 = "ALTER TABLE {$name} MODIFY {$field} {$change_type} NOT NULL";
        $statement1 = $pdo->prepare($sql1);
        $statement1->execute();
    } else {
        $sql1 = "ALTER TABLE {$name} MODIFY {$field} {$change_type} NULL";
        $statement1 = $pdo->prepare($sql1);
        $statement1->execute();
    }

    $sql1 = "DESCRIBE $tab_name";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
    unset($_POST['change_type_form']);
}
#endregion

$sql = "SHOW TABLES";
$statement = $pdo->prepare($sql);
$statement->execute();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel="shortcut icon" href="image/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ADMINN</title>
</head>
<body>
<header class="header-container">
        <ul class="header-container__menu clearfix">
            <li class="header-container__menu__item"><img src="image/arrow.png" width="70" height="70"></li>
            <li class="header-container__menu__item"><a class="header-container-link logo-link" href="index.php?page=0">ADMINN</a></li>
            <li class="header-container__menu__item"><a class="header-container-link" href="index.php?page=0">Main</a></li>
            <li class="header-container__menu__item"><a class="header-container-link" href="index.php?page=1">Tables</a></li>
        </ul>
</header>
<hr class="horizontal-line">
<div class="main-container">
    <?php switch ($page) {
       case 0: ?>
            <fieldset class="<?= $fieldset_class; ?>">
                <h1 class="main-container-fieldset__text-h1"><span class="blue-color-span">ADMINN</span> App</h1>
                <h3 class="main-container-fieldset__text-h3">Administrate your tables</h3>
                <h3 class="main-container-fieldset__text-h3 arrow-text">Start!</h3>
                <p class="p-center"><a href="index.php?page=1"><img src="image/down-arrow.png" class="main-container-fieldset__arrow"></a></p>
            </fieldset>
            <?php break; case 1: ?>
            <fieldset class="<?= $fieldset_class; ?>">
                <h2 class="main-container-fieldset__text-h2">All tables</h2>
                <div class="main-container-form__add-table clearfix">
                    <form method="POST" action="add.php" class="main-container-fieldset__button-form clearfix" >
                        <button class="button add-button" name="default" value="1">+ADD TABLE</button>
                    </form>
                </div>
                <table class="main-container-table">
                    <tr class="table-row">
                        <td class="table-cell table-header first-column">№</td>
                        <td class="table-cell table-header second-column">Name</td>
                        <td class="table-cell table-third second-column">Action</td>
                    </tr>
                    <?php $count = 1; foreach ($statement as $table) {
                        $table_name = $table['Tables_in_denisiuk']; ?>
                        <tr class="table-row">
                            <td class="table-cell first-column"><?= $count; ?></td>
                            <td class="table-cell second-column"><?= $table_name; ?></td>
                            <td class="table-cell third-column"><a class="main-container-table__item-link" href="index.php?page=1&table_name=<?=$table_name;?>">Select</a></td>
                        </tr>
                        <?php $count++; } ?>
                </table>
                <div class="main-container-admin">
                    <div class="main-container-admin__header">
                        <p class="p-center main-container-fieldset__text-h2">ВАША ТАБЛИЧКА: <?= $tab_name; ?></p>
                    </div>
                    <div class="main-container-admin__content">
                        <table class="main-container-table table-admin">
                            <tr class="table-row">
                                <td class="table-cell table-header first-column">Field</td>
                                <td class="table-cell table-header second-column">Type</td>
                                <td class="table-cell table-header third-column">Null</td>
                                <td class="table-cell table-header fourth-column">Key</td>
                                <td class="table-cell table-header fifth-column">Extra</td>
                                <td class="table-cell table-header sixth-column">Action</td>
                            </tr>
                            <?php if (isset($_GET['table_name'])) { foreach ($statement1 as $atribut) {
                                $field = $atribut['Field'];
                                $type = $atribut['Type'];
                                $null = $atribut['Null'];
                                $key = $atribut['Key'];
                                $default = $atribut['Default'];
                                $extra = $atribut['Extra'];
                                ?>
                                <tr class="table-row">
                                    <td class="table-cell"><?= $field; ?></td>
                                    <td class="table-cell"><?= $type; ?></td>
                                    <td class="table-cell"><?= $null; ?></td>
                                    <td class="table-cell"><?= $key; ?></td>
                                    <td class="table-cell"><?= $extra; ?></td>
                                    <td class="table-cell">
                                        <ul class="action-menu clearfix">
                                            <li class="action-menu__item"><a class="action-menu__item-link" href="?page=1&table_name=<?=$tab_name;?>&field=<?= $field;?>&type=<?= $type; ?>&action=rename">Rename</a></li>
                                            <li class="action-menu__item"><a class="action-menu__item-link" href="?page=1&table_name=<?=$tab_name;?>&field=<?= $field;?>&type=<?= $type; ?>&action=change">Change</a></li>
                                            <li class="action-menu__item"><a class="action-menu__item-link" href="?page=1&table_name=<?=$tab_name;?>&del_field=<?= $field;?>&action=delete">Delete</a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php }} ?>
                        </table>
                        <form method="POST" action="index.php?page=1&table_name=<?=$tab_name;?>&field=<?= $_GET['field'];?>&type=<?= $_GET['type'];?>">
                            <?php if (isset($_GET['field'], $_GET['type'], $_GET['action']) && $_GET['action'] == 'rename') {
                                $field_name = $_GET['field'];
                            } else $field_name = ''; ?>
                            <div>
                                <input class="main-container-fieldset__input-text" type="text" name="rename_field_form" placeholder=" Rename field" value="<?= $field_name; ?>">
                                <input class="button alter-button" type="submit" value="Submit">
                            </div>
                            <?php if (isset($_GET['field'], $_GET['type'], $_GET['action']) && $_GET['action'] == 'change') {
                                $field_type = $_GET['type'];
                            } else $field_type = ''; ?>
                            <div>
                                <input class="main-container-fieldset__input-text" type="text" name="change_type_form" placeholder=" Change field type" value="<?= $field_type; ?>">
                                <input class="main-container-fieldset__checkbox" type="checkbox" name="check_null" value="check">
                                <input class="button alter-button" type="submit" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </fieldset>
            <?php break; } ?>
</div>
</body>
</html>
