import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';
import { useCallback, useEffect, useState } from 'react';
import { useUserContext } from '../../../../hooks/UserContext.jsx';

export const useMyFranchises = () => {
  const apiURL = getApirUrl();
  const [myFranchises, setMyFranchises] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const { user } = useUserContext();

  const getMyFranchises = useCallback(async () => {
    if (!user.id) return;
    setIsLoading(true);
    await fetchApi(`${apiURL}/franchises?createdBy=${user.id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })
      .then((response) => response.json())
      .then((data) => {
        setMyFranchises(data['hydra:member']);
        setIsLoading(false);
      });
    setIsLoading(false);
  }, [apiURL, user.id]);

  useEffect(() => {
    getMyFranchises();
  }, [getMyFranchises]);

  return {
    myFranchises,
    getMyFranchises,
    isLoading,
  };
};
