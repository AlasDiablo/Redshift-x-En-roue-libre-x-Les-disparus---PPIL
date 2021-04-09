/**
 * Check if the name is containing between 2 and 25 letter
 * @returns {boolean} true if check have pass, false in other case
 */
const checkName = () => {
    let nameForm = document.getElementById('name-form');
    let nameFormData = document.getElementById('nom');

    if (/^[a-zA-Z]{2,25}$/.test(nameFormData.value)) {
        nameForm.innerText = 'Nom';
        nameForm.style.backgroundColor = '';
        return true;
    } else {
        nameForm.innerText = 'Votre nom doit contenir entre 2 et 25 lettres et ne pas contenir de chiffre';
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

    if (/^[a-zA-Z]{2,25}$/.test(firstnameFormData.value)) {
        firstnameForm.innerText = 'Prénom';
        firstnameForm.style.backgroundColor = '';
        return true;
    } else {
        firstnameForm.innerText = 'Votre prénom doit contenir entre 2 et 25 lettres et ne pas contenir de chiffre';
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

    if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(emailFormData.value)) {
        emailForm.innerText = 'Adresse mail';
        emailForm.style.backgroundColor = '';
        return true;
    } else {
        emailForm.innerText = 'Votre adresse mail doit etre valide';
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

    if (regexResult && passwordMatchResult) {
        passwordConfirmForm.innerText = 'Confirmation du mot de passe';
        passwordConfirmForm.style.backgroundColor = '';
        passwordForm.innerText = 'Mot de passe';
        passwordForm.style.backgroundColor = '';
        return true;
    } else {
        if (!passwordMatchResult) {
            passwordConfirmForm.innerText = 'Votre mot de passe doit être identique au précédent';
            passwordConfirmForm.style.backgroundColor = '#F00';
        }
        if (!regexResult) {
            passwordForm.innerText = 'Votre mot de passe doit faire au moins 7 caractères et contenir au minimum [a-z], [A-Z] et [0-9]';
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

    if (/[0]([6]|[7])[- .?]?([0-9][0-9][- .?]?){4}$/.test(phoneFormData.value)) {
        phoneForm.innerText = 'Numéro de téléphone';
        phoneForm.style.backgroundColor = '';
        return true;
    } else {
        phoneForm.innerText = 'Votre numéro de téléphone doit etre numéro de portable valable en france';
        phoneForm.style.backgroundColor = '#F00';
        return false;
    }
};

/**
 * Check and valid the user transaction
 * @param event form button event
 */
const createAccount = (event) => {
    let boolName = checkName();
    let boolFirstname = checkFirstname();
    let boolEmail = checkEmail();
    let boolPassword = checkPassword();
    let boolPhone = checkPhone();
    if (boolName && boolFirstname && boolEmail && boolPassword && boolPhone) event.parentNode.submit();
};

const editAccount = (event) => {
    let boolName = checkName();
    let boolFirstname = checkFirstname();
    let boolPassword;
    if (document.getElementById('mdp').value === '' && document.getElementById('confirmMdp').value === '')
        boolPassword = true;
    else
        boolPassword = checkPassword();
    let boolPhone = checkPhone();
    if (boolName && boolFirstname && boolPassword && boolPhone) event.parentNode.submit();
};

const changePassword = (event) => {
    if (checkPassword()) event.parentNode.submit();
};

const checkPassengers = () => {
    let passengers = document.getElementById('passagers-form');
    let passengersData = document.getElementById('passagers').value;

    if (passengersData >= 1 && passengersData <= 9) {
        passengers.innerText = 'Nombre de passagers max :';
        passengers.style.backgroundColor = '';
        return true;
    } else {
        passengers.innerText = 'Le nombre de passagers doit se trouvé entre 1 et 9';
        passengers.style.backgroundColor = '#F00';
        return false;
    }
};

const checkPrice = () => {
    let price = document.getElementById('prix-form');
    let priceData = document.getElementById('prix').value;
    if (priceData > 0) {
        price.innerText = 'Prix de la place:';
        price.style.backgroundColor = '';
        return true;
    } else {
        price.innerText = 'Le prix doit etre positive';
        price.style.backgroundColor = '#F00';
        return false;
    }
};

const checkLocation = () => {
    let form = document.getElementById('depart-form');
    let departData = document.getElementById('depart').value;
    let arriverData = document.getElementById('arrivee').value;
    if (departData !== arriverData) {
        form.innerText = 'Départ :';
        form.style.backgroundColor = '';
        return true;
    } else {
        form.innerText = 'Le lieux de départ et le lieux d\'arrivé doit etré diffrent';
        form.style.backgroundColor = '#F00';
        return false;
    }
}

let stages = 0;

const addStages = () => {
    let container = document.getElementById('stages');
    let label = document.createElement('label');
    let input = document.createElement('input');
    let br = document.createElement('br');
    if (stages === 0) container.append(document.createElement('br'));
    stages++;
    label.setAttribute('for', 'etapes[' + stages + ']')
    label.innerText = 'étape ' + (stages + 1);
    input.type = 'text';
    input.name = 'stages[' + stages + ']';
    input.id = 'etapes[' + stages + ']';
    container.append(label, input, br);
};

const createRide = (event) => {
    let boolCheckPassengers = checkPassengers();
    let boolCheckPrice = checkPrice();
    let boolCheckLocation = checkLocation();
    if (boolCheckPassengers && boolCheckPrice && boolCheckLocation) event.parentNode.submit();
};
