<?php
// Chemin vers le fichier CSV
$cheminFichier = './client.csv';

// Ouvrir le fichier en lecture
$fichier = fopen($cheminFichier, 'r');

// Lire chaque ligne du fichier
while (($ligne = fgetcsv($fichier, 0, ';')) !== false) {
    // $ligne est un tableau contenant les valeurs de la ligne courante
    $codeClient =  $ligne[1];
    $nom =  $ligne[4];
    $adresse = $ligne[5];
    $ville = $ligne[7];
    $codePostal = $ligne[8];
    $adresse2 = $ligne[6];

        // Construire l'adresse complète en format URL
        $adresseComplete = urlencode("{$adresse} {$ville} {$codePostal}");
        $adresseComplete2 = urlencode("{$adresse2} {$ville} {$codePostal}");
  
        // Clé d'API MapQuest
 
        // Construire les URL de requête
        $url = "https://api-adresse.data.gouv.fr/search/?q={$adresseComplete}";
        $url2 = "https://api-adresse.data.gouv.fr/search/?q={$adresseComplete2}";


     $context = stream_context_create(array(
      'http' => array(
          'user_agent' => 'MonApplication/1.0'
      )
  ));
  
  $jsonData = file_get_contents($url, false, $context);

    // Convertir les données JSON en tableau associatif
    $data = json_decode($jsonData, true);

    $latitude = $data['features'][0]['geometry']['coordinates'][1];
            $longitude = $data['features'][0]['geometry']['coordinates'][0];
              // Données à ajouter au fichier CSV
    if ($latitude != "" && $longitude != "" )  {
      $dataCsv = [
                $codeClient,$nom, $adresse,$adresse2, $ville, $codePostal, $latitude,$longitude, "Ajouté"
              ];  
    }   else{
        $jsonData2 = file_get_contents($url2, false, $context);

        // Convertir les données JSON en tableau associatif
        $data2 = json_decode($jsonData2, true);
    
        $latitude2 = $data2['features'][0]['geometry']['coordinates'][1];
            $longitude2 = $data2['features'][0]['geometry']['coordinates'][0];
        if ($latitude2 != "" && $longitude2 != "" ){
            $dataCsv = [
                $codeClient,$nom, $adresse,$adresse2, $ville, $codePostal, $latitude2,$longitude2, "Ajouté"
              ];    
        }else{
             $dataCsv = [
                $codeClient,$nom, $adresse,$adresse2, $ville, $codePostal, $latitude2,$longitude2, "introuvable"
          ];    
        }
       
    }     

              
              

// Convertir les données en une ligne CSV
$newRow = implode(";", $dataCsv) . "\n";

// Nom du fichier
$filename = 'datagouv.csv';

// Ajouter la nouvelle ligne au fichier
file_put_contents($filename, $newRow, FILE_APPEND | LOCK_EX);

}
// Fermer le fichier
fclose($fichier);
?>