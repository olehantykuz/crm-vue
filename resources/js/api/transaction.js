const categoriesBaseUrl = '/api/categories';
const transactionsBaseUrl = '/api/transactions';
const { axios } = window;

const buildMonthYearQueryString = (month, year) => {
  const params = { month, year };

  return Object.keys(params).map((key) => (params[key] && (`${key}=${params[key]}`))).join('&');
};

const create = (categoryId, data) => axios({
  url: `${categoriesBaseUrl}/${categoryId}/transaction`,
  method: 'post',
  data,
}).then((response) => response);

const getList = (month, year) => {
  const queryString = buildMonthYearQueryString(month, year);
  const url = `${transactionsBaseUrl}?${queryString}`;

  return axios({
    url,
    method: 'get',
  }).then((response) => response);
};

const getById = (id) => axios({
  url: `${transactionsBaseUrl}/${id}`,
  method: 'get',
}).then((response) => response);

const transactionService = {
  create,
  getList,
  getById,
};

export default transactionService;
