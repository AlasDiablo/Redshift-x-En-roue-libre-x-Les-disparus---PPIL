function verifMail() {
    let mail = document.getElementById("email").innerHTML;

    if (!/^.+@.+[.].+$/.test(mail)) {
        return false;
    }

    return true;
}

function verifMdp() {
    let mdp = document.getElementById("mdp").innerHTML;
    let confirm = document.getElementById("confirmMdp").innerHTML;

    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})/.test(password)) {
        return false;
    }

    if (mdp != confirm) {
        return false;
    }

    return true;
}

function verifTel() {
    let tel = document.getElementById("tel").innerHTML;

    if (!/^0[1-9]{9}$/.test(phone)) {
        return false;
    }

    return true;
}

function verifNom() {
    let nom = document.getElementById("nom").innerHTML;

    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.{2,25})/.test(name)) {
        return false;
    }

    return true;
}

function verifPrenom() {
    let prenom = document.getElementById("prenom").innerHTML;

    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.{2,25})/.test(name)) {
        return false;
    }

    return true;
}

function verifForm() {
    mail = verifMail();
    mdp = verifMdp();
    tel = verifTel();
    nom = verifNom();
    prenom = verifPrenom();

    if (mail && mdp && tel && nom && prenom) {
        document.getElementById("form").submit();
    }
    else {
        var newP = document.createElement("p");
        var newContent = document.createTextNode("Veuillez saisir des valeurs correctes");
        newP.appendChild(newContent);
    }
}