function verifPassagers() {
    let passagers = document.getElementById("passagers").innerHTML;

    return (passagers >= 1) && (passagers <=9);
}

function verifPrix() {
    let prix = document.getElementById("prix").innerHTML;

    return prix >0;
}

function verifEtapes() {
    let etapes = document.getElementById("etapes").innerHTML;
    let depart = document.getElementById("depart").innerHTML;
    let arrivee = document.getElementById("arrivee").innerHTML;

    return (etapes != depart) && (etapes != arrivee);
}

function verifLieu() {
    let lieu = document.getElementById("lieu").innerHTML;

    return lieu.length <2000;
}

function verifCommentaires() {
    let commentaires = document.getElementById("commentaires").innerHTML;

    return commentaires.length <2000;
}

function verifDate() {
    let date = document.getElementById("date").innerHTML;
    let dateNow = Date();

    return date>dateNow;
}

function verifForm() {
    let passagers = verifPassagers();
    let prix = verifPrix();
    let etapes = verifEtapes();
    let lieu = verifLieu();
    let commentaires = verifCommentaires();
    let date = verifDate();

    if (passagers && prix && etapes && lieu && commentaires && date) {
        document.getElementById("form").submit();
    }
    else {
        let newP = document.createElement("p");
        let newContent = document.createTextNode("Veuillez saisir des valeurs correctes");
        newP.appendChild(newContent);
    }
}