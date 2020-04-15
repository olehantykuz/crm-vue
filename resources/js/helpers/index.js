const isAuth = () => {
  return JSON.parse(localStorage.getItem('auth'));
}

export { isAuth };
