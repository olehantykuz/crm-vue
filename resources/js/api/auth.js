const baseUrl = '/api';

const register = data => {
  return axios({
    url: `${baseUrl}/register`,
    method: 'post',
    data,
  }).then(response => response);
};

const login = data => {
  return axios({
    url: `${baseUrl}/login`,
    method: 'post',
    data,
  }).then(response => {
    localStorage.setItem('auth', JSON.stringify(true));

    return response;
  });
};

const getCookies = () => {
  return axios({
    url: `/sanctum/csrf-cookie`,
    method: 'get',
  }).then(response => response);
};

const logout = () => {
  return axios({
    url: `${baseUrl}/logout`,
    method: 'post',
  }).then(response => {
    localStorage.removeItem('auth');

    return response;
  });
};

export const authService = { register, getCookies, login, logout };
