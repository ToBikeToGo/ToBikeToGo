import React, { useState, useEffect } from 'react';
import { useBikes } from './hooks/useBike.jsx';
import Typography from '@mui/material/Typography';
import { useShop, useShopContext } from '../../hooks/UseShop.jsx';
import Container from '@mui/material/Container';
import { CircularProgress, Pagination, useTheme } from '@mui/material';
import Button from '@mui/material/Button';
import { Link, useParams } from 'react-router-dom';
import { BikeList } from '../../components/Bike/BikeList.jsx';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { formatSchedule } from '../../helpers/formatDate.js';

const BikesByShop = () => {
  const {
    bikes,
    getBikesByShop,
    isLoading: bikesAreLoading,
    totalPage,
    page,
    onChangePage,
  } = useBikes();
  const { shop, schedule, getShopById, isLoading: shopIsLoading } = useShop();
  const theme = useTheme();
  const { shopId } = useParams();

  useEffect(() => {
    getBikesByShop();
  }, [getBikesByShop]);

  useEffect(() => {
    getShopById(shopId);
  }, [getShopById, shopId]);

  console.log({
    bikes,
    shop,
    shopIsLoading,
    bikesAreLoading,
    totalPage,
    page,
    onChangePage,
  });
  if (bikesAreLoading) {
    return <CircularProgress className={'m-5'} />;
  }

  return (
    <Container>
      <Typography variant="h2" gutterBottom>
        Shop &nbsp;
        <span
          className={'p-3'}
          style={{
            backgroundColor: theme.palette.secondary.main,
          }}
        >
          {shop?.label}
        </span>
      </Typography>
      {formatSchedule(schedule)}

      <BikeList
        bikes={bikes}
        totalPage={totalPage}
        page={page}
        onChangePage={onChangePage}
      />
    </Container>
  );
};

export { BikesByShop };
