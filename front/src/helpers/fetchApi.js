const fetchApi = async (url, options) => {
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

  return data;
};

export default fetchApi;
