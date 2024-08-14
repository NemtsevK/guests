import { loadData, setModal } from './utils.js';

const DEFAULT_URL = 'countries.php';

async function initCountries() {
  const result = await sendCountries();
  const { success, message, items } = result;

  if (success === false) {
    setModal(message);
    return;
  }

  const form__select = document.querySelector('.form__select');

  items.forEach(({ name }) => {
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
    return await loadData({ url: DEFAULT_URL });
  } catch (error) {
    return false;
  }
}

export { initCountries }
