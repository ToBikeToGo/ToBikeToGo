import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';
import { useCallback, useEffect, useState } from 'react';

export const useMyShops = ({ franchiseId }) => {
  const apiURL = getApirUrl();
  const [myShops, setMyShops] = useState([]);
  const [isLoading, setIsLoading] = useState(false);

  const getMyShops = useCallback(async () => {
    if (!franchiseId) return;
    setIsLoading(true);
    await fetchApi(`${apiURL}/franchises/${franchiseId}/shops`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })
      .then((response) => response.json())
      .then((data) => {
        setIsLoading(false);
        setMyShops(data['hydra:member']);
      });
    setIsLoading(false);
  }, [apiURL, franchiseId]);

  useEffect(() => {
    getMyShops();
  }, [getMyShops]);

  return {
    myShops,
    getMyShops,
    isLoading,
  };
};
