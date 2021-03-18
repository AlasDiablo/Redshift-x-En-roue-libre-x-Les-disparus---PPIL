function verifMail() {
    let mail = document.getElementById("email").innerHTML;

    if( !/^.+@.+[.].+$/.test(mail) ) {
        return false;
    }

    return true;
}

function verifMdp() {
    let mdp = document.getElementById("mdp").innerHTML;
    let confirm = document.getElementById("confirmMdp").innerHTML;

    if(mdp != confirm) {
        return false;
    }

    return true;
}

function verifTel() {
    let tel = document.getElementById("tel").innerHTML;

    if(tel.length != 10) {
        return false;
    }

    return true;
}

function verifForm() {
    mail = verifMail();
    mdp = verifMdp();
    tel = verifTel();

    if(mail && mdp && tel) {
        document.getElementById("form").submit();
    }
    else {
        var newP = document.createElement("p");
        var newContent = document.createTextNode("Veuillez saisir des valeurs correctes");
        newP.appendChild(newContent);
    }
}