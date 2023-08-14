<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Adresse"]) && isset($_POST["Ville"]) && isset($_POST["Cp"])
 && !empty($_POST["Adresse"]) && !empty($_POST["Ville"]) && !empty($_POST["Cp"])
) {
  $adresse = $_POST["Adresse"];
  $ville = $_POST["Ville"];
  $codePostal = $_POST["Cp"];
  // Combinez l'adresse, la ville et le code postal
  $adresseComplete = "{$adresse} {$ville} {$codePostal}";

  // Encoder la valeur complète pour l'utiliser dans l'URL
  $encodedAdresseComplete = urlencode($adresseComplete);

    // Construire l'URL avec les valeurs encodées
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$encodedAdresseComplete}";


     $context = stream_context_create(array(
      'http' => array(
          'user_agent' => 'MonApplication/1.0'
      )
  ));
  
  $jsonData = file_get_contents($url, false, $context);

    // Convertir les données JSON en tableau associatif
    $data = json_decode($jsonData, true);

    // Traiter les données comme nécessaire
  
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Adresse2"]) && isset($_POST["Ville2"]) && isset($_POST["Cp2"])
 && !empty($_POST["Adresse2"]) && !empty($_POST["Ville2"]) && !empty($_POST["Cp2"])
) {
  $adresse2 = $_POST["Adresse2"];
  $ville2 = $_POST["Ville2"];
  $codePostal2 = $_POST["Cp2"];
  // Combinez l'adresse, la ville et le code postal

  // Construire l'adresse complète en format URL
  $adresseComplete2 = urlencode("{$adresse2} {$ville2} {$codePostal2}");
  
  // Clé d'API MapQuest
  $apiKey = "K7sZg3N3nPMQXDVJ6r1LZcesRrRFOsVp";
  
  // Construire l'URL de requête
  $url2 = "http://www.mapquestapi.com/geocoding/v1/address?key={$apiKey}&location={$adresseComplete2}";

     $context2 = stream_context_create(array(
      'http' => array(
          'user_agent' => 'MonApplication/1.0'
      )
  ));
  
  $jsonData2 = file_get_contents($url2, false, $context2);

    // Convertir les données JSON en tableau associatif
    $data2 = json_decode($jsonData2, true);

    // Traiter les données comme nécessaire
  
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.1/dist/cerulean/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <header>
    <main>
      <div class="container my-3">
        <div class="card bg-secondary mb-3">
          <div class="card-header text-center">coordonnées GPS</div>
          <div class="card-body">

            <p class="card-text">2/CLICPL, LIVRAIS : ajout champs coordonnées GPS et autres si besoin
              Florian : rechercher sur internet quelles seraient les infos (avec type, longueur etc..) à stocker dans la
              base de données
              pour avoir les coordonnées GPS complète d'une adresse (latitude, longitude ...)</p>
            <hr>
            <p>latitude DECIMAL(9, 7) : Les coordonnées de latitude vont de -90 à 90,
              avec généralement jusqu'à 7 chiffres décimaux après la virgule pour une grande précision.
              <br>
              <br>
              longitude DECIMAL(10, 7) : Les coordonnées de longitude vont de -180 à 180, et comme pour la
              latitude, jusqu'à 7 chiffres décimaux après la virgule sont généralement suffisants.
            </p>
          </div>
        </div>
      </div>
      <section class="d-flex align-items-center vh-75">


        <div class="container " style="border:2px black solid ;padding : 20px ; border-radius : 1rem;">
          <div class="row">
            <div class="alert alert-dismissible alert-secondary text-center">
              <strong> Grâce à l'adresse fournie, nous pouvons obtenir les coordonnées GPS en utilisant le service
                <a href="https://nominatim.openstreetmap.org/ui/search.html" style="color: red;"> OpenStreetMap</a>.
                Ensuite, en utilisant ces coordonnées, nous sommes en mesure d'afficher une carte
                qui montre précisément l'emplacement sur laquelle se situe l'adresse sur la carte.</strong>.
            </div>
            <div class="col-6">

              <form method="POST">
                <div class="form-group">
                  <label class="form-label mt-4">Votre adresse </label>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="Adresse" name="Adresse" placeholder="">
                    <label for="Adresse">
                      <?php echo (isset($adresse)) ? $adresse : 'Adresse';  ?>
                    </label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="Ville" name="Ville" placeholder="Ville">
                    <label for="Ville">
                      <?php echo (isset($ville)) ? $ville : 'Ville';  ?>
                    </label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="Cp" name="Cp" placeholder="Code postal">
                    <label for="Cp">
                      <?php echo (isset($codePostal)) ? $codePostal : 'Code postal';  ?>
                    </label>
                  </div>
                </div>
                <div class="d-flex justify-content-center">
                  <!-- Utilisation des classes Bootstrap pour aligner à droite -->
                  <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
              </form>
            </div>
            <div class="col-6">
              <?php   if (!empty($data)) {
              $latitude = $data[0]["lat"];
              $longitude = $data[0]["lon"];
                        // Données à ajouter au fichier CSV
                        $dataCsv = [
                          "code client", $adresse, $codePostal, $ville, "latitude : $latitude", "longitude : $longitude", "Ajouté"
                        ];
                        
  
  // Convertir les données en une ligne CSV
  $newRow = implode(",", $dataCsv) . "\n";
  
  // Nom du fichier
  $filename = 'data.csv';

// Ajouter la nouvelle ligne au fichier
file_put_contents($filename, $newRow, FILE_APPEND | LOCK_EX);

              // Utilisez $latitude et $longitude comme vous le souhaitez
              ?>
              <h3>Latitude :
                <span id="latitude">
                  <?= $latitude ?>
                </span> / Longitude :
                <span id="longitude">
                  <?= $longitude ?>
                </span>
              </h3>
              <?php   } else { 
              if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Adresse"]) && isset($_POST["Ville"]) && isset($_POST["Cp"])
              && !empty($_POST["Adresse"]) && !empty($_POST["Ville"]) && !empty($_POST["Cp"])
             ) {
                  // Données à ajouter au fichier CSV
                  $dataCsv = [
                    "code client", $adresse, $codePostal, $ville, "latitude : $latitude", "longitude : $longitude", "introuvable"
                  ];
                  
  // Convertir les données en une ligne CSV
  $newRow = implode(",", $dataCsv) . "\n";
  
  // Nom du fichier
  $filename = 'data.csv';
  

// Ajouter la nouvelle ligne au fichier
file_put_contents($filename, $newRow, FILE_APPEND | LOCK_EX);
 }?>
              <h3 class="text-center">Adresse introuvable </h3>
              <?php

              } ?>
              <div id="map" style="width: 100%; height: 400px;"></div>

            </div>
          </div>
        </div>



      </section>
      <div class="container mt-3" style="border:2px black solid ;padding : 20px ; border-radius : 1rem;">
        <div class="row">
          <div class="alert alert-dismissible alert-secondary text-center">
            <strong> Vous pouvez également utiliser <a href="https://developer.mapquest.com/"
                style="color: red;: ;">l'API MapQuest Geocoding</a> pour obtenir les coordonnées GPS à partir de
              l'adresse fournie.
              MapQuest offre un quota gratuit de jusqu'à 15 000 requêtes par mois.
              En utilisant votre clé d'API, vous pouvez interroger leur service de géocodage en fournissant l'adresse.
              Ensuite, les coordonnées de latitude et de longitude vous permettront d'afficher l'emplacement précis sur
              la carte.</strong>
          </div>
          <div class="col-6">

            <form method="POST">
              <div class="form-group">
                <label class="form-label mt-4">Votre adresse </label>
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="Adresse2" name="Adresse2" placeholder="">
                  <label for="Adresse2">
                    <?php echo (isset($adresse2)) ? $adresse2 : 'Adresse';  ?>
                  </label>
                </div>
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="Ville2" name="Ville2" placeholder="Ville">
                  <label for="Ville2">
                    <?php echo (isset($ville2)) ? $ville2 : 'Ville';  ?>
                  </label>
                </div>
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="Cp2" name="Cp2" placeholder="Code postal">
                  <label for="Cp2">
                    <?php echo (isset($codePostal2)) ? $codePostal2 : 'Code postal';  ?>
                  </label>
                </div>
              </div>
              <div class="d-flex justify-content-center">
                <!-- Utilisation des classes Bootstrap pour aligner à droite -->
                <button type="submit" class="btn btn-primary">Envoyer</button>
              </div>
            </form>
          </div>
          <div class="col-6">
            <?php   if (!empty($data2)) {
            $latitude2 = $data2["results"][0]["locations"][0]["latLng"]["lat"];;
            $longitude2 = $data2["results"][0]["locations"][0]["latLng"]["lng"];;
    
            // Utilisez $latitude et $longitude comme vous le souhaitez

            ?>
            <h3>Latitude :
              <span id="latitude2">
                <?= $latitude2 ?>
              </span> / Longitude :
              <span id="longitude2">
                <?= $longitude2 ?>
              </span>
            </h3>
            <?php   } else { ?>
            <h3 class="text-center">Adresse introuvable </h3>



            <?php   } ?>
            <div id="map2" style="width: 100%; height: 400px;"></div>

          </div>
        </div>
    </main>

  </header>

  <script>
    var latitudeElement = document.getElementById('latitude');
    var longitudeElement = document.getElementById('longitude');

    if (latitudeElement && longitudeElement) {
      var latitude = parseFloat(latitudeElement.textContent);
      var longitude = parseFloat(longitudeElement.textContent);

      // Maintenant vous pouvez utiliser les valeurs de latitude et de longitude
      // dans vos opérations ici

      var map = L.map('map').setView([latitude, longitude], 15);

      // Add an OpenStreetMap tile layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Add a marker at the specified position
      L.marker([latitude, longitude]).addTo(map)
        .bindPopup('Your position')
        .openPopup();
    }


    var latitudeElement2 = document.getElementById('latitude2');
    var longitudeElement2 = document.getElementById('longitude2');
    if (latitudeElement2 && longitudeElement2) {
      var latitude2 = parseFloat(latitudeElement2.textContent);
      var longitude2 = parseFloat(longitudeElement2.textContent);
      console.log(longitude2);
      var map2 = L.map('map2').setView([latitude2, longitude2], 15);

      // Add an OpenStreetMap tile layer to the second map
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map2);

      // Add a marker at the second specified position
      L.marker([latitude2, longitude2]).addTo(map2)
        .bindPopup('Your position')
        .openPopup();
      // Maintenant vous pouvez utiliser les valeurs de latitude et de longitude
      // dans vos opérations ici
    }



  </script>
</body>

</html>