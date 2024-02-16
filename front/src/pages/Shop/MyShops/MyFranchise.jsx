import { useMyFranchises } from './hooks/useMyFranchise.jsx';
import Box from '@mui/material/Box';
import { useMyShops } from './hooks/useMyShops.jsx';
import { ShopListComponent } from '../components/ShopList.jsx';
import Typography from '@mui/material/Typography';
import { CircularProgress, useTheme } from '@mui/material';
import Button from '@mui/material/Button';
import { Link } from 'react-router-dom';
import React, { useState } from 'react';
import { BarChart } from '@mui/icons-material';

const FranchiseList = ({ myFranchises, onSelectFranchise }) => {
  return (
    <Box
      sx={{
        marginRight: '2em',
        dislay: 'flex',
        flexDirection: 'column',
      }}
    >
      <Typography
        variant={'h2'}
        component={'h2'}
        className={'text-center text-4xl'}
      >
        My Franchises
      </Typography>
      <Box
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          backgroundColor: 'white',
        }}
      >
        {myFranchises.map((franchise) => (
          <Button
            key={franchise.id}
            onClick={() => onSelectFranchise(franchise)}
            variant="outlined"
            color="black"
            sx={{ mt: 2 }}
          >
            {franchise.label}
          </Button>
        ))}
      </Box>
    </Box>
  );
};

export const MyFranchise = () => {
  const { myFranchises, isLoading: isFranchiseLoading } = useMyFranchises();
  const theme = useTheme();
  const [selectedFranchise, setSelectedFranchise] = useState(null);

  const handleSelectFranchise = (franchise) => {
    setSelectedFranchise(franchise);
  };
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

  if (isLoading) {
    return (
      <CircularProgress
        sx={{
          m: 5,
        }}
      />
    );
  }

  return (
    <Box
      style={{
        display: 'flex',
        flexDirection: 'row',
        width: '80%',
        backgroundColor: 'white',
        padding: '2em',
        justifyContent: 'center',
        borderRadius: '2em',
        boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)',
        margin: 'auto',
      }}
    >
      <FranchiseList
        myFranchises={myFranchises}
        onSelectFranchise={handleSelectFranchise}
      />

      {selectedFranchise && (
        <Box sx={{ width: '70%' }}>
          <Typography variant={'h3'} className={'text-center'}>
            {selectedFranchise.label}
          </Typography>
          <Typography variant={'h4'} className={'text-center'}>
            {selectedFranchise.description}
          </Typography>

          <ShopListComponent shops={selectedFranchise.shops} isOwner={true} />
          <Box
            style={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
              backgroundColor: 'white',
            }}
          >
            <Button
              variant="contained"
              color="black"
              component={Link}
              to={`/franchise/${selectedFranchise.id}/edit`}
              href="#contained-buttons"
              sx={{ mt: 2, color: theme.palette.common.white }}
            >
              Voir les statistiques
              <BarChart />
            </Button>
          </Box>
        </Box>
      )}
    </Box>
  );
};
