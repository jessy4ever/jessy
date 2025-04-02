<?php
$servername = "localhost";
$username = "root"; // Modifier si nécessaire
$password = ""; // Laisser vide si XAMPP
$dbname = "test";

// Connexion à MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protéger les données contre les erreurs SQL
    $doc_title = mysqli_real_escape_string($conn, $_POST['doc_title']);
    $doc_keywords = mysqli_real_escape_string($conn, $_POST['doc_keywords']);
    $document = $_FILES['document'];

    if ($document['error'] == 0) {
        $target_dir = "C:/DocumentsIR/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($document["name"]);

        if (move_uploaded_file($document["tmp_name"], $target_file)) {
            $sql = "INSERT INTO documents (doc_title, doc_keywords, doc_path) 
                    VALUES ('$doc_title', '$doc_keywords', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                header("Location: success.html");
                exit();
            } else {
                echo "Erreur : " . $conn->error;
            }
        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        echo "Veuillez sélectionner un fichier.";
    }
}

$conn->close();
?>
