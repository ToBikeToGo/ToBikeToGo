import { useShopContext } from '../../hooks/UseShop.jsx';
import { useEffect, useState } from 'react';
import Container from '@mui/material/Container';
import Typography from '@mui/material/Typography';
import { Link } from 'react-router-dom';
import { CircularProgress } from '@mui/material';
import { MapContainer, Marker, Popup, TileLayer, useMap } from 'react-leaflet';
import { Icon } from 'leaflet';
import PinImg from '../../assets/images/pin-map.png';
import { getMapCoordonate } from '../../helpers/getMapCoordonate.js';
import { useLoading } from '../../hooks/useLoading.jsx';
import { BikeList } from '../../components/Bike/BikeList.jsx';
import { useBookingContext } from '../../hooks/useBooking.jsx';
import Button from '@mui/material/Button';
import { toShortDate } from '../../helpers/formatDate.js';
const legalIcon = new Icon({
  iconUrl: PinImg,
  iconSize: [35, 35],
  iconAnchor: [17.5, 35],
  popupAnchor: [0, -35],
});
const mockedAddress = [
  '1 rue de la paix 75000 Paris',
  '2 rue de la paix 75000 Paris',
  '3 rue de la paix 75000 Paris',
  '14 rue du faubourg 75000 Paris',
  '15 rue du faubourg 75000 Paris',
];

const getMockedPosition = async () => {
  let mockedPositionArray = [];
  for (let i = 0; i < mockedAddress.length; i++) {
    const mockedPosition = await getMapCoordonate(mockedAddress[i]);
    if (mockedPosition?.lat && mockedPosition?.lon)
      mockedPositionArray.push([mockedPosition?.lat, mockedPosition?.lon]);
  }
  return mockedPositionArray;
};

const LocateControl = ({ shops }) => {
  const map = useMap();

  useEffect(() => {
    map.locate({ setView: true, maxZoom: 16 });

    map.on('locationfound', function (e) {
      // Get user's location
      const userLocation = e.latlng;

      const shopsWithDistance = shops.map((shop) => {
        const shopLocation = L.latLng(shop.position[0], shop.position[1]);
        const distance = map.distance(userLocation, shopLocation);
        return { ...shop, distance };
      });

      shopsWithDistance.sort((a, b) => a.distance - b.distance);

      if (shopsWithDistance.length > 0) {
        map.flyTo(shopsWithDistance[0].position, 14);
      }
    });
  }, [map, shops]);

  return null;
};

const rouenLocation = [49.443231, 1.099971];

const ShopsMapView = () => {
  const { getAllShops, getAvailableBikesByShop, bikes } = useShopContext();
  const { isLoading, startLoading, stopLoading } = useLoading();
  const {
    isLoading: isLoadingBikes,
    startLoading: startLoadingBikes,
    stopLoading: stopLoadingBikes,
  } = useLoading();

  const [selectedShop, setSelectedShop] = useState(null);
  const [displaySlider, setDisplaySlider] = useState(true);
  const {
    bookingDates: { startDate, endDate },
  } = useBookingContext();

  const [shops, setShops] = useState([]);
  useEffect(() => {
    startLoading();
    getAllShops()
      .then(async (data) => {
        const mockedPosition = await getMockedPosition();

        const shopsWithMockedPosition = data.map((shop, index) => {
          return {
            ...shop,
            position: mockedPosition[index] ?? rouenLocation,
          };
        });
        setShops(shopsWithMockedPosition);
        stopLoading();
      })
      .catch((error) => {
        console.log(error);
      });
  }, [getAllShops, startLoading, stopLoading]);

  const handleFetchBikesByShop = async (shopId) => {
    startLoadingBikes();
    try {
      const data = await getAvailableBikesByShop({
        startDate: startDate,
        endDate: endDate,
        shopId: shopId,
      });
      stopLoadingBikes();
    } catch (error) {
      console.error(
        'Une erreur est survenue lors de la récupération des vélos disponibles :',
        error
      );
    }
  };

  useEffect(() => {
    if (startDate === endDate) return;

    if (startDate && endDate) {
      handleFetchBikesByShop(selectedShop?.id);
    }
  }, [startDate, endDate]);

  if (isLoading) {
    return <CircularProgress color={'secondary'} className={'m-5'} />;
  }

  return (
    <div className={'w-full relative'}>
      <div
        className={
          ' rounded-xl bg-white shadow-xl  flex items-center justify-center '
        }
      >
        <MapContainer
          center={[51.505, -0.09]}
          zoom={33}
          style={{
            height: '1000px',
            width: '100%',
          }}
        >
          <LocateControl shops={shops} />

          <TileLayer
            url="http://{s}.tile.osm.org/{z}/{x}/{y}.png"
            attribution='&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
          />

          {shops.map((shop) => (
            <Marker
              key={shop.id}
              position={shop.position}
              icon={legalIcon}
              eventHandlers={{
                click: () => {
                  setSelectedShop(shop);
                  setDisplaySlider(true);
                  handleFetchBikesByShop(shop.id);
                },
              }}
            >
              <Popup>
                {shop.label} <br /> {shop.address}
                <img
                  src="https://media.gettyimages.com/id/912819604/fr/vectoriel/ic%C3%B4ne-du-site-e-commerce-vitrine-design-plat.jpg?s=612x612&amp;w=gi&amp;k=20&amp;c=1ORkFTN_MAUixOP-jWzpELTYyiAob1cYDTXoQiGYf6I="
                  alt="place Rousset"
                />
                <Link to={`/bikes/${shop.id}`}>Voir les vélos</Link>
              </Popup>
            </Marker>
          ))}
          <Marker position={rouenLocation} icon={legalIcon}>
            <Popup>Rouen, Normandie</Popup>
          </Marker>
        </MapContainer>
        {selectedShop && displaySlider && (
          <div
            style={{
              position: 'absolute',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              overflow: 'scroll',
              top: '10px',
              right: '10px',
              backgroundColor: 'white',
              padding: '10px',
              maxWidth: '50%',
              height: '100%',
              boxShadow: '0 0 10px rgba(0, 0, 0, 0.5)',
              zIndex: 1000,
            }}
          >
            <Button onClick={() => setDisplaySlider(false)} color={'secondary'}>
              X
            </Button>

            <h2>{selectedShop.label}</h2>
            <p>{selectedShop.address}</p>
            <img
              src="https://www.cyclelab.eu/IMG/jpg/ouverture-bois-colombes-19-article.jpg"
              alt="place Rousset"
              className={'w-1/2 mx-auto'}
            />

            {isLoadingBikes && (
              <CircularProgress
                color={'secondary'}
                sx={{
                  margin: '1em',
                }}
              />
            )}
            {bikes?.length > 0 && (
              <>
                {startDate && endDate ? (
                  <h3 className={'m-2'}>
                    {bikes?.length} Vélos disponibles du{' '}
                    {toShortDate(startDate)} au {toShortDate(endDate)}
                  </h3>
                ) : (
                  <h3 className={'m-2'}>
                    {bikes?.length}
                    Vélos disponibles
                  </h3>
                )}
                <BikeList bikes={bikes} />
              </>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export { ShopsMapView };
