import React from 'react';

const UserContext = React.createContext('');

const useUser = () => {
  // const [user, setUser] = React.useState({})

  const user = {
    name: 'John Doe',
    email: 'johndoe@gmail.com',
    role: 'user',
    profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
  };

  return {
    user,
  };
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
