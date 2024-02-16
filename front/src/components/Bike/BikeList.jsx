import Button from '@mui/material/Button';
import { Link, useNavigate } from 'react-router-dom';
import { Modal, Pagination, useTheme } from '@mui/material';
import PropTypes from 'prop-types';
import { useUserContext } from '../../hooks/UserContext.jsx';
import IconButton from '@mui/material/IconButton';
import { MoreVert } from '@mui/icons-material';
import MenuItem from '@mui/material/MenuItem';
import Menu from '@mui/material/Menu';
import { useEffect, useState } from 'react';
import { AdminCrudMolette } from '../AdminCrudMolette/index.jsx';
import { getMediaUrl } from '../../helpers/getApirUrl.js';
import { useBooking } from '../../pages/Booking/hooks/useBooking.jsx';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import * as React from 'react';
import Rating from '@mui/material/Rating';

const BikeList = ({ bikes, page, onChangePage, totalPage }) => {
  const theme = useTheme();
  const mediaUrl = getMediaUrl();
  const navigate = useNavigate();
  const [selectedBike, setSelectedBike] = useState(null);
  const [ratings, setRatings] = useState([]);
  const [open, setOpen] = useState(false);
  const handleOpen = (bike) => {
    setSelectedBike(bike);
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };
  const { getBookingByBike } = useBooking();

  useEffect(() => {
    if (selectedBike) {
      getBookingByBike(selectedBike.id).then((data) => {
        setRatings(data);
      });
    }
  }, [selectedBike]);

  return (
    <>
      <div className={'flex flex-wrap justify-center md:justify-center'}>
        {bikes.length === 0 ? (
          <p>No bikes available</p>
        ) : (
          bikes.map((bike) => (
            <div
              key={bike.id}
              className={
                'bg-white p-5 m-10 rounded-xl shadow-xl md:w-2/5 hover:shadow-2xl hover:scale-105 transform transition-all duration-500'
              }
            >
              <AdminCrudMolette
                entityName={'bike'}
                handleEdit={() => navigate(`/bikes/edit/${bike.id}`)}
                handleRemove={() => console.log('remove')}
              />
              <img
                src={
                  bike?.media?.contentUrl
                    ? mediaUrl + bike.media.contentUrl
                    : 'https://ezeryders.com/cdn/shop/products/ScreenShot2022-02-23at5.37.41PM.png?v=1645666853'
                }
                alt={bike.name}
                className={'w-full h-80 object-cover'}
              />
              <hr className={'p-2'} />
              <div className="text ">
                <p className={'text-2xl font-bold'}>{bike.brand}</p>
                <p className={'text-2xl'}>{bike.label}</p>
                <p className={'font-semibold'}>{bike.price} € / day</p>
                <div class="flex items-center justify-center w-full">
                  <Button
                    variant="contained"
                    color="black"
                    component={Link}
                    to={`/rent/bike/${bike.id}`}
                    href="#contained-buttons"
                    sx={{ mt: 2, color: theme.palette.common.white }}
                  >
                    Rent this bike
                  </Button>
                  <Button sx={{ mt: 2 }} onClick={() => handleOpen(bike)}>
                    Lire les avis
                  </Button>
                </div>
              </div>
            </div>
          ))
        )}
      </div>
      <Modal open={open} onClose={handleClose}>
        <Box
          sx={{
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            width: 400,
            bgcolor: 'background.paper',
            border: '2px solid #000',
            boxShadow: 24,
            p: 4,
          }}
        >
          <Typography variant="h2">Avis pour {selectedBike?.brand}</Typography>
          {ratings?.map((rating) => (
            <>
              <Rating
                name="read-only"
                value={rating.rating}
                readOnly
                sx={{
                  marginBottom: 2,
                }}
              />
              <Typography key={rating.id}>{rating.rating}</Typography>
            </>
          ))}
        </Box>
      </Modal>
      {bikes.length > 0 && (
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
      )}
    </>
  );
};

BikeList.propTypes = {
  bikes: PropTypes.array.isRequired,
};

export { BikeList };
