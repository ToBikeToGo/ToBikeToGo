const fetchApi = async (url, options) => {
  const route = window.location.pathname;

  const config = options || {};
  // add token to headers
  const token = localStorage.getItem('token');
  if (token) {
    config.headers = {
      ...config?.headers,
      Authorization: `Bearer ${token}`,
    };
  }

  const response = await fetch(url, config);
  const data = await response;

  if (data.status === 401 && route !== '/login') {
    localStorage.removeItem('token');
    window.location.href = '/login';
  }

  return data;
};

export default fetchApi;
