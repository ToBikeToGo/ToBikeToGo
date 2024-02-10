import Button from '@mui/material/Button';
import { Link, useNavigate } from 'react-router-dom';
import { Pagination, useTheme } from '@mui/material';
import PropTypes from 'prop-types';
import { useUserContext } from '../../hooks/UserContext.jsx';
import IconButton from '@mui/material/IconButton';
import { MoreVert } from '@mui/icons-material';
import MenuItem from '@mui/material/MenuItem';
import Menu from '@mui/material/Menu';
import { useState } from 'react';
import { AdminCrudMolette } from '../AdminCrudMolette/index.jsx';
import { getMediaUrl } from '../../helpers/getApirUrl.js';

const BikeList = ({ bikes }) => {
  const theme = useTheme();
  const mediaUrl = getMediaUrl();
  const navigate = useNavigate();

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
              <div className="text">
                <p className={'text-2xl font-bold'}>{bike.brand}</p>
                <p className={'text-2xl'}>{bike.label}</p>
                <p className={'font-semibold'}>{bike.price} € / day</p>
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
              </div>
            </div>
          ))
        )}
      </div>
      {bikes.length > 0 && (
        <div
          className={
            'p-5 px-8 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
          }
        >
          <Pagination count={10} variant="outlined" />
        </div>
      )}
    </>
  );
};

BikeList.propTypes = {
  bikes: PropTypes.array.isRequired,
};

export { BikeList };
