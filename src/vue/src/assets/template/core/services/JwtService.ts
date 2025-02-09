const ID_TOKEN_KEY = "id_token" as string;
const ID_USER = "id_user" as string;

/**
 * @description get token form localStorage
 */
export const getToken = (): string | null => {
  return window.localStorage.getItem(ID_TOKEN_KEY);
};

/**
 * @description save token into localStorage
 * @param token: string
 */
export const saveToken = (token: string): void => {
  window.localStorage.setItem(ID_TOKEN_KEY, token);
};

/**
 * @description remove token from localStorage
 */
export const destroyToken = (): void => {
  window.localStorage.removeItem(ID_TOKEN_KEY);
};

/**
 * @description get user from localStorage
 */
export const getUser = (): object | null => {
  const userJSON = window.localStorage.getItem(ID_USER);
  return userJSON ? JSON.parse(userJSON) : null;
};

/**
 * @description save token user localStorage
 * @param user: object
 */
export const saveUser = (user: object): void => {
  const userJSON = JSON.stringify(user);
  window.localStorage.setItem(ID_USER, userJSON);
};

/**
 * @description remove user from localStorage
 */
export const destroyUser = (): void => {
  window.localStorage.removeItem(ID_USER);
};

export default { getToken, saveToken, destroyToken, getUser, saveUser, destroyUser };
