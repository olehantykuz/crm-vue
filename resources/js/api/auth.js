const register = data => {
  return axios({
    url: `/api/register`,
    method: 'post',
    data,
  }).then(response => response);
};

export const authService = { register };
