const BASE_URL = '//localhost:8080/';

const SPACES = /^[\s\n\t]+$/;

const TEXT = {
  string: /^[A-zА-яЁё\s-]+$/,
  name: 'Разрешены только буквы, дефис и пробел',
}

const PHONE = {
  string: /^[0-9+]+$/,
  name: 'Неправильный формат номера телефона',
}

const EMAIL = {
  string: /^([a-z0-9._-]+@[a-z0-9._-]+)$/,
  name: 'Неправильный формат электронной почты',
}

const inputsOptions = [
  {
    id: 'first-name',
    pattern: TEXT,
    max: 100,
    required: true,
  },
  {
    id: 'last-name',
    pattern: TEXT,
    max: 100,
    required: true,
  },
  {
    id: 'phone',
    pattern: PHONE,
    max: 20,
    required: true,
  },
  {
    id: 'email',
    pattern: EMAIL,
    max: 100,
    required: false,
  },
];

export { BASE_URL, SPACES, inputsOptions }
