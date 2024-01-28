import Button from '@mui/material/Button';
import { Link } from 'react-router-dom';
import React from 'react';
import { useTheme } from '@mui/material';
import PropTypes from 'prop-types';

const BikeList = ({ bikes }) => {
  const theme = useTheme();

  return (
    <div className={'flex flex-wrap justify-center md:justify-center'}>
      {bikes.map((bike) => (
        <div
          key={bike.id}
          className={
            'bg-white p-5 m-10 rounded-xl shadow-xl md:w-2/5 hover:shadow-2xl hover:scale-105 transform transition-all duration-500'
          }
        >
          <img
            src={
              'https://ezeryders.com/cdn/shop/products/ScreenShot2022-02-23at5.37.41PM.png?v=1645666853'
            }
            alt={bike.name}
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
      ))}
    </div>
  );
};

BikeList.propTypes = {
  bikes: PropTypes.array.isRequired,
};

export { BikeList };
