const checkName = () => {
    let nameForm = document.getElementById('name-form');
    let nameFormData = document.getElementById('nom');

    if (/^(?=.*[a-z])(?=.*[A-Z])(?=.{2,25})/.test(nameFormData.value))
        return true;
    else {
        nameForm.innerText = 'Votre nom doit faire contenir entre 2 et 25 lettre et ne pas contenir de chiffre';
        nameForm.style.backgroundColor = '#F00';
        return false;
    }
};

const checkFirstname = () => {
    let firstnameForm = document.getElementById('firstname-form');
    let firstnameFormData = document.getElementById('prenom');

    if (/^(?=.*[a-z])(?=.*[A-Z])(?=.{2,25})/.test(firstnameFormData.value))
        return true;
    else {
        firstnameForm.innerText = 'Votre prénom doit faire contenir entre 2 et 25 lettre et ne pas contenir de chiffre';
        firstnameForm.style.backgroundColor = '#F00';
        return false;
    }
};

const checkEmail = () => {
    let emailForm = document.getElementById('email-form');
    let emailFormData = document.getElementById('email');

    if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(emailFormData.value))
        return true;
    else {
        emailForm.innerText = 'Votre email doit etre un email valide';
        emailForm.style.backgroundColor = '#F00';
        return false;
    }
};

const checkPassword = () => {
    let passwordForm = document.getElementById('password-form');
    let passwordConfirmForm = document.getElementById('password-confirm-form');
    let passwordFormData = document.getElementById('mdp');
    let passwordConfirmFormData = document.getElementById('confirmMdp');
    let regexResult = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{7,})/.test(passwordFormData.value);
    let passwordMatchResult = passwordFormData.value === passwordConfirmFormData.value;

    if (regexResult && passwordMatchResult)
        return true;
    else {
        if (!passwordMatchResult) {
            passwordConfirmForm.innerText = 'Votre mot de passe doit etre idantique au precédant';
            passwordConfirmForm.style.backgroundColor = '#F00';
        }
        if (!regexResult) {
            passwordForm.innerText = 'Votre mot de passe doit faire au moins 7 character et contenir au minimum [a-z], [A-Z] et [0-9]';
            passwordForm.style.backgroundColor = '#F00';
        }
        return false;
    }
};

const checkPhone = () => {
    let phoneForm = document.getElementById('phone-form');
    let phoneFormData = document.getElementById('tel');

    if (/[0]([6]|[7])[- .?]?([0-9][0-9][- .?]?){4}$/.test(phoneFormData.value))
        return true;
    else {
        phoneForm.innerText = 'Votre numéro de téléphone doit etre numéro de portable valable en france'
        phoneForm.style.backgroundColor = '#F00';
        return false;
    }
}

const createAccount = (event) => {
    if (checkName() && checkFirstname() && checkEmail() && checkPassword() && checkPhone()) event.parentNode.submit();
};
