const baseUrl = '/api/categories';
const { axios } = window;

const create = (data) => axios({
  url: baseUrl,
  method: 'post',
  data,
}).then((response) => response);

const update = (categoryId, data) => axios({
  url: `${baseUrl}/${categoryId}`,
  method: 'put',
  data,
}).then((response) => response);

const fetchAll = () => axios({
  url: baseUrl,
  method: 'get',
}).then((response) => response);

const categoryService = {
  create,
  update,
  fetchAll,
};

export default categoryService;
