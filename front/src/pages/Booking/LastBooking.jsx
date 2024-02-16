import { isAfter } from 'date-fns';
import BikeImg from './../../assets/images/bike-home.jpg';
import { CircularProgress, Modal, Pagination, TextField } from '@mui/material';
import Rating from '@mui/material/Rating';
import Typography from '@mui/material/Typography';
import { Container } from '@mui/material';
import Button from '@mui/material/Button';
import StorefrontIcon from '@mui/icons-material/Storefront';
import { today } from 'react-big-calendar/lib/utils/dates.js';
import { useBooking } from './hooks/useBooking.jsx';
import { useEffect, useState } from 'react';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { getApirUrl, getMediaUrl } from '../../helpers/getApirUrl.js';
import Box from '@mui/material/Box';
import { calculateTotalPrice } from '../../helpers/calculateTotalPrice.js';
import { ArrowRight, ElectricBike, RateReview } from '@mui/icons-material';
import { Link } from 'react-router-dom';
import { TimeSlots } from '../../components/TimeSlots/Timeslot.jsx';
import { DateRangePicker } from 'react-date-range';
import { Calendar } from '../../components/Calendar/Calendar.jsx';
import { useCalendar } from '../../components/Calendar/hooks/useCalendar.jsx';
import { useSlots } from '../../hooks/useSlots.jsx';
import * as React from 'react';
import { TimeClock } from '@mui/x-date-pickers';
import fetchApi from '../../helpers/fetchApi.js';

const isEnded = (date) => {
  return isAfter(today, date);
};

