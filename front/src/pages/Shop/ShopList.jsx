import { useShopContext } from '../../hooks/UseShop.jsx';
import React, { useEffect, useState } from 'react';
import Container from '@mui/material/Container';
import Typography from '@mui/material/Typography';
import Button from '@mui/material/Button';
import { Link } from 'react-router-dom';
import { CircularProgress, Pagination, useTheme } from '@mui/material';
import { Language, Visibility } from '@mui/icons-material';
import { Shop } from './components/Shop.jsx';
import { ShopListComponent } from './components/ShopList.jsx';

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

  if (shops.length > 0) {
    return <ShopListComponent shops={shops} mapModeEnabled={true} />;
  }
};

export { ShopList };
