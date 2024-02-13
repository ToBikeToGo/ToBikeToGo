import { useMyFranchises } from './hooks/useMyFranchise.jsx';
import Box from '@mui/material/Box';
import { useMyShops } from './hooks/useMyShops.jsx';
import { ShopListComponent } from '../components/ShopList.jsx';
import Typography from '@mui/material/Typography';
import { CircularProgress, useTheme } from '@mui/material';
import Button from '@mui/material/Button';
import { Link } from 'react-router-dom';
import React from 'react';

export const MyShops = () => {
  const { myFranchises, isLoading: isFranchiseLoading } = useMyFranchises();
  const theme = useTheme();

  const {
    myShops,
    isLoading: isShopLoading,
    totalPage,
    page,
    onChangePage,
  } = useMyShops({
    franchiseId: myFranchises[0]?.id,
  });

  const isLoading = isFranchiseLoading || isShopLoading;

  return (
    <Box
      sx={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
      }}
    >
      <Typography
        variant={'h2'}
        component={'h2'}
        className={'text-center text-4xl'}
      >
        My Shops
      </Typography>

      {isLoading && <CircularProgress color={'secondary'} className={'m-5'} />}

      {myShops.length > 0 && (
        <ShopListComponent
          shops={myShops}
          onChangePage={onChangePage}
          totalPage={totalPage}
          page={page}
        />
      )}

      {myShops.length === 0 && !isLoading && (
        <div>
          <Typography variant={'h3'} className={'text-center'}>
            You don't have any franchise yet
          </Typography>
          <Button
            variant="contained"
            color="black"
            component={Link}
            to={`/franchise/request`}
            href="#contained-buttons"
            sx={{ mt: 2, color: theme.palette.common.white }}
          >
            Create my franchise
          </Button>
        </div>
      )}
    </Box>
  );
};
