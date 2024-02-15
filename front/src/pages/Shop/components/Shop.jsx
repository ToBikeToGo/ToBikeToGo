import Button from '@mui/material/Button';
import { Link, useNavigate } from 'react-router-dom';
import { Visibility } from '@mui/icons-material';
import React from 'react';
import { useTheme } from '@mui/material';
import theme from '../../../theme/theme.js';
import { AdminCrudMolette } from '../../../components/AdminCrudMolette/index.jsx';
import { getMediaUrl } from '../../../helpers/getApirUrl.js';

export const Shop = ({ shop }) => {
  const theme = useTheme();
  const navigate = useNavigate();
  return (
    <div
      key={shop.id}
      className={
        'bg-white p-5 m-10 rounded-xl shadow-xl md:w-2/5 hover:shadow-2xl hover:scale-105 transform transition-all duration-500'
      }
    >
      <AdminCrudMolette
        entityName={'shop'}
        handleEdit={() => navigate(`/shop/${shop.id}/edit`)}
        handleRemove={() => console.log('remove')}
        extendsItems={[
          {
            label: 'Voir les membres',
            icon: <Visibility />,
            action: () => navigate(`/my-shops/${shop.id}/members`),
          },
          {
            label: 'Voir les stats',
            icon: <Visibility />,
            action: () => navigate(`/shop/stats/${shop.id}`),
          },
          {
            label: 'Ajouter un vélo',
            icon: <Visibility />,
            action: () => navigate(`/create-bike/${shop.id}`),
          },
          {
            label: 'Voir les requêtes de congés',
            icon: <Visibility />,
            action: () => navigate(`/vacations-request/${shop.id}`),
          },
        ]}
      />
      <img
        src={
          shop.media?.contentUrl
            ? getMediaUrl() + shop.media.contentUrl
            : 'https://media.gettyimages.com/id/912819604/fr/vectoriel/ic%C3%B4ne-du-site-e-commerce-vitrine-design-plat.jpg?s=612x612&w=gi&k=20&c=1ORkFTN_MAUixOP-jWzpELTYyiAob1cYDTXoQiGYf6I='
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
          to={`/bikes/${shop.id}`}
          href="#contained-buttons"
          sx={{ mt: 2, color: theme.palette.common.white }}
        >
          See the shop &nbsp; <Visibility />
        </Button>
      </div>
    </div>
  );
};
