import React, { useState, useEffect } from 'react';
import { useBikes } from './hooks/useBike.jsx';
import Typography from '@mui/material/Typography';
import { useShopContext } from '../../hooks/UseShop.jsx';
import Container from '@mui/material/Container';
import { Pagination, useTheme } from '@mui/material';
import Button from '@mui/material/Button';
import { Link } from 'react-router-dom';
import { BikeList } from '../../components/Bike/BikeList.jsx';

const BikesByShop = () => {
  const { bikes, getBikesByShop } = useBikes();
  const { shop } = useShopContext();
  const theme = useTheme();

  useEffect(() => {
    getBikesByShop();
  }, [getBikesByShop]);

  return (
    <Container>
      <Typography variant="h2" gutterBottom>
        Bikes in &nbsp;
        <span
          className={'p-3'}
          style={{
            backgroundColor: theme.palette.secondary.main,
          }}
        >
          {shop?.label}
        </span>
      </Typography>
      <BikeList bikes={bikes} />
      <div
        className={
          'p-5 px-8 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
        }
      >
        <Pagination count={10} variant="outlined" />
      </div>
    </Container>
  );
};

export { BikesByShop };
