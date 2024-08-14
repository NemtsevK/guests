import { initValidation } from './validation.js';
import { getReadData } from './contacts.js';
import { initCountries } from './countries.js';

const buttonAdd = document.querySelector('.guests__button ');

const onButtonAddClick = ({ target }) => {
  const form = document.querySelector('.form');

  target.classList.add('guests__button--hide');
  form.classList.remove('form--hide');
  initValidation();
}

buttonAdd.addEventListener('click', onButtonAddClick)

initCountries();
getReadData();
