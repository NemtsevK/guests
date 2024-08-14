import { setFormSuccess } from './contacts.js';
import { SPACES, inputsOptions } from './const.js';

/**
 * Инициализация валидации
 */
function initValidation() {
  const form = document.querySelector('.form');

  form.addEventListener('submit', handleFormSubmit);
  form.addEventListener('click', handleFormClick);
  form.addEventListener('input', handleFormInput);
}

/**
 *
 * @param inputElement
 * @param textElements
 * @return {null}
 */
function findTextElement(inputElement, textElements) {
  let element = null

  textElements.forEach((textElement) => {
    if (`${inputElement.id}-error` === textElement.id) {
      element = textElement;
    }
  });

  return element;
}

/**
 * поиск опции полей ввода
 * @param inputElement
 * @return {{max: number, pattern: {string: RegExp, name: string}, id: string, required: boolean}}
 */
function findInputOption(inputElement) {
  return inputsOptions.find((option) => inputElement.id === option.id);
}

/**
 * установка стилей для поля ввода и блока информации
 * @param inputElement
 * @param textElement
 */
function setValidInputField(inputElement, textElement) {

  const inputOption = findInputOption(inputElement);
  const inputErrorClass = 'form__input--error';
  const textErrorClass = 'form__text-error--active';
  const inputValue = inputElement.value;
  const { required, max, pattern } = inputOption;

  let textError = '';
  let isValid = true;

  if (inputValue === '' && inputOption.required === true) {
    textError = 'Обязательно к заполнению';
    isValid = false;
  } else if (inputValue.length > max && max !== null) {
    textError = `Количество символов не может быть больше ${max}`;
    isValid = false;
  } else if (pattern !== null && pattern.string.test(inputValue) === false && (inputValue !== '' && required === false || required === true)) {
    // проверка на регулярное выражение
    textError = pattern.name;
    isValid = false;
  } else if (SPACES.test(inputValue)) {
    textError = 'Запрещены одни пробелы';
    isValid = false;
  }

  if (textElement !== null) {
    if (isValid === true) {
      inputElement.classList.remove(inputErrorClass);
      textElement?.classList.remove(textErrorClass);
    } else {
      inputElement.classList.add(inputErrorClass);
      textElement?.classList.add(textErrorClass);
    }

    textElement.innerText = textError;
  }
}

/**
 * проверка валидации полей ввода
 */
function isEnableButton() {
  const form = document.querySelector('.form');
  const inputsElements = form.querySelectorAll('.form__input');

  return inputsOptions.every((inputOption) => isValidInput(inputsElements, inputOption));
}

/**
 * проверка на валидность поля ввода
 * @param inputsElements
 * @param inputOption
 * @return {boolean|boolean}
 */
function isValidInput(inputsElements, inputOption) {
  const { required, max, pattern } = inputOption;
  const value = findInputValue(inputsElements, inputOption)

  return (
    pattern.string.test(value)
    && (value.length <= max || max === null)
    && SPACES.test(value) === false
    && (value !== '' && required === true || required === false)
    || value === '' && required === false
  );
}

/**
 * поиск значения элемента по id
 * @param inputsElements
 * @param inputOption
 * @return {string}
 */
function findInputValue(inputsElements, inputOption) {
  let value = ''

  inputsElements.forEach((inputElement) => {
    if (inputOption.id === inputElement.id) {
      value = inputElement.value;
    }
  });

  return value;
}

/**
 *
 * @param target
 */
function handleFormClick({ target }) {
  if (target.classList.contains('form__button--reset')) {
    const buttonAdd = document.querySelector('.guests__button ');
    const form = document.querySelector('.form');
    const hiddenInput = form.querySelector('#id ');

    buttonAdd.classList.remove('guests__button--hide');
    form.classList.add('form--hide');
    form.reset();
    hiddenInput.value = '';

    form.removeEventListener('submit', handleFormSubmit);
    form.removeEventListener('click', handleFormClick);
    form.removeEventListener('input', handleFormInput);
  }
}

/**
 *
 * @param event
 */
function handleFormSubmit(event) {
  event.preventDefault();

  if (isEnableButton()) {
    setFormSuccess(event.currentTarget);
  } else {
    const form = document.querySelector('.form');
    const inputsElements = form.querySelectorAll('.form__input');
    const textElements = form.querySelectorAll('.form__text-error');

    inputsElements.forEach((inputElement) => {
      const textElement = findTextElement(inputElement, textElements);
      setValidInputField(inputElement, textElement);
    });
  }
}

/**
 *
 * @param target
 */
function handleFormInput({ target }) {
  if (target.classList.contains('form__input')) {
    onElementInput(target);
  }
}

/**
 *
 * @param inputElement
 */
function onElementInput(inputElement) {
  const form = document.querySelector('.form');
  const textElements = form.querySelectorAll('.form__text-error');
  const textElement = findTextElement(inputElement, textElements);

  setValidInputField(inputElement, textElement);
}

export { initValidation, handleFormSubmit, handleFormClick, handleFormInput }
