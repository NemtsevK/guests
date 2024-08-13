import { loadData } from './utils.js';


async function initCountries() {
  const result = await sendCountries();
  const form__select = document.querySelector('.form__select');

  result.forEach(({ name }) => {
    const optionElement = document.createElement('option');
    optionElement.innerText = name;
    form__select.appendChild(optionElement);
  });
}

/**
 * Отправить запрос на сервер для создания или обновления гостя
 * @return {Promise<boolean|*>}
 */
async function sendCountries() {
  try {
    return await loadData({ url: 'php/countries.php' });
  } catch (error) {
    return false;
  }
}

export { initCountries }
