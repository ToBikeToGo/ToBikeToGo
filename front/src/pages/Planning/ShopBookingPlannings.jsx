import React, { useEffect, useState } from 'react';
import { useBooking } from '../Booking/hooks/useBooking.jsx';
import { useParams } from 'react-router-dom';
import { Planning } from '../../components/Planning/Planning.jsx';
import Typography from '@mui/material/Typography';
import { useShop } from '../../hooks/UseShop.jsx';



function ShopBookingPlannings() {
  const { bookings, getBookingsByShop, isLoading, formatBookingsPlanning } = useBooking();
  const {shopId} = useParams()
  const {shop, getShopById} =useShop()
  
  useEffect(() => {
    getBookingsByShop(shopId)
    getShopById(shopId)
  }, [getBookingsByShop, getShopById]);

  
  console.log(bookings, formatBookingsPlanning(bookings))
  if (isLoading) return <div>Loading...</div>;

  return (
   <div className={'flex flex-col w-full p-12 '}>
      <Typography variant={'h1'} className={'text-center p-12'}>
        Booking at {shop.label}
      </Typography>

       <Planning events={formatBookingsPlanning(bookings)} />
   </div>
  );
}

export {ShopBookingPlannings};
