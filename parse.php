<?php
/**
 * Created by PhpStorm.
 * User: almazbeck
 * Date: 09.11.14
 * Time: 18:06
 */

//Create connection

$mysqli = new mysqli('localhost', 'root', '','kite','3306');

//Check connection

if($mysqli->connect_error){
    printf("Soedinenie ne udalos'",mysqli_connect_error());
    exit();

}
echo '<p>Connected succesfully</br></p>';

//берём все id что бы парсировать всех
$query = "SELECT id,name FROM tags ";

$result = $mysqli->query($query);


    while ($stroka = $result->fetch_assoc()) {
        echo '<p>' . $stroka['name'] . '</p>';
        // парсируем всех
        $sql = 'UPDATE `tags` SET name="'.strtolower(preg_replace("/[^\p{L}\p{N}\s\p{Cyrillic}]/", "", trim($stroka['name']))).'" WHERE id='.$stroka['id'];
        $mysqli->query($sql);
//запросируем имена и ид которые повторяются и сохраняем массив с наименьшим ид
        $sqli = " SELECT n1.id,n1.name FROM `tags` n1 , `tags` n2 WHERE n1.name = n2.name AND n1.id<n2.id GROUP BY  name";
        $result2 = $mysqli->query($sqli);

        while ($stroka2 = $result2->fetch_assoc()) {
            echo '<p>' . $stroka2['name'] . $stroka2['id'] . '</p>';
// берём ид у которых одинаковые имена
            $sqlii = "SELECT n1.id,n2.name FROM `tags` n1 WHERE n1.name LIKE" . $stroka2['name'];
            $result3 = $mysqli->query($sqlii);
            $i = 0;
            while ($i <= sizeof($result3)) {
// запросируем из geop. эти ид и заменяем их с наименьшим ид
                $sqliii = "UPDATE `geopoints_tags` SET `tags_id`=".$result3['id']."WHERE `tags_id`=".$result3[$i];
                $mysqli->query($sqliii);
                $i++;
            }
        }
// так как в geop. у одинаковых именых одинаковые tags_id удаляем из tags тех которых чьи ююююююююю
        $sqliiii = "DELETE n2 FROM `tags` n1,  `tags` n2 WHERE n1.name = n2.name AND n1.id< n2.id";
        $mysqli->query($sqliiii);
    }
