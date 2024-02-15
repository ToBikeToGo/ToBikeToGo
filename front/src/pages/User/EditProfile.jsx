import withToast from '../../components/HOC/WithToastHOC.jsx';
import { FormBuilder } from '../../components/Form/FormBuilder.jsx';
import { useEffect, useState } from 'react';
import { getApirUrl, getMediaUrl } from '../../helpers/getApirUrl.js';
import fetchApi from '../../helpers/fetchApi.js';
import { CircularProgress } from '@mui/material';
import { useUserContext } from '../../hooks/UserContext.jsx';

const EditProfile = () => {
  const [error, setError] = useState(null);
  const { user, refreshUser } = useUserContext();

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
    initialSelectedImage: user?.avatar ? user.avatar : null,
  };

  if (user.lastname === undefined) {
    return <CircularProgress color={'secondary'} className={'m-5'} />;
  }

  const onSubmit = () => {
    refreshUser();
  };

  return <FormBuilder form={form} onSubmit={onSubmit} />;
};

const EditProfileWithToast = withToast(EditProfile);

export { EditProfileWithToast as EditProfile };
