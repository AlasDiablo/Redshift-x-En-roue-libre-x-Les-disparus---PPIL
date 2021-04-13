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
        nameForm.hidden = true;
        return true;
    } else {
        nameForm.innerText = 'Votre nom doit contenir entre 2 et 25 lettres et ne pas contenir de chiffre';
        nameForm.style.backgroundColor = '#F00';
        nameForm.hidden = false;
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
        firstnameForm.hidden = true;
        return true;
    } else {
        firstnameForm.innerText = 'Votre prénom doit contenir entre 2 et 25 lettres et ne pas contenir de chiffre';
        firstnameForm.style.backgroundColor = '#F00';
        firstnameForm.hidden = false;
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
        emailForm.hidden = true;
        return true;
    } else {
        emailForm.innerText = 'Votre adresse mail doit etre valide';
        emailForm.style.backgroundColor = '#F00';
        emailForm.hidden = false;
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
        passwordForm.hidden = true;
        passwordConfirmForm.hidden = true;
        return true;
    } else {
        if (!passwordMatchResult) {
            passwordConfirmForm.innerText = 'Votre mot de passe doit être identique au précédent';
            passwordConfirmForm.style.backgroundColor = '#F00';
            passwordConfirmForm.hidden = false;
        }
        if (!regexResult) {
            passwordForm.innerText = 'Votre mot de passe doit faire au moins 7 caractères et contenir au minimum [a-z], [A-Z] et [0-9]';
            passwordForm.style.backgroundColor = '#F00';
            passwordForm.hidden = false;
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
        phoneForm.hidden = true;
        return true;
    } else {
        phoneForm.innerText = 'Votre numéro de téléphone doit etre numéro de portable valable en france';
        phoneForm.style.backgroundColor = '#F00';
        phoneForm.hidden = false;
        return false;
    }
};

/**
 * function call for checking and valid the account creation
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

/**
 * Function call for checking all data on account edition
 * @param event user transaction event
 */
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

/**
 * Check the new user password
 * @param event user transaction event
 */
const changePassword = (event) => {
    if (checkPassword()) event.parentNode.submit();
};

/**
 * Check if the number of passagers is correct
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check if the price is positive
 * @returns {boolean} true if check have pass, false in other case
 */
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

/**
 * Check if the start is different to the end
 * @returns {boolean} true if check have pass, false in other case
 */
const checkLocation = () => {
    let form = document.getElementById('depart-form');
    let startData = document.getElementById('depart').value;
    let endData = document.getElementById('arrivee').value;
    if (startData !== endData) {
        form.innerText = 'Départ :';
        form.style.backgroundColor = '';
        return true;
    } else {
        form.innerText = 'Le lieux de départ et le lieux d\'arrivé doit etré diffrent';
        form.style.backgroundColor = '#F00';
        return false;
    }
}

/**
 * Store the current stages id
 * @type {number} stage id
 */
let stages = 0;

/**
 * Function call for create a new stage between the ride start and the ride end
 */
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

/**
 * Function call for checking all data on ride creation
 * @param event user transaction event
 */
const createRide = (event) => {
    let boolCheckPassengers = checkPassengers();
    let boolCheckPrice = checkPrice();
    let boolCheckLocation = checkLocation();
    if (boolCheckPassengers && boolCheckPrice && boolCheckLocation) event.parentNode.submit();
};

/**
 * Check if the group name is containing between 3 and 25 letter
 * @returns {boolean} true if check have pass, false in other case
 */
const checkGroupName = () => {
    let groupNameForm = document.getElementById('groupname-form');
    let groupNameFormData = document.getElementById('nomGroupe');

    if (/^.{3,25}$/.test(groupNameFormData.value)) {
        groupNameForm.innerText = 'Nom du groupe';
        groupNameForm.style.backgroundColor = '';
        return true;
    } else {
        groupNameForm.innerText = 'Le nom de groupe doit contenir entre 3 et 25 caractères';
        groupNameForm.style.backgroundColor = '#F00';
        return false;
    }
};

/**
 * Check and valid the group of friends transaction
 * @param event form button event
 */
const createGroup = (event) => {
    let boolCheckGroupName = checkGroupName();
    if (boolCheckGroupName) event.parentNode.submit();
}
