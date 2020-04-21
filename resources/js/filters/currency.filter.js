export default function currencyFilter(value, currency = 'UAH') {
  return new Intl.NumberFormat('ru-Uk', {
    style: 'currency',
    currency,
  }).format(value);
}
