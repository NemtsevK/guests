import { loadData, setModal } from './utils.js';
import { initValidation, handleFormSubmit, handleFormClick, handleFormInput } from './validation.js';

/**
 * Получить ответ от сервера после создания или обновления гостя
 * @param form
 * @return {Promise<void>}
 */
async function setFormSuccess(form) {
  const result = await sendGuestData();

  if (result.success === false) {
    setModal(result.message);
    return;
  }

  const table = document.querySelector('table');
  table.removeEventListener('click', handleTableClick);

  setModal(result.message);
  await getReadData();
  const buttonAdd = document.querySelector('.guests__button ');
  const hiddenInput = document.querySelector('#id ');

  buttonAdd.classList.remove('guests__button--hide');
  form.classList.add('form--hide');
  form.reset();
  hiddenInput.value = '';
}

/**
 * Отправить запрос на сервер для создания или обновления гостя
 * @return {Promise<boolean|*>}
 */
async function sendGuestData() {
  const id = document.querySelector('#id').value;
  const firstName = document.querySelector('#first-name').value;
  const lastName = document.querySelector('#last-name').value;
  const phone = document.querySelector('#phone').value;
  const email = document.querySelector('#email').value;
  const country = document.querySelector('#country').value;

  const formData = new URLSearchParams();
  formData.append('id', id);
  formData.append('first_name', firstName);
  formData.append('last_name', lastName);
  formData.append('phone', phone);
  formData.append('email', email);
  formData.append('country', country);

  const url = id ? 'php/update.php' : 'php/create.php';

  try {
    return await loadData({ url, method: 'POST', body: formData });
  } catch (error) {
    return false;
  }
}

/**
 * Получить список гостей
 * @return {Promise<void>}
 */
async function getReadData() {
  const result = await sendReadGuests();

  if (result === false) {
    setModal('Ошибка получения данных');
    return;
  }

  const table = document.querySelector('.table');
  const tableBody = table.querySelector('.table__body');
  tableBody.innerHTML = '';

  if (result.length > 0) {
    table.classList.remove('table--hide');
  } else {
    table.classList.add('table--hide');
  }

  result.forEach(contact => {
    const { id, first_name, last_name, phone, email, country } = contact
    const row = document.createElement('tr');

    row.classList.add('table__body-row')
    row.innerHTML = `
      <td class="table__body-cell">${id}</td>
      <td class="table__body-cell">${first_name}</td>
      <td class="table__body-cell">${last_name}</td>
      <td class="table__body-cell">${phone}</td>
      <td class="table__body-cell">${email ?? ''}</td>
      <td class="table__body-cell">${country ?? ''}</td>
      <td class="table__body-cell">
        <div class="table__buttons">
          <button class="table__button table__button--edit" value="${id}" title="Изменить"></button>
          <button class="table__button table__button--delete" value="${id}" title="Удалить"></button>
        </div>
      </td>
    `;

    tableBody.appendChild(row);
  });

  table.addEventListener('click', handleTableClick);
}

/**
 * Обработчик событий для таблицы
 * @param target
 */
function handleTableClick({ target }) {
  if (target.classList.contains('table__button--edit')) {
    const buttonAdd = document.querySelector('.guests__button');
    const form = document.querySelector('.form');

    buttonAdd.classList.add('guests__button--hide');
    form.classList.remove('form--hide');
    getEditGuest(target.value);
    initValidation();
  }

  if (target.classList.contains('table__button--delete')) {
    getDeleteGuest(target.value);
    const form = document.querySelector('.form');

    if (!form.classList.contains('form--hide')) {
      const buttonAdd = document.querySelector('.guests__button ');
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
}

/**
 * Отправить запрос на сервер для получения списка гостей
 * @return {Promise<boolean|*>}
 */
async function sendReadGuests() {
  try {
    return await loadData({ url: 'php/read.php' });
  } catch (error) {
    return false;
  }
}

/**
 * Получить данные для редактирования гостя
 * @param value
 * @return {Promise<void>}
 */
async function getEditGuest(value) {
  const result = await sendEditGuest(value);

  if (result === false) {
    setModal('Ошибка получения данных');
    return;
  }

  const { id, first_name, last_name, phone, email, country } = result;
  document.querySelector('#id').value = id;
  document.querySelector('#first-name').value = first_name;
  document.querySelector('#last-name').value = last_name;
  document.querySelector('#phone').value = phone;
  document.querySelector('#email').value = email;
  document.querySelector('#country').value = country;
}

/**
 * Отправить запрос на сервер для редактирования гостя
 * @param value
 * @return {Promise<boolean|*>}
 */
async function sendEditGuest(value) {
  const formData = new URLSearchParams();
  formData.append('id', value);

  try {
    return await loadData({ url: 'php/read.php', method: 'POST', body: formData });
  } catch (error) {
    return false;
  }
}

/**
 * Получить ответ от сервера после удаления гостя
 * @param value
 * @return {Promise<void>}
 */
async function getDeleteGuest(value) {
  const result = await sendDeleteGuest(value);

  if (result === false) {
    setModal('Ошибка удаления данных');
    return;
  }

  setModal(result.message);
  await getReadData();
}

/**
 * Отправить запрос на сервер для удаления гостя
 * @param value
 * @return {Promise<boolean|*>}
 */
async function sendDeleteGuest(value) {
  const formData = new URLSearchParams();
  formData.append('id', value);

  try {
    return await loadData({ url: 'php/delete.php', method: 'POST', body: formData });
  } catch (error) {
    return false;
  }
}

export { setFormSuccess, getReadData }
