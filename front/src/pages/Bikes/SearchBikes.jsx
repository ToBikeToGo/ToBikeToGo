import React, { useState, useEffect } from 'react';
import Typography from '@mui/material/Typography';
import Container from '@mui/material/Container';
import { CircularProgress, Pagination, useTheme } from '@mui/material';
import { BikeList } from '../../components/Bike/BikeList.jsx';
import { useBookingContext } from '../../hooks/useBooking.jsx';

const SearchBikes = () => {
  const { availableBikes, isLoading } = useBookingContext();

  if (isLoading) return <CircularProgress sx={{ m: 5 }} />;

  return (
    <Container>
      <Typography variant="h2" gutterBottom>
        Bikes
      </Typography>

      <BikeList bikes={availableBikes} />
    </Container>
  );
};

export { SearchBikes };
