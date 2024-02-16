import { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { CircularProgress } from '@mui/material';
import { useShop } from '../../../hooks/UseShop.jsx';
import { FormBuilder } from '../../../components/Form/FormBuilder.jsx';
import { getMediaUrl } from '../../../helpers/getApirUrl.js';

const EditShop = () => {
  const { shopId } = useParams();

  const { shop, getShopById, isLoading } = useShop();

  useEffect(() => {
    getShopById(shopId);
  }, [shopId, , getShopById]);

  const formConfig = {
    title: 'Edit Shop',
    submitLabel: 'Edit',
    successMessage: 'Shop modified with success',
    call: {
      link: '/shops/' + shopId,
      method: 'PATCH',
    },
    fields: [
      {
        type: 'text',
        label: 'Label',
        name: 'label',
        value: shop.label,
        isEditable: true,
      },
      {
        type: 'text',
        label: 'Address',
        name: 'address',
        value: shop.address,
        isEditable: true,
      },
      {
        type: 'checkbox',
        label: 'Is Opened',
        name: 'isOpened',
        value: shop.isOpened,
        isEditable: true,
      },
      {
        type: 'image',
        id: 'profilePicture',
        label: 'Picture',
        name: 'profilePicture',
        isEditable: true,
      },
      {
        type: 'schedule',
        relateEntity: 'shops',
      },
    ],
    initialSelectedImage: shop?.media?.contentUrl
      ? getMediaUrl() + shop.media.contentUrl
      : null,
  };

  const onSubmit = () => {
    // Redirect to bikes page or refresh the page
  };

  if (isLoading) {
    return <CircularProgress sx={{ m: 5 }} />;
  }

  return <FormBuilder form={formConfig} onSubmit={onSubmit} />;
};

export { EditShop };
