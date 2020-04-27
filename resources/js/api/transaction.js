const baseUrl = '/api/categories';
const { axios } = window;

const create = (categoryId, data) => axios({
  url: `${baseUrl}/${categoryId}/transaction`,
  method: 'post',
  data,
}).then((response) => response);

const transactionService = {
  create,
};

export default transactionService;
