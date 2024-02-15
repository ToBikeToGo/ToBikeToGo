import { UsersTable } from './components/UsersTable.jsx';
import React, { useEffect } from 'react';
import { useUsers } from './hooks/useUsers.js';
import { CircularProgress, Box, TextField } from '@mui/material';
import { ShopListComponent } from '../Shop/components/ShopList.jsx';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';

export const ManageUsers = () => {
  const {
    users,
    getUsers,
    page,
    onChangePage,
    totalPage,
    isLoading,
    search,
    setSearch,
  } = useUsers();
  const navigate = useNavigate();
  useEffect(() => {
    getUsers();
  }, [getUsers]);

  const [tempSearch, setTempSearch] = useState(search);
  const handleSearchChange = (event) => {
    setTempSearch(event.target.value);
  };

  const handleSubmitSearch = async () => {
    await setSearch(tempSearch);
    getUsers();
  };

  const onMemberEdit = (id) => {
    navigate(`/user/edit-profile/${id}`);
  };

  if (isLoading) return <CircularProgress sx={{ m: 5 }} />;

  return (
    <Box
      style={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        width: '100%',
        padding: '2em',
      }}
    >
      <TextField
        label="Rechercher un utilisateur"
        variant="outlined"
        value={tempSearch}
        style={{
          backgroundColor: 'white',
          borderRadius: '5px',
          margin: '20px',
        }}
        fullWidth
        onChange={handleSearchChange}
        onKeyPress={(event) => {
          if (event.key === 'Enter') {
            handleSubmitSearch();
          }
        }}
      />
      {isLoading ? (
        <CircularProgress color={'secondary'} className={'m-5'} />
      ) : (
        <UsersTable
          page={page}
          totalPage={totalPage}
          onChangePage={onChangePage}
          users={users}
          onMemberDelete={(id) => {}}
          onMemberEdit={onMemberEdit}
        />
      )}
    </Box>
  );
};
