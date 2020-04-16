const baseUrl = '/api';
const { axios } = window;

const register = (data) => axios({
  url: `${baseUrl}/register`,
  method: 'post',
  data,
}).then((response) => response);


const login = (data) => axios({
  url: `${baseUrl}/login`,
  method: 'post',
  data,
}).then((response) => {
  localStorage.setItem('auth', JSON.stringify(true));

  return response;
});

const authUser = () => axios({
  url: `${baseUrl}/me`,
  method: 'get',
}).then((response) => response);

const getCookies = () => axios({
  url: '/sanctum/csrf-cookie',
  method: 'get',
}).then((response) => response);

const logout = () => axios({
  url: `${baseUrl}/logout`,
  method: 'post',
}).then((response) => {
  localStorage.removeItem('auth');

  return response;
});

const authService = {
  register, getCookies, login, authUser, logout,
};

export default authService;
