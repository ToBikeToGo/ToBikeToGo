import { useCallback, useState } from 'react';
import { useParams } from 'react-router-dom';
import { getApirUrl } from '../../../helpers/getApirUrl.js';
import fetchApi from '../../../helpers/fetchApi.js';
import { usePagination } from '../../../hooks/usePagination.jsx';

const useBikes = () => {
  const { shopId } = useParams();
  const apiUrl = getApirUrl();
  const pagination = usePagination(0);
  const [bikes, setBikes] = useState([]);
  const [bike, setBike] = useState({});
  const [isLoading, setIsLoading] = useState(false);
  const [bikesCategories, setBikesCategories] = useState([]);
  const { page, setPage, onChangePage, setTotalPage, totalPage } =
    usePagination(0);

  const fetchBikesCategories = useCallback(async () => {
    setIsLoading(true);
    const apiUrl = getApirUrl();
    const response = await fetchApi(`${apiUrl}/bike_categories`);
    const data = await response.json();
    setBikesCategories(data['hydra:member']);
    setIsLoading(false);
  }, []);

  const getBikesByShop = useCallback(() => {
    setIsLoading(true);
    fetchApi(`${apiUrl}/shops/${shopId}/bikes?page=${page}`)
      .then(async (response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();
        setBikes(data['hydra:member']);
        setTotalPage(Math.ceil(data['hydra:totalItems'] / 10));
        setIsLoading(false);
      })
      .then((data) => {
        if (data) {
          return data['hydra:member'];
        } else {
          throw new Error('Data is not in the expected format');
        }
      });
  }, [apiUrl, shopId, page]);

  const getBikeById = useCallback(
    async (bikeId) => {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/bikes/${bikeId}`);
      const data = await response.json();
      setIsLoading(false);
      setBike(data);
    },
    [apiUrl]
  );

  return {
    bike,
    bikes,
    getBikesByShop,
    getBikeById,
    isLoading,
    pagination,
    fetchBikesCategories,
    bikesCategories,
    page,
    setPage,
    onChangePage,
    totalPage,
  };
};

export { useBikes };
