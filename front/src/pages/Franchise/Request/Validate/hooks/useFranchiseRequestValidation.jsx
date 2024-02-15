import { getApirUrl } from '../../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../../helpers/fetchApi.js';
import { useCallback, useEffect, useMemo, useState } from 'react';
import { usePagination } from '../../../../../hooks/usePagination.jsx';

const useFranchiseRequestValidation = () => {
  const [franchiseRequestValidation, setFranchiseRequestValidation] = useState(
    []
  );
  const [isLoading, setIsLoading] = useState(false);

  const { page, setPage, onChangePage, setTotalPage, totalPage } =
    usePagination(0);

  const getFranchiseRequestValidation = useCallback(async () => {
    const apiUrl = getApirUrl();
    setIsLoading(true);

    try {
      const response = await fetchApi(`${apiUrl}/requests?&page=${page}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();
      setFranchiseRequestValidation(() => data['hydra:member']);
      setTotalPage(Math.ceil(data['hydra:totalItems'] / 10));
      setIsLoading(false);
    } catch (error) {
      console.error(error);
    }
  }, [page]);

  const approveRequest = async (request) => {
    const apiUrl = getApirUrl();

    try {
      // Approve the request
      const response = await fetchApi(`${apiUrl}/requests/${request.id}`, {
        method: 'PATCH',
        body: JSON.stringify({
          status: true,
        }),
        headers: {
          'Content-Type': 'application/merge-patch+json',
        },
      });

      const data = await response.json();

      // Update user role to ROLE_PROVIDER
      const userResponse = await fetchApi(
        `${apiUrl}/users/${request.user.id}`,
        {
          method: 'PATCH',
          body: JSON.stringify({
            roles: ['ROLE_PROVIDER'],
          }),
          headers: {
            'Content-Type': 'application/merge-patch+json',
          },
        }
      );

      const userData = await userResponse.json();

      await getFranchiseRequestValidation();
    } catch (error) {
      console.error('Error while approving request:', error);
    }
  };
  const declineRequest = async (requestId) => {
    const apiUrl = getApirUrl();

    try {
      const response = await fetchApi(`${apiUrl}/requests/${requestId}`, {
        method: 'PATCH',
        body: JSON.stringify({
          status: false,
        }),
        headers: {
          'Content-Type': 'application/merge-patch+json', // Set the correct content type here
        },
      });

      const data = await response.json();
      await getFranchiseRequestValidation();
    } catch (error) {
      console.error('Error while declining request:', error);
    }
  };

  useEffect(() => {
    getFranchiseRequestValidation();
  }, [getFranchiseRequestValidation]);

  const value = useMemo(() => {
    return {
      franchiseRequestValidation,
      getFranchiseRequestValidation,
      approveRequest,
      declineRequest,
      setPage,
      page,
      totalPage,
      onChangePage,
      isLoading,
    };
  }, [
    getFranchiseRequestValidation,
    franchiseRequestValidation,
    approveRequest,
    declineRequest,
    setPage,
    page,
    onChangePage,
    totalPage,
    isLoading,
  ]);

  return value;
};

export { useFranchiseRequestValidation };
