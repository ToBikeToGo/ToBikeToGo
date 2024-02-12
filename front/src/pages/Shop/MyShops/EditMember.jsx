import React, { useState } from 'react';
import {
  Button,
  TextField,
  Checkbox,
  FormControlLabel,
  Box,
  useTheme,
} from '@mui/material';
import withToast from '../../../components/HOC/WithToastHOC.jsx';
import { FormBuilder } from '../../../components/Form/FormBuilder.jsx';
import { useEditMember } from './hooks/useEditMember.jsx';

const EditMember = ({ setToast }) => {
  const { userDatas, setUserDatas, handleSubmit } = useEditMember();

  const { lastname, firstname, email, status } = userDatas;

  const onChangeValue = (e) => {
    setUserDatas({ ...userDatas, [e.target.name]: e.target.value });
  };

  const form = {
    title: 'Add a member',
    canEditAll: true,
    fields: [
      {
        type: 'text',
        id: 'lastname',
        label: 'Last name',
        name: 'lastname',
        value: lastname,
      },
      {
        type: 'text',
        id: 'firstname',
        label: 'First name',
        name: 'firstname',
        value: firstname,
      },
      {
        type: 'text',
        id: 'email',
        label: 'Email',
        name: 'email',
        value: email,
      },
      {
        type: 'checkbox',
        id: 'status',
        label: 'Status',
        name: 'status',
        value: status,
      },
    ],
    submitLabel: 'Add a member',
  };

  return (
    <FormBuilder form={form} onChange={onChangeValue} onSubmit={handleSubmit} />
  );
};

const EditMemberPage = withToast(EditMember);

export { EditMemberPage as EditMember };
