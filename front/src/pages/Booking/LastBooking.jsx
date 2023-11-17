import { addDays, isAfter } from 'date-fns';
import BikeImg from './../../assets/images/bike-home.jpg';
import Bike2Img from './../../assets/images/bike1.avif';
import { Pagination } from '@mui/material';
import Typography from '@mui/material/Typography';
import { Card, CardContent, CardMedia, Grid, Container } from '@mui/material';
import Button from '@mui/material/Button';
import EngineeringIcon from '@mui/icons-material/Engineering';
import StorefrontIcon from '@mui/icons-material/Storefront';
import { today } from 'react-big-calendar/lib/utils/dates.js';

const LastBooking = () => {
  const mockedLastBookings = [
    {
      startDate: new Date(),
      endDate: addDays(new Date(), 7),
      bike: {
        id: '12121',
        image: BikeImg,
      },
      shopId: 'JZIEJE',
      responsable: {
        lastname: 'Jean',
        firstname: 'Dujardin',
        id: '1212121',
      },
      price: 12000,
    },
    {
      startDate: new Date(),
      endDate: addDays(new Date(), 7),
      bike: {
        id: '12121',
        image: Bike2Img,
      },
      shopId: 'JZIEJE',
      responsable: {
        lastname: 'Jean',
        firstname: 'Dujardin',
        id: '1212121',
      },
      price: 12000,
    },
    {
      startDate: new Date(),
      endDate: addDays(new Date(), 7),
      bike: {
        id: '12121',
        image: BikeImg,
      },
      shopId: 'JZIEJE',
      responsable: {
        lastname: 'Jean',
        firstname: 'Dujardin',
        id: '1212121',
      },
      price: 12000,
    },
  ];

  return (
    <div
      className={'flex flex-col h-100 w-2/3 justify-center items-center p-5'}
    >
      <Container>
        <Typography variant="h2" gutterBottom>
          Last bookings
        </Typography>
        {mockedLastBookings.map((reservation) => (
          <div
            className={'flex bg-white m-5 p-8 rounded-xl shadow-xl'}
            key={reservation.id}
          >
            <img
              src={reservation.bike.image}
              alt={reservation.bike.name}
              className={'w-1/4 mr-8'}
            />
            <div className="flex-col flex items-start">
              <Typography variant="h6" gutterBottom>
                <StorefrontIcon />
                <span className={'ml-3'}>{reservation.shopId}</span>
              </Typography>
              <Typography variant="h6" gutterBottom>
                <EngineeringIcon />
                <span className={'ml-3'}>
                  {reservation.responsable.firstname}{' '}
                  {reservation.responsable.lastname}
                </span>
              </Typography>
              <Typography variant="h6" gutterBottom>
                {reservation.startDate.toLocaleDateString()} -{' '}
                {reservation.endDate.toLocaleDateString()}
              </Typography>
            </div>
            <div className={'flex flex-col  items-center ml-auto'}>
              <Typography variant="h6" gutterBottom>
                {reservation.price} €
              </Typography>
              {!isAfter(today, reservation.endDate) && (
                <Button
                  variant="contained"
                  color="red"
                  sx={{
                    color: 'white',
                  }}
                >
                  Cancel
                </Button>
              )}
              <Button
                variant="contained"
                color="secondary"
                sx={{
                  marginTop: '1em',
                  color: 'white',
                }}
              >
                Details
              </Button>
            </div>
          </div>
        ))}
      </Container>
      <Pagination count={10} variant="outlined" />
    </div>
  );
};

export { LastBooking };
