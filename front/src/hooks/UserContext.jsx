import React, { useCallback, useEffect, useState } from 'react';
import { getApirUrl } from '../helpers/getApirUrl.js';
import fetchApi from '../helpers/fetchApi.js';
//import jwt from 'jsonwebtoken';

const UserContext = React.createContext('');

const useUser = () => {
  const [user, setUser] = useState({});
  const [userToRegister, setUserToRegister] = useState({});
  const [error, setError] = useState(null);

  useEffect(() => {
    const apiUrl = getApirUrl();
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
            schedules: data.schedules,
          });
        } else {
          throw new Error('Data is not in the expected format');
        }
      })
      .catch((error) => {
        setError(error);
      });
  }, []);

  const value = React.useMemo(() => {
    return {
      user,
      error,
      //isLogged,
      setUserToRegister,
      userToRegister,
    };
  }, [
    user,
    error,
    // isLogged
    setUserToRegister,
    userToRegister,
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
