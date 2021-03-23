function verifMail() {
    let mail = document.getElementById("email").innerHTML;

    return /^.+@.+[.].+$/.test(mail);
}

function verifMdp() {
    let mdp = document.getElementById("mdp").innerHTML;
    let confirm = document.getElementById("confirmMdp").innerHTML;

    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})/.test(password)) {
        return false;
    }

    return mdp === confirm;
}

function verifTel() {
    let tel = document.getElementById("tel").innerHTML;

    return /^0[1-9]{9}$/.test(tel);
}

function verifNom() {
    let nom = document.getElementById("nom").innerHTML;

    return /^(?=.*[a-z])(?=.*[A-Z])(?=.{2,25})/.test(nom);
}

function verifPrenom() {
    let prenom = document.getElementById("prenom").innerHTML;

    return /^(?=.*[a-z])(?=.*[A-Z])(?=.{2,25})/.test(prenom);
}

function verifForm() {
    let mail = verifMail();
    let mdp = verifMdp();
    let tel = verifTel();
    let nom = verifNom();
    let prenom = verifPrenom();

    if (mail && mdp && tel && nom && prenom) {
        document.getElementById("form").submit();
    }
    else {
        let newP = document.createElement("p");
        let newContent = document.createTextNode("Veuillez saisir des valeurs correctes");
        newP.appendChild(newContent);
    }
}