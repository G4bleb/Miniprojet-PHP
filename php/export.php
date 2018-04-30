<?php
require_once 'dbconnect.php';

$detailsList = $dbCnx->prepare("SELECT * FROM cambrure WHERE id_param=:id");
$detailsList->execute(array(':id'=>$_GET['id']));
$detailsListArray = $detailsList->fetchAll(PDO::FETCH_CLASS, 'Cambrure');

$fileName = $_GET['id'].'.csv';
$filePath = "../exports/".$fileName;

$fp = fopen($filePath, "w") or die();
foreach ($detailsListArray as $fields) {
  if (is_object($fields)) {
    $fields = (array) $fields;
    array_shift($fields); //Retire l'id des lignes du tableau
  }
  fputcsv($fp, $fields);
}
fclose($fp);


//Get file type and set it as Content Type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
header('Content-Type: ' . finfo_file($finfo, $filePath));
finfo_close($finfo);

//Use Content-Disposition: attachment to specify the filename
header('Content-Disposition: attachment; filename="'.basename($filePath).'"');

header('Content-Type: application/force-download');

//No cache
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

//Define file size
header('Content-Length: ' . filesize($filePath));

ob_clean();//Efface le tampon de sortie
flush();//Vide le tampon de sortie
readfile($filePath);
 ?>
