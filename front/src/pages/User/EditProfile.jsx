import withToast from '../../components/HOC/WithToastHOC.jsx';
import { FormBuilder } from '../../components/Form/FormBuilder.jsx';
import { useEffect, useState } from 'react';
import { getApirUrl, getMediaUrl } from '../../helpers/getApirUrl.js';
import fetchApi from '../../helpers/fetchApi.js';
import { CircularProgress } from '@mui/material';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { useParams } from 'react-router-dom';
import { useUsers } from '../Admin/hooks/useUsers.js';
import { useTranslation } from '../../locales/hooks/getTranslation.js';

const EditProfile = () => {
  const [error, setError] = useState(null);
  const { user: userFromContext, refreshUser } = useUserContext();
  const { getTranslation } = useTranslation();

  const { userId } = useParams();
  const { getUser, user: externalUser } = useUsers();

  useEffect(() => {
    if (userId) {
      getUser(userId);
    }
  }, [getUser, userId]);

  const user = userId ? externalUser : userFromContext;

  const form = {
    title: getTranslation('edit-profile.title'),
    canEditAll: false,
    fields: [
      {
        type: 'text',
        id: 'lastname',
        label: getTranslation('edit-profile.last-name'),
        name: 'lastname',
        value: user.lastname,
        isEditable: true,
      },
      {
        type: 'text',
        id: 'firstname',
        label: getTranslation('edit-profile.first-name'),
        name: 'firstname',
        value: user.firstname,
        isEditable: true,
      },
      {
        type: 'text',
        id: 'email',
        label: getTranslation('edit-profile.email'),
        name: 'email',
        value: user.email,
        isEditable: false,
      },
      {
        type: 'select',
        id: 'locale',
        label: getTranslation('edit-profile.locale'),
        options: [
          { value: 'FR', label: 'Français' },
          { value: 'EN', label: 'English' },
        ],
        name: 'locale',
        value: user.locale,
        isEditable: true,
      },
      {
        type: 'image',
        id: 'profilePicture',
        label: getTranslation('edit-profile.profile-picture'),
        name: 'profilePicture',
        isEditable: true,
      },
    ],
    submitLabel: userId ? getTranslation('edit-profile.submit.edit-user') : getTranslation('edit-profile.submit.edit-profile'),
    call: {
      link: `/users/${user.id}`,
      method: 'PATCH',
    },
    successMessage: getTranslation('edit-profile.submit.success'),
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
