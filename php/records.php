<?php
     require 'dbconnect.php';
?>
     <h2>Liste des enregistrements :</h2>
<?php
     $records = $dbCnx->prepare("SELECT * from parametre");
     $records->execute();
     $recordsList = $records->fetchAll(PDO::FETCH_CLASS, 'Parametre');
?>
     <table border=1 style = 'border-collapse: collapse'>
          <tr>
            <th>Label</th>
            <th>Corde</th>
            <th>Tmax(%)</th>
            <th>Tmax(mm)</th>
            <th>Fmax(%)</th>
            <th>Fmax(mm)</th>
            <th>Nb Points</th>
            <th>Date</th>
           <tr/>
<?php
     foreach ($recordsList as $value) {
          $tempId = $value->getId();
          $tempLibelle = $value->getLibelle();
          echo "<tr>";
          echo "<form method='get'>
                    <input type='hidden' name='id_param' value=$tempId>
                    <td><input type='submit' value=$tempLibelle></td>";
          echo "<td>".$value->getCorde()."</td>";
          echo "<td>".$value->getTmax_prc()."</td>";
          echo "<td>".$value->getTmax_mm()."</td>";
          echo "<td>".$value->getFmax_prc()."</td>";
          echo "<td>".$value->getFmax_mm()."</td>";
          echo "<td>".$value->getNb_points()."</td>";
          echo "<td>".$value->getDate()."</td>";
     }

     echo "</table>";

     // if(isset($_GET['id_param'])) {
     //      $
     // }

?>
