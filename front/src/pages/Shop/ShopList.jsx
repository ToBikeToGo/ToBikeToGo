import { useShopContext } from '../../hooks/UseShop.jsx';
import React, { useEffect, useState } from 'react';
import Container from '@mui/material/Container';
import Typography from '@mui/material/Typography';
import Button from '@mui/material/Button';
import { Link } from 'react-router-dom';
import { CircularProgress, Pagination, useTheme } from '@mui/material';
import { Language, Visibility } from '@mui/icons-material';

const ShopList = () => {
  const { getAllShops } = useShopContext();
  const [loading, setLoading] = useState(false);

  const [shops, setShops] = useState([]);
  const theme = useTheme();

  useEffect(() => {
    setLoading(true);
    getAllShops()
      .then((data) => {
        console.log(data);
        setShops(data);
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        setLoading(false);
      });
  }, [getAllShops]);

  if (loading) {
    return <CircularProgress color={'secondary'} className={'m-5'} />;
  }
  return (
    <Container>
      <div className={'flex justify-center'}>
        <Link to={'/shops/map'} className={'ml-auto cursor-pointer'}>
          View in map mode <Language />
        </Link>
      </div>

      <Typography variant="h2" gutterBottom>
        Shops
      </Typography>
      <div className={'flex flex-wrap justify-center md:justify-center'}>
        {shops?.map((shop) => (
          <div
            key={shop.id}
            className={
              'bg-white p-5 m-10 rounded-xl shadow-xl md:w-2/5 hover:shadow-2xl hover:scale-105 transform transition-all duration-500'
            }
          >
            <img
              src={
                'https://media.gettyimages.com/id/912819604/fr/vectoriel/ic%C3%B4ne-du-site-e-commerce-vitrine-design-plat.jpg?s=612x612&w=gi&k=20&c=1ORkFTN_MAUixOP-jWzpELTYyiAob1cYDTXoQiGYf6I='
              }
              alt={shop.label}
            />
            <hr className={'p-2'} />
            <div className="text">
              <p className={'text-2xl font-bold'}>{shop.label}</p>
              <p className={'text-2xl'}>{shop.address}</p>
              <Button
                variant="contained"
                color="black"
                component={Link}
                to={`/rent/bike/${shop.id}`}
                href="#contained-buttons"
                sx={{ mt: 2, color: theme.palette.primary.main }}
              >
                See the shop &nbsp; <Visibility />
              </Button>
            </div>
          </div>
        ))}
      </div>

      <div
        className={
          'p-5 px-8 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
        }
      >
        <Pagination count={10} variant="outlined" />
      </div>
    </Container>
  );
};

export { ShopList };
