import React, { useCallback } from 'react';
import { useParams } from 'react-router-dom';
import { getApirUrl } from '../../../helpers/getApirUrl.js';
import fetchApi from '../../../helpers/fetchApi.js';

const VacationContext = React.createContext('');

const useVacation = () => {
  const [error, setError] = React.useState(null);
  const apiUrl = getApirUrl();

  const getShopVacations = useCallback(
    async (shopId) => {
      return fetchApi(`${apiUrl}/shops/${shopId}/vacations`)
        .then((response) => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then((data) => {
          if (data && data['@id']) {
            return data;
          } else {
            throw new Error('Data is not in the expected format');
          }
        })
        .catch((error) => {
          setError(error);
        });
    },
    [apiUrl]
  );

  const changeVacationRequestStatus = (vacationId, status) => {
    return fetchApi(`${apiUrl}/vacations/${vacationId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/merge-patch+json',
      },
      body: JSON.stringify({
        status: status,
      }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        if (data && data['@id']) {
          return data;
        } else {
          throw new Error('Data is not in the expected format');
        }
      })
      .catch((error) => {
        setError(error);
      });
  };

  const acceptVacationRequest = (vacationId) => {
    return changeVacationRequestStatus(vacationId, true);
  };

  const rejectVacationRequest = (vacationId) => {
    return changeVacationRequestStatus(vacationId, false);
  };

  return {
    error,
    getShopVacations,
    acceptVacationRequest,
    rejectVacationRequest,
  };
};

const VacationProvider = ({ children }) => {
  const vacation = useVacation();
  return (
    <VacationContext.Provider value={vacation}>
      {children}
    </VacationContext.Provider>
  );
};

const useVacationContext = () => {
  const context = React.useContext(VacationContext);
  if (context === undefined) {
    throw new Error('useUserContext must be used within a ShopProvider');
  }
  return context;
};

export { VacationProvider, VacationContext, useVacationContext };
