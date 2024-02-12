import { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { FormBuilder } from '../../components/Form/FormBuilder.jsx';
import { CircularProgress } from '@mui/material';
import { useBikes } from './hooks/useBike.jsx';

const EditBike = () => {
  const { bikeId } = useParams();

  const {
    bike,
    isLoading,
    getBikeById,
    fetchBikesCategories,
    bikesCategories,
  } = useBikes();

  useEffect(() => {
    getBikeById(bikeId).then(() => fetchBikesCategories());
  }, [bikeId, fetchBikesCategories, getBikeById]);

  const formConfig = {
    title: 'Edit Bike',
    submitLabel: 'Edit',
    successMessage: 'Bike modified with success',
    call: {
      link: '/bikes/' + bikeId,
      method: 'PATCH',
    },
    fields: [
      {
        type: 'text',
        label: 'Brand',
        name: 'brand',
        value: bike.brand,
        isEditable: true,
      },
      {
        type: 'text',
        label: 'Label',
        name: 'label',
        value: bike.label,
        isEditable: true,
      },
      {
        type: 'number',
        label: 'Price',
        name: 'price',
        value: bike.price,
        isEditable: true,
      },
      {
        type: 'checkbox',
        label: 'Is Electric',
        name: 'isElectric',
        value: bike.isElectric,
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
        type: 'select',
        label: 'Category',
        name: 'category',
        options: [
          ...bikesCategories.map((category) => ({
            value: category['@id'],
            label: category.type,
          })),
        ],
        value: bike?.category?.type,
        isEditable: true,
      },
    ],
  };

  const onSubmit = () => {
    // Redirect to bikes page or refresh the page
  };

  if (isLoading) {
    return <CircularProgress sx={{ m: 5 }} />;
  }

  return <FormBuilder form={formConfig} onSubmit={onSubmit} />;
};

export { EditBike };
