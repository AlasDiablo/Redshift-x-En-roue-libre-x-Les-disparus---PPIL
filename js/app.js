/**
 * Check if the name is containing between 2 and 25 letter
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check if the firstname is containing between 2 and 25 letter
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check if the email is an valid email [user](.[user])@[domain].[top-level domain]
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check if the password are equals, and if is containing one upper case letter, lower case letter and a numeric value
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check if the phone number are a valid french mobile phone number
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check and valid the user transaction
 * @param event form button event
 */
const createAccount = (event) => {
    if (checkName() && checkFirstname() && checkEmail() && checkPassword() && checkPhone()) event.parentNode.submit();
};

const editAccount = (event) => {
    if (checkName() && checkFirstname() && checkPassword() && checkPhone()) event.parentNode.submit();
};

const changePassword = (event) => {
    if (checkPassword()) event.parentNode.submit();
};
