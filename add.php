<?php
error_reporting(E_ALL);
include 'connection.php';

if (isset($_POST['create']) && $_POST['create'] == "true" && isset($_POST['table_name'])) {
    $table_name = $_POST['table_name'];
        $sql = "CREATE TABLE $table_name (
    `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `price` double NOT NULL,
  `color` varchar(100) NOT NULL
) ENGINE='InnoDB';";
    $statement = $pdo->prepare($sql);
    $statement->execute();

    /*$arr = []; //Пока не готово!
    for ($j = 0; $j <= count($mass); $j++) {
        $field_name = $value['field_name'];
        $field_type = $value['field_type'];
        $field_length = $value['field_length'];
        $field_NULL = ($value['field_NULL'] == NULL ? "NULL" : "NOT NULL");
        $field_AI = ($value['field_AI'] == NULL ? "" : "AUTO_INCREMENT");
    }

        $sql = "CREATE TABLE {$table_name} (
      {$field_name} {$field_type}({$field_length}) {$field_NULL} {$field_AI} PRIMARY KEY,
      <?php for ($j = 0; $j <= count($mass); $j++) { echo ?>
      {$field_name} {$field_type}({$field_length}) {$field_NULL} {$field_AI}
<?php ; } ?>
    ) ENGINE='InnoDB'";
    $statement = $pdo->prepare($sql);
    $statement->execute();*/
}


#region //Добавление нового поля
$field_amount = 1;
if (isset($_POST['default']) && $_POST['default'] == 1) {
    $field_amount = $_POST['default'];
    unset($_POST['default']);
}

if (isset($_POST['add_field'])) {
    $_POST['add_field']++;
    $field_amount = $_POST['add_field'];
}
#endregion

#region //Определение значений в select
for ($i = 1; $i <= $field_amount; $i++) {
    $mass[$i] = [
        "field_name" => (isset($_POST["field_name_$i"]) ? $_POST["field_name_$i"] : NULL),
        "field_type" => (isset($_POST["field_type_$i"]) ? $_POST["field_type_$i"] : NULL),
        "field_length" => (isset($_POST["field_length_$i"]) ? $_POST["field_length_$i"] : NULL),
        "field_NULL" => (isset($_POST["field_NULL_$i"]) ? $_POST["field_NULL_$i"] : NULL),
        "field_AI" => (isset($_POST["field_AI"]) ? $_POST["field_AI"] : NULL)
    ];
}
$type_mass = ["TINYINT","SMALLINT","MEDIUMINT","INT","BIGINT","FLOAT","DOUBLE","REAL","DECIMAL","NUMERIC","TINYTEXT","TEXT","MEDIUMTEXT","LONGTEXT","TINYBLOB","BLOB","MEDIUMBLOB","LONGBLOB","DATE","TIME","TIMESTAMP","DATETIME"];
#endregion

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
    <form method="POST" name="add_form">
    <table class="main-container-table table-admin">
        <tr class="table-row">
            <td class="table-cell table-header first-column_add">Name</td>
            <td class="table-cell table-header second-column_add">Type</td>
            <td class="table-cell table-header third-column_add">Length</td>
            <td class="table-cell table-header fourth-column_add">NULL</td>
            <td class="table-cell table-header fifth-column_add">AI</td>
            <td class="table-cell table-header sixth-column_add">Action</td>
        </tr>
        <?php for ($i = 1; $i <= $field_amount; $i++) {
            if (isset($_POST["field_NULL_$i"]) && $_POST["field_NULL_$i"] == $i) {
                $checked_NULL = "checked";
            } else $checked_NULL = "";
            if (isset($_POST["field_AI"]) && $_POST["field_AI"] == $i) {
                $checked_AI = "checked";
            } else $checked_AI = ""; ?>
        <tr class="table-row">
            <td class="table-cell first-column_add">
                <input class="main-container-table__input" type="text" name="field_name_<?= $i; ?>" placeholder=" Field name" value="<?= $mass[$i]['field_name']; ?>">
            </td>
            <td class="table-cell second-column_add">
                <select class="main-container-table__input select" name="field_type_<?= $i; ?>">
                    <optgroup label="Integer">
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[0]) ? "selected" : "")) ?> value="<?= $type_mass[0]; ?>">TINYINT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[1]) ? "selected" : "")) ?> value="<?= $type_mass[1]; ?>">SMALLINT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[2]) ? "selected" : "")) ?> value="<?= $type_mass[2]; ?>">MEDIUMINT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[3]) ? "selected" : "")) ?> value="<?= $type_mass[3]; ?>">INT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[4]) ? "selected" : "")) ?> value="<?= $type_mass[4]; ?>">BIGINT</option>
                    </optgroup>
                    <optgroup label="Double">
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[5]) ? "selected" : "")) ?> value="<?= $type_mass[5]; ?>">FLOAT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[6]) ? "selected" : "")) ?> value="<?= $type_mass[6]; ?>">DOUBLE</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[7]) ? "selected" : "")) ?> value="<?= $type_mass[7]; ?>">REAL</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[8]) ? "selected" : "")) ?> value="<?= $type_mass[8]; ?>">DECIMAL</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[9]) ? "selected" : "")) ?> value="<?= $type_mass[9]; ?>">NUMERIC</option>
                    </optgroup>
                    <optgroup label="String">
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[10]) ? "selected" : "")) ?> value="<?= $type_mass[10]; ?>">TINYTEXT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[11]) ? "selected" : "")) ?> value="<?= $type_mass[11]; ?>">TEXT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[12]) ? "selected" : "")) ?> value="<?= $type_mass[12]; ?>">MEDIUMTEXT</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[13]) ? "selected" : "")) ?> value="<?= $type_mass[13]; ?>">LONGTEXT</option>
                    </optgroup>
                    <optgroup label="Binary">
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[14]) ? "selected" : "")) ?> value="<?= $type_mass[14]; ?>">TINYBLOB</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[15]) ? "selected" : "")) ?> value="<?= $type_mass[15]; ?>">BLOB</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[16]) ? "selected" : "")) ?> value="<?= $type_mass[16]; ?>">MEDIUMBLOB</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[17]) ? "selected" : "")) ?> value="<?= $type_mass[17]; ?>">LONGBLOB</option>
                    </optgroup>
                    <optgroup label="Date">
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[18]) ? "selected" : "")) ?> value="<?= $type_mass[18]; ?>">DATE</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[19]) ? "selected" : "")) ?> value="<?= $type_mass[19]; ?>">TIME</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[20]) ? "selected" : "")) ?> value="<?= $type_mass[20]; ?>">TIMESTAMP</option>
                        <option <?= ((isset($_POST["field_type_$i"]) && ($_POST["field_type_$i"] == $type_mass[21]) ? "selected" : "")) ?> value="<?= $type_mass[21]; ?>">DATETIME</option>
                    </optgroup>
                </select>
            </td>
            <td class="table-cell third-column_add"><input class="main-container-table__input" type="text" name="field_length_<?= $i; ?>" placeholder=" Field length" value="<?= $mass[$i]['field_length']; ?>">
            </td>
            <td class="table-cell fourth-column_add">
                <input class="main-container-table__input" type="checkbox" name="field_NULL_<?= $i; ?>" value="<?= $i; ?>" <?= $checked_NULL; ?>>
            </td>
            <td class="table-cell fifth-column_add">
                <input class="main-container-table__input" type="radio" name="field_AI" value="<?= $i; ?>"  <?= $checked_AI; ?>>
            </td>
            <td class="table-cell sixth-column_add">
                <button class="button add-field-button" name="add_field" value="<?= $field_amount; ?>">+add field</button>
            </td>
        </tr>
        <?php } ?>
    </table>
        <input class="main-container__create-table" type="text" name="table_name">
        <button class="button add-button" name="create" value="true">Create table</button>
    </form>
</div>
</body>
</html>