<?php
// Chemin vers le fichier CSV
$cheminFichier = './datagouv.csv';

// Ouvrir le fichier en lecture
$fichier = fopen($cheminFichier, 'r');
$add = 0 ;
$introuvable = 0 ;
// Lire chaque ligne du fichier
while (($ligne = fgetcsv($fichier, 0, ';')) !== false) {
    // $ligne est un tableau contenant les valeurs de la ligne courante
    $result = $ligne[8];
  
   if($result == "Ajouté") {
  $add += 1 ; 
}else if($result == "introuvable" ){
  $introuvable += 1 ;
}


}
// Fermer le fichier
fclose($fichier);
echo "ajouter = ". $add . " <br> introuvable = ". $introuvable;
?>