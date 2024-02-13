import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';
import { useCallback, useEffect, useState } from 'react';
import { usePagination } from '../../../../hooks/usePagination.jsx';

export const useMyShops = ({ franchiseId }) => {
  const apiURL = getApirUrl();
  const [myShops, setMyShops] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const { page, setPage, onChangePage, setTotalPage, totalPage } =
    usePagination(0);

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
        setTotalPage(Math.ceil(data['hydra:totalItems'] / 10));
      });
    setIsLoading(false);
  }, [apiURL, franchiseId, page]);

  useEffect(() => {
    getMyShops();
  }, [getMyShops]);

  return {
    myShops,
    getMyShops,
    isLoading,
    page,
    setPage,
    onChangePage,
    setTotalPage,
  };
};
