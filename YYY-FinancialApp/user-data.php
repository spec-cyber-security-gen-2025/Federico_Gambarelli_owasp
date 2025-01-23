<?php

header('Content-Type: application/json');

// Percorso del file JSON
$filePath = './data.json';

// Controlla se il file esiste
if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(["error" => "File not found"]);
    exit();
}

// Leggi il contenuto del file
$json = file_get_contents($filePath);

// Decodifica il JSON in un array associativo
$data = json_decode($json, true);

// Controlla se la decodifica è riuscita
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(["error" => "Error decoding JSON"]);
    exit();
}

// Restituisci i dati come risposta JSON
echo json_encode($data);

?>