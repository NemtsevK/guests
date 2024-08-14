import { BASE_URL } from './const.js';

/**
 * загрузка данных на сервер и их получение
 * @param {string}url
 * @param {string}method
 * @param {URLSearchParams|Object|null} body
 * @param {Object} headers
 * @returns {Promise<any>}
 */
async function loadData({
  url,
  method = 'GET',
  body = null,
  headers = { 'Content-Type': 'application/x-www-form-urlencoded' },
}) {
  try {
    const response = await fetch(`${BASE_URL}${url}` , { method, body, headers });
    return await response.json();
  } catch (error) {
    throw Error(error);
  }
}

/**
 * добавить модальное окно
 * @param text
 */
function setModal(text) {
  const page = document.querySelector('.page');
  const modal = document.querySelector('.modal');
  const modalText = modal.querySelector('.modal__text')
  const buttonModal = modal.querySelector('.modal__button');

  const closeModal = () => {
    modal.close();
    page.classList.remove('page--scroll-lock');
  }

  const onButtonClick = () => closeModal();

  const onModalClick = ({ currentTarget, target }) => {
    if (target === currentTarget) {
      closeModal();
    }
  }

  page.classList.add('page--scroll-lock');
  modal.showModal();
  modalText.innerText = text;

  modal.addEventListener('click', onModalClick);
  modal.addEventListener('keydown', (event) => {
    if (event.code === 'Escape') {
      closeModal();
    }
  })

  buttonModal.addEventListener('click', onButtonClick);
}

export { loadData, setModal }
