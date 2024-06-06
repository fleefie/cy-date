/*ANIMATION POUR L'OUVERTURE ET LA FERMETURE DU FORMULAIRE + CHANGEMENT*/

const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnLog = document.querySelector('.btnLogin');
const iconClose = document.querySelector('.icon-close');
const iconCloseR = document.querySelector('.icon-close');

registerLink.addEventListener('click', ()=> {
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', ()=> {
    wrapper.classList.remove('active');
});

btnLog.addEventListener('click', ()=> {
    wrapper.classList.add('active-popup');
});

iconClose.addEventListener('click', ()=> {
    wrapper.classList.remove('active-popup');
});

iconCloseR.addEventListener('click', ()=> {
    wrapper.classList.remove('active-popup');
});

