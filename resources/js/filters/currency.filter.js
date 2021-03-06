export default function currencyFilter(value, currencyCode) {
  const currency = currencyCode || 'UAH';

  return new Intl.NumberFormat('ru-Uk', {
    style: 'currency',
    currency,
  }).format(value);
}
