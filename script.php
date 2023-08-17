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

  // Combinez l'adresse, la ville et le code postal
  $adresseComplete = "{$adresse} {$ville} {$codePostal}";
  $adresseComplete2 = "{$adresse2} {$ville} {$codePostal}";

  // Encoder la valeur complète pour l'utiliser dans l'URL
  $encodedAdresseComplete = urlencode($adresseComplete);

    // Construire l'URL avec les valeurs encodées
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$encodedAdresseComplete}";
    $url2 = "https://nominatim.openstreetmap.org/search?format=json&q={$encodedAdresseComplete2}";


     $context = stream_context_create(array(
      'http' => array(
          'user_agent' => 'MonApplication/1.0'
      )
  ));
  
  $jsonData = file_get_contents($url, false, $context);

    // Convertir les données JSON en tableau associatif
    $data = json_decode($jsonData, true);

    $latitude = $data[0]["lat"];
    $longitude = $data[0]["lon"];
              // Données à ajouter au fichier CSV
    if ($latitude != "" && $longitude != "" )  {
      $dataCsv = [
                $codeClient,$nom, $adresse,$adresse2, $ville, $codePostal, $latitude,$longitude, "Ajouté"
              ];  
    }   else{
        $jsonData2 = file_get_contents($url2, false, $context);

        // Convertir les données JSON en tableau associatif
        $data2 = json_decode($jsonData2, true);
    
        $latitude2 = $data2[0]["lat"];
        $longitude2 = $data2[0]["lon"];
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
$filename = 'data.csv';

// Ajouter la nouvelle ligne au fichier
file_put_contents($filename, $newRow, FILE_APPEND | LOCK_EX);

}
// Fermer le fichier
fclose($fichier);
?>