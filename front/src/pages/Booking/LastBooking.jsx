import { addDays, isAfter } from 'date-fns';
import BikeImg from './../../assets/images/bike-home.jpg';
import Bike2Img from './../../assets/images/bike1.avif';
import {
  CircularProgress,
  Input,
  Modal,
  Pagination,
  TextField,
} from '@mui/material';
import Rating from '@mui/material/Rating';
import Typography from '@mui/material/Typography';
import { Card, CardContent, CardMedia, Grid, Container } from '@mui/material';
import Button from '@mui/material/Button';
import EngineeringIcon from '@mui/icons-material/Engineering';
import StorefrontIcon from '@mui/icons-material/Storefront';
import { today } from 'react-big-calendar/lib/utils/dates.js';
import { useShopContext } from '../../hooks/UseShop.jsx';
import { useBooking } from './hooks/useBooking.jsx';
import { useEffect, useState } from 'react';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { getMediaUrl } from '../../helpers/getApirUrl.js';
import Box from '@mui/material/Box';
import { calculateTotalPrice } from '../../helpers/calculateTotalPrice.js';
import { BookmarkBorder, ElectricBike, RateReview } from '@mui/icons-material';
import { Link } from 'react-router-dom';

const LastBooking = () => {
  const { user } = useUserContext();
  const mediaUrl = getMediaUrl();
  const [open, setOpen] = useState(false);
  const [selectedBooking, setSelectedBooking] = useState(null);
  const [value, setValue] = useState(2);

  const handleOpen = (bookingId, rating) => {
    setSelectedBooking(bookingId);
    setRating(rating);
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  const {
    bookings,
    fetchBookingByUserId,
    isLoading,
    rateBooking,
    rating,
    setRating,
  } = useBooking();

  useEffect(() => {
    if (user?.id) fetchBookingByUserId(user.id);
  }, [fetchBookingByUserId, user?.id]);

  const handleRatingChange = (event, newValue) => {
    setRating(newValue);
  };

  const submitRating = () => {
    rateBooking(selectedBooking, rating);
    handleClose();
    fetchBookingByUserId(user?.id);
  };

  if (isLoading) return <CircularProgress sx={{ m: 5 }} />;

  return (
    <div
      className={'flex flex-col h-100 w-2/3 justify-center items-center p-5'}
    >
      <Container>
        <Typography variant="h2" gutterBottom>
          Last bookings
        </Typography>
        {bookings.map((reservation) => (
          <div
            className={
              'flex lg:flex-row flex-col bg-white m-5 p-8 rounded-xl shadow-xl sm:justify-center  sm:items-center'
            }
            key={reservation.id}
          >
            <img
              src={
                reservation?.bike?.media?.contentUrl
                  ? mediaUrl + reservation.bike.media.contentUrl
                  : BikeImg
              }
              alt={reservation.bike.label}
              className={'w-1/4 mr-8'}
            />
            <div className="flex-col flex items-start">
              <Typography variant="h6" gutterBottom>
                <StorefrontIcon />
                <span className={'ml-3'}>{reservation?.bike?.shop?.label}</span>
              </Typography>
              <Rating
                name="read-only"
                value={reservation?.rating}
                readOnly
                sx={{
                  marginBottom: 2,
                }}
              />

              <Typography variant="h6" gutterBottom>
                Du {new Date(reservation.startDate).toLocaleDateString()} au
                {new Date(reservation.endDate).toLocaleDateString()}
              </Typography>
              <Typography variant="h6" gutterBottom>
                {reservation.bike.label}
              </Typography>
            </div>
            <div className={'flex flex-col  items-center ml-auto'}>
              <Typography variant="h6" gutterBottom>
                {calculateTotalPrice(
                  reservation.bike.price,
                  reservation.startDate,
                  reservation.endDate
                )}{' '}
                $
              </Typography>

              {!isAfter(today, reservation?.endDate) && (
                <Button
                  variant="contained"
                  color="red"
                  sx={{
                    color: 'white !important',
                  }}
                >
                  Cancel
                </Button>
              )}
              <Button
                variant="contained"
                color="secondary"
                style={{
                  marginTop: '1em',
                  color: 'white',
                }}
              >
                Details
              </Button>
              <Button
                sx={{
                  margin: '1em',
                  color: 'white',
                }}
                variant="outlined"
                color={'black'}
                onClick={() => handleOpen(reservation.id, reservation.rating)}
              >
                Donner mon avis
                <RateReview sx={{ marginLeft: '0.5em' }} />
              </Button>
              <Button
                variant="outlined"
                color="black"
                component={Link}
                to={`/rent/bike/${reservation.bike.id}`}
                sx={{
                  marginTop: '1em',
                  color: 'white',
                }}
              >
                Commander le même vélo
                <ElectricBike
                  sx={{
                    marginLeft: '0.5em',
                    color: 'black',
                  }}
                />
              </Button>
            </div>
          </div>
        ))}
        <Modal
          open={open}
          onClose={handleClose}
          aria-labelledby="modal-modal-title"
          aria-describedby="modal-modal-description"
        >
          <Box
            sx={{
              position: 'absolute',
              top: '50%',
              left: '50%',
              transform: 'translate(-50%, -50%)',
              width: '50vw',
              bgcolor: 'background.paper',
              border: '2px solid #000',
              boxShadow: 24,
              p: 4,
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
            }}
          >
            <Typography id="modal-modal-title" variant="h6" component="h2">
              Qu'avez vous pensez de votre location ?
            </Typography>
            <Rating
              name="simple-controlled"
              value={rating}
              onChange={handleRatingChange}
              sx={{
                marginBottom: 2,
              }}
            />

            <TextField
              id="outlined-multiline-static"
              label="Comment"
              multiline
              rows={4}
              defaultValue="Default Value"
              variant="outlined"
              sx={{
                marginBottom: 2,
              }}
            />
            <Button
              variant="contained"
              color="primary"
              sx={{
                margin: '1em',
                color: 'white',
              }}
              onClick={submitRating}
            >
              Submit
            </Button>
            <Button
              sx={{
                margin: '1em',
                color: 'white',
              }}
              onClick={handleClose}
            >
              Close
            </Button>
          </Box>
        </Modal>
      </Container>
      <div
        className={
          'p-5 px-8 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
        }
      >
        <Pagination count={10} variant="outlined" />
      </div>
    </div>
  );
};

export { LastBooking };
