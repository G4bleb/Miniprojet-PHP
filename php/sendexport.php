<?php

if (isset($_GET['id'])) {
     $filePath = "../exports/".$_GET['id'].'.csv';

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
} else{
     echo "Erreur lors de l'envoi du fichier <br>";
     echo '<a href="../">Retour à l\'accueil</a>';
}

?>
