const baseUrl = '/api';
const { axios } = window;

const getConversation = () => axios({
  url: `${baseUrl}/currencies`,
  method: 'get',
}).then((response) => response);

const currencyService = {
  getConversation,
};

export default currencyService;
