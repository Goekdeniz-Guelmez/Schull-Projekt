<?php
include "dbConfig.php"; // Einbinden der Datenbankkonfigurationsdatei
?>

<!-- HTML ANFANG -->

<!DOCTYPE html>
<html lang="de">

<!-- Head Sektion -->
<head>
    <meta charset="utf8">
    <title>Smarthome Produkte - Smart GmBH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<!-- Body Sektion -->
<body>
    <!-- Nav Sektion -->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">Smart GmbH</a>
            </div>

            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="home.php">Produkte</a>
                </li>
                <li>
                    <a href="registrieren.php">Registrierung</a>
                </li>
                <li>
                    <a href="bestellen.php">Bestellformular</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ------------------------------------ -->
    <!-- Div Sektion -->
    <div class="container">
        <h1>Unsere Produkte</h1>
        <br>

        <div id="products" class="row list-group">
        
        <?php
        $query = $db->query("SELECT * FROM Artikel ORDER BY ANr LIMIT 10");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) { ?>
        
        <div class="item col-lg-4">
            <div class="thumbnail">

                <!-- PRODUKTE ANZEIGEN UND ITERIEREN -->
                <img
                    src="
                        <?php echo $row[
                            "Bild"
                        ]; ?>
                    "

                    alt="
                        <?php echo htmlspecialchars(
                            $row["Bild"]
                        ); ?>
                    "
                    class="product-image"
                >

                <div class="caption">
                    <h4 class="list-group-item-heading"><?php echo htmlspecialchars(
                        $row["Bezeichnung"]
                    ); ?></h4>

                    <p class="list-group-item-text" style="padding-bottom:10px"><?php echo htmlspecialchars(
                        $row["Beschreibung"]
                    ); ?></p>

                    <h3 class="list-group-item-heading">Produkt ID: <?php echo htmlspecialchars(
                        $row["ANr"]
                    ); ?></h3>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead">
                                <?php echo
                                $row["Preis"] .
                                " €"; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php }
        } else {
             ?>
        <p>Produkte wurden nicht gefunden.</p>
        <?php
        }
        ?>

    </div>
    
    <!-- Footer Sektion -->
    <footer>
        <p>Developed by Gökdeniz and Ralf. Databank modelling by Adrian and Elias, Project Management by Natalie</p>
    </footer>

</body>

</html>