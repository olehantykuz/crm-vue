const baseUrl = '/api/categories';
const { axios } = window;

const create = (categoryId, data) => axios({
  url: `${baseUrl}/${categoryId}/transaction`,
  method: 'post',
  data,
}).then((response) => response);

const getTotals = (month, year) => axios({
  url: `/api/transactions/amounts?month=${month}&year=${year}`,
  method: 'get',
}).then((response) => response);

const transactionService = {
  create,
  getTotals,
};

export default transactionService;
