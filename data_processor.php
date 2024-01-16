<?php
// I Jay Patel, 000881881 certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else. 
/**
* @author: Jay Patel
* @version: 202335.00
* @package COMP 10260 Assignment 2
*/
$pokemon = "pokemon.txt";
$movies = "movies.json";

$choice = filter_var($choice, FILTER_SANITIZE_STRING);
$sort = filter_var($sort, FILTER_SANITIZE_STRING);

/** 
 * Reads and parses the pokemon.txt
 *
 * @param string $file The path to the file containing Pokemon data.
 * @return array|bool Returns an array with data on success, or false if the file cannot be opened.
 */
function readPokemonData($file) {
    $data = [];
    $resource = fopen($file, "r");

    // If the file opening fails, return false
    if ($resource === false) {
        return false;
    } 
    else {
        while (!feof($resource)) {
            $name = fgets($resource); // Read a line as a name
            $image = fgets($resource); // Read the next line as an image

            if ($name !== false && $image !== false) {
                $data[] = ['name' => $name, 'image' => $image]; // Store name and image in the data array
            }
        }
        fclose($resource);
        return $data;
    }
}

/**
 * Reads and parses the movies.json
 *
 * @param string $file The path to the JSON file containing movies data.
 * @return array Returns an array with JSON data.
 */
function readMoviesData($file) {
    $json = file_get_contents($file); // Get the contents of the JSON file
    $data = json_decode($json, true); // Decode the JSON data
    return $data;
}

if (isset($_GET['choice']) && isset($_GET['sort'])) {

    $choice = $_GET['choice'];
    $sort = $_GET['sort'];

    // Read Pokemon data and sort alphabetically
    if ($choice === 'pokemon' && $sort === 'a') {
        $data = readPokemonData("$pokemon");
        usort($data, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
    } 
    // Read Pokemon data and sort reverse alphabetically
    elseif ($choice === 'pokemon' && $sort === 'd') {
        $data = readPokemonData("$pokemon");
        usort($data, function ($a, $b) {
            return strcmp($b['name'], $a['name']);
        });
    } 
    // Read movies data and sort alphabetically
    elseif ($choice === 'movies' && $sort === 'a') {
        $data = readMoviesData("$movies");
        usort($data, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
    } 
    // Read movies data and sort reverse alphabetically
    elseif ($choice === 'movies' && $sort === 'd') {
        $data = readMoviesData("$movies");
        usort($data, function ($a, $b) {
            return strcmp($b['name'], $a['name']);
        });
    } 
    
    echo json_encode($data); // Output the sorted data as JSON
} 
else {
    echo ("Invalid choice or sort parameter.");
}
?>
