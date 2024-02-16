import { useState, useRef, useEffect } from 'react';
import 'react-date-range/dist/styles.css'; // main css file
import 'react-date-range/dist/theme/default.css'; // theme css file
import styled from 'styled-components';
import theme from '../../theme/theme.js';
import { Typography, Button, CircularProgress } from '@mui/material';
import './../../style/dayPicker.css';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import { Icon } from 'leaflet';
import PinImg from './../../assets/images/pin-map.png';
import { Calendar } from '../../components/Calendar/Calendar.jsx';
import { useCalendar } from '../../components/Calendar/hooks/useCalendar.jsx';
import { TimeSlots } from '../../components/TimeSlots/Timeslot.jsx';
import { useBikes } from '../Bikes/hooks/useBike.jsx';
import {getApirUrl, getMediaUrl} from '../../helpers/getApirUrl.js';
import { useParams } from 'react-router-dom';
import { useSlots } from '../../hooks/useSlots.jsx';
import { getMapCoordonate } from '../../helpers/getMapCoordonate.js';
import fetchApi from "../../helpers/fetchApi.js";
import { useUserContext } from '../../hooks/UserContext.jsx';
const legalIcon = new Icon({
  iconUrl: PinImg,
  iconSize: [35, 35],
  iconAnchor: [22, 94],
  popupAnchor: [0, 0],
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

function RentABike() {
  const { bikeId } = useParams();
  const {
    slots,
    getAvailableSlotsForDateAndShop,
    getUnavailableDatesForTheBike,
    isLoading: isLoadingSlots,
    unavailableDates,
  } = useSlots();
  const mediaUrl = getMediaUrl();
  const { bike, getBikeById, isLoading } = useBikes();
  const [mapCenter, setMapCenter] = useState([51.505, -0.09]);
  const { user } = useUserContext();

  const [isLoadingMap, setIsLoadingMap] = useState(true);

  useEffect(() => {
    if (bike?.shop?.address) {
      setIsLoadingMap(true);
      getMapCoordonate(bike.shop.address)
        .then((coordinates) => {
          setMapCenter(coordinates);
          setIsLoadingMap(false);
        })
        .catch((error) => {
          console.error('Error getting coordinates from address', error);
          setIsLoadingMap(false);
        });
    }
  }, [bike?.shop?.address]);

  const isDateRangeUnavailable = (startDate, endDate) => {
    return unavailableDates.some(
      (unavailableDate) =>
        startDate <= new Date(unavailableDate.endDate) &&
        endDate >= new Date(unavailableDate.startDate)
    );
  };

  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: (d) => {
      //setDates(d);

      if (isDateRangeUnavailable(d.startDate, d.endDate)) {
        console.log('date range unavailable');
        return false;
      }

      getAvailableSlotsForDateAndShop({
        shopId: bike.shop.id,
        dates: d,
      });
    },
  });
  const handlePayment = async () => {
    try {
      const apiUrl = getApirUrl();
      const response = await fetchApi(`${apiUrl}/payments/booking`,{
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        method: 'POST',
        body: JSON.stringify({
          price:  (bike.price * (dates[0]?.endDate - dates[0]?.startDate)) /
              (1000 * 60 * 60 * 24)
        .toFixed(2),
          booking: {
            startDate: "2024-02-15T14:29:48.771Z",
            endDate: "2024-02-15T14:29:48.771Z",
            bike: '/api/bikes/' + bike.id
          },
          user: [
            '/api/users/' + user.id,
          ]
        })
      })  // Attendre la réponse JSON et extraire les données
      const responseData = await response.json();

      // Accéder à la propriété redirect_url dans les données JSON
      const redirectUrl = responseData.redirect_url;

      // Rediriger l'utilisateur vers l'URL de redirection
      window.location.href = redirectUrl;
    } catch (error) {
      console.error('Error initiating payment:', error);
      // Gérez les erreurs de connexion au serveur
    }
  };
  const isDateUnavailable = (date) => {
    return unavailableDates.some(
      (unavailableDate) =>
        date >= new Date(unavailableDate.startDate) &&
        date <= new Date(unavailableDate.endDate)
    );
  };

  const mapRef = useRef();

  useEffect(() => {
    getBikeById(bikeId).then((data) => {
      if (bike) {
        getUnavailableDatesForTheBike({
          bikeId: bikeId,
        });
      }
    });
  }, [bikeId, getAvailableSlotsForDateAndShop, getBikeById]);

  function handleOnFlyTo() {
    const { current = {} } = mapRef;
    const { leafletElement: map } = current;

    map.flyTo(disneyLandLatLng, 14, {
      duration: 2,
    });
  }

  if (isLoading || isLoadingSlots) return <CircularProgress sx={{ m: 5 }} />;

  return (
    <StyledPage>
      <StyledWrapper className={'flex-col sm:flex-row'}>
        <div className={' md:w-1/2 w-full flex flex-col items-center'}>
          <Typography variant="h2" component="h4" gutterBottom>
            {bike.label}
          </Typography>
          <StyledImage
            src={
              bike?.media?.contentUrl
                ? mediaUrl + bike.media.contentUrl
                : 'https://ezeryders.com/cdn/shop/products/ScreenShot2022-02-23at5.37.41PM.png?v=1645666853'
            }
            alt={bike.label}
          />
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
            disabledDateCallback={(date) => isDateUnavailable(date)}
            dates={dates}
            isOpen={isOpen}
          />
          <div
            className={'flex justify-end items-end border-8 mt-4 rounded-2xl'}
            style={{
              borderColor: theme.palette.secondary.main,
            }}
          >
            {!isLoadingMap && (
              <MapContainer
                center={[mapCenter.lat, mapCenter.lon]}
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
            )}
          </div>
          <Typography variant="h3" component="h4" gutterBottom>
            Pickup time
          </Typography>
          <TimeSlots
            unavailableSlots={slots}
            onChange={(time) => console.log(time, 'add api call')}
          />

          <Button
            onClick={handlePayment}
            variant="outlined"
            color="black"
            sx={{
              marginLeft: 'auto',
              marginTop: '2em',
            }}
          >
            Book Now
          </Button>
          <Typography variant="h2" component="h4" gutterTop m={4}>
            {dates[0]?.startDate &&
              dates[0]?.endDate &&
              `Price : ${(
                (bike.price * (dates[0]?.endDate - dates[0]?.startDate)) /
                (1000 * 60 * 60 * 24)
              ).toFixed(2)} €`}{' '}
          </Typography>
        </div>
      </StyledWrapper>
    </StyledPage>
  );
}

export { RentABike };
