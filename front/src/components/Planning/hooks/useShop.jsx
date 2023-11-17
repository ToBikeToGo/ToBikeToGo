import { useParams } from 'react-router-dom';
import { useState } from 'react';

const useShop = () => {
  // get shop id from url
  const { shopId } = useParams();
  const [activeMember, setActiveMember] = useState(null);

  const getShopWithMembers = () => {
    const mockedShop = {
      id: 1,
      name: 'VeliCity Rouen',
      members: [
        {
          id: 1,
          name: 'John Doe',
          profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
        },
        {
          id: 2,
          name: 'Jane Doe',
          profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
        },
      ],
    };

    function createNewMember(id) {
      return {
        id: id,
        name: `Member ${id}`,
        profilePicture: 'xsgames.co/randomusers/avatar.php?g=male',
      };
    }

    for (let i = 3; i <= 22; i++) {
      mockedShop.members.push(createNewMember(i));
    }

    return mockedShop;
  };

  const { id, members, name } = getShopWithMembers();

  return {
    id,
    members,
    name,
    activeMember,
    setActiveMember,
  };
};

export { useShop };
