<?php
// Hier wird die Datenbank verknüpft
include "dbConfig.php";
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
    <nav class="navbar navbar-inverse"  style="border-radius: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">E-Shop</a>
            </div>

            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="home.php">Home (Produkte)</a>
                </li>
                <li>
                    <a href="registrieren.php">Registrierung</a>
                </li>
                <li>
                    <a href="bestellen.php">bestellungs formular</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ------------------------------------ -->
    <!-- Div Sektion -->
    <div class="container">
        <h1>Produkte</h1>
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
                                <?php echo "€" .
                                $row["Preis"] .
                                " EU"; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php }
        } else {
             ?>
        <p>Produkt(e) wurden nicht gefunden.</p>
        <?php
        }
        ?>

    </div>

    </div>
</body>

</html>