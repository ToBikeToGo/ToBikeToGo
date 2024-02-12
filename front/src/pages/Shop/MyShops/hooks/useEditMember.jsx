import { useState } from 'react';
import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';

export const useEditMember = () => {
  const [userDatas, setUserDatas] = useState({
    roles: ['ROLE_EMPLOYEE'],
    shop: 90,
    lastname: 'test',
    firstname: 'Laila',
    email: 'Test',
    status: false,
  });

  const handleSubmit = (event) => {
    event.preventDefault();

    const apiUrl = getApirUrl();

    fetchApi(`${apiUrl}/register/member`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(userDatas),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log('data from API', data);
      });
  };

  return { userDatas, setUserDatas, handleSubmit };
};
