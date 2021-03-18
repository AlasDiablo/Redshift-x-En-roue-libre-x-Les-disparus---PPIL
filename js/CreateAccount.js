function verifMail() {
    let mail = document.getElementById("email").innerHTML;

    if( !/^.+@.+[.].+$/.test(mail) ) {}
}

function verifMdp() {
    let mdp = document.getElementById("mdp").innerHTML;
    let confirm = document.getElementById("confirmMdp").innerHTML;

    if(mdp != confirm) {
        document.getElementById("mdp").
    }
}

function verifTel() {
    let tel = document.getElementById("tel").innerHTML;

    if( tel.length != 10) {}
}

function verifForm() {
    verifMail();
    verifMdp();
    verifTel();
}