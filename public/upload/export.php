<?php

$handle = fopen(__DIR__.'/data', 'r');

$headers = ['Numéro', 'Doublon', 'Date envoi formulaire', 'Date', 'Mois', 'Année', 'Heure', 'Adresse', 'Code postal',
  'Latitude', 'Longitude', 'Adresse inexacte', 'Route', 'Voie ferrée', 'Urbain', 'Jardin individuel', 'Jardin partagé',
  'Parc', 'Verger', 'Prairie', 'Culture', 'Haie', 'Friche', 'Lisière', 'Forêt', 'Zone humide',
  'Type observation', 'Etat vital', 'Nb vivants', 'Nb juvéniles vivants', 'Nb morts', 'Nb juvéniles morts',
  'Collision', 'Empoisonnement', 'Prédation', 'Parasitisme', 'Accident', 'Autre', 'Empreintes', 'Crottes',
  'Nid', 'Bruit', 'Compléments', 'Diffusion photo', 'Diffusion auteur',
  'Nom', 'Prénom', 'Code postal', 'Commune', 'Département', 'Email', 'Téléphone', 'Age',
  'Appréciation', 'Abonnement', 'Remarques'];

header('Content-type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=export-herisson'.date('Ymd').'.csv');
header('Pragma: no-cache');
header('Expires: 0');

$outstream = fopen('php://output', 'a');
fputs($outstream, chr(0xEF).chr(0xBB).chr(0xBF)); // add BOM to fix UTF-8 in Excel
fputcsv($outstream, $headers, ';');

if ($handle) {
  $numRow = 1;
  $relativeNumRow = 1;
  $previous = [];

  while (($line = fgets($handle)) !== false) {
    $numRow++;

    $relativeNumRow++;
    $data = json_decode($line, true);

    if (strpos($data['date'], '/') === 2) {
      $data['date'] = substr($data['date'], 6, 4).'-'.substr($data['date'], 3, 2).'-'.substr($data['date'], 0, 2);
    } elseif (strpos($data['date'], '/') === 4) {
      $data['date'] = str_replace('/', '-', $data['date']);
    }

    $month = substr($data['date'], 5, 2);
    $day = substr($data['date'], 8, 2);
    if (($month > 12 || $month < 1) && $day < 13 && $day > 0) {// mois/jour inversé
      $data['date'] = substr($data['date'], 0, 4).'-'.$day.'-'.$month;
    }

    $date = new DateTime($data['date']);

    if (!is_array($data['typeObs'])) {
      continue;
    }
    
    $obsDirecte = in_array('Directe', $data['typeObs']);
    $morts = $obsDirecte && in_array('Morts', $data['alive']);
    $doublon = [];
    $thisDate = $date->format('U');
    $oneWeekInSeconds = 60 * 60 * 24 * 7;
    $id = isset($data['id']) ? $data['id'] : $numRow - 1;

    foreach ($previous as $prevLine) {
      if (/*abs($thisDate - $prevLine[1]) < $oneWeekInSeconds &&*/
        abs($data['lat'] - $prevLine[2]) < 0.0001 &&
        abs($data['lon'] - $prevLine[3]) < 0.0001) {
        $doublon[] = $prevLine[0];
      }
    }

    $previous[] = [$id, $thisDate, $data['lat'], $data['lon']];
    $values = [
      $id,
      implode(', ', $doublon),
      isset($data['postDate']) ? $data['postDate'] : '',
      $date->format('d/m/Y'),
      $date->format('n'),
      $date->format('Y'),
      $data['heure'],
      $data['adresse'],
      $data['codePostal'],
      $data['lat'],
      $data['lon'],
      isset($data['adresseInexacte']) ? $data['adresseInexacte'] : '0',
      in_array('Route', $data['paysage']),
      in_array('Voie ferrée', $data['paysage']),
      in_array('Milieu urbanisé', $data['paysage']),
      in_array('Jardin individuel', $data['paysage']),
      in_array('Jardin partagé', $data['paysage']),
      in_array('Parc', $data['paysage']),
      in_array('Verger', $data['paysage']),
      in_array('Prairie, pâture', $data['paysage']),
      in_array('Champ cultivé', $data['paysage']),
      in_array('Haie', $data['paysage']),
      in_array('Friche buissonnante', $data['paysage']),
      in_array('Lisière de forêt', $data['paysage']),
      in_array('Forêt ou bois', $data['paysage']),
      in_array('Zone humide ou cours d\'eau', $data['paysage']),
      implode(', ', $data['typeObs']),
      $obsDirecte ? implode(', ', $data['alive']) : '',
      $obsDirecte && in_array('Vivants', $data['alive']) ? $data['nbAnimals'] : '',
      $obsDirecte && in_array('Vivants', $data['alive']) && isset($data['nbAnimalsYoung']) ? $data['nbAnimalsYoung'] : '',
      $morts ? $data['nbDead'] : '',
      $morts && isset($data['nbDeadYoung']) ? $data['nbDeadYoung'] : '',
      $morts ? in_array('Collision routière', $data['deadCause']) : '',
      $morts ? in_array('Empoisonnement', $data['deadCause']) : '',
      $morts ? in_array('Prédation', $data['deadCause']) : '',
      $morts ? in_array('Parasitisme', $data['deadCause']) : '',
      $morts ? in_array('Accident (noyade, chute...)', $data['deadCause']) : '',
      $morts ? in_array('Autre', $data['deadCause']) : '',
      !$obsDirecte ? in_array('Empreintes', $data['indices']): '',
      !$obsDirecte ? in_array('Crottes', $data['indices']) : '',
      !$obsDirecte ? in_array('Nid', $data['indices']) : '',
      !$obsDirecte ? in_array('Bruit', $data['indices']) : '',
      $data['info'],
      $data['share'],
      $data['public'],
      mb_strtoupper($data['name'], 'UTF-8'),
      $data['firstname'],
      $data['userZipCode'],
      $data['userCity'],
      $data['userDep'],
      $data['email'],
      $data['phone'],
      $data['old'],
      implode(', ', $data['satisfaction']),
      $data['subscribe'],
      $data['remarks']
    ];

    fputcsv($outstream, $values, ';');
  }

  fclose($outstream);
} else {
  echo 'Error lors de l\'ouverture du fichier de données';
}

