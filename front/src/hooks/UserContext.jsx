import React, { useCallback, useEffect, useMemo, useState } from 'react';
import { getApirUrl, getMediaUrl } from '../helpers/getApirUrl.js';
import fetchApi from '../helpers/fetchApi.js';
import { useLocation } from 'react-router-dom';
//import jwt from 'jsonwebtoken';
import { useNavigate } from 'react-router-dom';

const UserContext = React.createContext('');

const useUser = () => {
  const [user, setUser] = useState({});
  const [userToRegister, setUserToRegister] = useState({});
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const apiUrl = getApirUrl();
    const mediaUrl = getMediaUrl();
    fetchApi(`${apiUrl}/me`)
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        if (data && data['@id']) {
          setUser({
            id: data['id'],
            firstname: data.firstname,
            lastname: data.lastname,
            email: data.email,
            shops: data.shops,
            phone: data.phone,
            locale: data.locale,
            schedules: data.schedules,
            vacations: data.vacations,
            avatar: data.media?.contentUrl
              ? `${mediaUrl}${data.media.contentUrl}`
              : null,
            roles: data.roles,
          });
        } else {
          throw new Error('Data is not in the expected format');
        }
      })
      .catch((error) => {
        setError(error);
      });
  }, []);

  const handleLogout = useCallback(() => {
    localStorage.removeItem('token');
    setUser({});
    navigate('/login');
  }, []);

  const isAdmin = useMemo(() => {
    return user?.roles && user.roles.includes('ROLE_ADMIN');
  }, [user]);

  const isFranchiseProvider = useMemo(() => {
    return user?.roles && user.roles.includes('ROLE_PROVIDER');
  }, [user]);

  const isLogged = useMemo(() => {
    return user && user.id && localStorage.getItem('token');
  }, [user]);

  const userHasShop = useMemo(() => {
    return user?.shops && user.shops.length > 0;
  }, [user]);

  const value = React.useMemo(() => {
    return {
      userHasShop,
      user,
      error,
      isLogged,
      setUserToRegister,
      userToRegister,
      isAdmin,
      isFranchiseProvider,
      handleLogout,
    };
  }, [
    isAdmin,
    user,
    error,
    isLogged,
    setUserToRegister,
    userToRegister,
    isFranchiseProvider,
    handleLogout,
    userHasShop,
  ]);

  return value;
};

const UserProvider = ({ children }) => {
  const user = useUser();
  return <UserContext.Provider value={user}>{children}</UserContext.Provider>;
};

const useUserContext = () => {
  const context = React.useContext(UserContext);
  if (context === undefined) {
    throw new Error('useUserContext must be used within a UserProvider');
  }
  return context;
};

export { UserProvider, useUserContext };
