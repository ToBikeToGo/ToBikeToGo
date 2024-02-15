import { getApirUrl } from '../../../helpers/getApirUrl.js';
import fetchApi from '../../../helpers/fetchApi.js';
import { useCallback, useState } from 'react';
import { usePagination } from '../../../hooks/usePagination.jsx';

const useUsers = () => {
  const apiUrl = getApirUrl();
  const { page, onChangePage, setTotalPage, totalPage, setPage } =
    usePagination(0);
  const [user, setUser] = useState({});

  const [search, setSearchState] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [users, setUsers] = useState([]);

  const getUsers = useCallback(async () => {
    setIsLoading(true);

    console.log('search', search);

    const urlSearchParams = new URLSearchParams();
    urlSearchParams.append('itemsPerPage', 20);
    urlSearchParams.append('page', page + 1);
    urlSearchParams.append('firstname', search);

    const url = `${apiUrl}/users?${urlSearchParams.toString()}`;
    const response = await fetchApi(url);
    const data = await response.json();
    setUsers(data['hydra:member']);
    console.log(data);
    setTotalPage(Math.ceil(data['hydra:totalItems'] / 20));
    setIsLoading(false);
    return data;
  }, [apiUrl, page, setTotalPage, search]);

  const getUser = useCallback(
    async (id) => {
      setIsLoading(true);
      const url = `${apiUrl}/users/${id}`;
      const response = await fetchApi(url);
      const data = await response.json();
      setIsLoading(false);
      setUser(data);
      console.log('ici', data);
      return data;
    },
    [apiUrl]
  );

  const setSearch = async (search) => {
    console.log('search', search);
    setSearchState(search);
    setPage(0);
  };

  return {
    isLoading,
    getUsers,
    users,
    page,
    onChangePage,
    totalPage,
    search,
    setSearch: setSearch,
    getUser,
    user,
  };
};

export { useUsers };
