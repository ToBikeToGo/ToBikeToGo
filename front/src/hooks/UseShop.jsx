import React, { useCallback, useEffect, useState } from 'react';
import { getApirUrl } from '../helpers/getApirUrl.js';
import { useParams } from 'react-router-dom';

const ShopContext = React.createContext('');

const useShop = () => {
  const [shop, setShop] = React.useState({});
  const [error, setError] = React.useState(null);
  const { shopId } = useParams();
  const [activeMember, setActiveMember] = useState(null);
  const apiUrl = getApirUrl();

  const getShopWithMembers = () => {
    const mockedShop = {
      id: 1,
      name: 'VeliCity Rouen',
      members: [
        {
          id: 1,
          name: 'John Doe',
          profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
        },
        {
          id: 2,
          name: 'Jane Doe',
          profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
        },
      ],
    };

    function createNewMember(id) {
      return {
        id: id,
        name: `Member ${id}`,
        profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
      };
    }

    for (let i = 3; i <= 22; i++) {
      mockedShop.members.push(createNewMember(i));
    }

    return mockedShop;
  };

  const { id, members, name } = getShopWithMembers();

  useEffect(() => {
    fetch(`${apiUrl}/shops/121`)
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        if (data && data['@id']) {
          setShop({
            ...shop,
            id: data['@id'],
            ...data,
          });
        } else {
          throw new Error('Data is not in the expected format');
        }
      })
      .catch((error) => {
        setError(error);
      });
  }, []);

  const getAllShops = useCallback(() => {
    return fetch(`${apiUrl}/shops`)
      .then((response) => response.json())
      .then((data) => {
        console.log('data list', data);
        return data['hydra:member'];
      });
  }, [apiUrl]);

  return {
    shop,
    error,
    id,
    members,
    name,
    activeMember,
    setActiveMember,
    getAllShops,
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
