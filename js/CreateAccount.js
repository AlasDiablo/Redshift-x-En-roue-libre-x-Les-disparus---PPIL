function verifMail() {
    let mail = document.getElementById("email").innerHTML;

    return /^.+@.+[.].+$/.test(mail);
}

function verifMdp() {
    let mdp = document.getElementById("mdp").innerHTML;
    let confirm = document.getElementById("confirmMdp").innerHTML;

    return mdp === confirm;
}

function verifTel() {
    let tel = document.getElementById("tel").innerHTML;

    return tel.length === 10;
}

function verifForm() {
    let mail = verifMail();
    let mdp = verifMdp();
    let tel = verifTel();

    if(mail && mdp && tel) {
        document.getElementById("form").submit();
    }
    else {
        let newP = document.createElement("p");
        let newContent = document.createTextNode("Veuillez saisir des valeurs correctes");
        newP.appendChild(newContent);
    }
}