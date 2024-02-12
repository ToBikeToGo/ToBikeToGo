import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { FormBuilder } from '../../components/Form/FormBuilder.jsx';
import fetchApi from '../../helpers/fetchApi.js';
import { getApirUrl } from '../../helpers/getApirUrl.js';
import { CircularProgress } from '@mui/material';

const CreateBikePage = () => {
  const { shopId } = useParams();

  const [bikesCategories, setBikesCategories] = useState([]);

  const fetchBikesCategories = async () => {
    const apiUrl = getApirUrl();
    const response = await fetchApi(`${apiUrl}/bike_categories`);
    const data = await response.json();
    setBikesCategories(data['hydra:member']);
  };

  const formConfig = {
    title: 'Create Bike',
    submitLabel: 'Create',
    successMessage: 'Bike created successfully',
    call: {
      link: '/bikes',
      method: 'POST',
    },
    fields: [
      {
        type: 'text',
        label: 'Brand',
        name: 'brand',
        value: '',
        isEditable: true,
      },
      {
        type: 'text',
        label: 'Label',
        name: 'label',
        value: '',
        isEditable: true,
      },
      {
        type: 'number',
        label: 'Price',
        name: 'price',
        value: 0,
        isEditable: true,
      },
      {
        type: 'checkbox',
        label: 'Is Electric',
        name: 'isElectric',
        value: false,
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
        isEditable: true,
      },
      {
        type: 'hidden',
        label: 'Shop',
        name: 'shop',
        value: '/api/shops/' + shopId,
        isEditable: false,
      },
    ],
  };

  const onSubmit = () => {
    // Redirect to bikes page or refresh the page
  };

  if (bikesCategories.length === 0) {
    fetchBikesCategories();
    return <CircularProgress sx={{ m: 5 }} />;
  }

  return <FormBuilder form={formConfig} onSubmit={onSubmit} />;
};

export { CreateBikePage };
