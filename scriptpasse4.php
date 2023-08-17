<?php
set_time_limit(36000);
// Chemin vers le fichier CSV
$cheminFichier = './data2.csv';

// Ouvrir le fichier en lecture
$fichier = fopen($cheminFichier, 'r');

// Nouveau contenu modifié
$nouveauContenu = [];

// Lire chaque ligne du fichier
while (($ligne = fgetcsv($fichier, 0, ';')) !== false) {
    // $ligne est un tableau contenant les valeurs de la ligne courante
    $result = $ligne[8];
  
    if ($result == "introuvable" && $ligne[4] != "" && $ligne[5] != "") {
        $codeClient = $ligne[0];
        $nom = $ligne[1];
        $adresse = $ligne[2];
        $adresse2 = $ligne[3];
        $ville = $ligne[4];
        $codePostal = $ligne[5];
        
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
        
        if (!empty($data['features'])) {
           
            $latitude = $data['features'][0]['geometry']['coordinates'][1];
            $longitude = $data['features'][0]['geometry']['coordinates'][0];
            if ( $latitude == 38.89037 && $longitude == -77.03196){
                $dataCsv = [
                    $codeClient, $nom, $adresse, $adresse2, $ville, $codePostal,"" ,"" , "introuvable"
                ];
            }else{
                $dataCsv = [
                    $codeClient, $nom, $adresse, $adresse2, $ville, $codePostal, $latitude, $longitude, "Ajouté"
                ];
            }
        } else{
            $jsonData2 = file_get_contents($url2, false, $context);
            $data2 = json_decode($jsonData2, true);
            if (!empty($data2['features'])) {
                $latitude = $data2['features'][0]['geometry']['coordinates'][1];
                $longitude = $data2['features'][0]['geometry']['coordinates'][0];
                if ( $latitude == 38.89037 && $longitude == -77.03196){
                    $dataCsv = [
                        $codeClient, $nom, $adresse, $adresse2, $ville, $codePostal,"" ,"" , "introuvable"
                    ];
                }else{
                    $dataCsv = [
                        $codeClient, $nom, $adresse, $adresse2, $ville, $codePostal, $latitude, $longitude, "Ajouté"
                    ];
                }
            }else{
                $dataCsv = $ligne  ;
            }
        }
// Convert
        }else{
            $dataCsv = $ligne  ;
        }
            $newRow = implode(";", $dataCsv);
        
// Ajouter la ligne modifiée au nouveau contenu
            $nouveauContenu[] = $newRow;
}

// Fermer le fichier de lecture
fclose($fichier);

// Réécrire le contenu dans le fichier
file_put_contents($cheminFichier, implode("\n", $nouveauContenu));

echo "Opération terminée.";
?>