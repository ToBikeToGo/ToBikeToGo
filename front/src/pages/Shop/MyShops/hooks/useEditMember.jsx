import { useState } from 'react';
import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';

export const useEditMember = (memberId) => {
  const [userDatas, setUserDatas] = useState({
    roles: ['ROLE_EMPLOYEE'],
    shop: 90,
    lastname: '',
    firstname: '',
    email: '',
    status: true,
    schedules: [], // Ajouter le champ `schedules` ici
  });

  const handleSubmit = (event) => {
    event.preventDefault();

    const apiUrl = getApirUrl();

    fetchApi(`${apiUrl}/register/member/${memberId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(userDatas),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Erreur lors de la mise à jour du membre');
        }
        return response.json();
      })
      .then((data) => {
        console.log('Données de l\'API', data);
      })
      .catch((error) => {
        console.error('Erreur:', error);
      });
  };

  return { userDatas, setUserDatas, handleSubmit };
};
