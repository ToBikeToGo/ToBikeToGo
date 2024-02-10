import { useState, useRef } from 'react';
import 'react-date-range/dist/styles.css'; // main css file
import 'react-date-range/dist/theme/default.css'; // theme css file
import styled from 'styled-components';
import theme from '../../theme/theme.js';
import { Typography, Button } from '@mui/material';
import './../../style/dayPicker.css';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import { Icon } from 'leaflet';
import PinImg from './../../assets/images/pin-map.png';
import { Calendar } from '../../components/Calendar/Calendar.jsx';
import { useCalendar } from '../../components/Calendar/hooks/useCalendar.jsx';
import { TimeSlots } from '../../components/TimeSlots/Timeslot.jsx';
const legalIcon = new Icon({
  iconUrl: PinImg,
  iconSize: [35, 35], // size of the icon
  iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
  popupAnchor: [0, 0], // point from which the popup should open relative to the iconAnchor
});

const StyledPage = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 100px;
  margin: auto;
`;

const StyledWrapper = styled.div`
  background-color: #ffffff;
  display: flex;
  border-radius: 12px;
  box-shadow: 0px 4px 4px rgba(75, 158, 46, 0.25);
  padding: 2em;
  min-height: 500px;
  width: 80%;
  margin: 2rem;
`;

const StyledImage = styled.img`
  height: 100%;
  object-fit: contain;
  width: 100%;
`;

const disneyWorldLatLng = [28.3852, -81.5639];
const disneyLandLatLng = [33.8121, -117.919];
function RentABike() {
  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: () => {},
  });
  // TODO replace this with a call to the API
  const [bike, setBike] = useState({
    name: 'City Bike 300ksZA',
    image:
      'https://ezeryders.com/cdn/shop/products/ScreenShot2022-02-23at5.37.41PM.png?v=1645666853',
    dayPrice: 10,
  });

  const mapRef = useRef();

  /**
   * handleOnSetView
   */

  function handleOnSetView() {
    const { current = {} } = mapRef;
    const { leafletElement: map } = current;

    map.setView(disneyWorldLatLng, 14);
  }

  /**
   * handleOnFlyTo
   */

  function handleOnFlyTo() {
    const { current = {} } = mapRef;
    const { leafletElement: map } = current;

    map.flyTo(disneyLandLatLng, 14, {
      duration: 2,
    });
  }

  const unavailableSlots = ['10:00-11:00', '13:30-14:30'];

  return (
    <StyledPage>
      <StyledWrapper className={'flex-col sm:flex-row'}>
        <div className={' md:w-1/2 w-full flex flex-col items-center'}>
          <Typography variant="h2" component="h4" gutterBottom>
            {bike.name}
          </Typography>
          <StyledImage src={bike.image} alt={bike.name} />
        </div>
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
            handleOpen={handleOpen}
            onChangeDate={onChangeDate}
            dates={dates}
            isOpen={isOpen}
          />
          <div
            className={'flex justify-end items-end border-8 mt-4 rounded-2xl'}
            style={{
              borderColor: theme.palette.secondary.main,
            }}
          >
            <MapContainer
              center={[51.505, -0.09]}
              zoom={33}
              style={{
                height: '200px',
                width: '100%',
              }}
            >
              <TileLayer
                url="http://{s}.tile.osm.org/{z}/{x}/{y}.png"
                attribution='&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
              />
              <Marker position={[51.505, -0.09]} icon={legalIcon}>
                <Popup>
                  A pretty CSS3 popup. <br /> Easily customizable.
                </Popup>
              </Marker>
            </MapContainer>
          </div>
          <Typography variant="h3" component="h4" gutterBottom>
            Pickup time
          </Typography>
          <TimeSlots
            unavailableSlots={unavailableSlots}
            onChange={(time) => console.log(time, 'TODO add api call')}
          />

          <Button
            variant="outlined"
            color="black"
            sx={{
              marginLeft: 'auto',
              marginTop: '2em',
            }}
          >
            Book Now
          </Button>
        </div>
      </StyledWrapper>
      <Typography variant="h2" component="h4" gutterTop m={4}>
        {dates[0]?.startDate &&
          dates[0]?.endDate &&
          `Price : ${(
            (bike.dayPrice * (dates[0]?.endDate - dates[0]?.startDate)) /
            (1000 * 60 * 60 * 24)
          ).toFixed(2)} €`}{' '}
      </Typography>
    </StyledPage>
  );
}

export { RentABike };
