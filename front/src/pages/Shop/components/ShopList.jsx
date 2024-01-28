import Container from '@mui/material/Container';
import { Link } from 'react-router-dom';
import { Language } from '@mui/icons-material';
import Typography from '@mui/material/Typography';
import { Shop } from './Shop.jsx';
import { Pagination } from '@mui/material';
import React from 'react';

export const ShopListComponent = ({ shops, mapModeEnabled = false }) => {
  return (
    <Container>
      {mapModeEnabled && (
        <div className={'flex justify-center'}>
          <Link to={'/shops/map'} className={'ml-auto cursor-pointer'}>
            View in map mode <Language />
          </Link>
        </div>
      )}
      <div className={'flex flex-wrap justify-center md:justify-center'}>
        {shops?.map((shop) => (
          <Shop shop={shop} key={shop.id} />
        ))}
      </div>

      <div
        className={
          'p-5 px-8 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
        }
      >
        <Pagination count={1} variant="outlined" />
      </div>
    </Container>
  );
};
