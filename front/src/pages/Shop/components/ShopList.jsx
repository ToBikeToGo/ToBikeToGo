import Container from '@mui/material/Container';
import { Link } from 'react-router-dom';
import { Language } from '@mui/icons-material';
import { Shop } from './Shop.jsx';
import { Pagination } from '@mui/material';
import React from 'react';

export const ShopListComponent = ({
  shops,
  mapModeEnabled = false,
  page,
  onChangePage,
  totalPage,
}) => {
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
        {shops.length === 0 && (
          <div className={'text-center text-3xl'}>No shop found</div>
        )}
      </div>

      <div
        className={'p-5 px-8 rounded-xl m-5 flex items-center justify-center '}
      >
        <Pagination
          count={totalPage}
          page={page}
          onChange={onChangePage}
          variant="outlined"
        />
      </div>
    </Container>
  );
};
