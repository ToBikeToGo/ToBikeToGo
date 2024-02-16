const controllers = new Map();

const fetchApi = async (url, options) => {
  const route = window.location.pathname;

  const config = options || {};

  const token = localStorage.getItem('token');
  if (token) {
    config.headers = {
      ...config?.headers,
      Authorization: `Bearer ${token}`,
    };
  }

  const previousController = controllers.get(url);
  if (previousController) {
    previousController.abort();
  }

  const controller = new AbortController();
  controllers.set(url, controller);

  try {
    const response = await fetch(url, { ...config, signal: controller.signal });
    const data = await response;

    if (
      data.status === 401 &&
      route !== '/login' &&
      route !== '/register' &&
      !route.includes('activate')
    ) {
      localStorage.removeItem('token');

      localStorage.setItem('redirectAfterLogin', route);
      window.location.href = '/login';
    }

    return data;
  } catch (error) {
    if (error.name === 'AbortError') {
      console.log(`Request to ${url} was aborted`);
    } else {
      throw error;
    }
  }
};

export default fetchApi;
