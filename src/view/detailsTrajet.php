<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <?php include 'RideController.php';?>

</head>
    <body>
        <section>
            <section>
                <p>Départ:<?echo getRide($_GET[id])->ville_depart;?></p>
                <p>Arrivée:<?echo getRide($_GET[id])->ville_arrivee;?></p>
                <p>Date:<?echo getRide($_GET[id])->date;?></p>
            </section>
            <p>Nombre de passagers max:<?echo getNbPlacesOccupee($_GET[id])->nbr_passager;?></p>
            <p>Nombre de places occupées:<?echo getRide($_GET[id])->nbr_passager;?></p>
            <p>Heure de départ:<?echo getRide($_GET[id])->heure_depart;?></p>
            <p>Prix de la place:<?echo getRide($_GET[id])->prix;?></p>
            <p>Etapes intérmediaires:</p>
            <?php
            foreach (getEtape($_GET[id]) as &$ville) {
                echo "<li>ville->ville</li>";
            }
            ?>
            <p>Lieux de rendez-vous:<?echogetRide($_GET[id])->lieuxRDV?></p>
            <p>Commentaires/contraintes:<?echogetRide($_GET[id])->commentaires?></p>
            <p>Passagers actuels:</p>
            <?php
            foreach (getPassager($_GET[id]) as &$passager) {
                echo "<li>passager->prenom passager->nom</li>";
            }
            ?>
        </section>

        <section>
            <button>Annuler le Trajet</button>
            <button>Quitter</button>
        </section>
</body>
</html>