const LastBooking = () => {
  const { user } = useUserContext();
  const mediaUrl = getMediaUrl();
  const [openRatingModal, setOpenRatingModal] = useState(false);
  const [selectedBooking, setSelectedBooking] = useState(null);
  const [value, setValue] = useState(2);
  const [openNewBookingModal, setOpenNewBookingModal] = useState(false);
  const {
    slots,
    getAvailableSlotsForDateAndShop,
    getUnavailableDatesForTheBike,
    isLoading: isLoadingSlots,
    unavailableDates,
  } = useSlots();
  const {
    bookings,
    fetchBookingByUserId,
    isLoading,
    rateBooking,
    rating,
    setRating,
    totalPage,
    page,
    onChangePage,
    postBooking,
    bookingData,
    setBookingData,
  } = useBooking();

  const [timeSlot, setTimeSlot] = useState(null);
  const [initialBookingDaysCount, setInitialBookingDaysCount] = useState(1);
  const {
    calendarRef,
    dates,
    isOpen,
    onChangeDate,
    handleOpen: handleOpenCalendar,
  } = useCalendar({
    onChangeDateCallback: (d) => {
      getAvailableSlotsForDateAndShop({
        shopId: 22,
        dates: d,
      });
      console.log(d);
      setBookingData((prev) => {
        return {
          ...prev,
          startDate: d.startDate,
          endDate: d.endDate,
        };
      });
    },
  });

  const handleOpen = (bookingId, rating) => {
    setSelectedBooking(bookingId);
    setRating(rating);
    setOpenRatingModal(true);
  };

  // Ajoutez un nouvel état pour gérer l'ouverture et la fermeture de la modale d'annulation
  const [openCancelModal, setOpenCancelModal] = useState(false);

  // Fonction pour ouvrir la modale d'annulation
  const handleOpenCancelModal = (booking) => {
    setSelectedBooking(booking);
    setOpenCancelModal(true);
  };

  const handleCloseCancelModal = () => {
    setOpenCancelModal(false);
  };

  // Fonction pour gérer l'annulation de la réservation
  const handleCancelBooking = (id) => {
    setOpenCancelModal(false);

    const apiUrl = getApirUrl();

    fetchApi(`${apiUrl}/bookings/${id}`, {
      method: 'DELETE',
    }).then((response) => {
      if (!response.ok) {
        return Promise.reject();
      }
      return response.json();
    });
    handleCloseCancelModal();
  };

  const handleClose = () => {
    setOpenRatingModal(false);
  };

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

  const handleOpenNewBookingModal = (booking) => {
    setSelectedBooking(booking);
    setInitialBookingDaysCount(
      (new Date(booking.endDate) - new Date(booking.startDate)) /
        (1000 * 60 * 60 * 24)
    );
    setBookingData({
      bike: booking?.bike?.['@id'],
      user: `/api/users/${user?.id}`,
      id: booking.id,
    });

    getUnavailableDatesForTheBike({
      bikeId: booking.bike.id,
    });
    setOpenNewBookingModal(true);
  };

  const handleCloseNewBookingModal = () => {
    setOpenNewBookingModal(false);
  };

  const handleNewBooking = () => {
    postBooking().then(() => {
      fetchBookingByUserId(user?.id);
      setOpenNewBookingModal(false);
    });
  };

  const isDateUnavailable = (date) => {
    return unavailableDates.some(
      (unavailableDate) =>
        date >= new Date(unavailableDate.startDate) &&
        date <= new Date(unavailableDate.endDate)
    );
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
            style={{
              backgroundColor: isEnded(reservation.endDate) ? '#f5f5f5' : '',
            }}
            key={reservation.id}
          >
            <img
              src={
                reservation?.bike?.media?.contentUrl
                  ? mediaUrl + reservation.bike.media.contentUrl
                  : BikeImg
              }
              alt={reservation?.bike?.label}
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
                {reservation?.bike?.label}
              </Typography>
            </div>
            <div className={'flex flex-col  items-center ml-auto'}>
              <Typography variant="h6" gutterBottom>
                {calculateTotalPrice(
                  reservation?.payment?.price,
                  reservation?.startDate,
                  reservation?.endDate
                )}{' '}
                $
              </Typography>

              {!isAfter(today, reservation?.endDate) && (
                <Button
                  fullWidth
                  variant="contained"
                  color="red"
                  sx={{
                    color: 'white !important',
                  }}
                  onClick={() => handleOpenCancelModal(reservation)}
                >
                  Cancel
                </Button>
              )}
              {isEnded(reservation.endDate) && (
                <Button
                  sx={{
                    margin: '1em',
                    color: 'white',
                  }}
                  fullWidth
                  variant="outlined"
                  color={'black'}
                  onClick={() => handleOpen(reservation.id, reservation.rating)}
                >
                  Donner mon avis
                  <RateReview sx={{ marginLeft: '0.5em' }} />
                </Button>
              )}
              <Button
                fullWidth
                variant="outlined"
                color="black"
                component={Link}
                onClick={() => handleOpenNewBookingModal(reservation)}
                sx={{
                  marginTop: '1em',
                }}
              >
                Reporter ma location
                <ArrowRight />
              </Button>
              <Button
                variant="outlined"
                color="black"
                component={Link}
                to={`/rent/bike/${reservation.bike?.id}`}
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

              <Modal
                open={openNewBookingModal}
                onClose={handleCloseNewBookingModal}
                aria-labelledby="new-booking-modal-title"
                aria-describedby="new-booking-modal-description"
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
                  <Typography
                    id="new-booking-modal-title"
                    variant="h3"
                    component="h2"
                  >
                    Want to report your booking ?
                  </Typography>
                  <div
                    ref={calendarRef}
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      position: 'relative',
                    }}
                  >
                    <Calendar
                      calendarRef={calendarRef}
                      onChangeDate={onChangeDate}
                      disabledDateCallback={(date) => isDateUnavailable(date)}
                      dates={dates}
                      handleOpen={handleOpenCalendar}
                      isOpen={isOpen}
                      maxDays={initialBookingDaysCount}
                    />
                  </div>
                  <TimeSlots onChange={setTimeSlot} unavailableSlots={slots} />
                  <Button
                    variant="contained"
                    color="primary"
                    sx={{
                      margin: '1em',
                      color: 'white',
                    }}
                    onClick={handleNewBooking}
                  >
                    Submit
                  </Button>
                  <Button
                    sx={{
                      margin: '1em',
                      color: 'white',
                    }}
                    onClick={handleCloseNewBookingModal}
                  >
                    Close
                  </Button>
                </Box>
              </Modal>
            </div>
          </div>
        ))}
        <Modal
          open={openCancelModal}
          onClose={handleCloseCancelModal}
          aria-labelledby="cancel-modal-title"
          aria-describedby="cancel-modal-description"
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
            <Typography id="cancel-modal-title" variant="h6" component="h2">
              Are you sure you want to cancel this booking?
            </Typography>
            <Button
              variant="contained"
              color="primary"
              sx={{
                margin: '1em',
                color: 'white',
              }}
              onClick={() => handleCancelBooking(selectedBooking?.id)}
            >
              Yes, cancel it
            </Button>
            <Button
              sx={{
                margin: '1em',
                color: 'white',
              }}
              onClick={handleCloseCancelModal}
            >
              No, go back
            </Button>
          </Box>
        </Modal>
        <Modal
          open={openRatingModal}
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
        <Pagination
          count={totalPage}
          page={page}
          variant="outlined"
          onChange={onChangePage}
        />
      </div>
    </div>
  );
};

export { LastBooking };
