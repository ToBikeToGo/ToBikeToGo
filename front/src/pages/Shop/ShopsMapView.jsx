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

      // Calculate distance between user's location and each shop
      const shopsWithDistance = shops.map((shop) => {
        const shopLocation = L.latLng(shop.position[0], shop.position[1]);
        const distance = map.distance(userLocation, shopLocation);
        return { ...shop, distance };
      });

      // Sort shops based on distance
      shopsWithDistance.sort((a, b) => a.distance - b.distance);

      // Zoom in on the location of the nearest shop
      if (shopsWithDistance.length > 0) {
        map.flyTo(shopsWithDistance[0].position, 14);
      }
    });
  }, [map, shops]);

  return null;
};

const rouenLocation = [49.443231, 1.099971];

const ShopsMapView = () => {
  const { getAllShops } = useShopContext();
  const { isLoading, startLoading, stopLoading } = useLoading();

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
  }, [getAllShops]);

  if (isLoading) {
    return <CircularProgress color={'secondary'} className={'m-5'} />;
  }

  return (
    <Container
      sx={{
        width: '100%',
      }}
    >
      <Typography variant="h2" gutterBottom>
        Shops
      </Typography>

      <div
        className={
          'p-5 px-2 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
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
            <Marker key={shop.id} position={shop.position} icon={legalIcon}>
              <Popup>
                {shop.label} <br /> {shop.address}
                <Link to={`/bikes/${shop.id}`}>Voir les vélos</Link>
              </Popup>
            </Marker>
          ))}
          <Marker position={rouenLocation} icon={legalIcon}>
            <Popup>Rouen, Normandie</Popup>
          </Marker>
        </MapContainer>
      </div>
    </Container>
  );
};

export { ShopsMapView };
