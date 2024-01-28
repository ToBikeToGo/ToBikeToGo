import withToast from '../../components/HOC/WithToastHOC.jsx';
import { FormBuilder } from '../../components/Form/FormBuilder.jsx';
import { useParams } from 'react-router-dom';
import { useEffect, useState } from 'react';
import { getApirUrl } from '../../helpers/getApirUrl.js';
import fetchApi from '../../helpers/fetchApi.js';
import { CircularProgress } from '@mui/material';

const EditProfile = () => {
  const [user, setUser] = useState({});

  useEffect(() => {
    const apiUrl = getApirUrl();
    fetchApi(`${apiUrl}/me`)
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        if (data && data['@id']) {
          setUser({
            id: data['id'],
            firstname: data.firstname,
            lastname: data.lastname,
            email: data.email,
            shops: data.shops,
            phone: data.phone,
          });
        } else {
          throw new Error('Data is not in the expected format');
        }
      })
      .catch((error) => {
        setError(error);
      });
  }, []);

  const form = {
    title: 'Edit my profile',
    canEditAll: false,
    fields: [
      {
        type: 'text',
        id: 'lastname',
        label: 'Last name',
        name: 'lastname',
        value: user.lastname,
        isEditable: true,
      },
      {
        type: 'text',
        id: 'firstname',
        label: 'First name',
        name: 'firstname',
        value: user.firstname,
        isEditable: true,
      },
      {
        type: 'text',
        id: 'email',
        label: 'Email',
        name: 'email',
        value: user.email,
        isEditable: false,
      },
      {
        type: 'checkbox',
        id: 'status',
        label: 'Status',
        name: 'status',
        value: user.status,
        isEditable: true,
      },

      {
        type: 'select',
        id: 'locale',
        label: 'Language preferences',
        options: [
          { value: 'FR', label: 'French' },
          { value: 'EN', label: 'English' },
        ],
        name: 'locale',
        value: user.locale,
        isEditable: true,
      },
      {
        type: 'image',
        id: 'profilePicture',
        label: 'Profile Picture',
        name: 'profilePicture',
        isEditable: true,
      },
    ],
    submitLabel: 'Save my profile',
    call: {
      link: `/users/${user.id}`,
      method: 'PATCH',
    },
    successMessage: 'Votre profil a été mis à jour !',
    initialValues: {
      roles: ['ROLE_EMPLOYEE'],
      shop: 90,
      lastname: '',
      firstname: '',
      email: '',
      status: false,
    },
  };

  if (user.lastname === undefined) {
    return <CircularProgress color={'secondary'} className={'m-5'} />;
  }

  return <FormBuilder form={form} />;
};

const EditProfileWithToast = withToast(EditProfile);

export { EditProfileWithToast as EditProfile };
