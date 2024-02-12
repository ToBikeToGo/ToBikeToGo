import { useCallback, useState } from 'react';

const useLoading = () => {
  const [isLoading, setLoading] = useState(false);

  const startLoading = useCallback(() => setLoading(true), []);
  const stopLoading = useCallback(() => setLoading(false), []);

  return { isLoading, startLoading, stopLoading };
};

export { useLoading };
