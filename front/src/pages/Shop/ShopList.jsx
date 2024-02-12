import { useShopContext } from '../../hooks/UseShop.jsx';
import React, { useEffect, useState } from 'react';
import {
  CircularProgress,
  Container,
  Pagination,
  TextField,
  useTheme,
} from '@mui/material';
import { ShopListComponent } from './components/ShopList.jsx';

const ShopList = () => {
  const { getAllShops, setSearch, search, shops, isLoading } = useShopContext();

  const handleSearchChange = (event) => {
    setSearch(event.target.value);
  };

  const handleSubmitSearch = () => {
    getAllShops({
      label: search,
    });
  };

  useEffect(() => {
    getAllShops();
  }, [getAllShops]);

  return (
    <Container
      sx={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
      }}
    >
      <TextField
        label="Rechercher un magasin"
        variant="outlined"
        value={search}
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
        <ShopListComponent shops={shops} mapModeEnabled={true} />
      )}
    </Container>
  );
};

export { ShopList };
