const baseUrl = '/api/categories';
const { axios } = window;

const create = (data) => axios({
  url: baseUrl,
  method: 'post',
  data,
}).then((response) => response);

const categoryService = {
  create,
};

export default categoryService;
