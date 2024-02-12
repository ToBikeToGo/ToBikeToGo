import React, { useCallback, useEffect, useState } from 'react';
import { getApirUrl } from '../helpers/getApirUrl.js';
import { useParams } from 'react-router-dom';
import fetchApi from '../helpers/fetchApi.js';

const ShopContext = React.createContext('');

export const useShop = () => {
  const [shop, setShop] = React.useState({});
  const [error, setError] = React.useState(null);
  const [bikes, setBikes] = React.useState(null);
  const [activeMember, setActiveMember] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [shops, setShops] = useState([]);
  const [members, setMembers] = useState([]);

  const apiUrl = getApirUrl();

  const getShopWithMembers = useCallback(
    (id) => {
      setIsLoading(true);
      fetchApi(`${apiUrl}/shops/members/${id}`)
        .then((response) => response.json())
        .then((data) => {
          if (data && data['@id']) {
            setShop({
              id: data['@id'],
              ...data,
            });
            setMembers(data?.users);
            setActiveMember(data?.users[0]);
          } else {
            throw new Error('Data is not in the expected format');
          }

          setIsLoading(false);
        })
        .catch((error) => {
          setError(error);
        });
    },
    [apiUrl]
  );

  const getAvailableBikesByShop = ({ startDate, endDate, shopId }) => {
    return fetchApi(`${apiUrl}/bikes/available/shop/${shopId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        startDate: startDate,
        endDate: endDate,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        setBikes(data);
      });
  };

  const getShopById = useCallback(
    (shopId) => {
      setIsLoading(true);
      fetchApi(`${apiUrl}/shops/${shopId}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }

          return response.json();
        })
        .then((data) => {
          if (data && data['@id']) {
            setShop(data);
            setIsLoading(false);
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

  const getAllShops = useCallback(
    (params) => {
      setIsLoading(true);
      const { label } = params || {};
      const urlSearchParams = new URLSearchParams();
      if (label) {
        urlSearchParams.append('label', label);
      }

      return fetchApi(`${apiUrl}/shops?${urlSearchParams.toString()}`)
        .then((response) => response.json())
        .then((data) => {
          setShops(data['hydra:member']);
          setIsLoading(false);
        });
    },
    [apiUrl]
  );




  return {
    shops,
    shop,
    error,
    members,
    name,
    activeMember,
    setActiveMember,
    getAllShops,
    getAvailableBikesByShop,
    bikes,
    getShopWithMembers,
    getShopById,
    isLoading,
    search,
    setSearch,
  };
};

const ShopProvider = ({ children }) => {
  const shop = useShop();
  return <ShopContext.Provider value={shop}>{children}</ShopContext.Provider>;
};

const useShopContext = () => {
  const context = React.useContext(ShopContext);
  if (context === undefined) {
    throw new Error('useUserContext must be used within a ShopProvider');
  }
  return context;
};

export { ShopProvider, ShopContext, useShopContext };
