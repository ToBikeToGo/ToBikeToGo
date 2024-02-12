import { useState } from 'react';
import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';

export const useAddMember = (shopId) => {
  const [userDatas, setUserDatas] = useState({
    roles: ['ROLE_EMPLOYEE'],
    shop: 90,
    lastname: '',
    firstname: '',
    email: '',
    status: false,
  });

  const handleSubmit = (event) => {
    event.preventDefault();

    const apiUrl = getApirUrl();

    // Here you would typically send the data to your API
    console.log(userDatas);

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
